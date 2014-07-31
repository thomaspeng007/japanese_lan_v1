<?php

class Chinese_Lesson_Adminhtml_Lesson_ImportController extends Mage_Adminhtml_Controller_Action
{
	public function indexAction()
	{
		$this->loadLayout();
		$block = $this->getLayout()->createBlock(
				'Mage_Adminhtml_Block_Template',
				'lesson.uploadform',
				array(
						'template' => 'lesson/import/uploadform.phtml'
				)
		);
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
     
		// below for debugging only
		//$block = Mage::getBlockSingleton('lesson/adminhtml_plesson_match');
		//var_dump(get_class($block));exit;
        /*** Or $block can be expressed as below 
        $block = $this->getLayout()->createBlock(
        		'Mage_Core_Block_Template',
        		'lesson/adminhtml_plesson_match',
        		array('template' => 'lesson/match.phtml')
        );
        ***/ 
	}
	
	public function processAction()
	{
		$this->loadLayout();
		$block = $this->getLayout()->createBlock(
				'Mage_Adminhtml_Block_Template',
				'lesson.processfile',
				array(
						'template' => 'lesson/import/processfile.phtml'
				)
		);
		$this->getLayout()->getBlock('content')->append($block);
		$this->renderLayout();
	}
}