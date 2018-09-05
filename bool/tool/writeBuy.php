    /*
    訂單入庫最重要的一個環節
    
    從表單讀取送貨地址，手機要等信息
    從購物車讀取總價格信息
    寫入orderinfo表
    */




    //print_r($_POST);
    $OI = new OIModel();
    if(!$OI->_validate($_POST)) { // 如果數據檢驗沒通過，報錯退出.
        $msg = implode(',',$OI->getErr());
        include(ROOT . 'view/front/msg.html');
        exit;
    }

    
    // 自動過濾
    $data = $OI->_facade($_POST);

    // 自動填充
    $data = $OI->_autoFill($data);

    // 寫入總金額
    $total = $data['order_amount'] = $cart->getPrice();

    // 寫入用戶名，從session讀
    $data['user_id'] = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
    $data['username'] = isset($_SESSION['username'])?$_SESSION['username']:'匿名';

    // 寫入訂單號
    $order_sn = $data['order_sn'] = $OI->orderSn();


    if(!$OI->add($data)) {
        $msg = '下訂單失敗';
        include(ROOT . 'view/front/msg.html');
        exit;
    }

    // 獲取??產生的order_id的值
    $order_id = $OI->insert_id();

    // echo '訂單寫入入成功';

    /*
    要把訂單的商品寫入數據庫
    1?訂單裡有N?商品,我?可以循環寫入ordergoods表
    */
    $items = $cart->all(); // 返回訂單中所有的商品
    $cnt = 0;  // cnt用?紀錄插入ordergoods成功的次數

    $OG = new OGModel(); // 獲取ordergoods的操作model

    foreach($items as $k=>$v) {  // 循環訂單中的商品,寫入ordergoods表
        $data = array();
        
        $data['order_sn'] = $order_sn;
        $data['order_id'] = $order_id;
        $data['goods_id'] = $k;
        $data['goods_name'] = $v['name'];
        $data['goods_number'] = $v['num'];
        $data['shop_price'] = $v['price'];
        $data['subtotal'] = $v['price']*$v['num'];
        
        if($OG->addOG($data)) {
            $cnt += 1;  // 插入一條og成功,$cnt+1.
            // 因為，1?訂單有N條商品,必須N條商品,都插入成功,才算訂單插入成功!
        }
    }



    if(count($items) !== $cnt) { // 購物車裡的商品數量，並沒有全部入庫成功
        // 撤消此訂單
        $OI->invoke($order_id);
        $msg = '下訂單失敗';
        include(ROOT . 'view/front/msg.html');
        exit;
    }


    // 下訂單成功了
    // 清空購物車
    $cart->clear();

