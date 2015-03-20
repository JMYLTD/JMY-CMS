<?php
if (!defined('ACCESS')) {
	header('Location: /');
	exit;
}


$module_array['content'] = array(
		'name' => 'Статические страницы',
		'icon' => 'media/admin/pages.png',
		'desc' => 'Создание и редактирование страниц, которые как правило редко изменяются и имеют постоянный адрес.',
		'subAct' => array(
			'Список страниц' => '',
			'Добавить страницу' => 'add',			
			'Конфигурация' => 'config',
		)
);

$toconfig['content'] = array
(
'name' => 'Статические страницы',
'link' => 'module/content/config',
'param' => 'content_conf'
);