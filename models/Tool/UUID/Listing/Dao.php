<?php

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

namespace Pimcore\Model\Tool\UUID\Listing;

use Pimcore\Model;
use Pimcore\Model\Tool\UUID;

/**
 * @internal
 *
 * @property \Pimcore\Model\Tool\UUID\Listing $model
 */
class Dao extends Model\Listing\Dao\AbstractDao
{
    /**
     * Loads a list of UUID for the specified parameters, returns an array of UUID elements
     *
     * @return array
     */
    public function load(): array
    {
        $items = $this->db->fetchFirstColumn('SELECT uuid FROM ' . UUID\Dao::TABLE_NAME .' '. $this->getCondition() . $this->getOrder() . $this->getOffsetLimit(), $this->model->getConditionVariables());
        $result = [];
        foreach ($items as $uuid) {
            $result[] = UUID::getByUuid($uuid);
        }

        return $result;
    }

    /**
     * Returns the total amount of UUID entries
     *
     * @return int
     */
    public function getTotalCount(): int
    {
        try {
            return (int) $this->db->fetchOne('SELECT COUNT(*) FROM ' . UUID\Dao::TABLE_NAME .' ' . $this->getCondition(), $this->model->getConditionVariables());
        } catch (\Exception $e) {
            return 0;
        }
    }
}
