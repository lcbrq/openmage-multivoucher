<?php

class LCB_Multivoucher_Block_Adminhtml_Voucher_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        $this->_objectId = "id";
        $this->_blockGroup = 'lcb_multivoucher';
        $this->_controller = "adminhtml_voucher";
        $this->_updateButton("save", "label", Mage::helper('lcb_multivoucher')->__("Save Item"));
        $this->_updateButton("delete", "label", Mage::helper('lcb_multivoucher')->__("Delete Item"));

        $this->_addButton("saveandcontinue", array(
            "label" => Mage::helper('lcb_multivoucher')->__("Save And Continue Edit"),
            "onclick" => "saveAndContinueEdit()",
            "class" => "save",
                ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }";
    }

    public function getHeaderText()
    {
        if (Mage::registry("voucher_data") && Mage::registry("voucher_data")->getId()) {
            return Mage::helper('lcb_multivoucher')->__("Edit Voucher '%s'", $this->htmlEscape(Mage::registry('voucher_data')->getTitle()));
        } else {
            return Mage::helper('lcb_multivoucher')->__("Add Voucher");
        }
    }
}
