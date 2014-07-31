<?php
class Chinese_Lesson_Helper_Data extends Mage_Core_Helper_Abstract
{
	
	private $type = "downloadable";
	
	public function createProduct()
	{
		//$product = Mage::getModel('catalog/product');
		//return $product;
		$product = new Mage_Catalog_Model_Product();
		// Build the product
		$product->setSku('download7');
		$product->setAttributeSetId(9);
		$product->setTypeId('downloadable');
		$product->setName('Some cool product name');
		$product->setCategoryIds(array(22)); # some cat id's, my is 7
		$product->setWebsiteIDs(array(1)); # Website id, my is 1 (default frontend)
		$product->setDescription('Full description here');
		$product->setShortDescription('Short description here');
		$product->setPrice(39.99); # Set some price
		
		//Default Magento attribute
	    $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
		$product->setStatus(1);
		$product->setTaxClassId(0); # My default tax class
		$product->setStockData(array(
		'is_in_stock' => 1,
		'qty' => 99999
		));
		$product->setCreatedAt(strtotime('now'));
		try {
			$product->save();
		}
		catch (Exception $ex) {
		    echo $ex;	
		}
		echo "</br>Product created<br>";
		echo $product->getId();
	}

}