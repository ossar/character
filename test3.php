<?php

// UTF-8文字からコードポイントを取得する

/**
 * @param   string  UTF-8文字
 * @return  string  U+xxxx
 */

function utf8ToCp($chUtf8)
{
    $chUtf8 = mb_substr($chUtf8, 0, 1, 'UTF-8');
    if ($chUtf8 === '') {
        return false;
    }

    var_dump($chUtf8);

    $hexUtf8 = bin2hex($chUtf8);

    if (strlen($hexUtf8) <= 2) {
        echo "1byte\n";
        $byte1 = base_convert($hexUtf8, 16, 2);
        $bin = $byte1;
    } elseif (strlen($hexUtf8) <= 4) {
        echo "2byte\n";
        $byte1 = base_convert(substr($hexUtf8, 0, 2), 16, 2);
        $byte2 = base_convert(substr($hexUtf8, 2, 2), 16, 2);
        $bin = substr($byte1, 3, 5)
             . substr($byte2, 2, 6);
    } elseif (strlen($hexUtf8) <= 6) {
        echo "3byte\n";
        $byte1 = base_convert(substr($hexUtf8, 0, 2), 16, 2);
        $byte2 = base_convert(substr($hexUtf8, 2, 2), 16, 2);
        $byte3 = base_convert(substr($hexUtf8, 4, 2), 16, 2);
        $bin = substr($byte1, 4, 4)
             . substr($byte2, 2, 6)
             . substr($byte3, 2, 6);
    } elseif (strlen($hexUtf8) <= 8) {
        echo "4byte\n";
        $byte1 = base_convert(substr($hexUtf8, 0, 2), 16, 2);
        $byte2 = base_convert(substr($hexUtf8, 2, 2), 16, 2);
        $byte3 = base_convert(substr($hexUtf8, 4, 2), 16, 2);
        $byte4 = base_convert(substr($hexUtf8, 6, 2), 16, 2);
        $bin = substr($byte1, 5, 3)
             . substr($byte2, 2, 6)
             . substr($byte3, 2, 6)
             . substr($byte4, 2, 6);
    } else {
        return false;
    }

    $uniHex = sprintf('%04s', base_convert($bin, 2, 16));
    var_dump($hexUtf8);
    var_dump(strlen($hexUtf8));
    echo " 1: {$byte1}\n";
    echo " 2: {$byte2}\n";
    echo " 3: {$byte3}\n";
    echo " 4: {$byte4}\n";
    echo " {$bin}\n";
    echo "U+{$uniHex}\n";
    echo "-----------------\n";



}

header('Content-type:text/plain; charset=utf-8');

utf8ToCp('a');
utf8ToCp(11);
utf8ToCp('𝕸');
utf8ToCp('𠀋');
utf8ToCp('あ');
utf8ToCp('');
