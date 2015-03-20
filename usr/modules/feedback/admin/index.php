<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ADMIN_SWITCH')) {
    location(' /');
    exit;
}

require_once (ROOT.'etc/feedback.config.php');

switch(isset($url[3]) ? $url[3] : null) 
{
	default:
	case 'config':
		$configBox = array(
			'feedback' => array(
				'varName' => 'feedback_conf',
				'title' => _APFEEDBACK,
				'groups' => array(
					'main' => array(
						'title' => _APFEEDBACK_MAIN,
						'vars' => array(
							'allow_attach' => array(
								'title' => _APFEEDBACK_MAIN_ALLOW_ATTACHT,
								'description' => _APFEEDBACK_MAIN_ALLOW_ATTACHD,
								'content' => radio("allow_attach", $feedback_conf['allow_attach']),
							),
							'formats' => array(
								'title' => _APFEEDBACK_MAIN_FORMATST,
								'description' => _APFEEDBACK_MAIN_FORMATSD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'file_size' => array(
								'title' => _APFEEDBACK_MAIN_FILE_SIZET,
								'description' => _APFEEDBACK_MAIN_FILE_SIZED,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),	
							'keywords' => array(
								'title' => 'Keywords модуля',
								'description' => 'Для SEO оптимизации сайта',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),		
							'description' => array(
								'title' => 'Description модуля',
								'description' => 'Для SEO оптимизации сайта',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),		
						)
					),
				),
			),
		);
		
		$ok = false;
		
		if(isset($_POST['conf_file']))
		{
			$ok = true;
		}
		
		generateConfig($configBox, 'feedback', '{MOD_LINK}/config', $ok);
		
		break;
		
}
