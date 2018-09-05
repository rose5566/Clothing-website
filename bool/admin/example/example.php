<?php
header("Content-Type: text/html; charset=utf8");
define('ACC',true);

for($i=1;$i<100;$i++) {
    $t = 1;
    for($j=1;$j<=$i;$j++) {
        if($i%$j == 0) {
	    $t++;
	}
    }
    
    if($t == 3) {
        echo $i,'<br />'; 
    }	
}    
?>