<?php
header("Content-Type: text/html; charset=utf-8");
define('ACC',true);

require('../include/init00.php');

if(isset($_GET['act']) && $_GET['act'] == 'show') {
    $goods = new GoodsModel00();
    $goodlist = $goods->getTrash();
    require('./templates/goodslist.html');
} else {
    $goods_id = $_GET['goods_id'] + 0;
    $goods = new GoodsModel00();
    if($goods_id) {
        $goods->trash($goods_id);
	echo '回收OK';
    }
}



?>