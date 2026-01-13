<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('lcb_multivoucher/reward'), 'purchase_info', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable'  => true,
    'length'    => '64k',
    'after'     => 'description',
    'comment'   => 'Purchase Info',
));

$installer->getConnection()->addColumn($installer->getTable('lcb_multivoucher/brand'), 'purchase_info', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable'  => true,
    'length'    => '64k',
    'after'     => 'description',
    'comment'   => 'Purchase Info',
));

$installer->endSetup();
