<?php
class Chinese_Lesson_Model_Resource_Jpexetype extends Mage_Core_Model_Resource_Db_Abstract{
	
	protected function _construct()
	{
		$this->_init('lesson/jpexetype','id');
	}
	
}