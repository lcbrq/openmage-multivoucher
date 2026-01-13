<?php

class LCB_Multivoucher_Block_Adminhtml_Brand_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        $fieldset = $form->addFieldset('voucher_form', array('legend' => Mage::helper('lcb_multivoucher')->__('Item information')));

        $fieldset->addField('name', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Name'),
            'class' => 'required-entry',
            'required' => true,
            'name' => 'name',
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

        $fieldset->addField('url', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Url'),
            'class' => '',
            'name' => 'url',
        ));

        $fieldset->addField('regulations_url', 'text', array(
            'label' => Mage::helper('lcb_multivoucher')->__('Regulations Url'),
            'class' => '',
            'name' => 'regulations_url',
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
        } elseif (Mage::registry('brand_data')) {
            $form->setValues(Mage::registry('brand_data')->getData());
        }
        return parent::_prepareForm();
    }
}
