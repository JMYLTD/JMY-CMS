<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $admin_conf;
$admin_conf = array();
$admin_conf['num'] = "50";
$admin_conf['ipaccess'] = "";
$admin_conf['sessions'] = "0";
$admin_conf['bar'] = "0";
$admin_conf['htmlEditor'] = "0";
$admin_conf['lang'] = "ru";

