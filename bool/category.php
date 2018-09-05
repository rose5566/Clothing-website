<?php
header("Content-Type: text/html; charset=utf-8");
define('ACC',true);
require('./include/init00.php');

$cat_id = isset($_GET['cat_id'])?$_GET['cat_id'] + 0:0;//判斷傳值聲明

$cat = new CatModel00();
$category = $cat->find($cat_id);

if(empty($category)) {//沒有欄目
    header('location: index.php');
    exit;
}

//取出樹狀導覽
$cats = $cat->select();
$sort = $cat->getCatTree($cats,0,1);

//取出家譜導覽
$nav = $cat->getTree($cat_id);

//取出欄目下的商品
$goods = new GoodsModel00();
$goodslist = $goods->catGoods($cat_id);

require(ROOT . 'view/front/lanmu.html');

?>