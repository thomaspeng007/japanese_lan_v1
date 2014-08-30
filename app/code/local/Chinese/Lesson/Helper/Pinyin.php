<?php
/**
 * Admin Pinyin Helper
 * This class has two public access, one is for convert Chinese Character to Pinyin with tone mark 
 * but will save into DB with pinyin with number. The other one is to convert pinyin with number into
 * pinyin with tone mark. 
 * Note: number 5 means tone mark is light
 *
 * @category    Local
 * @package     Chinese_Lesson
 * @author      Thomas Pan
 */

class Chinese_Lesson_Helper_Pinyin extends Mage_Core_Helper_Abstract
{
	/**
	 * @param string $str
	 * @return string
	 */
	public static function convertCharToPinyin($str)
	{
		$pinyin = self::_convertCharToPinyin($str);
		$pinyin = explode(" ", $pinyin);
		foreach ($pinyin as $v){
			$pinyinNum .= self::_accent_number($v);
			$pinyinNum.=" ";		
		}
		return trim($pinyinNum);		
	}
	
	/**
	 * @param String $str, long sentence
	 * @return string
	 * @todo to be continued 
	 * 
	 */
	public static function convertSentenceToPinyin($str)
	{
		
	}
	
	/**
	 * @param string $str
	 * @return string
	 */
	public static function converPinyinNumberToToneMark($str)
	{
		return trim(self::_pinyin_addaccents($str));
	} 
	
	/**
	 * convert Chinese characters to Pinyin with tone marks
	 * @return string 
	 */
	private function _convertCharToPinyin($str)
    {  
	   include 'PinyinDic.php';  // $pydic has been initialised in this file 
   	   $strlen = mb_strlen($str,"UTF-8"); // checkout the length of the input Chinese string
   	   $pinyin = null;
   	   for($i=0;$i<$strlen;$i++){
   	      $givenChinese = mb_substr($str, $i, 1,"UTF-8");
   		  $pos = mb_strpos($pydic, $givenChinese, 0, "UTF-8"); 
   		  $next = 1;
   		    
   		   while(mb_substr($pydic, $pos+$next, 1,"UTF-8")!=","){
				$pinyin.= mb_substr($pydic, $pos+$next, 1,"UTF-8");
				$next++;
	       }
		   $pinyin.=" ";		
       } 	
   	   return $pinyin; // the pinyin will be separated by space
    }
    
    /**
     * convert pinyin with tone marks into pinyin with numbers so that
     * it can be safely saved into DB
     * @return string
     */
    private function _accent_number($word) 
    {
      
        static $vowels = Array('a','e','i','o','u','ü','A','E','I','O','U','Ü');

        static $pinyin = Array(
		1 => Array('ā','ē','ī','ō','ū','ǖ','Ā','Ē','Ī','Ō','Ū','Ǖ'),
		2 => Array('á','é','í','ó','ú','ǘ','Á','É','Í','Ó','Ú','Ǘ'),
		3 => Array('ǎ','ě','ǐ','ǒ','ǔ','ǚ','Ǎ','Ě','Ǐ','Ǒ','Ǔ','Ǚ'),
		4 => Array('à','è','ì','ò','ù','ǜ','À','È','Ì','Ò','Ù','Ǜ'),
		5 => Array('a','e','i','o','u','ü','A','E','I','O','U','Ü')
        );
        
        // split single pinyin into array with single letter : nin => array (n, i, n)  
        $words = self::mbStringToArray($word);
        
        /*
         * below code act as filter in case some pinyin has no tone mark, it will return it straight away
         */
        $pinyinWithTone = array_merge($pinyin[1], $pinyin[2], $pinyin[3], $pinyin[4]);
        if(!array_intersect($words, $pinyinWithTone)) {
        	return $word;
        }
       
        for($i=0; $i<count($words); $i++)
        {
        	$needle = $words[$i]; 
        	// $pinyin has 5 array and it's fixed
        	
        	for($j=1; $j<5; $j++)
            {         
        	   if(in_array($needle, $pinyin[$j])) 
        	   {	   	 
        	   	 $pos = array_search($needle, $pinyin[$j]); // return index of needle
        	   	 $newNeedle = $pinyin[5][$pos]; // changed the needle without tone mark
        	   	 
        	   	 foreach($words as $v){
        	   	 	if($v == $needle){
        	   	 		$v = $newNeedle;
        	   	 	}
        	   	 	$str .= $v;
        	   	 }
        	     return $str.$j;
        	   }      	
             }   
          }
     }
     
     /**
      * convert pinyin with numbers into pinyin with tone marks
      * @return string
      */
     private function _pinyin_addaccents($string) 
     {
	    # Find words with a number behind them, and replace with callback fn.
	    return preg_replace_callback(
	           '~([a-zA-ZüÜ]+)(\d)~',
	           'self::_pinyin_addaccents_cb',
	           $string);
     }
     
     /**
      * Helper callback for pinyin_addaccents()
      */ 
     private function _pinyin_addaccents_cb($match) 
     {
	    static $accentmap = null;
	    if( $accentmap === null ) {
		   # Where to place the accent marks
		   $stars = 'a* e* i* o* u* ü* '.
		            'A* E* I* O* U* Ü* '.
		            'a*i a*o e*i ia* ia*o ie* io* iu* '.
		            'A*I A*O E*I IA* IA*O IE* IO* IU* '.
		            'o*u ua* ua*i ue* ui* uo* üe* '.
		            'O*U UA* UA*I UE* UI* UO* ÜE*';
           $nostars = str_replace('*', '', $stars);

		   # Build an array like Array('a' => 'a*') and store statically
		   $accentmap = array_combine(explode(' ',$nostars), explode(' ', $stars));
		   unset($stars, $nostars);
	     }
    
	    static $vowels = Array('a*','e*','i*','o*','u*','ü*','A*','E*','I*','O*','U*','Ü*');

	    static $pinyin = Array(
			1 => Array('ā','ē','ī','ō','ū','ǖ','Ā','Ē','Ī','Ō','Ū','Ǖ'),
			2 => Array('á','é','í','ó','ú','ǘ','Á','É','Í','Ó','Ú','Ǘ'),
			3 => Array('ǎ','ě','ǐ','ǒ','ǔ','ǚ','Ǎ','Ě','Ǐ','Ǒ','Ǔ','Ǚ'),
			4 => Array('à','è','ì','ò','ù','ǜ','À','È','Ì','Ò','Ù','Ǜ'),
			5 => Array('a','e','i','o','u','ü','A','E','I','O','U','Ü')
	     );

	     list(,$word,$tone) = $match;
	     # Add star to vowelcluster
	     $word = strtr($word, $accentmap);
	     # Replace starred letter with accented
	     $word = str_replace($vowels, $pinyin[$tone], $word);
	     return $word;
      }	
      
      private function mbStringToArray ($string) { 
           $strlen = mb_strlen($string); 
           $id = 1;
           while ($strlen) {   
              $array[] = mb_substr($string,0,1,"UTF-8"); 
              $string = mb_substr($string,1,$strlen,"UTF-8"); 
              $strlen = mb_strlen($string);             
           } 
           return $array; 
       } 
      
      
}