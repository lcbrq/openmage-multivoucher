<?php

class LCB_Multivoucher_Model_Resource_Brand_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('lcb_multivoucher/brand');
    }
}
