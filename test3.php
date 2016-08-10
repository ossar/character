<?php

// UTF-8文字からコードポイントを取得する

/**
 * @param   string  UTF-8文字
 * @return  string  U+xxxx
 */

function utf8ToCp($chUtf8)
{
    // 文字列だけを扱う
    if (!is_string($chUtf8)) {
        return false;
    }
    // 先頭１文字だけが対象
    $chUtf8 = mb_substr($chUtf8, 0, 1, 'UTF-8');
    if ($chUtf8 === '') {
        return false;
    }
    // 16進数のコードに変換
    $hexUtf8 = bin2hex($chUtf8);

    // 長さでバイト数を分ける
    if (strlen($hexUtf8) <= 2) {
        // 1バイト文字
        $byte1 = sprintf('%08s', base_convert($hexUtf8, 16, 2));
        // 先頭ビットの確認
        if (0 !== strpos($byte1, '0')) {
            return false;
        }
        $bin = $byte1;
    } elseif (strlen($hexUtf8) <= 4) {
        // 2バイト文字
        $byte1 = base_convert(substr($hexUtf8, 0, 2), 16, 2);
        $byte2 = base_convert(substr($hexUtf8, 2, 2), 16, 2);
        // 各バイトのビット確認 (110.., 10..)
        if (0 !== strpos($byte1, '110') ||
            0 !== strpos($byte2, '10')) {
            return false;
        }
        $bin = substr($byte1, 3, 5)
             . substr($byte2, 2, 6);
    } elseif (strlen($hexUtf8) <= 6) {
        // 3バイト文字
        $byte1 = base_convert(substr($hexUtf8, 0, 2), 16, 2);
        $byte2 = base_convert(substr($hexUtf8, 2, 2), 16, 2);
        $byte3 = base_convert(substr($hexUtf8, 4, 2), 16, 2);
        // 各バイトの先頭ビット確認 (1110.., 10.., 10.., 10..)
        if (0 !== strpos($byte1, '1110') ||
            0 !== strpos($byte2, '10') ||
            0 !== strpos($byte3, '10')) {
            return false;
        }
        $bin = substr($byte1, 4, 4)
             . substr($byte2, 2, 6)
             . substr($byte3, 2, 6);
    } elseif (strlen($hexUtf8) <= 8) {
        // 4バイト文字
        $byte1 = base_convert(substr($hexUtf8, 0, 2), 16, 2);
        $byte2 = base_convert(substr($hexUtf8, 2, 2), 16, 2);
        $byte3 = base_convert(substr($hexUtf8, 4, 2), 16, 2);
        $byte4 = base_convert(substr($hexUtf8, 6, 2), 16, 2);
        // 各バイトの先頭ビット確認 (11110.., 10.., 10.., 10..)
        if (0 !== strpos($byte1, '11110') ||
            0 !== strpos($byte2, '10') ||
            0 !== strpos($byte3, '10') ||
            0 !== strpos($byte4, '10')) {
            return false;
        }
        $bin = substr($byte1, 5, 3)
             . substr($byte2, 2, 6)
             . substr($byte3, 2, 6)
             . substr($byte4, 2, 6);
    } else {
        return false;
    }

    $uniHex = sprintf('%04s', base_convert($bin, 2, 16));
    return "U+{$uniHex}";
}

error_reporting(E_ALL);
ini_set('display_errors', 'on');

header('Content-type:text/plain; charset=utf-8');

echo utf8ToCp('a')."\n";
echo utf8ToCp(11)."\n";
echo utf8ToCp('𝕸')."\n";
echo utf8ToCp('𠀋')."\n";
echo utf8ToCp('あ')."\n";
echo utf8ToCp(['a'])."\n";
echo utf8ToCp(mb_convert_encoding('あ', 'SJIS-win', 'UTF-8'))."\n";
echo utf8ToCp(pack('H*', 'fc01'))."\n";
