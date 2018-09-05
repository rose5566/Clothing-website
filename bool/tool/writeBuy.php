    /*
    �q��J�w�̭��n���@�����`
    
    �q���Ū���e�f�a�}�A����n���H��
    �q�ʪ���Ū���`����H��
    �g�Jorderinfo��
    */




    //print_r($_POST);
    $OI = new OIModel();
    if(!$OI->_validate($_POST)) { // �p�G�ƾ�����S�q�L�A�����h�X.
        $msg = implode(',',$OI->getErr());
        include(ROOT . 'view/front/msg.html');
        exit;
    }

    
    // �۰ʹL�o
    $data = $OI->_facade($_POST);

    // �۰ʶ�R
    $data = $OI->_autoFill($data);

    // �g�J�`���B
    $total = $data['order_amount'] = $cart->getPrice();

    // �g�J�Τ�W�A�qsessionŪ
    $data['user_id'] = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
    $data['username'] = isset($_SESSION['username'])?$_SESSION['username']:'�ΦW';

    // �g�J�q�渹
    $order_sn = $data['order_sn'] = $OI->orderSn();


    if(!$OI->add($data)) {
        $msg = '�U�q�楢��';
        include(ROOT . 'view/front/msg.html');
        exit;
    }

    // ���??���ͪ�order_id����
    $order_id = $OI->insert_id();

    // echo '�q��g�J�J���\';

    /*
    �n��q�檺�ӫ~�g�J�ƾڮw
    1?�q��̦�N?�ӫ~,��?�i�H�`���g�Jordergoods��
    */
    $items = $cart->all(); // ��^�q�椤�Ҧ����ӫ~
    $cnt = 0;  // cnt��?�������Jordergoods���\������

    $OG = new OGModel(); // ���ordergoods���ާ@model

    foreach($items as $k=>$v) {  // �`���q�椤���ӫ~,�g�Jordergoods��
        $data = array();
        
        $data['order_sn'] = $order_sn;
        $data['order_id'] = $order_id;
        $data['goods_id'] = $k;
        $data['goods_name'] = $v['name'];
        $data['goods_number'] = $v['num'];
        $data['shop_price'] = $v['price'];
        $data['subtotal'] = $v['price']*$v['num'];
        
        if($OG->addOG($data)) {
            $cnt += 1;  // ���J�@��og���\,$cnt+1.
            // �]���A1?�q�榳N���ӫ~,����N���ӫ~,�����J���\,�~��q�洡�J���\!
        }
    }



    if(count($items) !== $cnt) { // �ʪ����̪��ӫ~�ƶq�A�èS�������J�w���\
        // �M�����q��
        $OI->invoke($order_id);
        $msg = '�U�q�楢��';
        include(ROOT . 'view/front/msg.html');
        exit;
    }


    // �U�q�榨�\�F
    // �M���ʪ���
    $cart->clear();

