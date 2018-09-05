<?php
/***
====筆記部分====
購物車的流程頁面
商城的核心功能
***/



define('ACC',true);
require('./include/init00.php');


//設置一個動作參數，判斷用戶想幹什麼，比如是下訂單/寫地址/提交/清空購物車等
$act = isset($_GET['act'])?$_GET['act']:'buy';



$cart = CartTool::getCart(); // 獲取購物車實例
$goods = new GoodsModel00();

if($act == 'buy') { // 這是把商品加到購物車
    $goods_id = isset($_GET['goods_id'])?$_GET['goods_id']+0:0;
    $num = isset($_GET['num'])?$_GET['num']+0:1;

    if($goods_id) { // $goods_id為真,是想把商品放到購物車裡
        $g = $goods->find($goods_id);
        if(!empty($g)) { // 有此商品


            // 需要判斷此商品,是否在回收站
            // 此商品是否已下架
            if($g['is_delete'] == 1 || $g['is_on_sale'] == 0) {
                $msg = '此商品不能購買';
                include(ROOT . 'view/front/msg.html');
                exit;
            }

            // 先把商品加到購物車
            $cart->addItem($goods_id,$g['goods_name'],$g['shop_price'],$num);

            // 判斷庫存夠不夠
            $items = $cart->all();
            
            if($items[$goods_id]['num'] > $g['goods_number']) {
                // 庫存不夠了，把剛才加到購物車的動作撤回
                $cart->decNum($goods_id,$num);
                
                $msg = '庫存不足';
                include(ROOT . 'view/front/msg.html');
                exit;
            }

        }

        
        //print_r($cart->all());
    }

    $items = $cart->all();
    
    if(empty($items)) { // 如果購物車為空,返回首頁
        header('location: index.php');
        exit;
    }

    // 把購物車裡的商品詳細信息取出來
    $items = $goods->getCartGoods($items);

    //print_r($items);exit;

    $total = $cart->getPrice(); //獲取購物車中的商品總價格
    $market_total = 0.0;
    foreach($items as $v) {
        $market_total += $v['market_price'] * $v['num'];
    }

    $discount = $market_total - $total;
    $rate = round(100 * $discount/$total,2);

    include(ROOT . 'view/front/jiesuan.html');
} else if($act == 'clear') {
    $cart->clear();
    $msg = '購物車已清空';
    include(ROOT . 'view/front/msg.html');    
} else if($act == 'tijiao') {
    
    $items = $cart->all(); // 取出購物車中的商品

    // 把購物車裡的商品詳细信息取出来
    $items = $goods->getCartGoods($items);

    //print_r($items);exit;

    $total = $cart->getPrice(); //獲取購物車中的商品總價格
    $market_total = 0.0;
    foreach($items as $v) {
        $market_total += $v['market_price'] * $v['num'];
    }

    $discount = $market_total - $total;
    $rate = round(100 * $discount/$total,2);


    include(ROOT . 'view/front/tijiao.html'); 
} else if($act == 'done') {
    $msg = '感謝選購服飾西裝，您的訂單已提交成功。';
    
    include(ROOT . 'view/front/msg.html');
}
?>