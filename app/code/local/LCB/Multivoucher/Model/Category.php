<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Model_Category extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_multivoucher/category');
    }
}
