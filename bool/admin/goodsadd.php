<?php
/*define('ACC',true)*/
require('../include/init00.php');
$cat = new CatModel00();
$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist);
require(ROOT . 'admin/templates/goodsadd.html');

?>