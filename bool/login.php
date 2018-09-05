<?php
//define('ACC',true);
require('./include/init00.php');

if(isset($_POST['act'])) {
    //這說明是點擊按鈕發送過來的
    //接收用戶名/密碼 驗證碼等
    
    $u = $_POST['username'];
    $p = $_POST['passwd'];
    
    if(isset($u) && empty($u)) {
        exit('無輸入帳戶');
    }
    
    if(isset($p) && empty($p)) {
        exit('無輸入密碼');
    }
    
    
    //檢測用戶是否正確，密碼是否正確
    $user = new UserModel00();
    $row = $user->checkUser($u,$p);
    if(empty($row)) {
        $msg = '登入的帳號與密碼錯誤';
	echo $msg;
    } else {
        $msg = '登入成功';
	echo $msg;
	session_start();
        $_SESSION = $row;
    }
    
    require(ROOT . 'view/front/msg.html');
    exit;
    
} else {
    require(ROOT . 'view/front/denglu.html');
}
?>