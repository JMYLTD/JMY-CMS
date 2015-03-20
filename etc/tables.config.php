<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $tables_conf;
$tables_conf = array();
$tables_conf['num'] = "xol5";
$tables_conf['comments_num'] = "xol10";
$tables_conf['allowComm'] = "0";

