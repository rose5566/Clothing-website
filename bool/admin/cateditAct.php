<?php
header("Content-Type: text/html; charset=utf8");

require('../include/init00.php');



$data = array();
$cat_id = $_POST['cat_id'] + 0; 
$data['cat_name'] = $_POST['cat_name'];
$data['intro'] = $_POST['intro'];
$data['parent_id'] = $_POST['parent_id'] + 0;


if($cat_id == ''){
	exit('失敗空欄位傳送1');
}

if($data['cat_name'] == ''){
	exit('失敗空欄位傳送2');
}

if($data['intro'] == ''){
	exit('失敗空欄位傳送3');
}

if(is_null($data['parent_id'])){
	exit('失敗空欄位傳送4');
}

$cat = new CatModel00();
$tree = $cat->getTree($data['parent_id']);

$flag = true;
foreach($tree as $k){
	if($k['cat_id'] == $cat_id){
		$flag = false;
		break;
	}
}

if(!$flag){
	echo '欄目選取錯誤';
	exit;
}


if($cat->update($data,$cat_id)){
	echo '成功傳送';
	
}else{
	echo '失敗傳送';
	
}


?>
