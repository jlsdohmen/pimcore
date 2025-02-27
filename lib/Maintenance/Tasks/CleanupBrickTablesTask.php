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

namespace Pimcore\Maintenance\Tasks;

use Pimcore\Db;
use Pimcore\Maintenance\TaskInterface;
use Pimcore\Model\DataObject\ClassDefinition;
use Pimcore\Model\DataObject\Objectbrick\Definition;
use Psr\Log\LoggerInterface;

/**
 * @internal
 */
class CleanupBrickTablesTask implements TaskInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        $db = Db::get();
        $tableTypes = ['store', 'query', 'localized'];
        foreach ($tableTypes as $tableType) {
            $prefix = 'object_brick_' . $tableType . '_';
            $tableNames = $db->fetchAllAssociative("SHOW TABLES LIKE '" . $prefix . "%'");

            foreach ($tableNames as $tableName) {
                $tableName = current($tableName);

                if (strpos($tableName, 'object_brick_localized_query_') === 0) {
                    continue;
                }

                $fieldDescriptor = substr($tableName, strlen($prefix));
                $idx = strpos($fieldDescriptor, '_');
                $brickType = substr($fieldDescriptor, 0, $idx);

                $brickDef = Definition::getByKey($brickType);
                if (!$brickDef) {
                    $this->logger->error("Brick '" . $brickType . "' not found. Please check table " . $tableName);

                    continue;
                }

                $classId = substr($fieldDescriptor, $idx + 1);

                $classDefinition = ClassDefinition::getByIdIgnoreCase($classId);
                if (!$classDefinition) {
                    $this->logger->error("Classdefinition '" . $classId . "' not found. Please check table " . $tableName);

                    continue;
                }

                $fieldsQuery = 'SELECT fieldname FROM ' . $tableName . ' GROUP BY fieldname';
                $fieldNames = $db->fetchFirstColumn($fieldsQuery);

                foreach ($fieldNames as $fieldName) {
                    $fieldDef = $classDefinition->getFieldDefinition($fieldName);
                    if (!$fieldDef) {
                        $lfDef = $classDefinition->getFieldDefinition('localizedfields');
                        if ($lfDef instanceof ClassDefinition\Data\Localizedfields) {
                            $fieldDef = $lfDef->getFieldDefinition($fieldName);
                        }
                    }

                    if (!$fieldDef) {
                        $this->logger->info("Field '" . $fieldName . "' of class '" . $classId . "' does not exist anymore. Cleaning " . $tableName);
                        $db->delete($tableName, ['fieldname' => $fieldName]);
                    }
                }
            }
        }
    }
}
