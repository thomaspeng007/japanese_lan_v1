<?php
class Chinese_Lesson_Model_Jpexetype extends Mage_Core_Model_Abstract
{
	// Return all data from exetype table filtered by lesson_id
	private $exeTypes = NULL;
	// Set total types as 5 for the time being as part of the configuration 
	private $typeTotal = 5;
	//
	private $countType3 = NULL;
	private $countType4 = NULL;
	private $countType5 = NULL;
	// Initialise Dialogue model
	private $diaM = NULL;
	// Initialise Vocabulary model
	private $vocM = NULL;
	//
	public $lesson_id = NULL;
	// Set 4 members per group, apply to type 1 and 2
	private $typeMemCount = 4;
	
	// Type titles
	const TYPE_1 = '下記中国語と同じ意味の写真をクリックして下さい';
	const TYPE_2 = '読まれた音声と同じ意味の写真をクリックして下さい';
	const TYPE_3 = '空欄に入る正しい単語をクリックして下さい';
	const TYPE_4 = '日本語に合うように中国語を並べ替えて下さい';
	const TYPE_5 = '空欄に入る単語のピンインと声調を選んで下さい';
	// Get final type, @return INT . This number can be changed in the future if more types being added
	const FINALTYPE = 6;
	
	protected function _construct()
	{
		
		$this->_init('lesson/jpexetype');	
		// Initialise dialogue model in order to retrive type 3 4 5
		$this->diaM = Mage::getModel('lesson/jpdialogue');
		
	}
	
	public function setLessonId($lesson_id) {
		$this->lesson_id = $lesson_id;
		$exeTypes = $this->getCollection()->addFieldToFilter('lesson_id',$this->lesson_id);
		$this->exeTypes = $exeTypes;
		
	}
	
	/**
	 * Get how many exercise per type, finally add them together to get total exercises
	 * @return Int
	 */
	public function getTotalTypeCount() {
		// For the time being, there are 4 exercises for type1 and type2 perspectively
		$totalExe = 8 + count($this->getType(3)) + count($this->getType(4)) + count($this->getType(5));
		return $totalExe;
	}
	
	/***
	 * @return int
	 */
	public function getFinalType() {
		return self::FINALTYPE;
	}
	
	/**
	 * Get data from exetype table filtered by lesson id
	 * @return obj
	 */
	public function getExeTypes() {
		return $this->exeTypes;
	}
	
