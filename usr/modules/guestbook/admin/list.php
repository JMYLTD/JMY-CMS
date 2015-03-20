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


$module_array['guestbook'] = array(
		'name' => 'Гостевая книга',
		'icon' => 'media/admin/pages.png',
		'desc' => 'Позволяет посетителям оставлять пожелания, адресованные владельцу или будущим посетителям.',
		'subAct' => array(
			'Список комментариев' => '',						
			'Конфигурация' => 'config',
		)
);

$toconfig['guestbook'] = array
(
'name' => 'Гостевая книга',
'link' => 'module/guestbook/config',
'param' => 'content_conf'
);