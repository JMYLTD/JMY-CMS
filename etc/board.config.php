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


global $board_conf;
$board_conf = array();
$board_conf['posts_num'] = "15";
$board_conf['threads_num'] = "15";
$board_conf['loadFiles'] = "1";
$board_conf['maxWH'] = "500";
$board_conf['maxSize'] = "9097152";
$board_conf['formats'] = "gif,jpg,jpeg,png,zip,rar";

