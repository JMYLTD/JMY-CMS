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


$module_array['board'] = array (
		'name' => 'Форум',
		'desc' => 'Создание и редактирование форумов, применение к ним настроек доступа и создание правил.',		
		'subAct' => array(
			'Список форумов' => '',
			'Создать форум' => 'add',
			'Конфигурация' => 'config',
		)
);

$toconfig['board'] = array
(
'name' => 'Форум',
'link' => 'module/board/config',
'param' => 'board_conf'
);