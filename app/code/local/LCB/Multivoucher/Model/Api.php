<?php

/**
 * @author Tomasz Gregorczyk <tomasz@silpion.com.pl>
 * @author Dominik Rakszawski <d.rakszawski@silpion.io>
 */
class LCB_Multivoucher_Model_Api
{
    /**
     * @var string
     */
    private $endpoint = 'https://api.multivoucher.pl';

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $token;

    /**
     * Class constructor
     */
    public function __construct()
    {
        if (Mage::getStoreConfig('multivoucher/api/test')) {
            $this->endpoint = 'https://testapi.multivoucher.pl';
        }
        $this->username = Mage::getStoreConfig('multivoucher/api/username');
        $this->password = Mage::getModel('core/encryption')->decrypt((string) Mage::getStoreConfig('multivoucher/api/password'));
    }

    /**
     * @return void
     */
    public function authorize()
    {
        $response = $this->request('api/login_check', array(
            "username" => $this->username,
            "password" => $this->password,
        ));
        $result = json_decode($response);

        $this->token = $result->token;
    }

    /**
     * @return array
     */
    public function getVouchers()
    {
        $this->authorize();
        $response = $this->request('api/get_products/');
        $vouchers = json_decode($response, true);

        return $vouchers;
    }

    /**
     * @param  int   $brandId
     * @return array
     */
    public function getBrand($brandId)
    {
        $this->authorize();
        $response = $this->request("api/get_brand/$brandId/");
        $brandData = json_decode($response, true);

        return $brandData;
    }

    /**
     * @param Mage_Customer_Model_Customer
     * @param LCB_Multivoucher_Model_Reward
     * @return LCB_Multivoucher_Model_Purchase|string
     */
    public function purchase($customer, $voucher)
    {
        $this->authorize();
        $response = $this->request('api/buy_vouchers/', array(
           "product_id" => $voucher->getVoucherId(),
           "quantity" => 1,
        ));

        if (Mage::getStoreConfigFlag('multivoucher/api/log_all_responses')) {
            Mage::log($response, null, 'multivoucher.log', true);
        }

        $result = json_decode($response);
        $purchase = Mage::getModel('lcb_multivoucher/purchase');

        if (!empty($result->id)) {
            try {
                $purchasedVouchers = (array) $result->vouchers;
                foreach ($purchasedVouchers as $purchasedVoucher) {
                    $purchase->setCustomerId($customer->getId());
                    $purchase->setPurchaseId($result->id);
                    $purchase->setVoucherId($voucher->getId());
                    $purchase->setSerial($purchasedVoucher->serial);
                    $purchase->setPin(isset($purchasedVoucher->pin) ? $purchasedVoucher->pin : '');
                    $purchase->setLink($purchasedVoucher->link ?? '');
                    $purchase->setInfo(isset($purchasedVoucher->info) ? $purchasedVoucher->info : '');
                    $purchase->setWorth($purchasedVoucher->worth);
                    $purchase->setExpires($purchasedVoucher->expires);
                    $purchase->save();
                }
            } catch (Exception $e) {
                Mage::logException($e);
            }
        } elseif (!empty($result->message)) {
            Mage::log(json_encode($result), null, 'multivoucher.log', true);
            return (string) $result->message;
        }

        return $purchase;
    }

    /**
     * @param string $number
     * @return stdClass
     */
    public function getOrder($number)
    {
        $this->authorize();
        $response = $this->request('api/get_order/' . $number);

        return json_decode($response);
    }

    /**
     * @param  string $path
     * @param  array  $data
     * @return string
     */
    private function request($path, $data = array())
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->endpoint . '/' . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers =  $data ? ['Content-Type:application/json'] : [];
        if ($this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}
