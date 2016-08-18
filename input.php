<?php

header("Content-type:text/html; charset=UTF-8");

if (isset($_POST['str'])) {
    $str = $_POST['str'];
    $utf8Arr = [];
    $uniArr = [];
    $bufStr = $str;
    while ($bufStr !== '') {
        $ch = mb_substr($bufStr, 0, 1, 'UTF-8');
        $utf8Arr[] = bin2hex($ch);
        $buf = mb_encode_numericentity($ch, [0x0, 0x10ffff, 0, 0xffffff], 'UTF-8', true);
        $uniArr[] = preg_replace('/&#x([0-9a-fA-F]+);/', 'U+$1', $buf);
        $bufStr = mb_substr($bufStr, 1, null, 'UTF-8');
    }
}

?>
<form action="" method="post">
<pre>
input   : <?php echo htmlspecialchars($str) ?> 
utf8    : <?php echo implode(' ', $utf8Arr) ?> 
unicode : <?php echo implode(' ', $uniArr) ?> 
</pre>
<input type="text" name="str" value="">
<button>encode</button>
</form>
