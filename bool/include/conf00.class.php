<?
/*conf00.class.php
	配置文件讀寫類
*/
//defined('ACC') || exit('ACC defined');
class conf00{
	protected static $ins = NULL;
	protected $data = array();
	final protected function __construct(){
		/*引入配置文件*/
		include(ROOT . 'include/conf00.inc.php');
		$this->data = $_CFG;
	} 
	final protected function __clone(){
		
	}
	//靜態對象實例化
	public static function getIns(){
		if(self::$ins instanceof self){
			return self::$ins;
		}else{
			self::$ins = new self();
			return self::$ins;
		}	
	}
	//用魔術方法讀取data內的訊息
	public function __get($key){
		if(array_key_exists($key,$this->data)){
			return $this->data[$key];
		}else{
			return null;
		}
	}
	//用魔術方法，在運行時，動態增加或改變配置選項
	public function __set($key,$value){
		$this->data[$key] = $value;
	}
}

$conf = conf00::getIns();


?>