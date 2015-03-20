<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $gallery_config;
$gallery_config = array();
$gallery_config['size-mini'] = "150";
$gallery_config['size-long'] = "450";
$gallery_config['alb-col'] = "3";
$gallery_config['photos-col'] = "3";
$gallery_config['photos-num'] = "8";
$gallery_config['search-col'] = "3";
$gallery_config['search-num'] = "8";
$gallery_config['comment'] = "1";
$gallery_config['add'] = "1";
$gallery_config['save'] = "files/gallery/";
$gallery_config['save-thumb'] = "files/gallery/thumb/";
$gallery_config['waterdir'] = "files/gallery/watermark.png";
$gallery_config['quality'] = "100";
$gallery_config['max_size'] = "1048578";
$gallery_config['typefile'] = "jpg,jpeg,gif,png,bmp";

