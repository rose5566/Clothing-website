<?php
/*index.inc.php
主頁面
*/
require('.\init00.php');
//log::write('記錄');
//$mysql = mysql00::getIns();
//var_dump($mysql);

//$data['name'] = $_POST['name'];


//$sql = "insert into test(t1,t2) values('$t1','$t2')";
//var_dump($mysql->query($sql));


//var_dump($mysql->autoExecute('class1',$data,'insert'));









$data = array();
$data['name'] = $_POST['name'];
$data['sex'] = $_POST['sex'];




$TestModel = new TestModel00();
if($TestModel->reg($data)){
	$res = true;
}else{
	$res = false;
}
echo $res?'win':'lose';

?>