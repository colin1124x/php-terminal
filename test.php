<?php

function &x(){
    static $map = array(1,2,3);

    return $map[2];
}

function y(&$v)
{
    $v = &x();
}

$x1 = &x();
$x2 = &x();
y($y);

$x1 = 9;

var_dump($x1, $x2);


var_dump($y);

