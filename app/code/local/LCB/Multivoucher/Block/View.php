<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 */
class LCB_Multivoucher_Block_View extends Mage_Core_Block_Template
{
    /**
     * @return Varien_Object
     */
    public function getVoucher()
    {
        $voucherId = $this->getRequest()->getParam('id');

        return Mage::getModel('lcb_multivoucher/reward')->load($voucherId);
    }
}
