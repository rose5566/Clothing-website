<?php
class CatModel00 extends Model00{
	protected $table = 'category';
	protected $pk = 'cat_id';
	
	public function add($data){
		$row = $this->db->autoExecute($this->table,$data);
		return $row;
	}
	
	/*
	public function select(){
		$sql = 'select cat_id,cat_name,parent_id from ' . $this->table;
		return $this->db->getAll($sql);
	}
	*/
	
	/*
	public function find($cat_id){
		$sql = 'select * from ' . $this->table . ' where ' . $this->pk . '=' . $cat_id;
		return $this->db->getRow($sql);
	}
	*/
	
	//return array $id欄目的子孫數
	public function getCatTree($arr,$id=0,$lev=0) {
		$tree = array();	
		foreach($arr as $k) {
			if($k['parent_id'] == $id) {
				$k['lev'] = $lev;
				$tree[] = $k;
				$tree = array_merge($tree,$this->getCatTree($arr,$k['cat_id'],$lev+1));
			}
		}
		
		return $tree;
	}
	
	/*
	param: int $id 
	return array $id欄目的家譜數
	*/
	public function getTree($id) {
		$tree = array();
		$arr = $this->select();
		
		while($id > 0) {
			foreach($arr as $k) {
				if($id == $k['cat_id']) {
					$tree[] = $k;
					$id = $k['parent_id'];
					break;
				}
			}
		}
		
		return array_reverse($tree);
	}
	
	/*
	public function getSon($cat_id){
		$sql = 'select * from ' . $this->table .  ' where parent_id =' . $cat_id;
		return $this->db->getAll($sql);
	}
	*/
	
	/*
	public function delete($cat_id){
		$sql = 'delete from ' . $this->table . ' where ' . $this->pk . '=' . $cat_id;
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
	*/
	
	/*
	public function update($data,$cat_id){
		$row = $this->db->autoExecute($this->table,$data,'update',' where ' . $this->pk . '=' . $cat_id);
		return $this->db->affected_rows();
	}
	*/
	 
	
}



?>