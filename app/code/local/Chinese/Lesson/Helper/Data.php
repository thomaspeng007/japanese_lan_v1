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
  $product->setTaxClassId(o); # My default tax class
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
 
 /**
  * Return audio path
  * @param Int $int
  * @return String
  */
 public function getAudioPath(Int $int) {
  
  $lessonPath = $int;
  // Split int into an array
     $array = str_split((string) $int);
     if(count($array)>1) {
        // Convert int from 10 to 1/0 || 11 to 1/1 || 22 to 2/2 , note there is no end '/' slash
        $lessonPath = implode('/', $array);
     }
    
        return "Chinese/lesson/".$lessonPath;
     
 }
 
 /**
  * Get Exe Media Base path
  * @param Int $int
  * @return string
  */
 public function getExeMediaBase(Int $int) {
 	
 	$lessonPath = $int;
 	// Split int into an array
 	$array = str_split((string) $int);
 	if(count($array)>1) {
 		// Convert int from 10 to 1/0 || 11 to 1/1 || 22 to 2/2 , note there is no end '/' slash
 		$lessonPath = implode('/', $array);
 	}
 	$media = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
 	return $media."Chinese/lesson/".$lessonPath;
 }
 
 /**
  * Return Exe Image path
  * @param Int $int
  * @return String
  */
 public function getExeImagePath(Int $int) {
 
 	$media = $this->getExeMediaBase($int);
 	return $media."/image/";
 	 
 }
 
 /**
  * Return Exe Audio path
  * @param Int $int
  * @return String
  */
 public function getExeAudioPath(Int $int) {
 	
 	$media = $this->getExeMediaBase($int);
 	return $media."/audio/";
 		
 }

}