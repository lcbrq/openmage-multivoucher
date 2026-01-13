<?php

class LCB_Multivoucher_Block_Adminhtml_Voucher_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('voucher_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('lcb_multivoucher')->__('Item Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('lcb_multivoucher')->__('About Voucher'),
            'title' => Mage::helper('lcb_multivoucher')->__('About Voucher'),
            'content' => $this->getLayout()->createBlock('lcb_multivoucher/adminhtml_voucher_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}
