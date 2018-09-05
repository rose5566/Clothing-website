<?php
define('ACC',true);
require('./include/init00.php');

$goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;

$goods = new GoodsModel00();
$g = $goods->find($goods_id);

if(empty($g)) {
    header('location: index.php');
    exit;
}

$cat = new CatModel00();
$nav = $cat->getTree($g['cat_id']);


require(ROOT . 'view/front/shangpin.html');

?>