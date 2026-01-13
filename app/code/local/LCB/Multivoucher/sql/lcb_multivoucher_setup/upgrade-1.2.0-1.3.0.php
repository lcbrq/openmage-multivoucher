<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('lcb_multivoucher/purchase'), 'link', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable'  => true,
    'length'    => '255',
    'after'     => 'info',
    'comment'   => 'Link',
));

$installer->endSetup();
