<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   01.03.2015
*/

switch(isset($url[2]) ? $url[2] : null) 
{
	default:
		$adminTpl->admin_head(_PUBLIC_NAME);
		$adminTpl->info(_PUBLIC_INFO);
		$adminTpl->admin_foot();
		break;
		
	case 'mod':
		$mod = isset($url[3]) ? $url[3] : '';
		if(!empty($mod) && file_exists(ROOT.'usr/modules/' . $mod . '/admin/moderation.php'))
		{
			if(file_exists(ROOT . 'usr/modules/' . $mod . '/admin/lang/ru.admin.php')) 
			{
				require_once(ROOT . 'usr/modules/' . $mod . '/admin/lang/ru.admin.php');
			}
			
			require_once(ROOT.'usr/modules/' . $mod . '/admin/moderation.php');
		}
		break;
}