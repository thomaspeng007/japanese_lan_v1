<?php
class Chinese_Lesson_Model_Jpvocabulary extends Mage_Core_Model_Abstract
{
	protected function _construct()
	{
		$this->_init('lesson/jpvocabulary');
	}
	
	/**
	 * Retrieve the vocabulareis from current lesson or all if param set
	 * @param NULL or GE or BUS or TRA
	 * @return array of all vocabularies under current lesson or all lessons depends on param if set
	 */
	public function getVocabulary($lesson_id = NULL)
	{
		$lessonM = Mage::getModel('lesson/jpproductlesson');
		if($lesson_id=='GE'){
			$vocabulary = $this->getCollection();
		}else {
			$lesson_id = $lessonM->getLessonId();
		    // Return as array with all data from jp_vocabulary
		    $vocabulary = $this->getCollection()->addFieldToFilter('lesson_id',$lesson_id);
		}
		
		return $vocabulary;
	}
	
	/**
	 * Process and insert into vocabulary into DB
	 * @return void
	 */
	public function processV($voArray)
	{
	       // $helper = Mage::helper('lesson/pinyin');
		    // Separate by lesson
			foreach($voArray as $lessonV_id => $lessonV)
			{
				// Separate by lesson with all vocabulary
				$arrSource = array();
				$arrTarget = array();
				foreach($lessonV as $line_number => $lessonV_line)
				{
						foreach($lessonV_line as $lesson_source => $lesson_target)
						{
							$lesson_source = $this->validateFormatV($lesson_source);
							$lesson_target = $this->validateFormatV($lesson_target);
							$leftJoinSource = $this->leftJoinSource($lesson_source, $lesson_target);
							foreach($leftJoinSource as $k => $v)
							{
								// Separate by line with all vocabulary
								array_push($arrSource, $k);
								array_push($arrTarget, $v);
							}
						}
				}
			
				// Separate by lesson with unique vocabulary
				$leftJoinSource = $this->leftJoinSource($arrSource, $arrTarget);
				// loop through with key and value then insert them into vovabulary table
				foreach($leftJoinSource as $key => $value)
				{
					$vocabularyM = Mage::getModel('lesson/jpvocabulary'); 
					$source_pinyin = Mage::helper('lesson/pinyin')->convertCharToPinyin($key);
					if($this->getUid($key)){ // if old
						$uid = $this->getUid($key);
						$publish_status = NULL;
					}else { // if new
						$uid = NULL;
						$publish_status = 1;
					}
					echo $key."<br \>";
					echo $value."<br \>";
					echo $source_pinyin."<br \>";
					$vocabularyM ->setLesson_id($lessonV_id)
					             ->setUid($uid)
				                 ->setSource($key)
				                 ->setTarget($value)
				                 ->setSource_pinyin($source_pinyin)
				                 ->setPublish_status($publish_status)
				                 ->save();
				}
			}
	}
	
	
	/**
	 * Glue source and target as pairs but left join source just in case that target has no value
	 * @return array()
	 */
	private function leftJoinSource($arr1, $arr2)
	{
	    $arrLeftJoin = array();
        $id = 0;
        foreach($arr1 as $key)
        {
	        if(!array_key_exists($key,$arr1))
	        {
		        if(!empty($key))
		        {
		        	$arrLeftJoin[$key] = $arr2[$id];
		        }	
	        }
	        $id++;
        }
        return $arrLeftJoin;
	}
	
	/**
	 * volidate 'vacabulary' string format, without space, punctuation
	 * @return array()
	 */
	private function validateFormatV($string)
	{
		$string = explode('|', $string);
		$arr = array();
		foreach ($string as $str)
		{
		    $str = preg_replace("/\p{P}/u", "", $str);
		    $str = trim($str);
		    array_push($arr, $str);
		}
		//$arr = implode('|', $arr);
		return $arr;
	}
	
	/**
	 * set publish_status to decide which voc is new or old by 1 or 0/NULL
	 * @return FALSE or INT
	 */
	private function getUid($string)
	{
		$vocs = $this->getVocabulary('GE'); // get whole table data
        foreach($vocs as $voc){ // loop through each row
        	$source = trim($voc->getSource());
        	$id = $voc->getId();
        	if($source==trim($string)){
        	   return $id;
        	}
        }
        return FALSE;
	}
}