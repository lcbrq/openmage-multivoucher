<?php

class LCB_Multivoucher_Block_Adminhtml_Voucher_Renderer_Image extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        return $this->_getValue($row);
    }

    protected function _getValue(Varien_Object $row)
    {
        $output = '';
        $image = $row->getData($this->getColumn()->getIndex());
        if ($image) {
            if (filter_var($image, FILTER_VALIDATE_URL)) {
                $image = str_replace('test.', '', $image);
            } else {
                $image = Mage::getBaseUrl('media') . $image;
            }
            $output = "<img src=" . $image . " width='60px'/>";
        }
        return $output;
    }
}
