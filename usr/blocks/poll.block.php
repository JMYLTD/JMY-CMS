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
loadConfigBLOCK('poll');
loadLang('blocks');
global $core, $db, $poll_conf;
	
	require ROOT . 'usr/plugins/poll.plugin.php';
	if ($poll_conf['poll_rand']==1) 
	{
		$query2 = $db->query("SELECT * FROM `" . USER_PREFIX . "_polls`");		
		if($db->numRows($query2) > 0) 
		{			
			while($rows2 = $db->getRow($query2)) 
			{
				$id_arr[]=$rows2['id'];		
			}
			$max = rand(0, ($db->numRows($query2)-1));
			$id = $id_arr[$max];			
			show_poll($type = false, $id);
		}
		else
		{
			echo _BLOCK_POLLS_EMPTY;
		}
	}
	else
	{
		show_poll($type = false, $poll_conf['poll_id']);
	}
	
	
	


