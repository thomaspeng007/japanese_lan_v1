<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sales pricing helper
 *
 * @category    Chinese
 * @package     Chinese_Sales
 * @author      THOMAS
 */
class Chinese_Lesson_Helper_Pricing extends Mage_Core_Helper_Abstract
{
	/**
	 * Pricing params
	 */
	protected $interval;
	
    /**
     * Get virtual product interval based on given pricing product id from each order
     * @return string
     */
	public function getProductInterval($order_id) {
		
	    $order = Mage::getModel('sales/order')->load($order_id, 'increment_id');
        $orderItems = $order->getItemsCollection()
                            ->addAttributeToSelect('*')
                            ->addAttributeToFilter('product_type', array('eq'=>'virtual'))
                            ->load();
        $ids = array();                    
        //Loop through order Items
        foreach($orderItems as $sItem) {
            //Make sure Mage_Catalog_Model_Product_Type = 'virtual';
            if($sItem->getProductType() == "virtual") {
                //Simple Item Info from Order 
                array_push($ids, $sItem->getProductId());
                //echo "Product Id: ".$sItem->getProductId()."\n";
            }
        }      
        //List of virtual product ids for pricing
        $p1month = 43;
        $p3month = 44;
        $p6month = 45;
        $vid = array($p1month, $p3month, $p6month);
        $result = array_intersect($ids, $vid);
        if(count($result)==1) {
        	$pricingId = $result[0];
        	switch ($pricingId) {
        		case $p1month:
                   $interval = "P1M";
                   break;
                case $p3month:
                   $interval = "P3M";
                   break;
                case $p6month:
                   $interval = "P6M";
                   break;
 
        	}
        }else {
        	Mage::log("order id: ".$order_id." has exceptional pricing products per transaction, only one product allow per order from pricing category");
        }
        if($interval) {
        	$this->interval = $interval;
        	return $this;
        }
        return;
	}
	
	/**
	 * Set pricing product expiring date
	 */
	public function setProductExpireDate($createDate) {
		
		$date = new DateTime($createDate);
        $interval = new DateInterval($this->interval);
        $date->add($interval);
        $expireDate = $date->format('Y/m/d');
        unset($date);
        unset($interval);
        return $expireDate;
	}
    
}