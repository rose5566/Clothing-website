<?php
// defined('ACC') || exit('ACC defined');
class Model00 {
    protected $table = null;
    protected $db = null;
    
    protected $pk = '';
    protected $fields = array();
    protected $_auto = array();
    protected $_valid = array();
    protected $error = array();    
    
    public function __construct() {
        $this->db = mysql00::getIns();
    }
    
    public function table($table) {
        $this->table = $table;
    }
    
    /*自動過濾字段屬性*/
    public function _facade($array=array()) {
        $data = array();
	foreach($array as $k=>$v) {
	    if(in_array($k,$this->fields)) {
	        $data[$k] = $v;
	    }
	}
	
	return $data;
    }
    
    public function _autoFill($data) {
        foreach($this->_auto as $k=>$v) {
	    if(!array_key_exists($v[0],$data)) {
	        switch($v[1]) {
		    case 'value':
		    $data[$v[0]] = $v[2];
		    break;
		    
		    case 'function':
		    $data[$v[0]] = call_user_func($v[2]);
		    break;		    
		}
	    }
	}
	
	return $data;
    }
    
    public function _validate($data) {
        if(empty($this->_valid)) {
	    return true;
	}
	$this->error = array();
	
	foreach($this->_valid as $v) {
            switch($v[1]) {
	        case '1':
		    if(!isset($data[$v[0]])) {
		        $this->error[] = $v[2];
		        return false;
		    }
		
		    if(!$this->check($data[$v[0]],$v[3])) {
		        $this->error[] = $v[2];
		        return false;
		    }
		    break;
		
		
		case '0':
		    if(!isset($v[4])) {
                        $v[4] = '';
                    }
		
		    if(isset($data[$v[0]])) {
		        if(!$this->check($data[$v[0]],$v[3],$v[4])) {
		            $this->error[] = $v[2];
		            return false;
		        }
		    }
		    break;
		
		case '2':
		    if(isset($data[$v[0]])) {
		        if(!$this->check($data[$v[0]],$v[3],$v[4])) {
		            $this->error[] = $v[2];
		            return false;
		        }
		    }
		    break;
	    }
	}
	
	return true;
    }
    
    public function getErorr() {
        return $this->error;
    }
    
    public function check($value,$rule='',$parn='') {
	switch($rule) {
            case 'require':
	        return !empty($value);
	    
	    case 'number':
	        return is_numeric($value);
	    
	    case 'in':
	        $temp = explode(',',$parn);
	        return in_array($value,$temp);
	    
	    case 'between':
	        list($min,$max) = explode(',',$parn);
	        return $max >= $value && $value >= $min;
	    
	    case 'length':
	        list($min,$max) = explode(',',$parn);
	        $str = strlen($value);
	        return $max >= $str && $str >= $min;
	}
	
    } 
   
    public function add($data) {
        $row =  $this->db->autoExecute($this->table,$data);
        return $row;
    }
    
     /*
     access public
     return boolean
     */
     
    public function select() {
        $sql = 'select * from ' . $this->table;
	return $this->db->getAll($sql);
    }
     
     /*
     access public
     param int $id 序號
     return array
     */
     
    public function find($id) {
        $sql = 'select * from ' . $this->table . ' where ' . $this->pk . '=' . $id;
	return $this->db->getRow($sql);
    }
     
    /*
    access public
    param int $id
    return int
    */
     
    public function delete($id) {
        $sql = 'delete from ' . $this->table . ' where ' . $this->pk . '=' . $id;
	$this->db->query($sql);
	return $this->db->affected_rows();
    }
     
    public function update($data,$id) {
        $sql = $this->db->autoExecute($this->table,$data,'update',' where ' . $this->pk . '=' . $id);
	if($sql) {
	    return $this->db->affected_rows();
	} else {
	    return false;
	}
	
    }
     
    public function getSon($id) {
        $sql = 'select * from ' . $this->table . ' where parent_id =' . $id;
        return $this->db->getAll($sql);
    }
     
    /*
    access public
    param array $arr
    param int $id 家譜順序
    param int $lev 計數器
    return array
    */ 
    public function getCatTree($arr,$id=0,$lev=0) {
	$tree = array();
	foreach($arr as $k) {
	    if($k['parent_id'] == $id) {
	        $k['lev'] = $lev;
		$tree[] = $k;
		$tree = array_merge($tree,$this->getTree($arr,$k['cat_id'],$lev++));
	    }
	}
	
        return $tree;	
    }
    
    public function getTree($id) {
        $tree = array();
	$arr = $this->select();
	
	while($id > 0) {
	    foreach($arr as $k) {
                if($id == $k['cat_id']) {
	            $tree[] = $k;
		    $id = $k[parent_id];
		    break;
	        }   
	    }
	}
	
	return $tree;
    }
    
}

?>