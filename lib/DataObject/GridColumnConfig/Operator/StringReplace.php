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

namespace Pimcore\DataObject\GridColumnConfig\Operator;

use Pimcore\Model\Element\ElementInterface;

/**
 * @internal
 */
final class StringReplace extends AbstractOperator
{
    private string $search;

    private string $replace;

    private bool $insensitive;

    public function __construct(\stdClass $config, $context = null)
    {
        parent::__construct($config, $context);

        $this->search = $config->search ?? '';
        $this->replace = $config->replace ?? '';
        $this->insensitive = $config->insensitive ?? false;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabeledValue(array|ElementInterface $element): \Pimcore\DataObject\GridColumnConfig\ResultContainer|\stdClass|null
    {
        $result = new \stdClass();
        $result->label = $this->label;
        $result->value = null;

        $children = $this->getChildren();

        if ($children) {
            $newChildrenResult = [];

            foreach ($children as $c) {
                $childResult = $c->getLabeledValue($element);

                $childValues = $childResult->value;
                if ($childValues && !is_array($childValues)) {
                    $childValues = [$childValues];
                }

                $newValue = null;

                if (is_array($childValues)) {
                    foreach ($childValues as $value) {
                        if (is_array($value)) {
                            $newSubValues = [];
                            foreach ($value as $subValue) {
                                $subValue = $this->replace($subValue);
                                $newSubValues[] = $subValue;
                            }
                            $newValue = $newSubValues;
                        } else {
                            $newValue = $this->replace($value);
                        }
                    }
                }

                $newChildrenResult[] = $newValue;
            }

            $result->value = $newChildrenResult;
        }

        return $result;
    }

    public function replace(string $value): string
    {
        if ($this->getInsensitive()) {
            return str_ireplace($this->getSearch(), $this->getReplace(), $value);
        } else {
            return str_replace($this->getSearch(), $this->getReplace(), $value);
        }
    }

    public function getSearch(): string
    {
        return $this->search;
    }

    public function setSearch(string $search)
    {
        $this->search = $search;
    }

    public function getReplace(): string
    {
        return $this->replace;
    }

    public function setReplace(string $replace)
    {
        $this->replace = $replace;
    }

    public function getInsensitive(): bool
    {
        return $this->insensitive;
    }

    public function setInsensitive(bool $insensitive)
    {
        $this->insensitive = $insensitive;
    }
}
