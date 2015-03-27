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
$queryDB = $db->query("SELECT * FROM ".DB_PREFIX."_board_forums ORDER BY id ASC");
if($db->numRows($queryDB) > 0) 
{	
	while($board = $db->getRow($queryDB)) 
	{		
		$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $board['title']. "', '".$config['url']."/board/forum-".$board['id']."');");
		$queryPOST = $db->query("SELECT * FROM ".DB_PREFIX."_board_threads WHERE forum = ".$board['id']." ORDER BY id ASC");		
		if($db->numRows($queryPOST) > 0) 
		{			
			while($board_threads = $db->getRow($queryPOST)) 
			{
				$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $board_threads['title']. "', '".$config['url']."/board/topic-".$board_threads['id']."');");
			}
		}
	}	
}