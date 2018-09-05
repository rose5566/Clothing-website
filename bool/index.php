<?php
header("Content-Type: text/html; charset=utf-8");
define('ACC',true);
require('./include/init00.php');
    
$goods = new GoodsModel00();
$newlist = $goods->getNew(5);
    
//print_r($newlist);

$female_id = 2;
$felist = $goods->catGoods($female_id);

//print_r($felist);
//echo count($felist);exit;

$man_id = 1;
$manlist = $goods->catGoods($man_id);

require(ROOT . 'view/front/index.html');

?>