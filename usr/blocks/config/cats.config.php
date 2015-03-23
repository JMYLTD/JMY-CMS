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
			'cats' => array(
				'varName' => 'cats',
				'title' => _BLOCK_CATS,
				'groups' => array(
					'main' => array(
						'title' => _BLOCK_CATS,
						'vars' => array(
							'module' => array(
								'title' => _MODULES,
								'description' => _BLOCK_CATS_MODULE_DESC,
								'content' => changeModule(),
							),	
						)							
					),
				),
			),
		);	
		function changeModule()
			{
			global $config, $core;
				$exceMods = array('blog', 'board', 'feed', 'sitemap', 'feedback', 'gallery', 'pm', 'profile', 'search', 'poll','mainpage', 'guestbook');
				$content = '<select name="{varName}">';
				foreach ($core->getModList() as $module) 
				{
					if(!in_array($module, $exceMods) && !empty($core->tpl->modules[$module]))
					{
						$selected = ($module == $cats['module']) ? "selected" : "";
						$content .= '<option value="' . $module . '" ' . $selected . '>' . _mName($module) . '</option>';
					}
				}
				$content .= '</select>';
				return $content;
			}
		
		
		