<?php
require('../include/init00.php');
define('ACC',true); 

$cat = new CatModel00();
$catelist = $cat->select();
$catelist = $cat->getCatTree($catelist);
//print_r($tree);
require(ROOT . 'admin/templates/cateadd.html');

?>