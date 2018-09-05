<?php
require('../include/init00.php');


$cat_id = $_GET['cat_id'] + 0;


$cat = new CatModel00();
$catinfo = $cat->find($cat_id);

$catelist = $cat->select();
$catelist = $cat->getCatTree($catelist);

require(ROOT . 'admin\templates\catedit.html');
?> 