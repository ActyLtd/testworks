<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Acty\Test\Setup\Patch\Schema;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\App\ResourceConnection;

class createProcedures
implements
    DataPatchInterface,
    PatchRevertableInterface
{
    protected $triggerFactory;

    protected $resourceConnection;

    /**
     * @var SchemaSetupInterface
     */
    private $schemaSetup;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        SchemaSetupInterface $schemaSetup,
        ResourceConnection $resourceConnection,
        \Magento\Framework\DB\Ddl\TriggerFactory $triggerFactory

    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->schemaSetup = $schemaSetup;
        $this->resourceConnection = $resourceConnection;
        $this->triggerFactory = $triggerFactory;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $connection = $this->resourceConnection->getConnection();
        $this->schemaSetup->startSetup();
        $trigger1 = $this->triggerFactory->create()
            ->setName('adjacency_list_bi')
            ->setTime(\Magento\Framework\DB\Ddl\Trigger::TIME_BEFORE)
            ->setEvent("INSERT")
            ->setTable($connection->getTableName('adjacency_list'));
        $trigger1->addStatement("DECLARE found_count,new_parent_id,new_child_id,dummy INT;
        SET new_parent_id = NEW.parent_id;
        SET new_child_id = NEW.child_id;
        SELECT COUNT(1) INTO found_count FROM adjacency_list
        WHERE parent_id = new_child_id AND child_id = new_parent_id;
        IF found_count >= 1 THEN
            SELECT 1 INTO dummy FROM information_schema.tables;
        END IF;");
        $connection->createTrigger($trigger1);
        $trigger = $this->triggerFactory->create()
            ->setName('trg_check_bi')
            ->setTime(\Magento\Framework\DB\Ddl\Trigger::TIME_BEFORE)
            ->setEvent("INSERT")
            ->setTable($connection->getTableName('adjacency_list'));
        $trigger->addStatement("IF(NEW.parent_id = NEW.child_id)
            THEN
                SIGNAL SQLSTATE '44000'
                    SET MESSAGE_TEXT = 'Check constraint failed, same parent and child id';
            END IF;");
        $connection->createTrigger($trigger);
        $this->schemaSetup->endSetup();
        return $this;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies()
    {
        /**
         * This is dependency to another patch. Dependency should be applied first
         * One patch can have few dependencies
         * Patches do not have versions, so if in old approach with Install/Ugrade data scripts you used
         * versions, right now you need to point from patch with higher version to patch with lower version
         * But please, note, that some of your patches can be independent and can be installed in any sequence
         * So use dependencies only if this important for you
         */
        return [];
    }

    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        //Here should go code that will revert all operations from `apply` method
        //Please note, that some operations, like removing data from column, that is in role of foreign key reference
        //is dangerous, because it can trigger ON DELETE statement
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function getAliases()
    {
        /**
         * This internal Magento method, that means that some patches with time can change their names,
         * but changing name should not affect installation process, that's why if we will change name of the patch
         * we will add alias here
         */
        return [];
    }
}
