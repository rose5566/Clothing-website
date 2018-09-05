<?php
//defined('ACC')||exit('Acc Deined');


class UserModel00 extends Model00 {
    protected $table = 'user';
    protected $pk = 'user_id';
    protected $fields = array('user_id','username','email','passwd','regtime','lastlogin');

    protected $_valid = array(
                             array('username',1,'用戶必須不能夠空白','require'),
		     	     array('username',0,'用戶必須字符4-16個字之間','length','4,16'),
			     //array('email',1,'格式不正確','email'),
			     array('passwd',1,'passwd不能空白','require')
                             );
    

    protected $_auto = array(
                            array('regtime','function','time')
                            );


    /*
        用戶註冊
    */
    public function reg($data) {
        if($data['passwd']) {
            $data['passwd'] = $this->encPasswd($data['passwd']);
        }

        return $this->add($data);
    }
    
    
    protected function encPasswd($p) {
        return md5($p);
    }

    /*
    根據用戶名查詢用戶信息
    */
    public function checkUser($username,$passwd='') {
        if($passwd == '') {
            $sql = 'select count(*) from ' . $this->table . " where username='" .$username . "'";
            return $this->db->getOne($sql);
        } else {
            $sql = "select user_id,username,email,passwd from " . $this->table . " where username= '" . $username . "'";

            $row = $this->db->getRow($sql);

            if(empty($row)) {
                return false;
            }

            if($row['passwd'] != $this->encPasswd($passwd)) {
                return false;
            }

            unset($row['passwd']);
            return $row;
        }
    }
}


