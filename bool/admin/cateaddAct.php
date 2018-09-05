<?php
header("Content-Type: text/html; charset=utf-8");
require('../include/init00.php');
print_r($_POST);


$data = array();
if(empty($_POST['cat_name'])){
	exit('資料欄位不能是空');
}
$data['cat_name'] = $_POST['cat_name'];

if(isset($_POST['parent_id']) && $_POST['parent_id'] == '' ){
	exit('資料欄位不能是空');
}
$data['parent_id'] = $_POST['parent_id'] + 0;

if(empty($_POST['intro'])){
	exit('資料欄位不能是空');
}
$data['intro'] = $_POST['intro'];

$cat = new CatModel00();
if($cat->add($data)){
	echo '成功欄目傳送';
	exit;
}else{
	echo '欄目傳送失敗';
	exit;
}
?>