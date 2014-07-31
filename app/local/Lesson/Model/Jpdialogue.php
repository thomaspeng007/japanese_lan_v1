<?php
class Chinese_Lesson_Model_Jpdialogue extends Mage_Core_Model_Abstract
{
	protected function _construct()
	{
		$this->_init('lesson/jpdialogue');
	}
	
	/**
	 * Retrieve the vocabulareis from current lesson
	 * @return array of all dialogues under current lesson
	 */
	public function getDialogue()
	{
		$lessonM = Mage::getModel('lesson/jpproductlesson');
		$lesson_id = $lessonM->getLessonId();
		// Return as array with all data from jp_dialgoue
		$dailogue = $this->getCollection()->addFieldToFilter('lesson_id',$lesson_id);
		return $dailogue;
	}
	

}