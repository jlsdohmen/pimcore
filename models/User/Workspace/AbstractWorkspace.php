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

namespace Pimcore\Model\User\Workspace;

use Pimcore\Model;

/**
 * @internal
 *
 * @method \Pimcore\Model\User\Workspace\Dao getDao()
 * @method void save()
 */
abstract class AbstractWorkspace extends Model\AbstractModel
{
    /**
     * @internal
     *
     * @var int
     */
    protected int $userId;

    /**
     * @internal
     *
     * @var int
     */
    protected int $cid;

    /**
     * @internal
     *
     * @var string
     */
    protected string $cpath;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $list = false;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $view = false;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $publish = false;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $delete = false;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $rename = false;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $create = false;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $settings = false;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $versions = false;

    /**
     * @internal
     *
     * @var bool
     */
    protected bool $properties = false;

    public function setCreate(bool $create): static
    {
        $this->create = $create;

        return $this;
    }

    public function getCreate(): bool
    {
        return $this->create;
    }

    public function setDelete(bool $delete): static
    {
        $this->delete = $delete;

        return $this;
    }

    public function getDelete(): bool
    {
        return $this->delete;
    }

    public function setList(bool $list): static
    {
        $this->list = $list;

        return $this;
    }

    public function getList(): bool
    {
        return $this->list;
    }

    public function setProperties(bool $properties): static
    {
        $this->properties = $properties;

        return $this;
    }

    public function getProperties(): bool
    {
        return $this->properties;
    }

    public function setPublish(bool $publish): static
    {
        $this->publish = $publish;

        return $this;
    }

    public function getPublish(): bool
    {
        return $this->publish;
    }

    public function setRename(bool $rename): static
    {
        $this->rename = $rename;

        return $this;
    }

    public function getRename(): bool
    {
        return $this->rename;
    }

    public function setSettings(bool $settings): static
    {
        $this->settings = $settings;

        return $this;
    }

    public function getSettings(): bool
    {
        return $this->settings;
    }

    public function setVersions(bool $versions): static
    {
        $this->versions = $versions;

        return $this;
    }

    public function getVersions(): bool
    {
        return $this->versions;
    }

    public function setView(bool $view): static
    {
        $this->view = $view;

        return $this;
    }

    public function getView(): bool
    {
        return $this->view;
    }

    public function setCid(int $cid): static
    {
        $this->cid = $cid;

        return $this;
    }

    public function getCid(): int
    {
        return $this->cid;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;

        return $this;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setCpath(string $cpath): static
    {
        $this->cpath = $cpath;

        return $this;
    }

    public function getCpath(): string
    {
        return $this->cpath;
    }
}
