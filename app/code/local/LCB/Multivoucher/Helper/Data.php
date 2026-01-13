<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 */
class LCB_Multivoucher_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @var bool
     */
    public const DEVELOPMENT_MODE = false;

    /**
     * @var string
     */
    public const XPATH_USER_LIMIT_DAILY = 'multivoucher/limit/user_daily';

    /**
     * @var string
     */
    public const XPATH_GENERAL_LIMIT_DAILY = 'multivoucher/limit/general_daily';

    /**
     * Get amount of points that can be spend daily per user
     *
     * @return int
     */
    public function getUserLimitDaily()
    {
        return (int) Mage::getStoreConfig(self::XPATH_USER_LIMIT_DAILY);
    }

    /**
     * Get amount of points that can be spend daily for all users
     *
     * @return int
     */
    public function getGeneralLimitDaily()
    {
        return (int) Mage::getStoreConfig(self::XPATH_GENERAL_LIMIT_DAILY);
    }

    /**
     * @return Mage_Core_Model_Email_Template
     */
    public function getEmailTemplate()
    {
        $emailTemplate = Mage::getModel('core/email_template');

        $templateId = Mage::getStoreConfig('multivoucher/order/email_template', Mage::app()->getStore()->getId());
        $emailTemplate->load($templateId);
        if (!$emailTemplate || !$emailTemplate->getId()) {
            $emailTemplate->loadDefault('multivoucher_purchase');
        }

        Mage::dispatchEvent('lcb_multivoucher_purchase_email_template', array('template' => $emailTemplate));

        return $emailTemplate;
    }

    /**
     * @param  LCB_Multivoucher_Model_Purchase $voucherPurchase
     * @return bool
     */
    public function sendVoucherEmail($voucherPurchase)
    {
        $customer = Mage::getModel('customer/customer')->load($voucherPurchase->getCustomerId());
        $voucher =  Mage::getModel('lcb_multivoucher/reward')->load($voucherPurchase->getVoucherId());
        $emailTemplate = $this->getEmailTemplate();
        $emailTemplateVariables = array(
            'customer' => $customer,
            'purchase' => $voucherPurchase,
            'voucher' => $voucher,
        );

        if ($expires = $voucherPurchase->getExpires()) {
            $voucherPurchase->setExpires(date('d.m.Y', strtotime((string) $expires)));
        }

        $senderName = Mage::getStoreConfig('trans_email/ident_general/name');
        $senderEmail = Mage::getStoreConfig('trans_email/ident_general/email');
        $emailTemplate->setSenderName($senderName);
        $emailTemplate->setSenderEmail($senderEmail);

        if (self::DEVELOPMENT_MODE) {
            $text = $emailTemplate->getProcessedTemplate($emailTemplateVariables, true);
            var_dump($text);
            exit('DEVELOPMENT_MODE');
        }

        $emailTemplate->send($customer->getEmail(), $customer->getName(), $emailTemplateVariables);

        return true;
    }
}
