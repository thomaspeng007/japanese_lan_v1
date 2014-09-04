<?php
class Chinese_Lesson_Model_Resource_Jpcustomerlesson_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract {
	
    protected function _construct()
	{
		$this->_init('lesson/jpcustomerlesson');
	}
}