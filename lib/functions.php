<?php

function hello(){
    echo "hello";
}

function diskr($a, $b, $c){
    $d=$b*2-(4*$a*$c);
    echo $d;
}

function kosti(){
    $kost1 = rand(1,6);
    $kost2 = rand(1,6);


    echo "<div class=\"kosti_img\">";
        echo "<img src='img/ris$kost1.gif'>";
        echo "<img src='img/ris$kost2.gif'>";
    echo "</div>";

    $sum = $kost1+$kost2;

    echo "<div class=\"kosti_message\">Вам выпали числа на сумму ".$sum."</div>";
}