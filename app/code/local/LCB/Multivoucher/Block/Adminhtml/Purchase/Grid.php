<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Block_Adminhtml_Purchase_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('multivouchersPurchaseGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('lcb_multivoucher/purchase')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header' => Mage::helper('lcb_multivoucher')->__('ID'),
            'align' => 'right',
            'width' => '50px',
            'type' => 'number',
            'index' => 'entity_id',
        ));

        $this->addColumn('purchase_id', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Purchase ID'),
            'align' => 'right',
            'width' => '50px',
            'type' => 'number',
            'index' => 'purchase_id',
        ));

        $this->addColumn('customer_id', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Customer ID'),
            'align' => 'right',
            'width' => '50px',
            'type' => 'number',
            'index' => 'customer_id',
        ));

        $this->addColumn('voucher_id', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Voucher ID'),
            'align' => 'right',
            'width' => '50px',
            'type' => 'number',
            'index' => 'voucher_id',
        ));

        $this->addColumn('worth', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Worth'),
            'index' => 'worth',
        ));

        $this->addColumn('serial', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Serial'),
            'index' => 'serial',
        ));

        $this->addColumn('expires', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Expires'),
            'index' => 'expires',
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('lcb_multivoucher')->__('Created At'),
            'align'     => 'left',
            'width'     => '100px',
            'type'      => 'datetime',
            'index'     => 'created_at',
        ));

        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        return $this;
    }
}
