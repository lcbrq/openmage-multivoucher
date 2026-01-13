<?php

class LCB_Multivoucher_Block_Adminhtml_Purchase_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = 'lcb_multivoucher';
        $this->_controller = "adminhtml_voucher";
        $this->_removeButton('save');
        $this->_removeButton('delete');
        $this->_addButton('preview', array(
           'label'     => Mage::helper('lcb_multivoucher')->__('Preview Email'),
           'onclick'   => 'previewMultivoucherEmail()',
           'class'     => 'duplicate',
        ));
        $this->_addButton('update', array(
           'label'     => Mage::helper('lcb_multivoucher')->__('Update'),
           'onclick'   => 'updateMultivoucherPurchase()',
           'class'     => 'duplicate',
        ));
        $this->_addButton('resend', array(
           'label'     => Mage::helper('lcb_multivoucher')->__('Resend'),
           'onclick'   => 'resendMultivoucherEmail()',
           'class'     => 'duplicate',
        ));

        $previewUrl = $this->getUrl('*/*/emailPreview/id/' . $this->getRequest()->getParam($this->_objectId));
        $this->_formScripts[] = "function previewMultivoucherEmail() { setLocation('$previewUrl');}";
        $updateUrl = $this->getUrl('*/*/updatePurchase/id/' . $this->getRequest()->getParam($this->_objectId));
        $this->_formScripts[] = "function updateMultivoucherPurchase() { setLocation('$updateUrl');}";
        $resendUrl = $this->getUrl('*/*/emailResend', array('id' => $this->getRequest()->getParam($this->_objectId)));
        $this->_formScripts[] = "function resendMultivoucherEmail() { setLocation('$resendUrl');}";
    }

    public function getHeaderText()
    {
        if (Mage::registry("purchase_data") && Mage::registry("purchase_data")->getId()) {
            return Mage::helper('lcb_multivoucher')->__("Purchase '%s'", $this->htmlEscape(Mage::registry('purchase_data')->getId()));
        }
    }
}
