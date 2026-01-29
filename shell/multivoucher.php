<?php

require_once 'abstract.php';

class LCB_Multivoucher_Shell extends Mage_Shell_Abstract
{
    /**
     * Run command against given action
     *
     * @return void
     */
    public function run()
    {
        $import = $this->getArg('import');
        $refresh = $this->getArg('refresh');

        if (!$import) {
            return print_r($this->usageHelp());
        }

        if ($import === 'vouchers') {
            $vouchers = Mage::getModel('lcb_multivoucher/api')->getVouchers();
            foreach ($vouchers as $voucherData) {
                $reward = Mage::getModel('lcb_multivoucher/reward')->load($voucherData['id'], 'voucher_id');

                if (!$refresh && $reward->getId()) {
                    continue;
                }

                $voucherData['brand_id'] = $voucherData['brand'];
                if (!empty($voucherData['tags']) && is_array($voucherData['tags'])) {
                    $tags = implode(',', $voucherData['tags']);
                    $reward->setTags($tags);
                    unset($voucherData['tags']);
                }
                if (!empty($voucherData['categories']) && is_array($voucherData['categories'])) {
                    $categoryIds = array();
                    foreach ($voucherData['categories'] as $categoryName) {
                        $category = Mage::getModel('lcb_multivoucher/category')->load($categoryName, 'name');
                        $category->setName($categoryName);
                        $category->save();
                        $categoryIds[] = $category->getId();
                    }
                    $reward->setCategoryIds($categoryIds);
                }
                $reward->setVoucherId($voucherData['id']);
                $reward->addData($voucherData);
                $reward->save();

                echo sprintf("Imported voucher %s\n", $reward->getTitle());

            }
        }
        if ($import === 'brands') {
            $vouchersCollection = Mage::getModel('lcb_multivoucher/reward')->getCollection()
                    ->addFieldToFilter('brand_id', ['notnull' => true]);
            $vouchersCollection->getSelect()->group('brand_id');
            $vouchersBrandIds = $vouchersCollection->getColumnValues('brand_id');
            foreach ($vouchersBrandIds as $brandId) {
                $brandData = Mage::getModel('lcb_multivoucher/api')->getBrand($brandId);
                $brand = Mage::getModel('lcb_multivoucher/brand')->load($brandData['id'], 'brand_id');
                if (!$refresh && $brand->getId()) {
                    continue;
                }

                $brand->setBrandId($brandData['id']);
                $brand->addData($brandData);
                $brand->save();

                echo sprintf("Imported brand %s\n", $brand->getName());

            }
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
    Usage:  php multivoucher.php --import vouchers\n
        --refresh reload existing vouchers from API\n
USAGE;
    }

    /**
     * Print output
     *
     * @param string $message
     */
    public function output($message)
    {
        echo "$message\n";
    }
}

$shell = new LCB_Multivoucher_Shell();
$shell->run();
