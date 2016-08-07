<?php

require_once 'functions.php';

ini_set('display_errors', 'on');
error_reporting(E_ALL);
header('Content-type: text/html; charset=UTF-8');


$str = '';
if (!empty($_POST['post_string'])){
    $str = $_POST['post_string'];
}

$bytes = bin2hex($str);
$uni = utf8_to_uni($bytes);
$uniRef = utf8_to_uni($bytes, '&#x');

?>

<form action="" method="post">
    <input type="text" name="post_string">
    <button type="submit"> 確認 </button>
</form>

<pre>
文字列        : <?php echo htmlspecialchars($str) . "\n" ?>
UTF-8 byte列  : <?php echo $bytes . "\n" ?>
コードポイント: <?php echo implode(' ', $uni) . "\n" ?>
文字参照      : <?php echo implode(' ', $uniRef) . "\n" ?>
</pre>
