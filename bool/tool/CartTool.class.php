<?php
/***
技術類型: session+單例


功能分析:

判斷某個商品是否存在
添加商品
刪除商品
修改商品的數量

某商品數量加1
某商品數量减1


查詢購物車的商品種類
查詢購物車的商品數量
查詢購物車裡的商品總金額
返回購物裡的所有商品

清空購物車

***/

//defined('ACC')||exit('Acc Deined');

class CartTool {
    private static $ins = null;
    private $items = array();

    final protected function __construct() {
    }

    final protected function __clone() {
    }

    // 獲取實例
    protected static function getIns() {
        if(!(self::$ins instanceof self)) {
            self::$ins = new self();
        }

        return self::$ins;
    }


    // 把購物車的單利對象放到session裡
    public static function getCart() {
        if(!isset($_SESSION['cart']) || !($_SESSION['cart'] instanceof self)) {
            $_SESSION['cart'] = self::getIns();
        }

        return $_SESSION['cart'];
    }


    /*
    添加商品
    param int $id 商品主鍵
    param string $name 商品名稱
    param float $price 商品價格
    param int $num 購物數量
    */
    public function addItem($id,$name,$price,$num=1) {
        
        if($this->hasItem($id)) { // 如果該商品已经存在,則直接加其數量
            $this->incNum($id,$num);
            return;
        }
        
        $item = array();
        $item['name'] = $name;
        $item['price'] = $price;
        $item['num'] = $num;
        
        $this->items[$id] = $item;
    }


    /*
    修改購物車中的商品數量
    param int $id 商品主键
    param int $num 某個商品修改後的數量,即直接把某商品的數量改為$num
    */
    public function modNum($id,$num=1) {
        if(!$this->hasItem($id)) {
            return false;
        }

        $this->items[$id]['num'] = $num;

    }


    /*
    商品數量增加1
    */
    public function incNum($id,$num=1) {
        if($this->hasItem($id)) {
            $this->items[$id]['num'] += $num;
        }
    }


    /*
    商品數量减少1
    */
    public function decNum($id,$num=1) {
        if($this->hasItem($id)) {
            $this->items[$id]['num'] -= $num;
        }

        // 如果减少后,數量為0了,則把這個商品從購物車删掉
        if($this->items[$id]['num'] < 1) {
            $this->delItem($id);
        }
    }


    /*
        判斷某商品是否存在
    */
    public function hasItem($id) {
        return array_key_exists($id,$this->items);
    }
      

    /*
        删除商品
    */
    public function delItem($id) {
        unset($this->items[$id]);
    }


    /*
        查詢購物車中商品的種類
    */
    public function getCnt() {
        return count($this->items);
    }


    /*
        查詢購物車中商品的個數
    */
    public function getNum() {
        if($this->getCnt() == 0) {
            return 0;
        }
        
        $sum = 0;

        foreach($this->items as $item) {
            $sum += $item['num'];
        }

        return $sum;
    }


    /*
        查詢購物車中商品的總金額
    */
    public function getPrice() {
        if($this->getCnt() == 0) {
            return 0;
        }
        
        $price = 0.0;

        foreach($this->items as $item) {
            $price += $item['num'] * $item['price'];
        }

        return $price;
    }
    


    /*
    返回購物車中的所有商品
    */

    public function all() {
        return $this->items;
    }

    /*
        清空購物車
    */
    public function clear() {
        $this->items = array();
    }
}


/*
session_start();

// print_r(CartTool::getCart());

$cart = CartTool::getCart();


if(!isset($_GET['test'])) {
   $_GET['test'] = '';
}

if($_GET['test'] == 'addwangba') {
    $cart->addItem(1,'王八',23.4,1);
    echo 'add wangba ok';
} else if($_GET['test'] == 'addfz') {
    $cart->addItem(2,'方舟',2347.56,1);
    echo 'add fangzhou ok';
} else if($_GET['test'] == 'clear') {
    $cart->clear();
} else if($_GET['test'] == 'show') {
    print_r($cart->all());
    echo '<br />';
    echo '共',$cart->getCnt(),'種',$cart->getNum(),'個商品<br />';
    echo '共',$cart->getPrice(),'元';
} else {
    print_r($cart);
}
*/

















