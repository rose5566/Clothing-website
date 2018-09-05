<?php



/***
regAct.php
作用:接收用戶註冊的表單信息，完成註冊
***/

//print_r($_POST);


define('ACC',true);
require('./include/init00.php');


$user = new UserModel00();

if(!$user->_validate($_POST)) {  // 自動檢驗
    $msg = implode('<br />',$user->getErorr());
    include(ROOT . 'view/front/msg.html');
    exit;
}


// 檢驗用戶名是否已存在
if($user->checkUser($_POST['username'])) {
    $msg = '用戶名已存在';
    include(ROOT . 'view/front/msg.html');
    exit;
}


$data = $user->_autoFill($_POST);  // 自動填充
$data = $user->_facade($data);  // 自動過濾


if($user->reg($data)) {
   $msg = '用戶註冊成功';
} else {
   $msg = '用戶註冊失败';
}



// 引入view
include(ROOT . 'view/front/msg.html');





