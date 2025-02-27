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

namespace Pimcore\Model\Tool;

use Pimcore\Model;
use Pimcore\Model\Tool\SettingsStore\Dao;

/**
 * @method Dao getDao()
 */
final class SettingsStore extends Model\AbstractModel
{
    protected static array $allowedTypes = ['bool', 'int', 'float', 'string'];

    /**
     * @internal
     *
     * @var string
     */
    protected string $id;

    /**
     * @internal
     *
     * @var string|null
     */
    protected ?string $scope = null;

    /**
     * @internal
     *
     * @var string
     */
    protected string $type = '';

    /**
     * @internal
     *
     * @var mixed
     */
    protected mixed $data = null;

    /**
     * @internal
     *
     * @var self|null
     */
    protected static ?self $instance = null;

    private static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @throws \Exception
     */
    private static function validateType(string $type): bool
    {
        if (!in_array($type, self::$allowedTypes)) {
            throw new \Exception(sprintf('Invalid type `%s`, allowed types are %s', $type, implode(',', self::$allowedTypes)));
        }

        return true;
    }

    /**
     * @param string $id
     * @param float|bool|int|string $data
     * @param string $type
     * @param string|null $scope
     *
     * @return bool
     *
     * @throws \Exception
     */
    public static function set(string $id, float|bool|int|string $data, string $type = 'string', ?string $scope = null): bool
    {
        self::validateType($type);
        $instance = self::getInstance();

        return $instance->getDao()->set($id, $data, $type, $scope);
    }

    public static function delete(string $id, ?string $scope = null): int|string
    {
        $instance = self::getInstance();

        return $instance->getDao()->delete($id, $scope);
    }

    public static function get(string $id, ?string $scope = null): ?SettingsStore
    {
        $item = new self();
        if ($item->getDao()->getById($id, $scope)) {
            return $item;
        }

        return null;
    }

    /**
     * @param string $scope
     *
     * @return string[]
     */
    public static function getIdsByScope(string $scope): array
    {
        $instance = self::getInstance();

        return $instance->getDao()->getIdsByScope($scope);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getScope(): ?string
    {
        return $this->scope;
    }

    public function setScope(?string $scope): void
    {
        $this->scope = (string) $scope;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @throws \Exception
     */
    public function setType(string $type): void
    {
        self::validateType($type);
        $this->type = $type;
    }

    public function getData(): float|bool|int|string
    {
        return $this->data;
    }

    public function setData(float|bool|int|string $data): void
    {
        if (!empty($this->getType())) {
            settype($data, $this->getType());
        }
        $this->data = $data;
    }
}
