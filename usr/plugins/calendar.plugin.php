<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}

function calendar($do, $ajax = true) 
{
global $core, $db, $date_massiv, $cache;
	if($ajax == true)
	{
		$arr = explode(',', $do);
		if(isset($arr[0], $arr[1]))
		{
			$time = strtotime(trim($arr[0]).'-'.(trim($arr[1])+1).'-01');
		}
		else
			$time = time();
	}
	else
		$time = time();

	if(time() >= $time) 
	{
		$dd = $cache->do_get('calendar_block_'.md5($time.$core->lang));
		if(empty($dd))
		{
			$query = $db->query("SELECT date FROM `" . DB_PREFIX . "_news` WHERE `date` BETWEEN " . mktime(0, 0, 0, date('m', $time), 1, date('Y', $time)) . " AND " . mktime(0, 0, 0, date('m', $time)+1, 1, date('Y', $time)) . " AND `active`=1");
			if($db->numRows($query) > 0)
			{
				$days = array();
				while($news = $db->getRow($query)) 
				{
					$j = date('j', $news['date']);
					$days[] = $j;
				}
			}
			
			if(isset($arr[0], $arr[1]) && (time()-31536000) < $time)
			{
				if((trim($arr[0]) == date('Y', time()) && (trim($arr[1])+1) != date('m', time())) || trim($arr[0]) != date('Y', time()))
				{
					$cache->do_put('calendar_block_'.md5($time.$core->lang), serialize($days), 1209600);
				}
			}
		}
		else
		{
			$days = unserialize($dd);
		}
	}
	
	if(!empty($days)) $days = array_unique($days);

	if(!empty($days)) $day = implode(',', $days); else $day = '';
	$content = '';
    if($ajax == false)
    {
        return '<script type="text/javascript" src="usr/plugins/calendar.js"></script><div id="_calendar"></div><script type="text/javascript">var newsDays = [' . $day . ']; calendar();</script>';
    }
    else
    {
        echo 'var newsDays = [' . $day . '];';
    }
}