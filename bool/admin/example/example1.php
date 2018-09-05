<?php

for($i=1;$i<=5;$i+=2) {
    $j = 1;
    while($j <= $i) {
        echo '*';
	$j += 1;
    }
    echo '<br />';
}
$j = 1;
while($j <= 3) {
    $i = 3;
    while($i >= $j) {
        echo '*';
	$i--;
    }
    echo '<br />';
    $j+=2;
}
?>