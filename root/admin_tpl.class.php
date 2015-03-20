<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/
 
class admin extends template
{
	var $page_nav = '';
	function __construct()
	{
		$this->adminTheme = true;
		$this->file_dir = 'usr/tpl/admin/';
		$this->toConf = '';
	}
    
    public function loadFile($file, $check = false) 
    {
        $this->sources = file_get_contents(ROOT . $this->file_dir . $file . '.tpl');
    }
	

	public function admin_head($title = null) 
	{
		$this->admin_title = $title;
		$this->sep = '';
		ob_start();
	}
		
	public function admin_foot($last_visit = null, $last_ip = null) 
	{
	global $config, $url, $db, $core, $errorClass;
		$content = ob_get_contents();
		ob_end_clean();
		$array = explode(' | ', $this->admin_title);
		$title_massiv = array_reverse($array);
		$admtitle = null;
		
		foreach($title_massiv as $title) 
		{
			if($title) $admtitle .= filter($title) . $config['divider'];
		}
		
		require ROOT . 'root/list.php';
		
		foreach(glob(ROOT.'usr/modules/*/admin/list.php') as $listed) 
		{
			include($listed);
		}
		
		$shown_tab = '_component';

		/*
		*Модули
		*/
		$module_list = '';
		$module_addition = '';
		$module_stlen = 0;
		foreach($module_array as $module => $params) 
		{
			if(checkAdmControl($module) && !empty($core->tpl->modules[$module]))
			{
				$m = 1;
				$str = "<li " . (isset($url[2]) && ($url[2] == $module) ? 'class="_active"' : '') . "><a href=\"" . (isset($params['url']) ? $params['url'] : ADMIN . '/module/' . $module) . "\" title=\"" . $params['name'] . "\" class=\"menu\"><img src=\"" . (isset($params['icon']) ? $params['icon'] : 'media/edit/li.png') . "\" border=\"0\" class=\"icon\" alt=\"" . $params['name'] . "\">" . $params['name'] . "</a></li>";
				
				if($module_stlen < 85)
				{
					$module_list .= $str;
				}
				else
				{
					$module_addition .= $str;
				}
				$module_stlen = mb_strlen(strip_tags(trim($module_list)));
				if(isset($url[2]) && ($url[2] == $module)) $shown_tab = '_modules';
			}
		}
		if(!isset($m)) $module_list .= "<li>Модули не доступны</li>";
		
		/*
		*Компоненты
		*/
		$component_list = '';
		$component_addition = '';
		$component_stlen = 0;
		foreach($component_array as $component => $params) 
		{
			if(checkAdmControl($component))
			{
				$c = 1;
				$str = "<li " . (isset($url[1]) && ($url[1] == $component) ? 'class="_active"' : '') . "><a href=\"" . ADMIN . '/' . $component . "\" title=\"" . $params['name'] . "\" class=\"menu\"><img src=\"" . (isset($params['icon']) ? $params['icon'] : 'media/edit/li.png') . "\" border=\"0\" class=\"icon\" alt=\"" . $params['name'] . "\">" . $params['name'] . "</a></li>";
				if($component_stlen < 85)
				{
					$component_list .= $str;
				}
				else
				{
					$component_addition .= $str;
				}
				if(isset($url[1]) && ($url[1] == $component)) $shown_tab = '_component';
				$component_stlen = mb_strlen(strip_tags(trim($component_list)));
			}
		}	
		if(!isset($c)) $component_list .= "<li>Компоненты не доступны</li>";

		/*
		*Сервисы
		*/
		$services_list = '';
		$services_addition = '';
		$services_stlen = 0;
		foreach($services_array as $sevices => $params) 
		{
			if(checkAdmControl($sevices))
			{
				$s = 1;
				$str = "<li " . (isset($url[1]) && ($url[1] == $sevices) ? 'class="_active"' : '') . "><a href=\"" . ADMIN . '/' . $sevices . "\" title=\"" . $params['name'] . "\" class=\"menu\"><img src=\"" . (isset($params['icon']) ? $params['icon'] : 'media/edit/li.png') . "\" border=\"0\" class=\"icon\" alt=\"" . $params['name'] . "\">" . $params['name'] . "</a></li>";
				if($component_stlen < 85)
				{
					$services_list .= $str;
				}
				else
				{
					$services_addition .= $str;
				}
				$services_stlen = mb_strlen(strip_tags(trim($services_list)));
				if(isset($url[1]) && ($url[1] == $sevices)) $shown_tab = '_services';
			}
		}
		if(!isset($s)) $services_list .= "<li>Сервисы не доступны</li>";
		
		if(isset($toconfig)) $this->toConf = $toconfig;
	
		$subNav = '';
		$jsNav = "<script type=\"text/javascript\">var _component = '" . $component_list . "';var _component_a = '" . $component_addition . "';var _modules = '" . $module_list . "';var _modules_a = '" . $module_addition . "';var _services = '" . $services_list . "';var _services_a = '" . $services_addition . "';</script>";
		
		/*
		* Быстрая навигация
		*/
		if(isset($url[1]))
		{
			if(isset($component_array[$url[1]]))			
			{			
			$subNav = '<div class="row mg-b"><div class="col-xs-6"><h3 class="no-margin">'.$component_array[$url[1]]['name'].'</h3><small>'.$component_array[$url[1]]['desc'].'</small></div></div>';
				if(isset($component_array[$url[1]]['subAct']))
				{					
					$subNav .= '<div class="row"><div class="col-lg-12"><div class="btn-group">';
					foreach($component_array[$url[1]]['subAct'] as $comAct => $comActLink)
					{
						$subNav .= '<button type="button" class="btn btn-white ' . ((isset($url[2]) && $url[2] == $comActLink OR !isset($url[2]) && $comActLink == '') ? 'active' : '') . '" onclick="location.href=\'' . ADMIN . '/' . $url[1] . '/' . $comActLink . '\';">' . $comAct . '</button>';					
					}
					$subNav .= '</div></div></div><br>';
				}				
				$noSub = '<span class="navMainActive"><a href="' . ADMIN . '/' . $url[1] . '">' . $component_array[$url[1]]['name'] . '</a></span>';
			}
			elseif(isset($url[2]) && isset($module_array[$url[2]]))			
			{			
			$subNav = '<div class="row mg-b"><div class="col-xs-6"><h3 class="no-margin">'.$module_array[$url[2]]['name'].'</h3><small>'.$module_array[$url[2]]['desc'].'</small></div></div>';
				if(isset($module_array[$url[2]]['subAct']))
				{				
					$subNav .= '<div class="row"><div class="col-lg-12"><div class="btn-group">';
					foreach($module_array[$url[2]]['subAct'] as $comAct => $comActLink)
					{
						$subNav .= '<button type="button" class="btn btn-white ' . ((isset($url[3]) && $url[3] == $comActLink OR !isset($url[3]) && $comActLink == '') ? 'active' : '') . '" onclick="location.href=\'' . ADMIN . '/module/' . $url[2] . '/' . $comActLink . '\';">' . $comAct . '</button>';
					}
					$subNav .= '</div></div></div><br>';
				}
				
				$noSub = '<span class="navMainActive"><a href="' . ADMIN . '/module/' . $url[2] . '">' . $module_array[$url[2]]['name'] . '</a></span>';
			}
			elseif(isset($services_array[$url[1]]))			
			{
				$subNav = '<div class="row mg-b"><div class="col-xs-6"><h3 class="no-margin">'.$services_array[$url[1]]['name'].'</h3><small>'.$services_array[$url[1]]['desc'].'</small></div></div>';				
				if(isset($services_array[$url[1]]['subAct']))
				{					
					$subNav .= '<div class="row"><div class="col-lg-12"><div class="btn-group">';
					foreach($services_array[$url[1]]['subAct'] as $comAct => $comActLink)
					{
						$subNav .= '<button type="button" class="btn btn-white ' . ((isset($url[2]) && $url[2] == $comActLink OR !isset($url[2]) && $comActLink == '') ? 'active' : '') . '" onclick="location.href=\'' . ADMIN . '/' . $url[1] . '/' . $comActLink . '\';">' . $comAct . '</button>';
					}
					$subNav .= '</div></div></div><br>';
				}				
				$noSub = '<span class="navMainActive"><a href="' . ADMIN . '/' . $url[1] . '">' . $services_array[$url[1]]['name'] . '</a></span>';
			}
		}
		else
		{
			$noSub = 'Добро пожаловать в админ панель, надеюсь тебе админ тут нравится :)';
		}
		$meta = "<title>" . (!empty($admtitle) && !empty($_REQUEST['url']) ? $admtitle : $config['slogan']) . " Панель управления</title>" . "\n";
		$meta .= "<meta http-equiv=\"content-type\" content=\"text/html; charset=" . $config['charset'] . "\">" . "\n";
		$meta .= "<base href=\"" . $config['url'] . "/\">" . "\n";	
		$meta .= "<script src=\"usr/plugins/js/adminPanel.js\" type=\"text/javascript\"></script>" . "\n";	
		$meta .= "<script src=\"usr/plugins/js/ajax_admin.js\" type=\"text/javascript\"></script>" . "\n";	
		$meta .= "<script src=\"usr/plugins/js/JMY_Ajax.js\" type=\"text/javascript\"></script>" . "\n";	
		$meta .= "<script src=\"usr/plugins/js/engine.js\" type=\"text/javascript\"></script>" . "\n";
		$meta .= "<script src=\"usr/plugins/js/bb_editor.js\" type=\"text/javascript\"></script>" . "\n";
		$meta .= "<script src=\"usr/plugins/js/drop_down_menu.js\" type=\"text/javascript\"></script>" . "\n";	
	
		
		foreach($this->headerIncludes as $metas)
		{
			if($metas)
			{
				$meta .= $metas;
			}		
		}
		
		//Notifications
		$i_n=0;
		$count = $db->numRows($db->query('SELECT id FROM '.DB_PREFIX.'_news WHERE active=2'));
	
	
		if(file_exists(ROOT . 'install.php'))
	{
		$notifications = '<li class="list-group-item"><span class="pull-left mg-t-xs mg-r-md"><img src="usr/tpl/admin/assets/images/notifications/install.png" class="avatar avatar-sm img-circle" alt=""></span><div class="m-body show pd-t-xs"><span>' . _INSTALLEX . '</span><br></div></li>';
		$i_n=$i_n+1;
	}
		if(!empty($onModer))
	{
		$notifications .=  '<li style="cursor:pointer" onclick="location.href=\'/administration/publications/mod/news\';" class="list-group-item"><span class="pull-left mg-t-xs mg-r-md"><img src="usr/tpl/admin/assets/images/notifications/nn.png" class="avatar avatar-sm img-circle" alt=""></span><div class="m-body show pd-t-xs"><span>'._ONMODER.' ('.$count.') </span><br></div></li>';;
		$i_n=$i_n+1;
	}
	
	if (file_exists('boot/update/lock.update'))
	{
		$notifications .=  '<li style="cursor:pointer" onclick="location.href=\'http://cms.jmy.su/\';" class="list-group-item"><span class="pull-left mg-t-xs mg-r-md"><img src="usr/tpl/admin/assets/images/notifications/update.png" class="avatar avatar-sm img-circle" alt=""></span><div class="m-body show pd-t-xs"><span>'. _UPDATE_JMY .'</span><br></div></li>';;
		$i_n=$i_n+1;
	}
	else
	{	
	if (file_exists('boot/update/time.txt'))
	{ 
		$file_array = file("boot/update/time.txt");
		if((date("Ymd") <> $file_array[0])or(!file_exists('boot/update/version.txt')))
		{
		$file = fopen ("boot/update/time.txt","w+");
		fputs ( $file, date("Ymd"));
		fclose ($file);	
		if (file_exists('boot/update/version.txt'))
			{
			$file_array_v = file("boot/update/version.txt");			
			$fp = file("http://cms.jmy.su/update/version.txt");			
			$version = $file_array_v[0];
			$now = $fp[0];			
			if ($version<>$now)
				{
				fopen('boot/update/lock.update', 'w');
				}
			}
			else
			{
			$notifications .=  '<li class="list-group-item"><span class="pull-left mg-t-xs mg-r-md"><img src="usr/tpl/admin/assets/images/notifications/noversion.png" class="avatar avatar-sm img-circle" alt=""></span><div class="m-body show pd-t-xs"><span>'. _NO_VERSION .'</span><br></div></li>';;
			$i_n=$i_n+1;				
			}
		}
	}
	else
	{
	fopen('boot/update/time.txt', 'w');
	}
	}
	
	 
	
	
	if ($i_n==0)
		{
		$i_n='';
		$notifications='<div class="panel-footer no-border">'._NOT_NOTIF.'</div>';
		
		}
		
//Профиль админа
if (file_exists('boot/update/version.txt'))
			{
			$file_array_v = file("boot/update/version.txt");	
				
			$version = $file_array_v[0];
			
			}
			else
			{
				$version = 'Неизвестно';		
			}
			
		$avatar =avatar($adminUser['id']);
		$this->loadFile('main');
		$this->setVar('META', $meta);
		$this->setVar('AVATAR', $avatar);
		$this->setVar('ADM_THEME', 'usr/tpl/admin');
		$this->setVar('NOTIF', $notifications);
		$this->setVar('NOTIF_NUMB', $i_n);
		$this->setVar('URL', $config['url']);
		$this->setVar('MODULE', $content);
		$this->setVar('VERSION', $version);
		$this->setVar('NAME', $core->auth->user_info['nick']);
		$this->setVar('IP', getenv("REMOTE_ADDR"));
		$this->setVar('ADMIN', ADMIN);
		$this->setVar('MODULE_NAME', $module_array[$url[2]]['name']);
		$this->setVar('SHOWN_TAB', $shown_tab);
		$this->setVar('JS_NAV', $jsNav);
		$this->setVar('VERT_MENU', isset($_COOKIE['_vertical_menu']) ? 'block' : 'none');	
		$this->setVar('LICENSE', 'Powered by <a href="http://cms.jmy.su" title="JMY CMS">JMY CMS</a>');
		$this->setVar('SUBNAV', isset($subNav) ? $subNav : $noSub);
		$this->setVar('MOD_LINK', (isset($url[2]) && $url[1] == 'module') ? ADMIN . '/module/' . $url[2] : false);
		$this->setVar('GENERATE', mb_substr(microtime(1) - TIMER, 0, 5));
		$this->setVar('GZIP', $config['gzip'] ? 'GZIP Включён' : '');
		$this->setVar('TIMEQUERIES', mb_substr($db->timeQueries, 0, 5));
		$this->setVar('QUERIES', $db->numQueries);
		$this->setVar('pages', $this->page_nav);
		$this->end();
	}
	
