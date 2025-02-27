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

namespace Pimcore\Model;

use Pimcore\Model\Element\ElementInterface;
use Pimcore\Model\Element\Service;

/**
 * @method \Pimcore\Model\Property\Dao getDao()
 * @method void save()
 */
final class Property extends AbstractModel
{
    protected string $name;

    protected mixed $data = null;

    protected string $type;

    protected string $ctype;

    protected ?string $cpath = null;

    protected int $cid;

    protected bool $inheritable = false;

    protected bool $inherited = false;

    /**
     * @internal
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setDataFromEditmode(mixed $data): static
    {
        // IMPORTANT: if you use this method be sure that the type of the property is already set

        if (in_array($this->getType(), ['document', 'asset', 'object'])) {
            $el = Element\Service::getElementByPath($this->getType(), (string)$data);
            $this->data = null;
            if ($el) {
                $this->data = $el->getId();
            }
        } elseif ($this->type == 'bool') {
            $this->data = false;
            if (!empty($data)) {
                $this->data = true;
            }
        } else {
            // plain text
            $this->data = $data;
        }

        return $this;
    }

    /**
     * @internal
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setDataFromResource(mixed $data): static
    {
        // IMPORTANT: if you use this method be sure that the type of the property is already set
        // do not set data for object, asset and document here, this is loaded dynamically when calling $this->getData();
        if ($this->type == 'date') {
            $this->data = \Pimcore\Tool\Serialize::unserialize($data);
        } elseif ($this->type == 'bool') {
            $this->data = false;
            if (!empty($data)) {
                $this->data = true;
            }
        } else {
            // plain text
            $this->data = $data;
        }

        return $this;
    }

    public function getCid(): int
    {
        return $this->cid;
    }

    /**
     * enum('document','asset','object')
     *
     * @return string
     */
    public function getCtype(): string
    {
        return $this->ctype;
    }

    public function getData(): mixed
    {
        // lazy-load data of type asset, document, object
        if (in_array($this->getType(), ['document', 'asset', 'object']) && !$this->data instanceof ElementInterface && is_numeric($this->data)) {
            return Element\Service::getElementById($this->getType(), $this->data);
        }

        return $this->data;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * enum('text','document','asset','object','bool','select')
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function setCid(int $cid): static
    {
        $this->cid = (int) $cid;

        return $this;
    }

    /**
     * enum('document','asset','object')
     *
     * @param string $ctype
     *
     * @return $this
     */
    public function setCtype(string $ctype): static
    {
        $this->ctype = $ctype;

        return $this;
    }

    public function setData(mixed $data): static
    {
        if ($data instanceof ElementInterface) {
            $this->setType(Service::getElementType($data));
            $data = $data->getId();
        }

        $this->data = $data;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * enum('text','document','asset','object','bool','select')
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCpath(): ?string
    {
        return $this->cpath;
    }

    public function getInherited(): bool
    {
        return $this->inherited;
    }

    /**
     * Alias for getInherited()
     *
     * @return bool
     */
    public function isInherited(): bool
    {
        return $this->getInherited();
    }

    public function setCpath(?string $cpath): static
    {
        $this->cpath = $cpath;

        return $this;
    }

    public function setInherited(bool $inherited): static
    {
        $this->inherited = (bool) $inherited;

        return $this;
    }

    public function getInheritable(): bool
    {
        return $this->inheritable;
    }

    public function setInheritable(bool $inheritable): static
    {
        $this->inheritable = (bool) $inheritable;

        return $this;
    }

    /**
     * @internal
     *
     * @return array
     */
    public function resolveDependencies(): array
    {
        $dependencies = [];

        if ($this->getData() instanceof ElementInterface) {
            $elementType = Element\Service::getElementType($this->getData());
            $key = $elementType . '_' . $this->getData()->getId();
            $dependencies[$key] = [
                'id' => $this->getData()->getId(),
                'type' => $elementType,
            ];
        }

        return $dependencies;
    }

    /**
     * Rewrites id from source to target, $idMapping contains
     * array(
     *  "document" => array(
     *      SOURCE_ID => TARGET_ID,
     *      SOURCE_ID => TARGET_ID
     *  ),
     *  "object" => array(...),
     *  "asset" => array(...)
     * )
     *
     * @param array $idMapping
     *
     * @internal
     */
    public function rewriteIds(array $idMapping)
    {
        if (!$this->isInherited()) {
            if (array_key_exists($this->getType(), $idMapping)) {
                if ($this->getData() instanceof ElementInterface) {
                    if (array_key_exists((int) $this->getData()->getId(), $idMapping[$this->getType()])) {
                        $this->setData(Element\Service::getElementById($this->getType(), $idMapping[$this->getType()][$this->getData()->getId()]));
                    }
                }
            }
        }
    }

    /**
     * @internal
     *
     * @return array
     */
    public function serialize(): array
    {
        return [
          'name' => $this->getName(),
          'type' => $this->getType(),
          'data' => $this->getData(),
        ];
    }
}
