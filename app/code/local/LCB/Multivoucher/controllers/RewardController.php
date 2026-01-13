<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_RewardController extends Mage_Core_Controller_Front_Action
{
    public function purchaseAction()
    {
        $id = $this->getRequest()->getParam('id');
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        $reward = Mage::getModel('lcb_multivoucher/reward')->load($id);

        if (!$reward->getActive()) {
            Mage::getSingleton('core/session')->addError($this->__('This voucher is not available'));
            return $this->_redirectReferer();
        }

        Mage::log(Mage::helper('lcb_multivoucher')->__('cusomer %s tries to purchase mulivoucher %s', $customer->getId(), $reward->getId()), null, 'multivoucher.log', true);
        Mage::dispatchEvent(
            'lcb_multivoucher_purchase_before',
            array(
                'customer' => $customer,
                'voucher' => $reward,
            )
        );

        $points = $customer->getMultivoucherPoints();
        $rewardPoints = $reward->getPoints() ? $reward->getPoints() : $reward->getWorth();
        if ($points < $rewardPoints) {
            Mage::log(Mage::helper('lcb_multivoucher')->__('customer %s not enough points for mulivoucher %s', $customer->getId(), $reward->getId()), null, 'multivoucher.log', true);
            Mage::getSingleton('core/session')->addError($this->__('You have not enough points to obtain this reward'));
            return $this->_redirectReferer();
        }

        if ($userLimitDaily = Mage::helper('lcb_multivoucher')->getUserLimitDaily()) {
            $purchasesCollection = Mage::getModel('lcb_multivoucher/purchase')
                    ->getCollection()
                    ->addFieldToFilter('customer_id', $customer->getId())
                    ->addFieldToFilter('created_at', ['from' => date('Y-m-d H:i:s', strtotime('-24 hours', time()))]);
            $purchasesCollectionAmountValues = $purchasesCollection->getColumnValues('worth');
            $purchasesCollectionAmount = array_sum($purchasesCollectionAmountValues);
            if ($purchasesCollectionAmount > $userLimitDaily) {
                Mage::log(Mage::helper('lcb_multivoucher')->__('user daily limit exceeded'), null, 'multivoucher.log', true);
                Mage::getSingleton('core/session')->addError($this->__('Daily transaction limit exceeded'));
                return $this->_redirectReferer();
            }
        }

        if ($generalLimitDaily = Mage::helper('lcb_multivoucher')->getGeneralLimitDaily()) {
            $purchasesCollection = Mage::getModel('lcb_multivoucher/purchase')
                    ->getCollection()
                    ->addFieldToFilter('created_at', ['from' => date('Y-m-d H:i:s', strtotime('-24 hours', time()))]);
            $purchasesCollectionAmountValues = $purchasesCollection->getColumnValues('worth');
            $purchasesCollectionAmount = array_sum($purchasesCollectionAmountValues);
            if ($purchasesCollectionAmount > $generalLimitDaily) {
                Mage::log(Mage::helper('lcb_multivoucher')->__('general daily transaction limit exceeded'), null, 'multivoucher.log', true);
                Mage::getSingleton('core/session')->addError($this->__('Daily transaction limit exceeded'));
                return $this->_redirectReferer();
            }
        }

        $purchase = Mage::getModel('lcb_multivoucher/api')->purchase($customer, $reward);

        if (is_string($purchase)) {
            Mage::log($purchase, null, 'multivoucher.log', true);
            Mage::getSingleton('core/session')->addError(Mage::helper('lcb_multivoucher')->__($purchase));
            return $this->_redirectReferer();
        }

        if ($purchase->getId()) {
            Mage::log(Mage::helper('lcb_multivoucher')->__('purchased mulivoucher %s', $purchase->getId()), null, 'multivoucher.log', true);
            Mage::dispatchEvent(
                'lcb_multivoucher_purchase_after',
                array(
                    'customer' => $customer,
                    'voucher' => $reward,
                    'purchase' => $purchase,
                )
            );
            Mage::helper('lcb_multivoucher')->sendVoucherEmail($purchase);
            Mage::getSingleton('customer/session')->setVoucherPurchaseId($purchase->getId());
            Mage::getSingleton('core/session')->addSuccess($this->__('Voucher purchased'));
        } else {
            Mage::getSingleton('core/session')->addError($this->__('Please try again later'));
        }

        $this->_redirectReferer();
    }
}
