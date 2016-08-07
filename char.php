<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);
header('Content-type: text/html; charset=UTF-8');


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
<?php

$arr = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F'];
$m = '0';
if (isset($_GET['m']) && in_array($_GET['m'], $arr)) {
    $m = $_GET['m'];
}

$n = '0';
if (isset($_GET['n']) && in_array($_GET['n'], $arr)) {
    $n = $_GET['n'];
}

echo '<div>';
echo '多言語面: ';
echo '<select onchange="chgLang(this.value)">';
foreach ($arr as $val) {
    if ($val == '0') {
        $disp = '基本';
    } else {
        $disp = '追加'.hexdec($val);
    }
    if ($val == $m) {
        echo "<option value='{$val}' selected>{$disp}</option>\n";
    } else {
        echo "<option value='{$val}'>{$disp}</option>\n";
    }
}
echo '</select>';
echo '</div>';
?>
<script>
function chgLang(m) {
    document.location.search = '?m='+m+'&n=<?php echo $n ?>';
}
</script>
<?php

echo '<div>';
foreach ($arr as $val) {
    if ($val == $n) {
        echo "<b>{$val}</b>\n";
    } else {
        echo "<a href='?m={$m}&n={$val}'>{$val}</a>\n";
    }
}
echo '</div>';

$sNum =  hexdec($m) * 256 * 256 + hexdec($n) * 4096;
$eNum =  hexdec($m) * 256 * 256 + (hexdec($n)+ 1) * 4096 - 1;

echo '<table>';
echo '<tr>';
echo '<th>Unicode</th>';
echo '<th>参照</th>';
echo '<th>UTF-8</th>';
echo '<th>文字</th>';
echo '</tr>';

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

echo '</table>';




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

/**
 * unicodeコードポイント列をUTF-8バイト列に変換する
 */
function uniStr($str)
{
    $res = preg_replace_callback(
        '/U\+[0-9A-Fa-f]{1,6}/',
        function ($matches) {
            return uni_to_utf8($matches[0]);
        },
        $str
    );
    return $res;
}

/**
 * unidodeコードポイント -> utf-8バイト列
 */
function uni_to_utf8($str)
{
    $str = strtoupper($str);
    if (!preg_match('/^U\+([0-9A-F]{1,6})$/', $str, $regex)) {
        return false;
    }
    $hex = $regex[1];
    $dec = hexdec($hex);
    if ($dec >= 0x0000 && $dec <= 0x007F) {
        return $hex;
    } elseif ($dec >= 0x0080 && $dec <= 0x07FF) {
        $bin = sprintf('%011s', base_convert($hex, 16, 2));
        return base_convert('110' . substr($bin, 0, 5), 2, 16)
             . base_convert('10'  . substr($bin, 5, 6), 2, 16);
    } elseif ($dec >= 0x0800 && $dec <= 0xFFFF) {
        $bin = sprintf('%016s', base_convert($hex, 16, 2));
        return base_convert('1110' . substr($bin, 0,  4), 2, 16)
             . base_convert('10'   . substr($bin, 4,  6), 2, 16)
             . base_convert('10'   . substr($bin, 10, 6), 2, 16);
    } elseif ($dec >= 0x10000 && $dec <= 0x10FFFF) {
        $bin = sprintf('%021s', base_convert($hex, 16, 2));
        return base_convert('11110' . substr($bin, 0,  3), 2, 16)
             . base_convert('10'    . substr($bin, 3,  6), 2, 16)
             . base_convert('10'    . substr($bin, 9,  6), 2, 16)
             . base_convert('10'    . substr($bin, 15, 6), 2, 16);
    } else {
        throw new Exception('Unkown : ' . $str);
    }
}

/**
 * バイト列から１バイト分切り出す
 */
function shiftByte(&$str)
{
    $byte = substr($str, 0, 2);
    $str = substr($str, 2);
    return $byte;
}

/**
 * UTF-8のバイト列をunicodeのコードポイントに変換する
 * @param   string  UTF-8のバイト列
 * @return  array   各文字のunicodeコードポイントを配列にしたもの
 */
function utf8_to_uni($str, $prefix = 'U+')
{
    $res = [];
    while ($str) {
        $byte1 = shiftByte($str);
        $dec = hexdec($byte1);

        if ($dec >= 0x00 && $dec <= 0x7F) {
            // 1byte文字
            $uniBin = base_convert($byte1, 16, 2);

        } elseif ($dec >= 0xC2 && $dec <= 0xDF) {
            // 2byte文字
            $byte2 = shiftByte($str);
            $bin1 = base_convert($byte1, 16, 2);
            $bin2 = base_convert($byte2, 16, 2);
            $uniBin = substr($bin1, 3) . substr($bin2, 2);

        } elseif ($dec >= 0xE0 && $dec <= 0xEF) {
            // 3byte文字
            $byte2 = shiftByte($str);
            $byte3 = shiftByte($str);
            $bin1 = base_convert($byte1, 16, 2);
            $bin2 = base_convert($byte2, 16, 2);
            $bin3 = base_convert($byte3, 16, 2);
            $uniBin = substr($bin1, 4) . substr($bin2, 2) . substr($bin3, 2);

        } elseif ($dec >= 0xF0 && $dec <= 0xF7) {
            // 4byte文字
            $byte2 = shiftByte($str);
            $byte3 = shiftByte($str);
            $byte4 = shiftByte($str);
            $bin1 = base_convert($byte1, 16, 2);
            $bin2 = base_convert($byte2, 16, 2);
            $bin3 = base_convert($byte3, 16, 2);
            $bin4 = base_convert($byte4, 16, 2);
            $uniBin = substr($bin1, 5) . substr($bin2, 2) . substr($bin3, 2) . substr($bin4, 2);

        } else {
            throw new Exception('Unkown : ' . $byte1);
        }

        $res[] = $prefix . base_convert($uniBin, 2, 16);
    }
    return $res;
}
