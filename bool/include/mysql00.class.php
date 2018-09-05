<?php
//defined('ACC') || exit('ACC defined');

class mysql00{
	private static $ins = null;
	private $conf = array();
	private $conn = null;

	protected function __construct(){
		
		$this->conf = conf00::getIns();
		
		$this->connect($this->conf->host,$this->conf->user,$this->conf->pwd);
		$this->select_db($this->conf->db);
		$this->setChar($this->conf->char);
	}
	
	public function __destruct(){
	}
	
	public static function getIns(){
		if(!self::$ins instanceof self){
			self::$ins = new self();
		}
		
		return self::$ins;
	}
	
	public function connect($h,$u,$p){
		$this->conn = mysql_connect($h,$u,$p);
		if(!$this->conn){
			$err = new Exception('連線失敗');
			throw $err;
		}
	}
	
	//連線資料庫
	public function select_db($d){
		$sql = 'use ' . $d ;
		$this->query($sql);
	}
	
	//設定字元集與連線校對
	public function setChar($c){
		$sql = 'set names ' . $c;
		return $this->query($sql);
	}
	
	//回傳一個資源識別碼
	public function query($sql){
		$rs = mysql_query($sql,$this->conn);
		
		log00::write($sql);
		
		return $rs;
	}
	
	public function autoExecute($table,$arr,$mode = 'insert',$where = ' where 1 limit 1'){
		
		if(!is_array($arr)){
			
			return null;
		}
		
		if($mode == 'update'){
			$sql = 'update ' . $table . ' set ';
			foreach($arr as $k=>$v){
				$sql .= $k . "='" . $v ."'," ;
			}
			$sql = rtrim($sql,',');
			$sql .= $where;
			
			return $this->query($sql);
		}
		
       /* $sql = 'insert into ' . $table . ' ('; 
		$sql .= implode(',',array_keys($arr));
        $sql .= ') values (\'';
        $sql .= implode("','",array_values($arr));
        $sql .= '\')';
		
		return $this->query($sql);
	*/
		$sql = 'insert into ' . $table . ' (' . implode(',',array_keys($arr)) . ')';
		$sql .= ' values (\'';
        $sql .= implode("','",array_values($arr));
        $sql .= '\')';

        return $this->query($sql);
	}
	
	public function getAll($sql){
		$rs = $this->query($sql);
		$list = array();
		while($row = mysql_fetch_assoc($rs)){
			$list[] = $row;
		}
		
		return $list;
	}
	
	public function getRow($sql){
		$rs = $this->query($sql);
		$row = mysql_fetch_assoc($rs);
		
		return $row;
	}
	
	public function getOne($sql){
		$rs = $this->query($sql);
		$row = mysql_fetch_row($rs);
		
		return $row[0];
	}
	
	//返回影響行的函數
	public function affected_rows(){
		return mysql_affected_rows($this->conn);
	}
	
	//返回最新的auto_increment列的自增長的值
	public function insert_id(){
		return mysql_insert_id($this->conn);
	}
	
}

?>