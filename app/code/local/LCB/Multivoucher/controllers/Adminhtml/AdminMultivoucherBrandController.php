<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Piotr Dzierka <p.dzierka@silpion.io>
 */
class LCB_Multivoucher_Adminhtml_AdminMultivoucherBrandController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @inheritDoc
     */
    public const ADMIN_RESOURCE = 'multivoucher/brands';

    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('lcb_multivoucher/voucher')->_addBreadcrumb(Mage::helper('adminhtml')->__('Brand Manager'), Mage::helper('adminhtml')->__('Brand Manager'));
        return $this;
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock('lcb_multivoucher/adminhtml_brand'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->_title($this->__('lcb_multivoucher'));
        $this->_title($this->__('Brand'));
        $this->_title($this->__('Edit Item'));

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('lcb_multivoucher/brand')->load($id);
        if ($model->getId()) {
            Mage::register('brand_data', $model);
            $this->loadLayout();
            $this->_setActiveMenu('lcb_multivoucher/brand');
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Brands Manager'), Mage::helper('adminhtml')->__('Brands Manager'));
            $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Brands Description'), Mage::helper('adminhtml')->__('Brands Description'));
            $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock('lcb_multivoucher/adminhtml_brand_edit'))->_addLeft($this->getLayout()->createBlock('lcb_multivoucher/adminhtml_brand_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('lcb_multivoucher')->__('Item does not exist.'));
            $this->_redirect('*/*/');
        }
    }

    public function newAction()
    {
        $this->_title($this->__('lcb_multivoucher'));
        $this->_title($this->__('Vouchers'));
        $this->_title($this->__('New Item'));

        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('lcb_multivoucher/brand')->load($id);

        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register('brand_data', $model);

        $this->loadLayout();
        $this->_setActiveMenu('lcb_multivoucher/brand');

        $this->getLayout()->getBlock('head')->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper('adminhtml')->__('Brand Manager'), Mage::helper('adminhtml')->__('Voucher Manager'));
        $this->_addContent($this->getLayout()->createBlock('lcb_multivoucher/adminhtml_brand_edit'))
                ->_addLeft($this->getLayout()->createBlock('lcb_multivoucher/adminhtml_brand_edit_tabs'));

        $this->renderLayout();
    }

    public function saveAction()
    {
        $postData = $this->getRequest()->getPost();

        if ($postData) {
            try {
                try {
                    if (!empty($postData['image']) && !empty($postData['image']['delete'])) {
                        $postData['image'] = '';
                    } else {
                        unset($postData['image']);

                        if (isset($_FILES)) {
                            if ($_FILES['image']['name']) {
                                if ($this->getRequest()->getParam('id')) {
                                    $model = Mage::getModel('lcb_multivoucher/brand')->load($this->getRequest()->getParam('id'));
                                    if ($model->getData('image')) {
                                        $io = new Varien_Io_File();
                                        $io->rm(Mage::getBaseDir('media') . DS . 'multivoucher' . DS . 'brand' . DS . $model->getData('image'));
                                    }
                                }
                                $path = Mage::getBaseDir('media') . DS . 'multivoucher' . DS . 'brand' . DS;
                                $uploader = new Varien_File_Uploader('image');
                                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                                $uploader->setAllowRenameFiles(false);
                                $uploader->setFilesDispersion(false);
                                $destFile = $path . preg_replace('/[^a-zA-Z0-9-_\.]/', '', $_FILES['image']['name']);
                                $filename = $uploader->getNewFileName($destFile);
                                $uploader->save($path, $filename);

                                $postData['image'] = 'multivoucher/brand/' . $filename;
                            }
                        }
                    }
                } catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                    $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                    return;
                }

                if ($this->getRequest()->getParam('id')) {
                    $model = Mage::getModel('lcb_multivoucher/brand')->load($this->getRequest()->getParam('id'));
                } else {
                    $model = Mage::getModel('lcb_multivoucher/brand');
                }

                $model->addData($postData)
                        ->setId($this->getRequest()->getParam('id'))
                        ->save();

                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Brand was successfully saved'));
                Mage::getSingleton('adminhtml/session')->setVouchersData(false);

                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setVouchersData($this->getRequest()->getPost());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('id') > 0) {
            try {
                $model = Mage::getModel('lcb_multivoucher/brand');
                $model->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            }
        }
        $this->_redirect('*/*/');
    }

    public function massRemoveAction()
    {
        try {
            $ids = $this->getRequest()->getPost('ids', array());
            foreach ($ids as $id) {
                $model = Mage::getModel('lcb_multivoucher/brand');
                $model->setId($id)->delete();
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('adminhtml')->__('Item(s) was successfully removed'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName = 'voucher_brand.csv';
        $grid = $this->getLayout()->createBlock('lcb_multivoucher/adminhtml_brand_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }

    /**
     * Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName = 'voucher_brand.xml';
        $grid = $this->getLayout()->createBlock('lcb_multivoucher/adminhtml_brand_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}
