<?php
class Chinese_Lesson_Helper_Createproduct extends Mage_Core_Helper_Abstract
{
	/***
	 * below attributes are must for any new product
	 * fail to give valid value will fail to create new one
	 */
	public $sku = NULL;
	public $attributeSetId = 4; // downloadable type
	public $typeId = 'downloadable'; // set as default
	public $name = NULL;
	public $categoryIds = array(3);
	public $websiteIds = array(1);
	public $description = NULL;
	public $shortDescription = NULL; 
	public $price = 0; // set it to 0 as there's no price individually 
	
	private $_product = NULL;
	
	protected function _construct()
	{
	
	}
	
	
	/**
	 * @return new downloadable product object
	 */
	public function createProduct()
	{
		//$product = new Mage_Catalog_Model_Product();
		//return $product;
		$product = Mage::getModel('catalog/product');
											
		// Build the product
		$product->setSku($this->sku);
		$product->setAttributeSetId($this->attributeSetId);
		$product->setTypeId($this->typeId);
		$product->setName($this->name);
		$product->setCategoryIds($this->categoryIds); # some cat id's, my is 2
		$product->setWebsiteIDs($this->websiteIds); # Website id, my is 1 (default frontend)
		$product->setDescription($this->description);
		$product->setShortDescription($this->shortDescription);
		$product->setPrice($this->price); # Set some price
		
		//Default Magento attribute
		$product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
		$product->setStatus(1);
		$product->setTaxClassId(0); # My default tax class
		/**
		 * no need to manage stock at the moment
		$product->setStockData(array(
		'is_in_stock' => 1,
		'qty' => 99999
		));
		***/
		$product->setCreatedAt(strtotime('now'));
		try {
			//$product->save();
			//to avoid the MySQL integrity contraint violation when first saving the product.
			$product->getResource()->save($product);
		}
		catch (Exception $ex) {
			echo $ex;
		}
		
		 //assign product object $product->getId();
		 $this->_product = $product;
		
	  }
	  
	  public function getId()
	  {
	     return $this->_product->getId();
	  }
	
	
}