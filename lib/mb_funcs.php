<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

function mb_strlen($s)
{
    return preg_match_all('/./u', $s, $tmp);
}

function mb_substr($s, $offset, $len = 'all')
{
    if ($offset<0) $offset = mb_strlen($s) + $offset;
    if ($len!='all') 
    {
        if ($len<0) $len = mb_strlen($s) - $offset + $len;
        $xlen = mb_strlen($s) - $offset;
        $len = ($len>$xlen) ? $xlen : $len;
        preg_match('/^.{' . $offset . '}(.{0,'.$len.'})/us', $s, $tmp);
    }
    else
    {
        preg_match('/^.{' . $offset . '}(.*)/us', $s, $tmp);
    }
    return (isset($tmp[1])) ? $tmp[1] : false;
}

?>