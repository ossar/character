<?php

// コードポイントからUTF-8に変換する
/**
 * @param  string  U+xxxx
 * @return string  UTF-8文字
 */
function cpToUtf8($cp)
{
    // 数字部分だけ抜き出す
    if (!preg_match('/^U\+([0-9a-fA-F]{1,6})$/', $cp, $matches)) {
        return false;
    }
    $cpHex = $matches[1];
    $cpDec = hexdec($cpHex);

    // コードポイントの範囲でバイト数を決定
    if ($cpDec <= 0x7f) {
        // 1バイト文字（有効桁数7ビット）
        $cpBin = base_convert($cpDec, 10, 2);
        $bin = sprintf('%08s', $cpBin);
    } elseif ($cpDec <= 0x7ff) {
        // 2バイト文字（有効桁数11ビット）
        $cpBin = sprintf('%011s', base_convert($cpDec, 10, 2));
        $bin = sprintf('110%05s', substr($cpBin, 0, 5))
             . sprintf('10%06s',  substr($cpBin, 5, 6));
    } elseif ($cpDec <= 0xffff) {
        // 3バイト文字（有効桁数16ビット）
        $cpBin = sprintf('%016s', base_convert($cpDec, 10, 2));
        $bin = sprintf('1110%04s', substr($cpBin, 0,  4))
             . sprintf('10%06s',   substr($cpBin, 4,  6))
             . sprintf('10%06s',   substr($cpBin, 10, 6));
    } elseif ($cpDec <= 0x10ffff) {
        // 4バイト文字（有効桁数21ビット）
        $cpBin = sprintf('%021s', base_convert($cpDec, 10, 2));
        $bin = sprintf('11110%03s', substr($cpBin, 0,  3))
             . sprintf('10%06s',    substr($cpBin, 3,  6))
             . sprintf('10%06s',    substr($cpBin, 9,  6))
             . sprintf('10%06s',    substr($cpBin, 15, 6));
    } else {
        return false;
    }
    $hex = base_convert($bin, 2, 16);
    $char = pack('H*', $hex);
    return $char;
}

header("Content-type:text/plain; charset=utf-8");

echo cpToUtf8('U+3042') ."\n";
echo cpToUtf8('U+0102') ."\n";
echo cpToUtf8('U+41') ."\n";
echo cpToUtf8('U+141') ."\n";
echo cpToUtf8('U+3050') ."\n";
echo cpToUtf8('U+1f196') ."\n";




