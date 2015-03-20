<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @outside     Youshi
*/
 
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}

if($config['multiLang'] && $config['lang'] != $core->lang)
{
	foreach(glob(ROOT . 'etc/' . $core->lang . '.*.config.php') as $confFile)
	{
		require $confFile;
	}
}

$core->initCore();

if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('#(up-browser)|(blackberry)|(windows ce)|MIDP-2.0|symbian|palm|nokia#i', $_SERVER['HTTP_USER_AGENT']))
{
	define("SMART", true);
}


if(count($url) == 1 && eregStrt('.htm', $url[0]))
{
	$GLOBALS['url'][1] = $url[0];
	$GLOBALS['url'][0] = 'content';
}

if($user['isBan'] == 1)
{
	$banIp = configMatch($security['banIp']);
	$banArr = explode("\n", $banIp);
	foreach($banArr as $v)
	{
		if(trim($v) != '')
		{
			$banned = explode('=', $v);
			preg_match("#" . Only1br($banned[0], '') . "#", $_SERVER['REMOTE_ADDR'], $match);
			if(isset($match[0]))
			{
				$core->ban = true;
				if(isset($banned[1]))
				{
					$core->banReason = $banned[1];
				}
			}
		}
	}

	if($core->ban == false && $core->auth->banUser == true)
	{
		$core->ban = true;
	}
}

