<?php
/*
配置允許的性質
配置文許的大小
隨機生成目錄
隨機生成文件的名稱

獲取的文件
判斷文件的性質

良好的錯誤偵錯
*/

class UpModel00 {
    protected $allowExt = 'jpg,jpeg,bmp,gif,png';
    protected $maxSize = 1;
    
    protected $erron = 0;//偵錯級別
    protected $error = array(
        0=>'無錯',
	1=>'上傳文件超出系統限制',
	2=>'上傳文件大小超出網頁表單頁面',
	3=>'文件只有部分被上傳',
	4=>'沒有文件被上傳',
	6=>'找不到臨時文件夾',
	7=>'文件寫入失敗',
	8=>'不允許的文件性質',
	9=>'文件檔案大小超出限制',
	10=>'沒有創建目錄',
	11=>'無法移動'
			
    );
    
    
    public function Up($key) {
        if(!isset($_FILES[$key])) {
	    return false;
	}
	
	$f = $_FILES[$key];
	
	//檢驗上傳有沒有成功
	if($f['error']) {
	    $this->erron = $f['error'];
	    return false;
	}
	
	//獲取文件
	$ext = $this->getExt($f['name']);
	
	//檢查文件性質
	if(!$this->isAllowExt($ext)) {
	    $this->erron = 8;
	    return false;
	}
	
	//檢查文件檔案大小
	if(!$this->isAllowSize($f['size'])) {
	    $this->erron = 9;
	    return false;
	}
	
	//創建目錄
	$dir = $this->mk_dir();
	
	if($dir == false) {
	    $this->erron = 10;
	    return false;
	}
	
	//隨機生成文件名稱
	$newname = $this->randHame() . '.' . $ext;
	$dir = $dir . '/' . $newname;
	
	//移動檔案位置
        if(!move_uploaded_file($f['tmp_name'],$dir)) {
	    return false;
	}
	
	return str_replace(ROOT,'',$dir); 
    }
    
    public function getErr() {
        return $this->error[$this->erron];
    }
    
    Public function maxSize($size) {
        return $this->maxSize = $size;
    }
    
    //獲取文件
    public function getExt($file) {
        $tmp = explode('.',$file);
        return end($tmp);	
    }
    
    //配置文件的大小
    public function isAllowSize($size) {
        return $size <= $this->maxSize * 1024 * 1024;
    }
    
    //隨機生成文件名
    public function randHame() {
        $str = 'abcdefghijklmnopqrstuvwxyz0123456789';
	return substr(str_shuffle($str),0,6);
    }
    
    //JPG大小寫的問題
    public function isAllowExt($ext) {
        return in_array(strtolower($ext),explode(',',strtolower($this->allowExt)));
    }
    
    //隨機生成目錄
    public function mk_dir() {
        $dir = ROOT . 'model/images/' . date('Ym/d');
	if(is_dir($dir) || mkdir($dir,0777,true)) {
	    return $dir;
	} else {
	    return false;
	}
    }
    
}




?>