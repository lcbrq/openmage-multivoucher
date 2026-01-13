<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Model_Brand extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('lcb_multivoucher/brand');
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        $image = parent::getImage();
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            $imageUrl =  str_replace('test.', '', $image);
        } elseif ($image) {
            $imageUrl = Mage::getBaseUrl('media') . $image;
        } else {
            $imageUrl = '';
        }

        return $imageUrl;
    }
}
