<?php
class Chinese_Lesson_Model_Jpdialogue extends Mage_Core_Model_Abstract
{
 protected function _construct()
 {
  $this->_init('lesson/jpdialogue');
 }
 
 private function getLessonId()
 {
  $lessonM = Mage::getModel('lesson/jpproductlesson');
  $lesson_id = $lessonM->getLessonId();
  return $lesson_id;
 }
 
 /**
  * Retrieve the vocabulareis from current lesson
  * @return array of all dialogues under current lesson
  */
 public function getDialogue()
 {
  //$lessonM = Mage::getModel('lesson/jpproductlesson');
  //$lesson_id = $lessonM->getLessonId();
  $lesson_id = $this->getLessonId();
  // Return as array with all data from jp_dialgoue
  $dailogue = $this->getCollection()->addFieldToFilter('lesson_id',$lesson_id);
  //$dailogue = $this->getCollection()->getSelect("*")->where("lesson_id=4");
  return $dailogue;
 }
 
 /**
  * Get audio file path, this function is used for frontend
  * @return String
  */
 public function getAudioUrl()
 {
        $lesson_id = $this->getLessonId();
        // Get media base url. e.g:- http://yoursite.com/media/
        $media = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA);
        return $media.Mage::helper('lesson')->getAudioPath($lesson_id)."/audio/"; 
  
 }
 
    /**
  * Checking if an audio file exist, this function is for backend admin
  * @return bool
  * @param String $filename
  * @example ni3hao3.mp3, ni3hao is the file name
  */
 public function isAudioExist(Int $lesson_id, String $filename)
 {
  
  $file = $_SERVER['DOCUMENT_ROOT']."/magento/media/".Mage::helper('lesson')->getAudioPath($lesson_id)."/audio/".$filename.".mp3";

  if (file_exists($file)) {
            return true;
        } else {
            return false;
        }
 }
 

}