<?php
require('../include/init00.php');
define('ACC',true); 


$cat = new CatModel00();
$catlist = $cat->select();
$catlist = $cat->getCatTree($catlist);


require(ROOT . 'admin\templates\catelist.html');



?>