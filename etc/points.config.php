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


global $points_conf;
$points_conf = array();
$points_conf['add_news'] = "10";
$points_conf['add_comment'] = "10";
$points_conf['register'] = "0";
$points_conf['carma'] = "0";
$points_conf['add_friend'] = "0";
$points_conf['rating'] = "10";

