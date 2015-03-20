<?php
if (!defined('ACCESS')) {
	header('Location: /');
	exit;
}


$module_array['news'] = array(
		'name' => 'Новости системы',
		'desc' => 'Новостной модуль поможет быстро создавать новости на Вашем сайте, а так же редактировать их.',	
		'subAct' => array(
			'Список' => '',
			'Добавить' => 'add',
			'Теги' => 'tags',
			'Доп. поля' => '../../xfields/add',
			'Настройки' => 'config',
		)
);

$component_array['publications']['subAct'] = array('Новости' => 'mod/news');

$toconfig['news'] = array
(
'name' => 'Новости системы',
'link' => 'module/news/config',
'param' => 'news_conf'
);