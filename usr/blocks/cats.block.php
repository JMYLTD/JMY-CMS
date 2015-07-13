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
loadConfigBLOCK('cats');

global $core, $db, $cats;

$module = $cats['module'];
if(empty($core->catArray))
{
	$core->catArray = getcache('categories');
}
$parse = $core->catArray[$module];

echo '<ul><li class="cat-item"><a href="/">Главная</a></li>';

	foreach($parse as $info)
	{
		echo '<li class="cat-item"><a href="' . $module . '/' . $info['altname'] . '">' . $info['title'] . '</a></li>';
	}

echo '</ul>';