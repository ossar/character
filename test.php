<?php
ini_set('display_errors', 'on');
error_reporting(E_ALL);

header('Content-type:text/plain; charset=utf-8');



$strUtf8 = 'こんにちは😄!!';
$strUtf8 = 'あ';

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


echo "-----------\n";
$cp = 'U+1F005';
//$cp = 'U+3042';
$ft = preg_replace('/^U\+/', '', $cp);
$dec = hexdec($ft);
echo "{$cp}\n";
echo "{$ft}\n";
echo "{$dec}\n";
echo IntlChar::chr($dec)."\n";

$entity = "&#x{$ft};";
echo $entity . "\n";
echo mb_decode_numericentity($entity, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8') . "\n";


echo "===========\n";


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

