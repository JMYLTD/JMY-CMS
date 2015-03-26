<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $sitemap_conf;
$sitemap_conf = array();
$sitemap_conf['priority'] = "0.5";
$sitemap_conf['change'] = "weekly";
$sitemap_conf['keywords'] = "карта сайта, sitemap, карта";
$sitemap_conf['description'] = "Карта сайта, где вы можете найти все необходимые ссылки!";

