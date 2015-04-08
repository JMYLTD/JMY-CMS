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

global $config;

echo '<div align="center"><select id="_themeselect">';
foreach(glob(ROOT.'usr/tpl/*/index.tpl') as $file)
{

	if(is_file($file) && !eregStrt('/admin', $file) && !eregStrt('/default', $file) && !eregStrt('/smartphone', $file))
	{
		$file = explode('/', $file);
		$file = $file[count($file)-2];
		echo '<option value="' . $file . '" ' . ($config['tpl'] == $file ? 'selected' : '') . '>' . $file . '</option>';
	}
}
echo '</select>';

echo '<br /><br /><input type="button" value="Выбрать тему" onclick="window.location.href = \'index.php?theme=\'+gid(\'_themeselect\').value;" /></div>';