	public function blockCookie($block, $type = 'block')
	{
		if($type == 'block')
		{
			if(isset($_COOKIE['Block_'.$block]) && $_COOKIE['Block_'.$block] == true) return 'style="display:none"';
		}
		else
		{
			if(isset($_COOKIE['Block_'.$block]) && $_COOKIE['Block_'.$block] == true) return 'close'; else return 'open';
		}
	}
	
	public function a_pages($page, $num, $all, $link, $onClick = false) 
	{
		global $config, $url;
		if(!eregStrt('{page}', $link)) $link = $link . '/{page}';
		$numpages = ceil($all/$num);
		$nums = '';
		$predel = 4;
		$prevpage = $page-1;
		if($prevpage != 0) $nums .= '<li><a href="' . str_replace('{page}', 'page/'.$prevpage, $link) . '" title="' . $prevpage . '" ' . ($onClick ? str_replace('{num}', $prevpage, $onClick) : '') . '>&lt; Назад</a></li>';
		for ($var = 1; $var < $numpages+1; $var++) 
		{
			if ($var == $page) 
			{
				$nums .= '<li><a href="' . str_replace('{page}', 'page/'.$var, $link) . '" title="' . $var . '" ' . ($onClick ? str_replace('{num}', $var, $onClick) : '') . ' class="current">' . $var . '</a></li>';
			} 
			else 
			{
				if ((($var > ($page - $predel)) && ($var < ($page + $predel))) or ($var == $numpages) || ($var == 1)) 
				{
					$nums .= '<li><a href="' . str_replace('{page}', 'page/'.$var, $link) . '" title="' . $var . '" ' . ($onClick ? str_replace('{num}', $var, $onClick) : '') . '>' . $var . '</a></li>';
				} 
				
				if ($var < $numpages) 
				{
					if (($var > ($page - $predel-2)) && ($var < ($page + $predel))) $nums .= "";
					if (($page > $predel+2) && ($var == 1)) $nums .= "<li class=\"dots\">...</li>";
					if (($page < ($numpages - $predel)) && ($var == ($numpages - 2))) $nums .= "<li class=\"dots\">...</li>";
				}
			}
		}
		$nextpage = $page + 1;
		if($numpages != $page) $nums .= '<li><a href="' . str_replace('{page}', 'page/'.$nextpage, $link) . '" title="' . $nextpage . '" ' . ($onClick ? str_replace('{num}', $nextpage, $onClick) : '') . '>Вперёд &gt;</a></li>';
		
		if($numpages != 1 && $numpages != 0) 
		{
			/*$this->loadFile('pages');
			$this->setVar('NUM', $nums);
			$this->sources = preg_replace( "#\\{" . $this->sep . "NEXT" . $this->sep . "\\}(.*?)\\{/" . $this->sep . "NEXT" . $this->sep . "\\}#ies", ($page < $var-1) ? "pageLink('".$link."', '\\1', $nextpage" . ($onClick ? ", '" . str_replace("'", "\'", $onClick) . "'" : '') . ")" : '', $this->sources);
			$this->sources = preg_replace( "#\\{" . $this->sep . "PREV" . $this->sep . "\\}(.*?)\\{/" . $this->sep . "PREV" . $this->sep . "\\}#ies", ($page != 1) ? "pageLink('".$link."', '\\1', $prevpage" . ($onClick ? ", '" . str_replace("'", "\'", $onClick) . "'" : '') . ")" : '', $this->sources);
			$this->end();*/
			$nums .= '</ul>';
			$this->page_nav = $nums;
		}
	}
}

$adminTpl = new admin;