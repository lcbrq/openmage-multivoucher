<?php

$installer = $this;
$installer->startSetup();

$vouchersTable = $installer->getConnection()->newTable($installer->getTable('lcb_multivoucher/reward'))
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'Primary entry key'
        )
        ->addColumn(
            'voucher_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Multivoucher ID'
        )
        ->addColumn(
            'title',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '255',
            array(),
            'Title'
        )
        ->addColumn(
            'image',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '255',
            array(),
            'Image'
        )
        ->addColumn(
            'worth',
            Varien_Db_Ddl_Table::TYPE_FLOAT,
            null,
            array(),
            'Value'
        )
        ->addColumn(
            'brand_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Multivoucher Brand ID'
        )
        ->addColumn(
            'description',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '64k',
            array(),
            'Description'
        )
        ->addColumn(
            'tags',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '1024',
            array(),
            'Tags'
        )
        ->addColumn(
            'stock',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Qty'
        )
        ->addColumn(
            'active',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(),
            'Active'
        )
        ->addColumn(
            'position',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Position'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Created At'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Updated At'
        );
$installer->getConnection()->createTable($vouchersTable);

$brandsTable = $installer->getConnection()->newTable($installer->getTable('lcb_multivoucher/brand'))
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'Primary entry eky'
        )
        ->addColumn(
            'brand_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Multivoucher ID'
        )
        ->addColumn(
            'name',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '255',
            array(),
            'Title'
        )
        ->addColumn(
            'image',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '255',
            array(),
            'Image'
        )
        ->addColumn(
            'description',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '64k',
            array(),
            'Description'
        )
        ->addColumn(
            'url',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '255',
            array(),
            'Url'
        )
        ->addColumn(
            'active',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(),
            'Active'
        )
        ->addColumn(
            'position',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Position'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Created At'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Updated At'
        );
$installer->getConnection()->createTable($brandsTable);

$categoryTable = $installer->getConnection()->newTable($installer->getTable('lcb_multivoucher/category'))
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'Primary entry eky'
        )
        ->addColumn(
            'name',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '255',
            array(),
            'Title'
        )
        ->addColumn(
            'active',
            Varien_Db_Ddl_Table::TYPE_SMALLINT,
            null,
            array(),
            'Active'
        )
        ->addColumn(
            'position',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Position'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Created At'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Updated At'
        );
$installer->getConnection()->createTable($categoryTable);

$vocherCategoryTable = $installer->getConnection()->newTable($installer->getTable('lcb_multivoucher/reward_category'))
    ->addColumn(
        'reward_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Voucher ID'
    )
    ->addColumn(
        'category_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned' => true,
            'nullable' => false,
        ),
        'Category Id'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(),
        'Position'
    );
$installer->getConnection()->createTable($vocherCategoryTable);

$purchaseTable = $installer->getConnection()->newTable($installer->getTable('lcb_multivoucher/purchase'))
        ->addColumn(
            'entity_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true,
            ),
            'Primary entry eky'
        )
        ->addColumn(
            'customer_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Customer ID'
        )
        ->addColumn(
            'purchase_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Purchase ID'
        )
        ->addColumn(
            'voucher_id',
            Varien_Db_Ddl_Table::TYPE_INTEGER,
            null,
            array(),
            'Voucher ID'
        )
        ->addColumn(
            'worth',
            Varien_Db_Ddl_Table::TYPE_FLOAT,
            null,
            array(),
            'Worth'
        )
        ->addColumn(
            'serial',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '255',
            array(),
            'Serial'
        )
        ->addColumn(
            'pin',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '255',
            array(),
            'Pin'
        )
        ->addColumn(
            'info',
            Varien_Db_Ddl_Table::TYPE_TEXT,
            '64k',
            array(),
            'Info'
        )
        ->addColumn(
            'expires',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Expires At'
        )
        ->addColumn(
            'created_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Created At'
        )
        ->addColumn(
            'updated_at',
            Varien_Db_Ddl_Table::TYPE_DATETIME,
            null,
            array(),
            'Updated At'
        );
$installer->getConnection()->createTable($purchaseTable);

$installer->endSetup();
