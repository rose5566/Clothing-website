<?php
require('../include/init00.php');

$cat_id = $_GET['cat_id'] + 0;

if($cat_id == ''){
	exit('接收失敗');
}

$cat = new CatModel00();
$sons = $cat->getSon($cat_id);
if(!empty($sons)){
	exit('有子欄目不能刪除');
}
$catedel = $cat->delete($cat_id);
if($catedel){
	echo '成功接收';
	exit;
}else{
	echo '失敗接收';
	exit;
}
?>