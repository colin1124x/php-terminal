#!/usr/bin/env php

<?php

require __DIR__.'/../vendor/autoload.php';

use Rde\Flag;

$flag = new Flag;

// 取參照
$str = &$flag->string('str-name', 'default', 'describes...');

// 傳參照
$flag->intVar($int, 'int-name', 0, 'describes...');

// 解析
$args = $flag->parse($argv);

var_dump($args, $str, $int);

