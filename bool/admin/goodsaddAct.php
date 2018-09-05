<?php
header("Content-Type: text/html; charset=utf-8");
/*define('ACC',true);*/
require('../include/init00.php');

/*if($data['goods_name'] == ''){
	echo '不能為空';
	exit;
}*/
 /*[MAX_FILE_SIZE] => 2097152
    [goods_name] => 1
    [goods_sn] => 1
    [cat_id] => 6
    [shop_price] => 1
    [goods_desc] => 1
    [goods_weight] => 1
    [weight_unit] => 0.001
    [goods_number] => 1
    [is_best] => 1
    [is_on_sale] => 1
    [keywords] => 1
    [goods_brief] => 1
    [seller_note] => 1
    [goods_id] => 0
    [act] => insert*/
/* 
$data = array();
$data['goods_name'] = trim($_POST['goods_name']);
$data['goods_sn'] = trim($_POST['goods_sn']);
$data['cat_id'] = $_POST['cat_id'] + 0;
$data['goods_price'] = $_POST['shop_price'] + 0;
$data['goods_desc'] = trim($_POST['goods_desc']);
$data['goods_weight'] = $_POST['goods_weight'] * $_POST['weight_unit'] + 0;
$data['is_best'] = isset($_POST['is_best'])?1:0;
$data['is_hot'] = isset($_POST['is_hot'])?1:0;
$data['is_new'] = isset($_POST['is_new'])?1:0;
$data['is_on_sale'] = isset($_POST['is_on_sale'])?1:0;
$data['goods_breif'] = $_POST['goods_brief'] + 0;
$data['add_time'] = time();
*/

$goods = new GoodsModel00();
$_POST['goods_weight'] = $_POST['goods_weight'] * $_POST['weight_unit'] + 0; 
//print_r($_POST);

$data = array();

//自動過濾
$data = $goods->_facade($_POST);
//print_r($data);

//自動填充
$data = $goods->_autoFill($data);
//print_r($data);

if(empty($data['good_sn'])) {
    $data['goods_sn'] = $goods->setRand();
}

if(!$goods->_validate($data)) {
    echo implode('<br />',$goods->getErorr());
    exit;
}


$ext = new UpModel00();
$ori_img = $ext->up('goods_img');

if($ori_img) {
    $data['ori_img'] = $ori_img; 
}

if($ori_img) {

    $ori_img = ROOT . $ori_img; // 加上絕對路徑 

    $goods_img = dirname($ori_img) . '/goods_' . basename($ori_img);
    if(ImageTool::thumb($ori_img,$goods_img,300,400)) {
        $data['goods_img'] = str_replace(ROOT,'',$goods_img);
    }

    // 再次生成瀏覽時用縮略圖 160*220
    // 定好缩略圖的地址
    // aa.jpeg --> thumb_aa.jpeg
    $thumb_img = dirname($ori_img) . '/thumb_' . basename($ori_img);

    if(ImageTool::thumb($ori_img,$thumb_img,160,220)) {
        $data['thumb_img'] = str_replace(ROOT,'',$thumb_img);
    }

}

if($goods->add($data)){
	echo '商品發送win';
	exit;
}else{
	echo '商品發送lose';
	exit;
}


?>