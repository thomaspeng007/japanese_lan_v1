<?php
class Chinese_Lesson_Model_Jpcustomerlesson extends Mage_Core_Model_Abstract
{
	private $customerId;
	private $productId;
	
	protected function _construct()
	{
		$this->_init('lesson/jpcustomerlesson');
	}
	
	public function setCustomerId($customerId)
	{
		$this->customerId = (int)$customerId;
	}
	
	public function setProductId($productId)
	{
		$this->productId = (int)$productId;
	}

	/**
	 * Retrieve the vocabulareis from current lesson
	 * @return array of all dialogues under current lesson
	 */
    public function getCustomerProduct()
	{
		
		$collection = $this->getCollection()->addFieldToFilter('customer_id',$this->customerId);
		                                   
		return $collection;
	}
	
	public function getCustomerFavorite()
	{
		
		$collection = $this->getCollection()->addFieldToFilter('customer_id',$this->customerId)
		                                    ->addFieldToFilter('favorite', true);
	    $productIds = array();
		foreach ($collection as $collect){
			array_push($productIds, $collect->getProduct_id());
		}
		return $productIds;
		
	}
	public function setProductStatus()
	{
		$collection = $this->loadCustomerProduct();
		foreach ($collection as $collect){
			$id = $collect->getId();
			$productStatus = $collect->getStatus();
			break;
		}
		
		if(empty($id)) {
			$productStatus=1;
		    $this ->setStatus($productStatus)
		          ->setCustomer_id($this->customerId)
		          ->setProduct_id($this->productId)
	              ->save();
		}else{
		    if($productStatus==1){ $productStatus = 0; } else { $productStatus = 1; }
			$this->load($id)
		         ->setStatus($productStatus)
	             ->save();
		}
		
	    return $this;			       
	}
	
    public function getProductStatus()
	{
		$collection = $this->loadCustomerProduct();
		foreach ($collection as $collect){
			$id = $collect->getId();
			$productStatus = $collect->getStatus();
			break;
		}
	    return $productStatus;			       
	}
	
	public function setFavoriteStatus()
	{
        $collection = $this->loadCustomerProduct();
		foreach ($collection as $collect){
			$id = $collect->getId();
			$favoriteStatus = $collect->getFavorite();
			break;
		}
		
		if(empty($id)) {
			$favoriteStatus=1;
		    $this ->setFavorite($favoriteStatus)
		          ->setCustomer_id($this->customerId)
		          ->setProduct_id($this->productId)
	              ->save();
		}else{
		    if($favoriteStatus==1){ $favoriteStatus = 0; } else { $favoriteStatus = 1; }
			$this->load($id)
		         ->setFavorite($favoriteStatus)
	             ->save();
		}
		
	    return $this;			      
		
	}
	
    public function getFavoriteStatus()
	{
        $collection = $this->loadCustomerProduct();
		foreach ($collection as $collect){
			$id = $collect->getId();
			$favoriteStatus = $collect->getFavorite();
			break;
		}
		
	    return $favoriteStatus;			      
		
	}
	
	public function loadCustomerProduct()
	{
		$collection = $this->getCollection()->addFieldToFilter('customer_id', $this->customerId)
		                                    ->addFieldToFilter('product_id', $this->productId);
		return $collection;
	}
	

}





