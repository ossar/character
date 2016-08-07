<?php

require_once 'functions.php';

ini_set('display_errors', 'on');
error_reporting(E_ALL);
header('Content-type: text/html; charset=UTF-8');


$arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
// 多言語面の指定
$m = '0';
if (isset($_GET['m']) && in_array($_GET['m'], $arr)) {
    $m = $_GET['m'];
}
// 番号
$n = '0';
if (isset($_GET['n']) && in_array($_GET['n'], $arr)) {
    $n = $_GET['n'];
}

// 開始コード、終了コード
$sNum =  hexdec($m) * 65536 + hexdec($n) * 4096;
$eNum =  hexdec($m) * 65536 + (hexdec($n)+ 1) * 4096 - 1;

?>

<style type="text/css">
table {
    border-collapse: collapse;
    font-family: monospace;
    line-height: 1.2em;
    font-size: 16px;
}
td, th {
    border: 1px solid #999;
    padding: 2px 8px;
}
th {
    background-color: #CCC;
}
</style>

<div>
多言語面: 
<select onchange="chgLang(this.value)">
<?php
foreach ($arr as $val) {
    $disp = $val == '0' ? '基本' : '追加'.hexdec($val);
    if ($val == $m) {
        echo "<option value='{$val}' selected>{$disp}</option>\n";
    } else {
        echo "<option value='{$val}'>{$disp}</option>\n";
    }
}
?>
</select>
</div>

<script>
function chgLang(m) {
    document.location.search = '?m='+m;
}
</script>

<div>
|
<?php
foreach ($arr as $val) {
    if ($val == $n) {
        echo "<b>{$val}</b>\n";
    } else {
        echo "<a href='?m={$m}&n={$val}'>{$val}</a>\n";
    }
    echo " | ";
}
?>
</div>

<table>
<tr>
<th>Unicode</th>
<th>参照</th>
<th>UTF-8</th>
<th>文字</th>
</tr>
<?php
for($i=$sNum; $i<=$eNum; $i++) {
    $hex = dechex($i);
    $bytes = uni_to_utf8('U+'.$hex);
    echo '<tr>';
    echo '<td>U+'.strtoupper($hex).'</td>';
    echo '<td>&#x'.strtoupper($hex).'</td>';
    echo '<td>'.$bytes.'</td>';
    echo '<td>'.(strlen($bytes) > 1 ? hex2bin($bytes) : '').'</td>';
    echo '</tr>';
} 
?>
</table>
