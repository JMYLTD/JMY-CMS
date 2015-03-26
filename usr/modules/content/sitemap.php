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
$queryDB = $db->query("SELECT c.*, l.* FROM " . DB_PREFIX . "_content as c LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=c.id and l.module='content') WHERE active!='0' ORDER BY date DESC");
if($db->numRows($queryDB) > 0) 
{	
	while($static = $db->getRow($queryDB)) 
	{
		$link = $static['cat'] !== ',0,' ? 'content/' . $core->getCat('content', $static['cat'], 'development') . '/' : 'content/';	
		$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $static['title']. "', '".$config['url']."/".$link.$static['translate'].".html');");		
	}	
}