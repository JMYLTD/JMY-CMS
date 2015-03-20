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


$module_array['sitemap'] = array(
		'name' => 'Карта сайта',
		'desc' => 'Данный модуль предназначен для создания карт сайта для поисковых систем Google и Yandex.',
		'subAct' => array(
			'Карта сайта' => '',
			'Генерировать карту' => 'create',
			'Уведомить поисковые системы' => 'update',						
			'Конфигурация' => 'config',
		)
);


$toconfig['sitemap'] = array
(
'name' => 'Карта сайта',
'link' => 'module/sitemap/config',
'param' => 'sitemap_config'
);