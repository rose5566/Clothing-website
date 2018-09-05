<?php
    require('./include/init00.php'); 
    session_destroy();
    
    $msg = '退出登入';
    
    //顯示$msg的訊息
    require(ROOT . 'view/front/msg.html');
?>