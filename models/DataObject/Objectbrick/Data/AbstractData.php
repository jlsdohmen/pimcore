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

namespace Pimcore\Model\DataObject\Objectbrick\Data;

use Pimcore\Model;
use Pimcore\Model\DataObject;
use Pimcore\Model\DataObject\ClassDefinition\Data\LazyLoadingSupportInterface;
use Pimcore\Model\DataObject\Concrete;
use Pimcore\Model\DataObject\Exception\InheritanceParentNotFoundException;

/**
 * @method Dao getDao()
 * @method void save(Concrete $object, $params = [])
 * @method array getRelationData($field, $forOwner, $remoteClassId)
 */
abstract class AbstractData extends Model\AbstractModel implements Model\DataObject\LazyLoadedFieldsInterface, Model\Element\ElementDumpStateInterface, Model\Element\DirtyIndicatorInterface
{
    use Model\DataObject\Traits\LazyLoadedRelationTrait;
    use Model\Element\ElementDumpStateTrait;
    use Model\Element\Traits\DirtyIndicatorTrait;

    /**
     * Will be overriden by the actual ObjectBrick
     *
     * @var string
     */
    protected string $type = '';

    protected ?string $fieldname = null;

    protected bool $doDelete = false;

    protected Concrete|Model\Element\ElementDescriptor|null $object = null;

    protected ?int $objectId = null;

    public function __construct(Concrete $object)
    {
        $this->setObject($object);
    }

    public function getFieldname(): ?string
    {
        return $this->fieldname;
    }

    public function setFieldname(?string $fieldname): static
    {
        $this->fieldname = $fieldname;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getDefinition(): DataObject\Objectbrick\Definition
    {
        $definition = DataObject\Objectbrick\Definition::getByKey($this->getType());

        return $definition;
    }

    public function setDoDelete(bool $doDelete): static
    {
        $this->flushContainer();
        $this->doDelete = (bool)$doDelete;

        return $this;
    }

    public function getDoDelete(): bool
    {
        return $this->doDelete;
    }

    public function getBaseObject(): ?Concrete
    {
        return $this->getObject();
    }

    public function delete(Concrete $object)
    {
        $this->doDelete = true;
        $this->getDao()->delete($object);
        $this->flushContainer();
    }

    /**
     * @internal
     * Flushes the already collected items of the container object
     */
    protected function flushContainer()
    {
        $object = $this->getObject();
        if ($object) {
            $containerGetter = 'get' . ucfirst($this->fieldname);

            $container = $object->$containerGetter();
            if ($container instanceof DataObject\Objectbrick) {
                $container->setItems([]);
            }
        }
    }

    /**
     * @param string $key
     *
     * @return mixed
     *
     * @throws InheritanceParentNotFoundException
     */
    public function getValueFromParent(string $key): mixed
    {
        $object = $this->getObject();
        if ($object) {
            $parent = DataObject\Service::hasInheritableParentObject($object);

            if (!empty($parent)) {
                $containerGetter = 'get' . ucfirst($this->fieldname);
                $brickGetter = 'get' . ucfirst($this->getType());
                $getter = 'get' . ucfirst($key);

                if ($parent->$containerGetter()->$brickGetter()) {
                    return $parent->$containerGetter()->$brickGetter()->$getter();
                }
            }
        }

        throw new InheritanceParentNotFoundException('No parent object available to get a value from');
    }

    public function setObject(?Concrete $object): static
    {
        $this->objectId = $object ? $object->getId() : null;
        $this->object = $object;

        return $this;
    }

    public function getObject(): ?Concrete
    {
        if ($this->objectId && !$this->object) {
            $this->setObject(Concrete::getById($this->objectId));
        }

        return $this->object;
    }

    public function getValueForFieldName(string $key): mixed
    {
        if ($this->$key) {
            return $this->$key;
        }

        return false;
    }

    /**
     * @param string $fieldName
     * @param string|null $language
     *
     * @return mixed
     */
    public function get(string $fieldName, string $language = null): mixed
    {
        return $this->{'get'.ucfirst($fieldName)}($language);
    }

    /**
     * @param string $fieldName
     * @param mixed $value
     * @param string|null $language
     *
     * @return mixed
     */
    public function set(string $fieldName, mixed $value, string $language = null): mixed
    {
        return $this->{'set'.ucfirst($fieldName)}($value, $language);
    }

    /**
     * @internal
     *
     * @return array
     */
    protected function getLazyLoadedFieldNames(): array
    {
        $lazyLoadedFieldNames = [];
        $fields = $this->getDefinition()->getFieldDefinitions(['suppressEnrichment' => true]);
        foreach ($fields as $field) {
            if ($field instanceof LazyLoadingSupportInterface && $field->getLazyLoading()) {
                $lazyLoadedFieldNames[] = $field->getName();
            }
        }

        return $lazyLoadedFieldNames;
    }

    /**
     * {@inheritdoc}
     */
    public function isAllLazyKeysMarkedAsLoaded(): bool
    {
        $object = $this->getObject();
        if ($object instanceof Concrete) {
            return $this->getObject()->isAllLazyKeysMarkedAsLoaded();
        }

        return true;
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        $parentVars = parent::__sleep();
        $blockedVars = ['loadedLazyKeys', 'object'];
        $finalVars = [];

        if (!$this->isInDumpState()) {
            //Remove all lazy loaded fields if item gets serialized for the cache (not for versions)
            $blockedVars = array_merge($this->getLazyLoadedFieldNames(), $blockedVars);
        }

        foreach ($parentVars as $key) {
            if (!in_array($key, $blockedVars)) {
                $finalVars[] = $key;
            }
        }

        return $finalVars;
    }

    public function __wakeup()
    {
        if ($this->object) {
            $this->objectId = $this->object->getId();
        }
    }
}
