<?php
/*init00.php
初始框架
*/
//defined('ACC') || exit('ACC defined');
define('ROOT',str_replace('\\','/',dirname(dirname(__FILE__))) . '/');
define('DEBUG',true);

function __autoload($class) {
	if(strtolower(substr($class,-7)) == 'model00'){
	    require(ROOT . 'Model/' . $class . '.class.php');
	} else if(strtolower(substr($class,-4)) == 'tool') {
	    require(ROOT . 'tool/' . $class . '.class.php');
	} else {
	    require(ROOT . 'include/' . $class . '.class.php');
	}
	
}

/*
require(ROOT . 'include/conf00.class.php');
require(ROOT . 'include/mysql00.class.php');
require(ROOT . 'include/log00.class.php');
require(ROOT . 'Model/Model00.class.php');
require(ROOT . 'Model/TestModel00.class.php');
*/

//開啟session
session_start();

//設定報錯級別

if(defined('DEBUG')){
	error_reporting(E_ALL);
}else{
	error_reporting(0);
}


?>