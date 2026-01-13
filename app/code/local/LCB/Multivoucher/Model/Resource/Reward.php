<?php

class LCB_Multivoucher_Model_Resource_Reward extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Reward category table name
     *
     * @var string
     */
    protected $_rewardCategoryTable = 'lcb_multivoucher_reward_category';

    public function _construct()
    {
        $this->_init('lcb_multivoucher/reward', 'entity_id');
    }

    /**
     * Get categories associated to voucher
     *
     * @param  LCB_Multivoucher_Model_Reward $reward
     * @return array
     */
    public function getCategories($reward)
    {
        $select = $this->_getWriteAdapter()->select()
            ->from($this->_rewardCategoryTable, array('position', 'category_id'))
            ->where('reward_id = :reward_id');
        $bind = array('reward_id' => (int) $reward->getId());

        return $this->_getWriteAdapter()->fetchPairs($select, $bind);
    }

    /**
     * Save category reward relation
     *
     * @param  LCB_Multivoucher_Model_Reward $reward
     * @return $this
     */
    protected function _saveCategoryIds($reward)
    {
        $reward->setIsChangedCategoriesList(false);
        $id = $reward->getId();

        /**
         * new article-part relationships
         */
        $categoryIds = $reward->getCategoryIds();

        /**
         * Ignore save on null
         */
        if ($categoryIds === null) {
            return $this;
        }

        /**
         * old voucher-category relationships
         */
        $oldCategoryIds = $this->getCategories($reward);

        $insert = array_diff_key($categoryIds, $oldCategoryIds);
        $delete = array_diff_key($oldCategoryIds, $categoryIds);

        /**
         * Find voucher ids which are presented in both arrays
         * and saved before (check $oldCategoryIds array)
         */
        $update = array_intersect_key($categoryIds, $oldCategoryIds);
        $update = array_diff_assoc($update, $oldCategoryIds);

        $adapter = $this->_getWriteAdapter();

        /**
         * Delete vouchers from category
         */
        if (!empty($delete)) {
            $cond = array(
                'category_id IN(?)' => array_keys($delete),
                'voucher_id=?' => $id,
            );
            $adapter->delete($this->_rewardCategoryTable, $cond);
        }

        /**
         * Add vouchers to category
         */
        if (!empty($insert)) {
            $data = array();
            foreach ($insert as $position => $categoryId) {
                $data[] = array(
                    'reward_id' => (int)$id,
                    'category_id'  => (int) $categoryId,
                    'position'    => (int)$position,
                );
            }
            $adapter->insertMultiple($this->_rewardCategoryTable, $data);
        }

        /**
         * Update vouchers positions in category
         */
        if (!empty($update)) {
            foreach ($update as $position => $categoryId) {
                $where = array(
                    'reward_id = ?' => (int) $id,
                    'category_id = ?' => (int) $categoryId,
                );
                $bind  = array('position' => (int)$position);
                $adapter->update($this->_rewardCategoryTable, $bind, $where);
            }
        }

        return $this;
    }

    protected function _beforeSave(Mage_Core_Model_Abstract $object)
    {
        if ($object->isObjectNew() || !$object->getId() || !$object->getCreatedAt()) {
            $object->setCreatedAt(Varien_Date::now());
        }

        $object->setUpdatedAt(Varien_Date::now());

        return parent::_beforeSave($object);
    }

    /**
     * Process category data after voucher object save
     *
     * @param Varien_Object $object
     * @inheritDoc
     */
    protected function _afterSave(Varien_Object $object)
    {
        $this->_saveCategoryIds($object);
        return parent::_afterSave($object);
    }
}
