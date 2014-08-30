<?php
class Chinese_Lesson_Model_Jpproductlesson extends Mage_Core_Model_Abstract
{
	public  $product_id;
	private $lesson_id;
	private $lesson_title;
	
	protected function _construct()
	{
		$this->_init('lesson/jpproductlesson');
		
	}
	
	/**
	 * Check if lesson id co-responding to a product id
	 * @return bool
	 */
	public function isMatch()
	{
		$productId = $this->getCurrentProductId();
		//$plesson = $this->getLessons();// ->addFieldToFilter('product_id',$productId);
		//$lesson_id = $plesson->getLesson_id();
		$this->load($productId,'product_id');
		//$lesson_id = $this->getLesson_id();
		//$lesson_id = $plesson->getLesson_id();
		if(!empty($productId)){
			return true;
		}
		return false;
	}
	
	/**
	 * Retrieve current product id
	 * @return product id
	 */
	public function getCurrentProductId()
	{		
		//return $this->product_id;
		$current_product = $this->product_id;
		
		if(empty($current_product)) {
		    return Mage::registry('current_product')->getId();
		} else { // in case current product is not registered yet 
			return $current_product;
		}
	}
	
	/**
	 * Retrieve current lesson id
	 * @return lesson id
	 */
	public function getLessonId()
	{
		// load product_id in order to get co-responding lesson id
		$this->load($this->product_id,'product_id');
		$lesson_id = $this->getLesson_id();
		if(empty($lesson_id)) {
			//var_dump($lesson_id);
			$this->load($this->getCurrentProductId(),'product_id');
		     $lesson_id = $this->getLesson_id();
		}
		return $lesson_id;
	}
	
	/**
	 * Retrieve all records and sort by lesson_id a-z
	 * @return array of records
	 */
	public function getLessons()
	{
		$collection = $this->getCollection()->setOrder('lesson_id', 'ASC');
		return $collection;
	}
	
	/**
	 * Check if lesson ID already exist
	 * @retun bool
	 */
	public function is_lessonidExist($input_ids)
	{
	    $collection = $this->getCollection();
	    $collection = $collection->addFieldToFilter('lesson_id',array('in'=>$input_ids));
	    
	    $ids = $collection->getData(); 
	    $lessonIds = array();
	    
	    if(empty($ids)) 
	    {
	    	return true;
	    }
	    // If not empty, it means lesson id exists 
	    // $ids is two dimension arrays
	    foreach ($ids as $arr)
	    {
	    	foreach ($arr as $key => $value)
	    	{
	    		//echo $key.": ".$value."<br />";
	    		if($key == 'lesson_id')
	    		{
	    			array_push($lessonIds, $value);
	    		}
	    	} 	  	
	    }    
	    echo "<br />The following lesson ID has already existed, please check your lesson ID again !<br />";
	    foreach ($lessonIds as $lessonId)
	    {
	    	echo "Lesson ID: ".$lessonId."<br />";
	    }
	    return false;
	}
}