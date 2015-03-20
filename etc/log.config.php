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


global $log_conf;
$log_conf = array();
$log_conf['phpError'] = "1";
$log_conf['queryError'] = "1";
$log_conf['dbError'] = "1";
$log_conf['accesError'] = "1";
$log_conf['compressSize'] = "204800";

