<?php
class Chinese_Lesson_Model_Jppricing extends Mage_Core_Model_Abstract
{
	
    private $customerId = null;
	private $orderId = null;
	private $status = null;
	private $createdAt = null;
	private $expireDate = null;
	
	protected function _construct()
	{
		$this->_init('lesson/jppricing');
	}
	
	public function initialOrder($customer_id, $order_id) {
		$this->customerId = $customer_id;
		$this->orderId = $order_id;
		$date = new DateTime($createDate);
		$this->createdAt = $date->format('Y/m/d');
		return $this;
	}
	
	public function setOrder() {
		$this->setCustomer_id($this->customerId)
		     ->setOrder_id($this->orderId)
		     ->setCreated_at($this->createdAt)
		     ->setExpire_date($this->setExpireDate())
		     ->setStatus($this->status)
		     ->save();
	}
	
	private function setExpireDate() {
		/***
		 * Thomas:
		 * First to find out if customer is new or returned one
		 * By using the last record of SELECT, for example: 
		 * select * from jp_pricing where customer_id = 1 order by id DESC LIMIT 1;
		 * if sql query return NULL, it's new customer, otherwise it's returned customer. we then need to 
		 * check if it's an extended order or a different new order by checking the last order's expire date
		 */
		$pricing = $this->getCollection()
		                ->addFieldToFilter('customer_id',$this->customerId)
		                ->setOrder('id');     
		//Initialse pricing helper to calculate the actual exprire date
		$helper = Mage::helper("lesson/pricing");              
	    if($pricing){
	    	//Return query sorted by DESC, the first one is the last order and we only need the last order
	    	foreach ($pricing as $obj) {
			    $lastExpireDate = $obj->expire_date;
			    break;
		    }         
		    //If no record return then $lastExpireDate = null, which means equals 0, new order
		    if($this->isNewOrder($lastExpireDate)) { //If a different new order
		    	$this->status = (int)1; // 1 stand for 'new'
		    	//Load helper
		    	$newExpireDate = $helper->getProductInterval($this->orderId)
		    	                        ->setProductExpireDate($this->createdAt);
		    	return $newExpireDate;    	
		    } else { //This order is an extend order
		    	$this->status = (int)2; // 2 stand for 'extend'
		    	$newExpireDate = $helper->getProductInterval($this->orderId)
		    	                        ->setProductExpireDate($lastExpireDate);
		    	return $newExpireDate;
		    }
	    } else { //It should return pricing collection object, otherwise error return
	    	Mage::log("pricing collection object failed to load");  	
	    }
		                
		
	}
	
	private function isNewOrder($lastExpireDate) {
		$date = new DateTime($createDate);
		
		$createdAt = $date->format('Y-m-d');
		if(strtotime($lastExpireDate) < strtotime($createdAt)) {
			return true; // new order
		} else {
			return false;
		}		
	}
	
	/**
	 * Display all the order history based on customer id
	 * @return array
	 */ 
	public function getOrderHistory($customerId) {
		//Return pricing object collection
		$historyObj = $this->getCollection()
		                   ->addFieldToFilter('customer_id',$customerId);
	    //Convert history object into array with order_id as key
	    // we only need to retrive three values: order_id, status(1 = 'new', 2 = 'extend'), expire_date
	    $historyArr = array();
	    if($historyObj) {
	    	foreach($historyObj as $obj) {
	    		
	    		if((int)$obj->getStatus() ==1) {
	    			$status = '新規'; // new
	    		} else {
	    			$status = '延長'; // extend
	    		}	
	    		$historyArr[$obj->order_id] = array('status'=>$status, 'expireDate'=>$obj->getExpire_date());
	    	}
	    }
	    return $historyArr;
	}	
}