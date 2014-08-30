<?php
class Chinese_Lesson_IndexController extends Mage_Core_Controller_Front_Action {
	
	public function indexAction() {
		//echo 'Hello Index!';
		$params = $this->getRequest()->getParams();
		$type = $params['type'];
		$typeId = (int)trim($type);
		$productId = $params['productId'];
		$type = 'type'.trim($type).'.phtml';	
		$plM = Mage::getModel('lesson/jpproductlesson');
		$plM->product_id = $productId;
		$lessonId = $plM->getLessonId();
	    $this->getResponse()->setBody($this->getLayout()
	                                       ->createBlock('core/template')
	                                       ->setProductId($lessonId)
	                                       ->setOrigProductId($productId)
	                                       ->setFinalType($typeId)
	                                       ->setTemplate('lesson/exe/html/'.$type)->toHtml());
	                                       
	}
	
	/**
	 * Calculation for the user's mistake, register the score into session
	 * then show it in the final type template, but for this action there is no need to set template
	 */
	public function saveScoreAction() {
		
		$params = $this->getRequest()->getParams();
		$score = $params['score'];
		$getScore = Mage::getSingleton('core/session')->getExeScoreValue();
		$exeScoreValue = (int)$getScore + (int)$score; 
		Mage::getSingleton('core/session')->setExeScoreValue($exeScoreValue);
		
	}
	
	/**
	 * update product 'status' attribute to record is user has complete the lesson or not
	 * Enter description here ...
	 */
	public function customerProductStatusAction() {
		
		$params = $this->getRequest()->getParams();
		$customerId = $params['customerId'];
		$productId = $params['productId'];
		$clM = Mage::getModel('lesson/jpcustomerlesson');
		$clM->setCustomerId($customerId);
		$clM->setProductId($productId);
		$clM->setProductStatus();
		
		
	}
	
    /**
	 * update product 'status' attribute to record is user has complete the lesson or not
	 * Enter description here ...
	 */
	public function customerFavoriteAction() {
		
		$params = $this->getRequest()->getParams();
		$customerId = $params['customerId'];
		$productId = $params['productId'];
		$clM = Mage::getModel('lesson/jpcustomerlesson');
		$clM->setCustomerId($customerId);
		$clM->setProductId($productId);
		$clM->setFavoriteStatus();
	
		
	}
	
	public function testAction() {
		$clM = Mage::getModel('lesson/jpcustomerlesson');
		$customerId = 1;
		$clM->setCustomerId($customerId);
		$favorite = $clM->getCustomerFavorite();
		var_dump($favorite);
	    
	}
}