if(($config['off'] == 0 OR $core->auth->isAdmin == 1 OR $url[0] == ADMIN) && $core->ban == false )
{
	$core->LoadLang(false, true);
	
	if($config['multiLang'] == 1)
	{
		if(isset($_GET['lang']) && isset($_GET['theme']))
		{
			if(isset($core->langsLang[$_GET['lang']]) && $_GET['lang'] == $config['lang'])
			{
				setcookie('lang', false, time() - 86400, '/');
			}
			elseif(isset($core->langsLang[$_GET['lang']]))
			{
				setcookie('lang', filter($_GET['lang'], 'a'), time() + 86400, '/');
			}
			
			$theme = filter($_GET['theme'], 'a');
			if(is_dir(ROOT . 'usr/tpl/' . $theme . '/') && file_exists(ROOT . 'usr/tpl/' . $theme . '/index.tpl'))
			{
				setcookie('theme', $theme, time() + 86400, '/');
			}
			
			location('?nocache='.time());
		}
		
		if(isset($_GET['lang']))
		{
			if(isset($core->langsLang[$_GET['lang']]) && $_GET['lang'] == $config['lang'])
			{
				setcookie('lang', false, time() - 86400, '/');
				location('?nocache='.time());
			}
			elseif(isset($core->langsLang[$_GET['lang']]))
			{
				setcookie('lang', filter($_GET['lang'], 'a'), time() + 86400, '/');
				location('?nocache='.time());
			}
		}
	}
	
	if(isset($_GET['theme']))
	{
		
		$theme = filter($_GET['theme'], 'a');
		if(is_dir(ROOT . 'usr/tpl/' . $theme . '/') && file_exists(ROOT . 'usr/tpl/' . $theme . '/index.tpl'))
		{
	
			setcookie('theme', $theme, time() + 86400, '/');
			location('?nocache='.time());
		}
	}
	
	
	if($config['reffer']) 
	{
		init_reffer();
	}
	
	if($core->auth->isAdmin) 
	{
		$logFiles = glob(ROOT.'tmp/*.log');
		if(!empty($logFiles))
		{
			foreach(@glob(ROOT.'tmp/*.log') as $logFile) compress($logFile);
		}
	}
			
	if (!file_exists(ROOT . 'tmp/cache/categories.cache'))	
	{
		$query = $db->query("SELECT id, name, altname, description, module, icon, parent_id as pid FROM ".DB_PREFIX."_categories ORDER BY name, parent_id");
		while($rows = $db->getRow($query)) 
		{
			$cat_array[$rows['module']][$rows['id']] = array('title' => $rows['name'], 'parent' => $rows['pid'], 'altname' => $rows['altname'], 'icon' => $rows['icon'], 'description' => $rows['description']);
			$checkCatArr[$rows['altname']] = $rows['module'];
		}
		
		if(isset($cat_array))
		{
			setcache('categories', $cat_array);
		}
	}
	
	if(file_exists(ROOT . 'usr/modules/' . mb_strtolower($url[0]) . '/index.php') && !isset($_GET['download'])) 
	{

		$core->LoadLang();
		
		$modAccess = modAccess($url[0]);
		pmNew();
		
		if(!$config['fullajax']) 
		{
			if(!isset($no_head)) 
				$core->tpl->head();
			if($modAccess == 'groupOk')
				require ROOT . 'usr/modules/' . mb_strtolower($url[0]) . '/index.php';
			elseif($modAccess == 'groupError')
				$core->tpl->info(str_replace('[group]', $core->auth->group_info['gname'], _GR_DENIDE));
			else
			{
				$core->tpl->info(_MOD_NOT_FOUND, 'warning');
				delcache('plugins');
			}
			
			if(!isset($no_head)) 
				$core->tpl->foot();

		} 
		else 
		{
			$initFullAjax = (isset($_SERVER['HTTP_AJAX_ENGINE']) OR eregStrt('FULL_AJAX', $_SERVER['QUERY_STRING']) OR isset($_REQUEST['fullajax'])) ? true : false;
			ob_start();
			if($modAccess == 'groupOk')
			{
				require ROOT . 'usr/modules/' . mb_strtolower($url[0]) . '/index.php';
			}
			elseif($modAccess == 'groupError')
			{
				$core->tpl->info('Группе "' . $core->auth->group_info['gname'] . '" закрыт доступ в данный раздел.');
			}
			else
			{
				$core->tpl->info('Данный раздел не найден на нашем сайте.', 'warning');
			}

			$module_content = ob_get_contents();
			ob_end_clean();
			
			if(isset($no_head)) 
			{
				echo $module_content;
			}
			else
			{
				if(!$initFullAjax) 
				{
					
					$core->tpl->head(); 
					echo $module_content;
					$core->tpl->foot(); 
				}
				else
				{
					$meta = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http:// 
		www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><title>' . html_entity_decode((!empty($core->tpl->title) && !empty($_REQUEST['url']) ? $core->tpl->title . $config['name'] : $config['name'] . $config['divider'] . $config['slogan']), ENT_QUOTES) . '</title>';
					array_unique($core->tpl->headerIncludes);
							
					foreach($core->tpl->headerIncludes as $metas)
					{
						if($metas)
							$meta .= $metas;
					}
					
					$meta .= '</head>';

					ob_start();
					echo $meta;
					$core->tpl->head();
					echo $module_content;
					$core->tpl->foot(true);
					$fullAjaxContent = ob_get_contents();
					ob_end_clean();
					
					$uniqAuth = '<!-- authGr: ' . $core->auth->group . ' -->';
					
					$etag = md5($fullAjaxContent . $uniqAuth);
					
					header('Etag:' . $etag);
					if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) == $etag) 
					{
						header("HTTP/1.1 304 Not Modified"); 
						exit; 
					}
					else
					{
						header('Content-type: text/html; charset=utf-8');
						echo $fullAjaxContent;
					}
				}
			}
			
		}
	}
	elseif($url[0] == ADMIN && (($core->auth->isUser == true && $core->auth->isAdmin == true) OR $core->auth->isUser == false)) 
	{
		$core->LoadLang(false, false, true);
		$core->LoadLang(true, false, true);
		require ROOT . 'root/index.php';
	} 
	elseif(isset($_GET['download']))
	{
		$downId = intval($_GET['download']);
		$q = $db->query("SELECT * FROM `" . DB_PREFIX . "_attach` WHERE `id`='" . $downId . "'");
		if($db->numRows($q) > 0)
		{
			$rows = $db->getRow($q);
			if(file_exists($rows['url']))
			{
				$db->query("UPDATE `" . DB_PREFIX . "_attach` SET `downloads` = `downloads`+'1' WHERE `id` =" . $downId . ";");
				location($rows['url']);
			}
			else
			{
				location();
			}
		}
		else
		{
			location();
		}
	}
	else 
	{
		if(isset($_SERVER['HTTP_AJAX_ENGINE'])) 
		{
			$core->tpl->info(_MOD_NOT_FOUND, 'warning');
			exit();
		} 
		else 
		{
			location();
		}
	}

}
elseif($core->ban == true)
{
	echo (!empty($core->banReason) ? $core->banReason : $security['banIpMessage']);
}
else
{
	include(ROOT . 'usr/tpl/lock.tpl');
}

