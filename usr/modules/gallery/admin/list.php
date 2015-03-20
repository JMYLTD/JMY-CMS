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


$module_array['gallery'] = array(
		'name' => 'Галерея',
		'desc' => 'Модуль в котором вы и ваши пользователи могут организовывать галереи с фотографиями.',
		'subAct' => array(
			'Альбомы' => '',
			'Фотографии' => 'photos',
			'Новые' => 'new',
			'Добавить альбом' => 'addAlbum',
			'Добавить фото' => 'addPhoto',			
			'Конфигурация' => 'config',
		)
);


$toconfig['gallery'] = array
(
'name' => 'Галерея',
'link' => 'module/gallery/config',
'param' => 'gallery_config'
);