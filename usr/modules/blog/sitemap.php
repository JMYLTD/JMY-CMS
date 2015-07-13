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
$queryDB = $db->query("SELECT * FROM ".DB_PREFIX."_blogs ORDER BY id ASC");
if($db->numRows($queryDB) > 0) 
{	
	while($blog = $db->getRow($queryDB)) 
	{		
		$queryPOST = $db->query("SELECT * FROM ".DB_PREFIX."_blog_posts WHERE bid = ".$blog['id']." AND status='1' ORDER BY id ASC");		
		if($db->numRows($queryPOST) > 0) 
		{
			$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $blog['title']. "', '".$config['url']."/blog/view/".$blog['altname']."');");
			while($blog_posts = $db->getRow($queryPOST)) 
			{
				$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $blog_posts['title']. "', '".$config['url']."/blog/view/".$blog_posts['id']."');");
			}
		}
	}	
}

$core->loadModLang('blog');
$core->tempModule = 'blog';
$uid = '';
$queryP = $db->query("SELECT * FROM ".DB_PREFIX."_blog_posts WHERE bid = '0' AND status='1' ORDER BY uid ASC");		
if($db->numRows($queryP) > 0) 
{	
	while($blog_p = $db->getRow($queryP)) 
	{	
		if ($uid != $blog_p['uid'])
		{
			$uid = $blog_p['uid'];
			$user_q = $db->query("SELECT * FROM ".DB_PREFIX."_users WHERE id = '".$uid."' LIMIT 1");
			if($db->numRows($user_q) == 1)
			{
				$userbb = $db->getRow($user_q);
			}
			$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('"._BLOG_PERSONAL .": ". $userbb['nick']. "', '".$config['url']."/blog/user/".$uid."');");
		}
		$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $blog_p['title']. "', '".$config['url']."/blog/view/".$blog_p['id']."');");
	}
}

