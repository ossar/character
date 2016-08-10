<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

header('Content-type:text/plain; charset=utf-8');



$strUtf8 = 'こんにちは😄!!';
$strUtf8 = '𠮷吉';

$strUni  = mb_convert_encoding($strUtf8, 'UTF-32', 'UTF-8');
$codeUni = bin2hex($strUni);


codeUni($codeUni);

function codeUni($codeUni)
{
    $strUni = pack('H*', $codeUni);
    $strUtf8 = mb_convert_encoding($strUni, 'UTF-8', 'UTF-32');
    $codeUtf8 = bin2hex($strUtf8);
    echo "code uni : {$codeUni}\n";
    echo "str  utf8: {$strUtf8}\n";
    echo "code utf8: {$codeUtf8}\n";
}


$entityStr = '&#x0031;&#x20E3;';
$entityStr = '&#x0041;&#x0300;';
//$entityStr = '&#x41;&#x0300;';
$utf8Str = mb_decode_numericentity($entityStr, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8');
echo "{$entityStr}\n";
echo "{$utf8Str}\n";
echo bin2hex($utf8Str)."\n";


echo "=========================\n\n";

$cp = 'U+1F005';
//$cp = 'U+3042';
$cp = 'U+1F358';
$ft = preg_replace('/^U\+/', '', $cp);
$dec = hexdec($ft);
echo "{$cp}\n";
echo "{$ft}\n";
echo "{$dec}\n";
echo IntlChar::chr($dec)."\n";

$entityStr = "&#x{$ft}; &#x1F004; &#12320; &#x2000B;";
echo $entityStr . "\n";
echo mb_decode_numericentity($entityStr, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8') . "\n";
echo mb_decode_numericentity($entityStr, [0x0, 0x10ffff, 0, 0x00f230], 'UTF-8') . "\n";
echo mb_decode_numericentity($entityStr, [0x0, 0x10ffff, 0, 0x000000], 'UTF-8') . "\n";

echo mb_encode_numericentity('〠', [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8', true) . "\n";
echo mb_encode_numericentity('〠', [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8', false) . "\n";
echo "=========================\n\n";

$utf8Str = '1Aあ丈𠀋𝒜';
$utf8Code = bin2hex($utf8Str);
$entityStr = mb_encode_numericentity($utf8Str, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8', true);
echo $utf8Str . "\n";
echo $utf8Code . "\n";
echo hex2bin($utf8Code) . "\n";
echo pack('H*', $utf8Code) . "\n";
echo $entityStr . "\n";

if (preg_match_all('/&#x([0-9a-f]{1,6});/', $entityStr, $regex)) {
    print_r($regex[1]);
}
echo "=========================\n\n";


$codepoints = [
    0x0031,
    0x2051,
    0x20B5,
    0x1F430,
];
printf("%-10s\t%-10s\t%s\t%8s\t%8s\t%8s\t%8s\n"
    , 'codepoint'
    , 'entity'
    , 'char'
    , 'utf-8'
    , 'utf-16'
    , 'utf-16LE'
    , 'utf-32'
    , 'utf-32LE'
);
printf("%'-100s\n", '-');

foreach ($codepoints as $cp) {
    $cpHex = sprintf('%04s', dechex($cp));
    $entity = "&#x{$cpHex};";
    //echo "{$entity}\n";
    $utf8Str = mb_decode_numericentity($entity, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8');
    printf("%-10s\t%s\t%s\t%8s\t%8s\t%8s\t%8s\n"
        , $cpHex
        , $entity
        , $utf8Str
        , bin2hex($utf8Str)
        , bin2hex(mb_convert_encoding($utf8Str, 'UTF-16', 'UTF-8'))
        , bin2hex(mb_convert_encoding($utf8Str, 'UTF-16LE', 'UTF-8'))
        , bin2hex(mb_convert_encoding($utf8Str, 'UTF-32', 'UTF-8'))
        , bin2hex(mb_convert_encoding($utf8Str, 'UTF-32LE', 'UTF-8'))
    );
}
echo "=========================\n\n";

//echo "UTF-8文字列->コードポイント\n\n";
//$utf8Str = 'あいうえお';
//$entityStr = mb_encode_numericentity($utf8Str, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8', true);
//echo "{$utf8Str}\n";
//echo "{$entityStr}\n";
//$codePoints = call_user_func(function($entityStr) {
//    if (preg_match_all('/&#x([0-9A-F]{1,6});/i', $entityStr, $matches)) {
//        $arr = array_map(function($n) {
//            return "U+{$n}";
//        }, $matches[1]);
//        return $arr;
//    }
//    return [];
//}, $entityStr);
//print_r($codePoints);
////print_r($codePoints($entityStr));
//echo "=========================\n\n";

echo "②UTF-8文字列->コードポイント\n\n";
$utf8Str = '1Aあいう¶𝘉𠀋';
$entityStr = mb_encode_numericentity($utf8Str, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8', true);
$uniStr = preg_replace_callback('/&#x([0-9a-f]{1,6});/i', function($matches) {
    return sprintf("U+%04s", $matches[1]);
} , $entityStr);
echo "UTF-8 char: {$utf8Str}\n";
//echo "{$entityStr}\n";
echo "UTF-8 hex : " . bin2hex($utf8Str)."\n";
echo "codepoint : {$uniStr}\n";

echo "=========================\n\n";
echo "コードポイント->UTF-8文字列\n\n";
$uniStr = 'U+31U+42U+3046U+1F431';
$entityStr = preg_replace_callback('/U\+([0-9a-fA-F]{1,6})/', function($matches) {
    return sprintf("&#x%04s;", $matches[1]);
}, $uniStr);
$utf8Str = mb_decode_numericentity($entityStr, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8');
echo "codepoint : {$uniStr}\n";
//echo "{$entityStr}\n";
echo "UTF-8 hex : " . bin2hex($utf8Str)."\n";
echo "UTF-8 char: {$utf8Str}\n";

echo "=========================\n\n";
/*
echo "===========\n\n";
echo "-----------\n";
// テストデータ
$convmap = [ 0x0, 0xffff, 0, 0xffff ];
$msg = '';
for ($i=0; $i < 1000; $i++) {
  // chr() では 128 より大きい UTF-8 データを正しく生成できないので、mb_decode_numericentity() を使います
  $msg .= mb_decode_numericentity('&#'.$i.';', $convmap, 'UTF-8');
}
echo $msg."\n";
echo "===========\n";
*/

exit;

echo $str . "\n";
echo bin2hex($str) . "\n";
echo hex2bin(bin2hex($str)) . "\n";
echo bin2hex(mb_convert_encoding($str, 'UTF-32', 'UTF-8')) . "\n";
echo "\n";

echo $code . "\n";
echo sprintf('%08s', $code) . "\n";
//echo hex2bin(sprintf('%08s', $code)) . "\n";
echo mb_convert_encoding(hex2bin(sprintf('%08s', $code)), 'UTF-8', 'UCS-4') . "\n";
echo mb_convert_encoding(hex2bin(sprintf('%08s', $code)), 'UTF-8', 'UTF-32') . "\n";


$strings = [
    'こんにちは',
];


foreach ($strings as $val) {
    $strUtf8 = $val;
    $strUni = mb_convert_encoding($strUtf8, 'UTF-32', 'UTF-8');
    echo $str . "\n";
    echo bin2hex($strUtf8) . "\n";
    echo bin2hex($strUni) . "\n";
    echo "\n";
}

utf8ToUniChar('愛');
utf8ToUniChar('1');
utf8ToUniChar('A');
utf8ToUniChar('あ');
utf8ToUniChar('😄');


function utf8ToUniChar($ch)
{
    echo "\n";
    echo "STRING : {$ch}\n";
    echo "UTF-8  : ".bin2hex($ch)."\n";
    $ch = mb_convert_encoding($ch, 'UTF-32', 'UTF-8');
    echo "Unicode: ".bin2hex($ch). "\n";
    $uni = sprintf('%04s', ltrim(bin2hex($ch), '0'));
    echo "Unicode: {$uni}\n";
    UniToUtf8($uni);

}


function UniToUtf8($hex)
{
    echo "\n";
    echo __FUNCTION__."\n";
    echo "hex : {$hex}\n";
    $strUni = hex2bin(sprintf('%08s', $hex));
    $strUtf8 = mb_convert_encoding($strUni, 'UTF-8', 'UTF-32');
    echo "UTF-8 : {$strUtf8}\n";
    echo "UTF-8 : ".bin2hex($strUtf8)."\n";

}




// UTF-8 -> Unicode
// Unicode -> UTF-8
// Unicodeのコードポイントを取得
// UTF-8のバイトを取得

