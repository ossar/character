<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);
header('Content-type: text/html; charset=UTF-8');

// 多言語面
$mlPlanes = [
    0  => ['BMP',    '基本多言語面',  'Basic Multilingual Plain'],
    1  => ['SMP',    '追加多言語面',  'Supplementary Multilingual Plain'],
    2  => ['SIP',    '追加漢字面',    'Supplementary Ideographic Plain'],
    3  => ['TIP',    '第三漢字面',    'Tertiary Ideographic Plain'],
    14 => ['SSP',    '追加特殊用途面','Supplementary Special-purpose Plain'],
    15 => ['SPUA-A', '私用面-A',      'Supplementary Private Use Area-A'],
    16 => ['SPUA-B', '私用面-B',      'Supplementary Private Use Area-B'],
];
$plane = '0';
if (isset($_GET['p']) && isset($mlPlanes[$_GET['p']])) {
    $plane = $_GET['p'];
}

// 番号
$codeNums = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
$head = '0';
if (isset($_GET['n']) && in_array($_GET['n'], $codeNums)) {
    $head = $_GET['n'];
}

// 開始コード、終了コード
$sCode =  $plane * 65536 + hexdec($head) * 4096;
$eCode =  $plane * 65536 + (hexdec($head)+ 1) * 4096 - 1;

?>
<style type="text/css">
table {
    border-collapse: collapse;
    font-family: monospace;
    font-size: 14px;
    line-height: 1.2em;
}
th {
    border: 1px solid #999;
    padding: 2px 5px;
    background-color: #CCC;
    font-size: 0.9em;
    line-height: 1.2em;
}
td {
    border: 1px solid #999;
    padding: 3px 5px;
    text-align: center;
}
td .char {
    font-size: 1.4em;
    line-height: 1.3em;
}
td .code {
    font-size: 0.9em;
    line-height: 1.2em;
    color: #777;
}
</style>

<div>
<select onchange="chgPlane(this.value)">
<?php
foreach ($mlPlanes as $key => $val) {
    $slt = $key == $plane ? 'selected' : '';
    echo "<option value='{$key}' {$slt}>{$key}: {$val[1]} ({$val[0]})</option>\n";
}
?>
</select>

<script>
function chgPlane(p) {
    document.location.search = '?p='+p;
}
</script>

|
<?php
foreach ($codeNums as $val) {
    if ($val == $head) {
        echo "<b>{$val}</b>\n";
    } else {
        echo "<a href='?p={$plane}&n={$val}'>{$val}</a>\n";
    }
    echo " | ";
}
?>
</div>

<table>
<tr>
<th>Unicode</th>
<th>0</th><th>1</th><th>2</th><th>3</th>
<th>4</th><th>5</th><th>6</th><th>7</th>
<th>8</th><th>9</th><th>A</th><th>B</th>
<th>C</th><th>D</th><th>E</th><th>F</th>
</tr>
<?php
for($i=$sCode; $i<=$eCode; $i++) {
    $mod = $i%16;
    $hex = sprintf('%04s', dechex($i));
    $entity = '&#x'.strtoupper($hex).';';
    $utf8Str = mb_decode_numericentity($entity, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8');
    if ($mod == 0) {
        echo '<tr>';
        echo '<td>U+'.strtoupper($hex).'</td>';
    }
    echo '<td>';
    echo '<div class="char">'.$entity.'</div>';
    echo '<div class="code">'.bin2hex($utf8Str).'</div>';
    echo '</td>'."\n";
    if ($mod == 15) {
        echo '</tr>';
    }
}
?>
</table>

