<?php
class Chinese_Lesson_IndexController extends Mage_Core_Controller_Front_Action {
	
	public function indexAction() {
		echo 'Hello Index!';
	
		$helper = Mage::helper('lesson');
		$helper->createProduct();
		
		exit;
		
		$product = Mage::getModel('lesson/jpproductlesson');
		var_dump($product);
		$product->load(168,'product_id');
		//echo $product->getLesson_id();
		$vocabularies = Mage::getModel('lesson/jpvocabulary')->getVocabulary();
		var_dump($vocabularies); 
		
	}
	
	public function testModelAction() {
		echo "test model"; exit;
		$params = $this->getRequest()->getParams();
		$jpv = Mage::getModel('lesson/jpvocabulary');
		$jpv->load($params['id']);
	
		//$data = $jpvocabulary->getData();
		
		echo $jpv->getSource_pinyin(); exit;
	    //echo $data['id']; exit;
		var_dump($data); exit;
		//$time = $data->getData('source_pinyin');
		//$time = $data->getOrigData('source_pinyin');
		var_dump($time);
	}
	
	public function showAllBlogPostsAction() {
		$posts = Mage::getModel('lesson/jpvocabulary')->getCollection(); echo "hello";
		foreach($posts as $blogpost){
			echo '<h3>'.$blogpost->getSource().'</h3>';
			echo nl2br($blogpost->getTarget());
			echo nl2br($blogpost->getSource_pinyin());
			echo nl2br($blogpost->getTimestamp());
		}
	}
}
