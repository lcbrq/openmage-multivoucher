<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Adminhtml_AdminMultivoucherPurchaseController extends Mage_Adminhtml_Controller_Action
{
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('multivoucher/purchases');
    }

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('lcb_multivoucher/voucher')->_addBreadcrumb(Mage::helper('adminhtml')->__('Brand Manager'), Mage::helper('adminhtml')->__('Brand Manager'));
        return $this;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('lcb_multivoucher/adminhtml_purchase_grid'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__('lcb_multivoucher'));
        $this->_title($this->__('Purchase'));
        $this->_title($this->__('View'));

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('lcb_multivoucher/purchase')->load($id);
        if ($model->getId()) {
            if ($pin = $model->getPin()) {
                $model->setPin(preg_replace("/(^.|.$)(*SKIP)(*F)|(.)/", "*", $pin)); // obscure
            }
            Mage::register('purchase_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('lcb_multivoucher/purchase');
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('lcb_multivoucher/adminhtml_purchase_edit'))->_addLeft($this->getLayout()->createBlock('lcb_multivoucher/adminhtml_purchase_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lcb_multivoucher')->__('Item does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    /**
     * @return Mage_Core_Controller_Response_Http
     */
    public function emailResendAction()
    {
        try {
            $appEmulation = Mage::getSingleton('core/app_emulation');
            $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation(Mage_Core_Model_App::DISTRO_STORE_ID);

            $id = $this->getRequest()->getParam('id');
            $purchase = Mage::getModel('lcb_multivoucher/purchase')->load($id);
            Mage::helper('lcb_multivoucher')->sendVoucherEmail($purchase);
            ;

            $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('the order confirmation email was sent'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }

    /**
     * @return Mage_Core_Controller_Response_Http
     */
    public function emailPreviewAction()
    {
        $id = $this->getRequest()->getParam('id');
        $voucherPurchase = Mage::getModel('lcb_multivoucher/purchase')->load($id);
        $customer = Mage::getModel('customer/customer')->load($voucherPurchase->getCustomerId());
        $voucher =  Mage::getModel('lcb_multivoucher/reward')->load($voucherPurchase->getVoucherId());

        if ($pin = $voucherPurchase->getPin()) {
            $voucherPurchase->setPin(preg_replace("/(^.|.$)(*SKIP)(*F)|(.)/", "*", $pin)); // obscure
        }
        if ($serial = $voucherPurchase->getSerial()) {
            $voucherPurchase->setSerial(preg_replace("/(^.|.$)(*SKIP)(*F)|(.)/", "*", $serial)); // obscure
        }
        if ($expires = $voucherPurchase->getExpires()) {
            $voucherPurchase->setExpires(date('d.m.Y', strtotime((string) $expires)));
        }

        $emailTemplateVariables = array(
            'customer' => $customer,
            'purchase' => $voucherPurchase,
            'voucher' => $voucher,
        );

        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation(Mage_Core_Model_App::DISTRO_STORE_ID);

        $html = Mage::helper('lcb_multivoucher')->getEmailTemplate()->getProcessedTemplate($emailTemplateVariables);
        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

        return $this->getResponse()->setBody($html);
    }

    /**
     * @return Mage_Core_Controller_Response_Http
     */
    public function updatePurchaseAction()
    {
        $id = $this->getRequest()->getParam('id');
        $voucherPurchase = Mage::getModel('lcb_multivoucher/purchase')->load($id);

        if ($purchaseId = $voucherPurchase->getPurchaseId()) {
            $result = Mage::getModel('lcb_multivoucher/api')->getOrder($purchaseId);
            if (!empty($result->message)) {
                Mage::getSingleton('adminhtml/session')->addError($result->message);
            } else {
                $purchasedVouchers = (array) $result->vouchers;
                foreach ($purchasedVouchers as $purchasedVoucher) {
                    $voucherPurchase->setPin($purchasedVoucher->pin ?? '');
                    $voucherPurchase->setSerial($purchasedVoucher->serial ?? '');
                    $voucherPurchase->setLink($purchasedVoucher->link ?? '');
                    $voucherPurchase->save();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Updated voucher %s', $purchaseId));
            }

            $this->_redirect('*/*/');
        }
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'voucher_purchases.csv';
        $grid = $this->getLayout()->createBlock('lcb_multivoucher/adminhtml_purchase_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'voucher_purchases.xml';
        $grid = $this->getLayout()->createBlock('lcb_multivoucher/adminhtml_purchase_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}
