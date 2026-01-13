<?php

class LCB_Multivoucher_Model_Resource_Category extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('lcb_multivoucher/category', 'entity_id');
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->isObjectNew() || !$object->getId() || !$object->getCreatedAt()) {
            $object->setCreatedAt(Varien_Date::now());
        }

        $object->setUpdatedAt(Varien_Date::now());

        return parent::_beforeSave($object);
    }
}
