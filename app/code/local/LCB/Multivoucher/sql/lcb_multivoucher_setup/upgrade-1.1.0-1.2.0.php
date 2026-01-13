<?php

$installer = $this;
$installer->startSetup();

$installer->getConnection()->addColumn($installer->getTable('lcb_multivoucher/brand'), 'regulations_url', array(
    'type'      => Varien_Db_Ddl_Table::TYPE_TEXT,
    'nullable'  => true,
    'length'    => '255',
    'after'     => 'url',
    'comment'   => 'Regulations Url',
));

$installer->endSetup();
