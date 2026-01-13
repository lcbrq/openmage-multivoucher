<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Block_Adminhtml_Voucher extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_voucher';
        $this->_blockGroup = 'lcb_multivoucher';
        $this->_headerText = Mage::helper('lcb_multivoucher')->__('Multivoucher Manager');
        $this->_addButtonLabel = Mage::helper('lcb_multivoucher')->__('Add New Item');
        parent::__construct();
    }
}
