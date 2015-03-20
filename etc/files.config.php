<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $files_conf;
$files_conf = array();
$files_conf['imgFormats'] = "jpg,gif,png";
$files_conf['attachFormats'] = "zip,rar,mp3,avi,mp4,flv,3gp";
$files_conf['max_size'] = "10240000";
$files_conf['thumb_width'] = "600";
$files_conf['quality'] = "100";
$files_conf['watermark'] = "1";
$files_conf['watermark_text'] = "JMY CMS";
$files_conf['watermark_image'] = "";
$files_conf['watermark_valign'] = "bottom";
$files_conf['watermark_halign'] = "left";
$files_conf['lang'] = "ru";

