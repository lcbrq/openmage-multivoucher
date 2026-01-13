<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Model_Purchase extends Mage_Core_Model_Abstract
{
    /**
     * @return LCB_Multivoucher_Model_Reward
     */
    public function getVoucher()
    {
        return Mage::getModel('lcb_multivoucher/reward')->load($this->getVoucherId());
    }

    protected function _construct()
    {
        $this->_init('lcb_multivoucher/purchase');
    }
}
