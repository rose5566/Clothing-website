<?php
class TestModel00 extends Model00{
	protected $table = 'class1';
	
	public function reg($data){
		return $this->db->autoExecute($this->table,$data,'insert');
	}
	public function select(){
		return $this->db->getAll('select * from' . $this->table);
	}
}