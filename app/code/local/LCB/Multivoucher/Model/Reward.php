<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Model_Reward extends Mage_Core_Model_Abstract
{
    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'lcb_multivoucher_reward';

    /**
     * Parameter name in event
     *
     * In observe method you can use $observer->getEvent()->getObject() in this case
     *
     * @var string
     */
    protected $_eventObject = 'voucher';

    protected function _construct()
    {
        $this->_init('lcb_multivoucher/reward');
    }

    /**
     * @return LCB_Multivoucher_Model_Brand
     */
    public function getBrand()
    {
        return Mage::getModel('lcb_multivoucher/brand')->load($this->getBrandId(), 'brand_id');
    }

    /**
     * Get categories assigned to this reward
     *
     * @return LCB_Multivoucher_Model_Resource_Category_Collection|array
     */
    public function getCategoryCollection()
    {
        $categoryIds = $this->getResource()->getCategories($this);

        if (!$categoryIds) {
            return array();
        }

        $categoryCollection = Mage::getModel('lcb_multivoucher/category')->getCollection()
            ->addFieldToFilter('entity_id', ['IN' => $categoryIds]);

        return $categoryCollection;
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
