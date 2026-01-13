<?php

class LCB_Multivoucher_Block_Adminhtml_Purchase_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('voucher_form', array('legend' => Mage::helper('lcb_multivoucher')->__('Item information')));

        $fieldset->addField('worth', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Value'),
            'class' => 'required-entry',
            'readonly' => true,
            'name' => 'worth',
        ));

        $fieldset->addField('info', 'textarea', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Info'),
            'class' => '',
            'name' => 'info',
        ));

        $fieldset->addField('serial', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Serial'),
            'name' => 'serial',
        ));

        $fieldset->addField('pin', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Pin'),
            'name' => 'pin',
        ));

        $fieldset->addField('link', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Link'),
            'name' => 'link',
        ));

        if (Mage::getSingleton('adminhtml/session')->getPurchaseData()) {
            $form->setValues(Mage::getSingleton('adminhtml/session')->getPurchaseData());
            Mage::getSingleton('adminhtml/session')->setPurchaseData(null);
        } elseif (Mage::registry('purchase_data')) {
            $form->setValues(Mage::registry('purchase_data')->getData());
        }
        return parent::_prepareForm();
    }
}
