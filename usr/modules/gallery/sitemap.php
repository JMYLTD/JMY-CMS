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
$queryDB = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_albums ORDER BY album_id ASC");
if($db->numRows($queryDB) > 0) 
{	
	while($gallery = $db->getRow($queryDB)) 
	{		
		$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $gallery['title']. "', '".$config['url']."/gallery/album/".$gallery['trans']."');");	
	}	
}