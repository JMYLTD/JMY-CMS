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

switch(isset($url[1]) ? $url[1] : null) 
{
	default:
		$query = isset($_POST['query']) ? filter($_POST['query'], 'a') : '';
		$query = isset($url[1]) && empty($query) ? $url[1] : $query;
		$type = isset($_POST['type']) ? filter($_POST['type'], 'a') : '';
		set_title(array('Поиск', $query));
		if(!empty($query))
		{
			$type = 'mini';
		}
		else
		{
			$type = 'form';
		}
		$core->tpl->open();
		$core->tpl->loadFile('search/search-' . $type);
		$core->tpl->setVar('QUERY', !empty($query) ? $query : '');
		$core->tpl->end();
		$core->tpl->close();

		if(!empty($query) && mb_strlen($query) >= 3)
		{
			foreach(glob(ROOT.'usr/modules/*/search.php') as $file) include($file);
		}
		else
		{
			$core->tpl->info(_SEARCH_FAIL, 'warning');
		}
		break;
}