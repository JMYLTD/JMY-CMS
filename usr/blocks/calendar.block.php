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

global $cache;

$calContent = $cache->do_get('calendar_block_'.$core->lang);

if(empty($calContent))
{
	if(!function_exists('calendar'))
	{
		require ROOT . 'usr/plugins/calendar.plugin.php';
	}
	$calContent = calendar('', '');
	$cache->do_put('calendar_block_'.$core->lang, $calContent, 1200);
}

echo $calContent;
