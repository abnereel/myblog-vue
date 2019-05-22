<?php

/**
 * 获取随机字符串
 * @param Int $len
 * @return bool|string
 */
function random(Int $len)
{
    $pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    return substr(str_shuffle(str_repeat($pool, ceil($len / strlen($pool)))), 0, $len);
}