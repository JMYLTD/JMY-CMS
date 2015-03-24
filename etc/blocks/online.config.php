<?php

if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}

global $online_conf;
$online_conf = array();
$online_conf['guest'] = "1";
$online_conf['user'] = "1";
$online_conf['bot'] = "1";
$online_conf['top'] = "1";
$online_conf['top_numb'] = "30";

