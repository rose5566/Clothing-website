<?php
class GoodsModel00 extends Model00{
    protected $table = 'goods';
    protected $pk = 'goods_id';
    protected $fields = array('goods_sn','cat_id','brand_id','goods_name','shop_price','market_price','goods_number','click_count','goods_weight','goods_brief','goods_desc','thumb_img','goods_img','ori_img','is_on_sale','is_delete','is_best','is_new','is_hot','add_time');
    protected $_auto = array(
                            array('is_best','value',0),
			    array('is_new','value',0),
			    array('is_hot','value',0),
			    array('add_time','function','time')
                            );
    protected $_valid = array(
                            array('goods_name',1,'必須要有商品名','require'),
			    array('cat_id',1,'要整數型','number'),
			    array('is_best',0,'is_best只能是0或1','in','0,1'),
			    array('goods_breif',2,'20到100字以內','length','1,100')
			    );
    /*			    
    protected $error = array();    
    
    public function _facade($array=array()) {
        $data=array();
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
		        $this->error = $v[2];
		        return false;
		    }
		
		    if(!$this->check($data[$v[0]],$v[3])) {
		        $this->error = $v[2];
		        return false;
		    }
		    break;
		
		
		case '0':
		    if(!isset($v[4])) {
                        $v[4] = '';
                    }
		
		    if(isset($data[$v[0]])) {
		        if(!$this->check($data[$v[0]],$v[3],$v[4])) {
		            $this->error = $v[2];
		            return false;
		        }
		    }
		    break;
		
		case '2':
		    if(isset($data[$v[0]])) {
		        if(!$this->check($data[$v[0]],$v[3],$v[4])) {
		            $this->error = $v[2];
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
    */
    
    //is_delete = 1 顯示存在的商品
    public function getGood() {
        $sql = 'select * from ' . $this->table . ' where is_delete = 0';
        return $this->db->getAll($sql);
    }
    
    //is_delete = 0 代表商品在回收站顯示
    public function getTrash() {
        $sql = 'select * from ' . $this->table . ' where is_delete = 1';
	return $this->db->getAll($sql);
    }
    
    //刪除商品標記等於is_delete = 1
    public function trash($id) {
        return $this->update(array('is_delete'=>1),$id);
    }
    
    //貨號自動編號
    public function setRand(){
        $sn = 'NB' . date('Ymd') . mt_rand(10000,99999);
	$sql = 'select count(*) from ' . $this->table . " where goods_sn = '" . $sn . "'";
	
	return $this->db->getOne($sql)?$this->setRand():$sn;  
    }
    
    //取出指定條數的新品
    public function getNew($n = 5) {
        $sql = 'select * from ' . $this->table . ' limit ' . $n;
	return $this->db->getAll($sql);
    }
    
    /*
    找出cat_id下的子孫欄目，
    然後在尋找cat_id下的子孫欄目的商品
    */
    
    public function catGoods($cat_id) {
        $category = new catModel00();
	$cats = $category->select();//取出所有的欄目
        $sons = $category->getCatTree($cats,$cat_id);//取出所有給定的欄目
	
	$sub = array($cat_id);
	
	if(!empty($sons)) {//沒有子孫欄目
	    foreach($sons as $v) {
	        $sub[] = $v['cat_id'];
	    }
	}
        
	$in = implode(',',$sub);
	
	$sql = 'select goods_id,goods_name,thumb_img,shop_price,market_price from ' . $this->table . ' where cat_id in (' . $in . ') order by add_time desc limit 5'; 
	
	return $this->db->getAll($sql);
    }

    /*
        獲取購物中商品的詳細信息
        params array $items 購物車中的商品陣列
        return 商品陣列的詳細信息
    */

    public function getCartGoods($items) {
        foreach($items as $k=>$v) {  // 循環購物車中的商品,每循環一个,到數據查一下對應的詳細信息

            $sql = 'select goods_id,goods_name,thumb_img,shop_price,market_price from ' . $this->table . ' where goods_id =' . $k;

            $row = $this->db->getRow($sql);

            $items[$k]['thumb_img'] = $row['thumb_img'];
            $items[$k]['market_price'] = $row['market_price'];
        
        }

        return $items;
       
    }
    
}

?>