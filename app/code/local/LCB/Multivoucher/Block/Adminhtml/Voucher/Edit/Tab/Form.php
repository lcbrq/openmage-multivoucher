<?php

class LCB_Multivoucher_Block_Adminhtml_Voucher_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('voucher_form', array('legend' => Mage::helper('lcb_multivoucher')->__('Item information')));

        $fieldset->addField("active", "select", array(
            "label" => Mage::helper("lcb_multivoucher")->__("Active"),
            "name" => "active",
            'values' => Mage::getSingleton('adminhtml/system_config_source_yesno')->toArray(),
        ));

        $fieldset->addField('title', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Title'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'title',
        ));

        $fieldset->addField('description', 'textarea', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Description'),
            'class' => '',
            'name' => 'description',
        ));

        $fieldset->addField('purchase_info', 'textarea', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Purchase Info'),
            'class' => '',
            'name' => 'purchase_info',
        ));

        $fieldset->addField('worth', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Value'),
            'class' => '',
            'name' => 'worth',
        ));

        $fieldset->addField('image', 'image', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Image'),
            'name' => 'image',
            'note' => '(*.jpg, *.png, *.gif)',
        ));

        $fieldset->addField('position', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Position'),
            'name' => 'position',
        ));

        if (Mage::getSingleton('adminhtml/session')->getVoucherData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getVouchersData());
            Mage::getSingleton('adminhtml/session')->setVoucherData(null);
        } elseif (Mage::registry('voucher_data')) {
            $form->setValues(Mage::registry('voucher_data')->getData());
        }
        return parent::_prepareForm();
    }
}
