## LCB_Multivoucher

Magento 1 / OpenMage module for multivoucher.pl integration

#### Configuration

Fill requred multivoucher.pl credentials in System -> Configuration -> LCBRQ -> Multivoucher

#### Usage

To fetch Multivoucher.php data use following shell commands in shell directory

```
php multivoucher.php --import vouchers
php multivoucher.php --import brands
```

Then activate rewards manually in admin or within SQL query

```
UPDATE `lcb_multivoucher_reward` SET `active` = 1;
```

#### Important

This module comes out without any native logic of points balance.

Use `lcb_multivoucher_purchase_before` and `lcb_multivoucher_purchase_after` events to set and deduct points from customer account.

E.g: create new module named Vendor_Multivoucher with following etc/config.xml

```
<?xml version="1.0"?>
<config>
    <modules>
        <Vendor_Multivoucher>
            <version>1.0.0</version>
        </Vendor_Multivoucher>
    </modules>
    <global>
        <models>
            <vendor_multivoucher>
                <class>Vendor_Multivoucher_Model</class>
            </vendor_multivoucher>
        </models>
        <events>
            <lcb_multivoucher_purchase_before>
                <observers>
                    <vendor_multivoucher_purchase_before>
                        <class>vendor/observer</class>
                        <method>setMultivoucherPointsBeforePurchase</method>
                    </vendor_multivoucher_purchase_before>
                </observers>
            </lcb_multivoucher_purchase_before>
            <lcb_multivoucher_purchase_after>
                <observers>
                    <vendor_multivoucher_purchase_after>
                        <class>vendor/observer</class>
                        <method>deductMultivoucherPointsAfterPurchase</method>
                    </vendor_multivoucher_purchase_after>
                </observers>
            </lcb_multivoucher_purchase_after>
        </events>
    </global>
</config>
```

Then use Vendor_Multivoucher_Model_Observer class with following actions:

```
    /**
     * @param Varien_Event_Observer $observer
     */
    public function setMultivoucherPointsBeforePurchase(Varien_Event_Observer $observer)
    {
        $customer = $observer->getCustomer();
        $points = $customer->getLoyaltyPoints();
        $customer->setMultivoucherPoints($points);
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function deductMultivoucherPointsAfterPurchase(Varien_Event_Observer $observer)
    {
        $customer = $observer->getCustomer();
        // @todo reduction
    }
```


#### Uninstall

```
DELETE FROM `core_resource` WHERE `core_resource`.`code` = 'lcb_multivoucher_setup';
DROP TABLE `lcb_multivoucher_reward`;
DROP TABLE `lcb_multivoucher_brand`;
DROP TABLE `lcb_multivoucher_category`;
DROP TABLE `lcb_multivoucher_reward_category`;
DROP TABLE `lcb_multivoucher_purchase`;
```

#### Disclaimer

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
