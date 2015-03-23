<?php	

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   23.03.2015
*/

if (!defined('ADMIN_ACCESS')) 
{
    header('Location: /');
    exit;
}	
		$configBox = array(
			'online' => array(
				'varName' => 'online_conf',
				'title' => _BLOCK_ONLINE,
				'groups' => array(
					'main' => array(
						'title' => _BLOCK_ONLINE,
						'vars' => array(
							'guest' => array(
								'title' => _BLOCK_ONLINE_GUEST,
								'description' => _BLOCK_ONLINE_GUEST_DESC,
								'content' => radio("guest", $online_conf['guest']),
							),	
							'user' => array(
								'title' => _BLOCK_ONLINE_USER,
								'description' => _BLOCK_ONLINE_USER_DESC,
								'content' => radio("user", $online_conf['user']),
							),	
							'bot' => array(
								'title' => _BLOCK_ONLINE_BOT,
								'description' => _BLOCK_ONLINE_BOT_DESC,
								'content' => radio("bot", $online_conf['bot']),
							),	
							'top' => array(
								'title' => _BLOCK_ONLINE_TOP,
								'description' => _BLOCK_ONLINE_TOP_DESC,
								'content' => radio("top", $online_conf['top']),
							),	
							'top_numb' => array(
								'title' => _BLOCK_ONLINE_TOP_NUMB,
								'description' => _BLOCK_ONLINE_TOP_NUMB_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),	
							
							
						)							
					),
				),
			),
		);