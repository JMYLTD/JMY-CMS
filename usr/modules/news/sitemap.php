<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
global $core, $config;
$queryDB = $db->query("SELECT n.*, c.* FROM ".DB_PREFIX."_news as n LEFT JOIN ".DB_PREFIX."_langs as c on(c.postId=n.id and c.module='news') WHERE active!='0' ORDER BY date DESC");
if($db->numRows($queryDB) > 0) 
{	
	while($news = $db->getRow($queryDB)) 
	{
		$news_link = $news['cat'] !== ',0,' ? 'news/' . $core->getCat('news', $news['cat'], 'development') . '/' : 'news/';
		$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $news['title']. "', '".$config['url']."/".$news_link . $news['altname'] . ".html');");		
	}	
}