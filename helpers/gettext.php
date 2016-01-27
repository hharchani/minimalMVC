<?php

function __($str) {
    if (!$str) return $str;
    $str = str_replace("\r\n", "\n", $str);
    return _($str);
}