	/**
	 * Retrive first available type, return path to the type1 template
	 * @return String
	 */
	public function getFirstTypePath(){
		
		for($i=0; $i<$this->typeTotal; $i++) {
			$id = $i+1;
			if($this->getType($id)) {
				//return $this->_exeBaseDir() . 'type'.$id.'.php';
			}
		}
		return $this->_exeBaseDir() . 'type4.php';
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function getAllTypesInclude() {
		
	    for($i=0; $i<$this->typeTotal; $i++) {
			$id = $i+1;
			if($this->getType($id)) {
				return $this->_exeBaseDir() . 'type'.$id.'.php';
				
			}
		}
	}
	
	/**
	 * Retrive next available type, return path to next type template
	 * @return String
	 */
	public function getNextTypePath($id){
		
		return $this->_exeBaseDir(). 'type'.$id.'.php';
	}
	
	/**
	 * @return type1 images
	 */
	public function getImageUrl(){
		
		$lesson_id = $this->lesson_id;
        return Mage::helper('lesson')->getExeImagePath($lesson_id);
 
	}
	
	/**
	 * If the lesson id is known in advance
	 * @param unknown_type $lesson_id
	 * @return string path of the audio url for type 2
	 */
	public function getDirectAudioUrl($lesson_id)
	{
		$lesson_id = $this->lesson_id;
        return Mage::helper('lesson')->getExeAudioPath($lesson_id);
	}
	
	
	/**
	 * Define exe base folder for actual type template
	 * @return String
	 */
	private function _exeBaseDir(){
		
		//return Mage::getBaseDir('lib');
		$mainFolder = 'media';
		$subFolder = '/Chinese/Exe/html/';
		return $mainFolder.$subFolder;
	}
	
	/**
	 * Check if A lesson has exe registered
	 * @return bool
	 */
	public function isExist(){
		
		foreach ($this->exeTypes as $exetype){
			if($exetype->getId()) {
			   return TRUE;	
			}
		}
		return FALSE;
	}
	
	/**
	 * Get type exe as public interface
	 * @return requested type
	 */
	public function getType(INT $typeId) {
		
		if($typeId==1) { $type = $this->_getType1(); return $type; }	
		if($typeId==2) { $type = $this->_getType2(); return $type; }	
		if($typeId==3) { $type = $this->_getType3(); return $type; }	
		if($typeId==4) { $type = $this->_getType4(); return $type; }	
		if($typeId==5) { $type = $this->_getType5(); return $type; }	

	}
	
	/**
	 * @return array of two dimension for type 1
	 * Enter description here ...
	 */
	private function _getType1() {
		
		$exeTypes = $this->getCollection()->addFieldToFilter('type',1)
		                                  ->addFieldToFilter('lesson_id',$this->lesson_id);
		$exeTypes = $this->_ConvertObjToArr($exeTypes);
		return $exeTypes;
		
	}
	
	/**
	 * @return array of two dimension for type 2
	 * Enter description here ...
	 */
	private function _getType2() {
		
		$exeTypes = $this->getCollection()->addFieldToFilter('type',2)
		                                  ->addFieldToFilter('lesson_id',$this->lesson_id);
		$exeTypes = $this->_ConvertObjToArr($exeTypes);
		return $exeTypes;
	}
	
	/**
	 * @return array of two demesion, source and candidate keyword
	 * @example select * from jp_dialogue where lesson_id=27 and exe_type3 is not null
	 */
	private function _getType3() {
		
		$exeTypes = $this->diaM->getCollection()->addFieldToFilter('exe_type3', array('notnull' => true))
		                                        ->addFieldToFilter('lesson_id',$this->lesson_id);
	                                     
		$exeTypes = $this->_ConvertObjToArr($exeTypes);
	
		return $exeTypes;
	
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	private function _getType4() {
		
		$exeTypes = $this->diaM->getCollection()->addFieldToFilter('exe_type4', 1)
		                                        ->addFieldToFilter('lesson_id',$this->lesson_id);
		                                     
		$exeTypes = $this->_ConvertObjToArr($exeTypes);
	 
		return $exeTypes;
	}
	
	private function _getType5() {
		
		$exeTypes = $this->diaM->getCollection()->addFieldToSelect(array('exe_type5', 'source', 'target'))
		                                        ->addFieldToFilter('exe_type5', array('notnull' => true))
		                                        ->addFieldToFilter('lesson_id',$this->lesson_id);
                                      
		$exeTypes = $this->_ConvertObjToArr($exeTypes);
		return $exeTypes;
		
	}
	
	/**
	 * @return String
	 * this function is particularly for type5
	 */
	public function getPinyinFromSource($type5) {
		
		return $this->_getPinyinFromSource($type5);
	}
	
	private function _getPinyinFromSource($source) {
		
		$type5 = $this->_getType5();
		// get source only from type5
		$typeSourceArr = $this->getSourceArr($type5, 5);
		//return $typeSourceArr;
		// Initialise vocabulary model to get pinyin for source
		$vocM = Mage::getModel('lesson/jpvocabulary');
		$sourcePinyinArr = $vocM->getCollection()->addFieldToSelect(array('source', 'pinyin'))
		                      ->addFieldToFilter('source',array('in' => ($typeSourceArr)))                
		                      ->addFieldToFilter('lesson_id',$this->lesson_id);
		$sourcePinyinArr = $this->_ConvertObjToArr($sourcePinyinArr);                      
		//return $sourcePinyinArr;
		$pinyin = $this->getValueFromSource($source, $sourcePinyinArr, 'pinyin'); 
		$pinyin = str_replace(' ', '', $pinyin);
		return $pinyin;
	
	}
	
	/**
	 * Get requested source array with source value only for specific type
	 * @param $typeId 1 or 2 or 5
	 * @return Array
	 */
	public function getSourceArr($type_arr,$typeId) {
		if((INT)$typeId==1 || (INT)$typeId==2 || (INT)$typeId==5) {
	        $type1_source = array();
            foreach($type_arr as $type1_arr) { 
            	if ($typeId==5) {
            		array_push($type1_source, $type1_arr['exe_type5']);
            	}
	            else {
	            	array_push($type1_source, $type1_arr['source']);
	            }
            }
            return $type1_source;
	     }
	}
	
     /**
	 * Get requested audio url array with source value only for specific type, for now it's type2
	 * @param $typeId
	 * @return Array
	 */
	public function getAudioArr($type2_source, $type2_ran_source, $lesson_id) {
		
		// Return all audio url array only from type 2
        $audioArray1 = array();
        $audioArray2 = array();

        foreach($type2_source as $v){
	       if($v!=$type2_ran_source) { 
	          array_push($audioArray1, $v);
	       }
        }
        unset($v);
        $k = 0;
        foreach ($audioArray1 as $v){
	       if($k==0){ array_push($audioArray2, $type2_ran_source); };
	       array_push($audioArray2, $v);
	       $k++;
        }
        unset($k);
        unset($audioArray1);
		
		$type2 = $this->getType(2);
		$type_audio = array();
		foreach($audioArray2 as $type2_source_v) { 
       	       $type2_pinyin  = $this->getValueFromSource($type2_source_v, $type2, 'pinyin');
       	       $audioPath = $this->getDirectAudioUrl($lesson_id).str_replace(' ', '', $type2_pinyin).'.mp3';
       	       array_push($type_audio, $audioPath);
		}
		return $type_audio;
	}
	
	/**
	 * Get Random source value from source array
	 * @param Array
	 * @return String
	 */
	public function getRandomSource($type_source) {
		
		$type_random = array_rand($type_source, 1);
        $type_ran_source = $type_source[$type_random];
        return $type_ran_source;
        
	}
	
	// COnvert Object to array 
	private function _ConvertObjToArr($exeTypes) {
		
		$arr = array();
		$i = 0;
	    foreach ($exeTypes as $exeType) {
	    	$arr[$i]['source'] = $exeType->getSource();
	    	$arr[$i]['target'] = $exeType->getTarget();
	    	if($exeType->getPinyin()){
	    		$arr[$i]['pinyin'] = $exeType->getPinyin();
	    	}
	    	if($exeType->getExe_type3()) {
	    		$arr[$i]['exe_type3'] = $exeType->getExe_type3();
	    	}
	        if($exeType->getExe_type5()) {
	    		$arr[$i]['exe_type5'] = $exeType->getExe_type5();
	    	}
	    	$arr[$i]['id']    = $exeType->getId();
	    	$i++;
		}
		return $arr;
	}
	
	/**
	 * Convert given source to String or Arry
	 * @param string
	 * @return String or Array
	 */
	public function convertTextSource($str, $option = NULL) {
		
		return $this->_convertTextSource($str, $option);
		
		
	}
	
    private function _convertTextSource($str, $option = NULL) {
		
        $str = $this->_cleanSource($str);
		// Convert to array
        $str = explode('|', $str);
        if($option == 'array') {
        	return $str;
        }else {
        	$str = implode("", $str);
        	return trim($str);
        }
	}
	
	/**
	 * @param String, 'array'
	 * @return Array
	 * return array within array, each inner array keep their order key untouched, this function is for type 4
	 */
	public function getType4Arr($str, $arr) {
		
		$newArr = $this->_convertSourceToOrderedArr($str, $arr);
		return $newArr;
	}
	
	/**
	 *@return Array
     *@example Array ( [0] => Array ( [source] => 我|叫|李明，|你|呢？| [target] => 私は李明と言います、あなたは？  [id] => 905 ) 
     * [1] => Array ( [source] => 我|叫|山本一郎| [target] => 私は山本一郎と言います  [id] => 906 ) )
	 */
    private function _convertSourceToOrderedArr($str, $arr) {
		
		$arr = 'array';
		// Return associated array
		$array = $this->_convertTextSource($str, $arr);
		// Random array members with perseved keys
		$array = $this->_shuffle_assoc($array);
		return $array;
   	}
	
	/**
	 * @return Array
	 * @param $array
	 * return shuffled/random array orders with preserved keys
	 */
	private function _shuffle_assoc($array)
    {
        // Initialize
        $shuffled_array = array();     
        // Get array's keys and shuffle them.
        $shuffled_keys = array_keys($array);
        shuffle($shuffled_keys);  
        // Create same array, but in shuffled order.
        foreach ( $shuffled_keys AS $shuffled_key ) {
            $shuffled_array[  $shuffled_key  ] = $array[  $shuffled_key  ];
        } // foreach
        return $shuffled_array;
    }
	
	
	/**
	 * @return String
	 * clean the str to get rid of the '|' at both of the beginning and the end if any
	 */
	private function _cleanSource($str) {
		
		if(substr($str, -1) == '|') {
            $str = substr($str, 0, -1);  
        }
        if(substr($str, 0, 1) == '|') {
            $str = substr($str, 1);  
        }
        return $str;
		
	}
	
	public function getTypeMemCount() {
		
		return $this->typeMemCount;
	}
	
	/**
	 * Get any value from co-responding source, dependency of vocabulary model
	 * @return String
	 */
    public function getValueFromSource($source, $typeArr, $option = NUll) {
	
	    foreach ($typeArr as $typeArrV) {
		    if($source == $typeArrV['source']) {	
		    			
		        switch ($option) {
		    	
                case 'target':
                return $typeArrV['target'];
                break;
                
                case 'pinyin':
                return $typeArrV['pinyin'];
                break;
                
                case 'id':
                return $typeArrV['id'];
                break;
                
                }
		    }
	     }
    }
    
    /**
     * Get exe image path if $option is NULL, return audio path if $option is set to 'audio'
     * @param $int
     * @return String
     */
    public function getTypeResource(INT $int, $option = NUll){
	    $int = (string)$int;
        $len = strlen($int);
        $path = '';
        for($j=0; $j<$len; $j++) {
    	    if($j!=($len-1)) {
                $path .= $int[$j].'/';
        	}else {
    	        $path .= $int[$j];
    	    }
         }
         if((INT)$option == 1) {
         	return Mage::getBaseUrl('media').'Chinese/lesson/exe/audio/'.$path.'.mp3';	
         }
         return Mage::getBaseUrl('media').'Chinese/lesson/exe/'.$path.'.gif';	
      
        
    }
    
    /**
     * @return constant
     * @param INT type ID
     */
    public function getTypeTitle(INT $typeId) {
    	
    	switch ($typeId) {
		    	
                case 1:
                return self::TYPE_1;
                break;
                
                case 2:
                return self::TYPE_2;
                break;
                
                case 3:
                return self::TYPE_3;
                break;
                
                case 4:
                return self::TYPE_4;
                break;
                
                case 5:
                return self::TYPE_5;
                break;
      
    	}
    }
	
	
	
		
}