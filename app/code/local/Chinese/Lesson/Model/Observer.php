<?php
class Chinese_Lesson_Model_Observer
{
	/**
	 * Flag to stop observer executing more than once
	 *
	 * @var static bool
	 */
	static protected $_singletonFlag = false;

	/**
	 * This method will run when the product is saved from the Magento Admin
	 * Use this function to update the product model, process the
	 * data or anything you like
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function saveProductTabData(Varien_Event_Observer $observer)
	{
		if (!self::$_singletonFlag) {
			self::$_singletonFlag = true;
			 
			$product = $observer->getEvent()->getProduct();
			 
			try {
				/**
				 * Perform any actions you want here
				 *
				 *
				 */
				
				/**
				 * Save data from Vocabulary tab
				 */
				
				// initialise vacobulary model
				$vocabularyM = Mage::getModel('lesson/jpvocabulary');
				// Retrieve all vocabularies from current lesson
				$vocabularies = $vocabularyM->getVocabulary();
				// loop through all values, then update all of them once user hit the "Save" button
				for($i=0; $i<count($vocabularies); $i++){
					
					$primary_id = $this->_getRequest()->getPost('primary_id'.$i);
					$exe_type1 = $this->_getRequest()->getPost('exe_type1'.$i);
					$exe_type2 = $this->_getRequest()->getPost('exe_type2'.$i);
					$source = $this->_getRequest()->getPost('jp_source'.$i);
					$target = $this->_getRequest()->getPost('jp_target'.$i);
					$pinyin = $this->_getRequest()->getPost('jp_pinyin'.$i);
					// load from primary id then update all the data into database 
					$vocabularyM->load($primary_id)
					            ->setExe_type1($exe_type1)
					            ->setExe_type2($exe_type2)
					            ->setSource($source)
					            ->setTarget($target)
					            ->setPinyin($pinyin)
					            ->save();					
				}
				
				/**
				 * Save data for Exe types from Vocabulary tab
				 */
				// Before initialise jpexetype, we need to make sure type 1 and 2 has been filled, otherwise it means there is no
				// data , or empty, in this case no need to initialise Exe type model.
				// To do this we check the first row of values, this method still has faults need to be improved in the future
				$source =  $this->_getRequest()->getPost('type_source_0');
				$target =  $this->_getRequest()->getPost('type_target_0');
				if(!empty($source) AND !empty($target)) {
					
				
				// initialise productlessmon model
                $productlessonM = Mage::getModel('lesson/jpproductlesson');
                $lesson_id = $productlessonM->getLessonId();
				// initialise exeTypes model
                $exetypeM = Mage::getModel('lesson/jpexetype');
                // Set fixed rows as 4 for type 1 and 4 for type 2
                $setCount = 8;
                for($i=0;$i<$setCount;$i++){
                
                	$primary_id = $this->_getRequest()->getPost('type_primary_id_'.$i);
                    if($i<4){ $type = 1; } else { $type = 2; }
					$source =  $this->_getRequest()->getPost('type_source_'.$i);
					$target =  $this->_getRequest()->getPost('type_target_'.$i);
					$pinyin =  $this->_getRequest()->getPost('type_pinyin_'.$i);
					// Check if primary key exist? 
					if(!$primary_id) {
					   // If empty means first time to setup exe_type
					   $exetypeM = Mage::getModel('lesson/jpexetype');
					   $exetypeM->setLesson_id($lesson_id)
					            ->setType($type)
					            ->setSource($source)
					            ->setTarget($target)
					            ->setPinyin($pinyin)
					            ->save();	
					} else {
						// load from primary id then update all the data into database 
					   $exetypeM->load($primary_id)
					            ->setType($type)
					            ->setSource($source)
					            ->setTarget($target)
					            ->setPinyin($pinyin)
					            ->save();	
					}  			
                } // finish for loop for exe type
				} // finish if check
                
				
				
				/**
				 * Save data from Dialogue tab
				 */
				// initialise dialogue model
				$dialogueM = Mage::getModel('lesson/jpdialogue');
				// Retrieve all dialogues from current lesson
				$dialogues = $dialogueM->getDialogue();
				// loop through all values, then update all of them once user hit the "Save" button
				for($i=0; $i<count($dialogues); $i++){
						
					$primary_id = $this->_getRequest()->getPost('dailogue_primary_id'.$i);
					$exe_type3 = $this->_getRequest()->getPost('exe_type3'.$i);
					$exe_type4 = $this->_getRequest()->getPost('exe_type4'.$i);
					$exe_type5 = $this->_getRequest()->getPost('exe_type5'.$i);
					$actor = $this->_getRequest()->getPost('jp_dailogue_actor'.$i);
					$source = $this->_getRequest()->getPost('jp_dailogue_source'.$i);
					$target = $this->_getRequest()->getPost('jp_dailogue_target'.$i);
					// load from primary id then update all the data into database
					$dialogueM->load($primary_id)
					          ->setExe_type3($exe_type3)
					          ->setExe_type4($exe_type4)
					          ->setExe_type5($exe_type5)
					          ->setActor($actor)
					          ->setSource($source)
					          ->setTarget($target)
					          ->save();
				}
				
				
				/**
				 * Uncomment the line below to save the product
				 *
				*/
				//$product->save();
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
	}

	/**
	 * Retrieve the product model
	 *
	 * @return Mage_Catalog_Model_Product $product
	 */
	public function getProduct()
	{
		return Mage::registry('product');
	}
	 
	/**
	 * Shortcut to getRequest
	 *
	 */
	protected function _getRequest()
	{
		return Mage::app()->getRequest();
	}
	
	public function showData()
	{
		return "observer";
	}
	
	public function updateOrderHistory($observer) {
		/***
		$observer->getEvent()->getOrder()->getIncrementId() 
		$orderId = $observer->getEvent()->getOrder()->getId();
		$orders = Mage::getModel('sales/order')
                    ->getCollection()
                    ->addFieldToSelect('increment_id')
                    ->addFieldToFilter('customer_id', array('eq' => $observer->getEvent()->getOrder()->getId()));
        ***/
        $orderId = $observer->getEvent()->getOrder()->getIncrementId();
        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $pricing = Mage::getModel('lesson/jppricing');
        $pricing->initialOrder($customerId, $orderId)->setOrder();
        return ;
	}
}