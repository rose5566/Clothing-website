<?php
header("Content-Type: text/html; charset=utf8");
define('ACC',true);
require('../include/init00.php');
$goods = new GoodsModel00();
$goodlist = $goods->getGood();



require('./templates/goodslist.html');
?>