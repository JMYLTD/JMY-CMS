<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $content_conf;
$content_conf = array();
$content_conf['num'] = "5";
$content_conf['comments_num'] = "10";
$content_conf['allowComm'] = "0";

