<?php

require_once 'functions.php';

ini_set('display_errors', 'on');
error_reporting(E_ALL);
header('Content-type: text/html; charset=UTF-8');



$str = '012ABCxyzαγθあいう①💹Ⅱ㌔';
$bytes = bin2hex($str);
$uni = utf8_to_uni($bytes);
$uniStr = implode(' ', $uni);
$uniRef = utf8_to_uni($bytes, '&#x');
$uniRefStr = implode(' ', $uniRef);




echo <<<HTML
<pre>
文字列        : {$str}
UTF-8 byte列  : {$bytes}
コードポイント: {$uniStr}
文字参照      : {$uniRefStr}

</pre>
HTML;



$res = uniStr('U+238U+41U+33U+C5U+3080U+1F004');
var_dump(hex2bin($res));

