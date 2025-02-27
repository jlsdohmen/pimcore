<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

/**
 * ----------------------------------------------------------------------------------
 * based on Zend\Config https://github.com/zendframework/zend-config
 * ----------------------------------------------------------------------------------
 */

/**
 * @see       https://github.com/zendframework/zend-config for the canonical source repository
 *
 * @copyright Copyright (c) 2005-2017 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   https://github.com/zendframework/zend-config/blob/master/LICENSE.md New BSD License
 */

namespace Pimcore\Config;

use ArrayAccess;
use Countable;
use Exception;
use Iterator;

/**
 * @deprecated Will be removed in Pimcore 11.
 *
 * Provides a property based interface to an array.
 * The data are read-only unless $allowModifications is set to true
 * on construction.
 *
 * Implements Countable, Iterator and ArrayAccess
 * to facilitate easy access to the data.
 */
final class Config implements Countable, Iterator, ArrayAccess
{
    /**
     * Whether modifications to configuration data are allowed.
     *
     * @var bool
     */
    protected bool $allowModifications;

    /**
     * Data within the configuration.
     *
     * @var array
     */
    protected array $data = [];

    /**
     * Used when unsetting values during iteration to ensure we do not skip
     * the next element.
     *
     * @var bool
     */
    protected bool $skipNextIteration;

    /**
     * Constructor.
     *
     * Data is read-only unless $allowModifications is set to true
     * on construction.
     *
     * @param  array   $array
     * @param bool $allowModifications
     */
    public function __construct(array $array, bool $allowModifications = false)
    {
        $this->allowModifications = (bool) $allowModifications;

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->data[$key] = new static($value, $this->allowModifications);
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    /**
     * Retrieve a value and return $default if there is no element set.
     *
     * @param string $name
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $name, mixed $default = null): mixed
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        return $default;
    }

    /**
     * Magic function so that $obj->value will work.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->get($name);
    }

    /**
     * Set a value in the config.
     *
     * Only allow setting of a property if $allowModifications  was set to true
     * on construction. Otherwise, throw an exception.
     *
     * @param string|null $name
     * @param  mixed  $value
     *
     * @return void
     *
     * @throws Exception
     */
    public function __set(?string $name, mixed $value)
    {
        if ($this->allowModifications) {
            if (is_array($value)) {
                $value = new static($value, true);
            }

            if (null === $name) {
                $this->data[] = $value;
            } else {
                $this->data[$name] = $value;
            }
        } else {
            throw new Exception('Config is read only');
        }
    }

    /**
     * Deep clone of this instance to ensure that nested Zend\Configs are also
     * cloned.
     *
     * @return void
     */
    public function __clone()
    {
        $array = [];

        foreach ($this->data as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = clone $value;
            } else {
                $array[$key] = $value;
            }
        }

        $this->data = $array;
    }

    /**
     * Return an associative array of the stored data.
     *
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        $data = $this->data;

        foreach ($data as $key => $value) {
            if ($value instanceof self) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    /**
     * isset() overloading
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset(string $name)
    {
        return isset($this->data[$name]);
    }

    /**
     * unset() overloading
     *
     * @param string $name
     *
     * @return void
     *
     * @throws Exception
     */
    public function __unset(string $name)
    {
        if (! $this->allowModifications) {
            throw new Exception('Config is read only');
        } elseif (isset($this->data[$name])) {
            unset($this->data[$name]);
            $this->skipNextIteration = true;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function current(): mixed
    {
        $this->skipNextIteration = false;

        return current($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function key(): string|int|null
    {
        return key($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        if ($this->skipNextIteration) {
            $this->skipNextIteration = false;

            return;
        }

        next($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->skipNextIteration = false;
        reset($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->key() !== null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset): bool
    {
        return $this->__isset($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset): mixed
    {
        return $this->__get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value): void
    {
        $this->__set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset): void
    {
        $this->__unset($offset);
    }

    /**
     * @internal
     *
     * Merge another Config with this one.
     *
     * For duplicate keys, the following will be performed:
     * - Nested Configs will be recursively merged.
     * - Items in $merge with INTEGER keys will be appended.
     * - Items in $merge with STRING keys will overwrite current values.
     *
     * @param  Config $merge
     *
     * @return $this
     */
    public function merge(Config $merge): static
    {
        foreach ($merge as $key => $value) {
            if (array_key_exists($key, $this->data)) {
                if (is_int($key)) {
                    $this->data[] = $value;
                } elseif ($value instanceof self && $this->data[$key] instanceof self) {
                    $this->data[$key]->merge($value);
                } else {
                    if ($value instanceof self) {
                        $this->data[$key] = new static($value->toArray(), $this->allowModifications);
                    } else {
                        $this->data[$key] = $value;
                    }
                }
            } else {
                if ($value instanceof self) {
                    $this->data[$key] = new static($value->toArray(), $this->allowModifications);
                } else {
                    $this->data[$key] = $value;
                }
            }
        }

        return $this;
    }

    /**
     * @internal
     *
     * Prevent any more modifications being made to this instance.
     *
     * Useful after merge() has been used to merge multiple Config objects
     * into one object which should then not be modified again.
     *
     * @return void
     */
    public function setReadOnly(): void
    {
        $this->allowModifications = false;

        /** @var Config $value */
        foreach ($this->data as $value) {
            if ($value instanceof self) {
                $value->setReadOnly();
            }
        }
    }

    /**
     * @internal
     *
     * Returns whether this Config object is read only or not.
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return ! $this->allowModifications;
    }

    /**
     * @return string|false
     */
    public function __toString()
    {
        if (empty($this->data)) {
            return '';
        }

        return is_string($this->data) ? (string)$this->data : json_encode($this->data, JSON_PRETTY_PRINT);
    }
}
