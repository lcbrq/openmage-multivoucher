<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Block_Adminhtml_Voucher_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('multivouchersGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('lcb_multivoucher/reward')->getCollection();
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

        $this->addColumn('voucher_id', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Voucher ID'),
            'index' => 'voucher_id',
        ));

        $this->addColumn("active", array(
            "header" => Mage::helper("lcb_multivoucher")->__("Active"),
            "type" => "options",
            "options" => Mage::getSingleton('adminhtml/system_config_source_yesno')->toArray(),
            "index" => "active",
        ));

        $this->addColumn('image', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Image'),
            'index' => 'image',
            'width' => '50px',
            'renderer' => 'LCB_Multivoucher_Block_Adminhtml_Voucher_Renderer_Image',
        ));

        $this->addColumn('title', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Name'),
            'index' => 'title',
        ));

        $this->addColumn('worth', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Value'),
            'index' => 'worth',
        ));

        $this->addColumn('position', array(
            'header' => Mage::helper('lcb_multivoucher')->__('Position'),
            'index' => 'position',
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
