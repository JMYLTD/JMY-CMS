<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/
 
if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

function main() {
	global $adminTpl, $config, $core, $configs, $clear;
}
switch(isset($url[2]) ? $url[2] : null) {
	default:
		if(isset($url[2]) && $url[2] == 'ok') 
		{
		$adminTpl->admin_head(_LOG_OK_COM);
		$adminTpl->info(_LOG_OK_CLEAR);
		$adminTpl->admin_foot();
		}
		else
		{
		$adminTpl->admin_head(_LOG_LOG);
		echo '<div class="row"><div class="col-lg-12"><section class="panel">';
		$adminTpl->open();		
		$i = 0;
		$logFiles = glob(ROOT . 'tmp/*.log');
		if(!empty($logFiles))
			{
			foreach(glob(ROOT . 'tmp/*.log') as $file) 
			{
				$data = unserialize(@file_get_contents($file));
				$content = '';
				$errors = 0;
				foreach($data as $dat) 
				{
					$errors++;
					$content .= 'Сообщение: '."\n----\n" . $dat['msg'] . "\n----\n"
					."IP: " . $dat['ip'] . "\n"
					."Адрес: " . $dat['url'] . "\n"
					."Браузер: " . $dat['agent'] . "\n"
					."Дата: " . formatDate($dat['time']) . "\n"
					."\n";
				}					
				echo '<div class="panel-heading no-border"><b>'. _LOG_NAME .'</b>  - ['. _LOG_ERROR.': ' . $errors . '] </div><div class="panel-body"><div class="switcher-content">';
				echo '<textarea cols="30" rows="20" class="form-control">' . $content . '</textarea><br>';
				echo '<a href="{ADMIN}/log/clear"><button type="button" class="btn btn-danger btn-sm" data-placement="top" title="">'. _LOG_CLEAR.'</button></a>';
				echo '</div></div>';
			}
			
		}
		else
		{
				echo '<div class="panel-heading no-border"><b>'. _LOG_NAME .'</b></div><div class="panel-body"><div class="switcher-content">';
				echo   _LOG_EMPTY ;				
				echo '</div></div>';
		}
		echo '</section></div></div>';
		$adminTpl->close();
		$adminTpl->admin_foot();
		}
		break;	
	
	case "clear":
		foreach(glob(ROOT.'tmp/*.log') as $file) @unlink($file);		
		location(ADMIN.'/log/ok');
		break;
}