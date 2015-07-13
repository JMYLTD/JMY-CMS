<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/
 
if (!defined('ACCESS')) 
{
	header('Location: /');
	exit;
}

class core
{
	public $tpl;
	public $db;
	public $parse;
	public $isAdmin;
	public $langsLang = array('ru' => 'Русский');
	public $deniedHTML = array('#</?(script|javascript|iframe|meta|body|form)[^>]*>#si' => '',	/*'#<img.*?src=("|\')?() #si' => '', */ '#(javascript|onmouseover|expression)#si' => '',);
	public $module;
	public $initModule;
	public $ban = false;
	public $banReason = '';
	public $isIndex = INDEX;
	public $modArray = '';
	public $lang = '';
	public $tempModule = '';
	public $loadedLangs = array();
	public $cacheContent = array();
	private $urlSeparator = '/';
	private $urlEnd = '.html';
	var $htmlArr = array();
	public $catArray = '';
	public $aCatArray = '';
	public $html_editor = 0;
	
	function __construct()
	{
		$this->InitLang();
	}
	
	function initCore()
	{
		$this->auth = new auth();
		$this->tpl = new template();
		$this->buildUrls();
		$this->urlLang();

	global $url;
        if(isset($url[0]) && $url[0] == ADMIN)
        {
			global $admin_conf;
			$this->html_editor = $admin_conf['htmlEditor'];
		}
	}
	
	function LoadLang($module = true, $system = false, $admin = false)
	{
	global $url;
		if($module == true && $admin == false)
		{
			if(file_exists(ROOT . 'usr/modules/' . $url[0] . '/lang/'. $this->lang . '.module.php'))
			{
				include(ROOT . 'usr/modules/' . $url[0] . '/lang/'. $this->lang . '.module.php');
			}
			elseif(file_exists(ROOT . 'usr/modules/' . $url[0] . '/lang/ru.module.php'))
			{
				include(ROOT . 'usr/modules/' . $url[0] . '/lang/ru.module.php');
			}
		}
		elseif($system == true)
		{
			if(file_exists(ROOT . 'usr/langs/' . $this->lang . '.system.php'))
			{
				include(ROOT . 'usr/langs/' . $this->lang . '.system.php');
			}
			elseif(file_exists(ROOT . 'usr/langs/ru.system.php'))
			{
				include(ROOT . 'usr/langs/ru.system.php');
			}
		}
		elseif($admin == true && $module == false)
		{
			if(file_exists(ROOT . 'root/langs/' . $this->lang . '.root.php'))
			{
				include(ROOT . 'root/langs/' . $this->lang . '.root.php');
			}
			else
			{
				include(ROOT . 'root/langs/ru.root.php');
			}
		}
		elseif($module == true && $admin == true)
		{
			if(isset($url[1]) && $url[1] == 'module' && isset($url[2]))
			{
				if(file_exists(ROOT . 'usr/modules/' . $url[2] . '/admin/lang/'. $this->lang . '.admin.php'))
				{
					include(ROOT . 'usr/modules/' . $url[2] . '/admin/lang/'. $this->lang . '.admin.php');
				}
			}
		}
	}
	
	function loadLangFile($str)
	{
		$url = str_replace('{lang}', $this->lang, $str);
		if(file_exists(ROOT . $url))
		{
			require_once(ROOT . $url);
		}
		else
		{
			if(file_exists(ROOT . str_replace('{lang}', 'ru', $str))) 
			{
				require_once(ROOT . str_replace('{lang}', 'ru', $str));
			}
		}
	}
	
	function loadModLang($mod)
	{
		if(!isset($this->loadedLangs[$mod]) && file_exists(ROOT . 'usr/modules/' . $mod . '/lang/'. $this->lang . '.module.php'))
		{
			$this->loadedLangs[$mod] = true;
			require_once(ROOT . 'usr/modules/' . $mod . '/lang/'. $this->lang . '.module.php');
		}
	}
	
	function loadModLangADM($mod)
	{
		if(!isset($this->loadedLangs[$mod]) && file_exists(ROOT . 'usr/modules/' . $mod . '/admin/lang/'. $this->lang . '.admin.php'))
		{
			$this->loadedLangs[$mod] = true;
			require_once(ROOT . 'usr/modules/' . $mod . '/admin/lang/'. $this->lang . '.admin.php');
		}
	}
	
	function getLang($const)
	{
		return constant($const);
	}
	
	function getLangList($force = false)
	{
		if($force == false)
		{
			foreach(scandir(ROOT . 'usr/langs') as $k => $v)
			{
				if(eregStrt('system.php', $v))
				{
					$l = str_replace('.system.php', '', $v);
					$lang[] = array($l, $this->langsLang[$l]);
				}
			}
		}
		else
		{
			foreach($this->langsLang as $key => $val)
			{
				$lang[] = array($key, $val);
			}			
		}
			
		return $lang;
	}
	
	function getModList()
	{
		if(empty($this->modArray))
		{
			foreach(glob(ROOT.'usr/modules/*/index.php') as $dir)
			{
				$dir_arr = explode('/', $dir);
				$this->modArray[] = $dir_arr[count($dir_arr)-2];
			}
		}
		
		return $this->modArray;
	}
	
	function InitLang()
	{
	global $url, $config;
		$config_multiLang = 0;
		if($config_multiLang == 0) return $this->lang = $config['lang'];
		
		if((isset($url[0]) && $url[0] != ADMIN) OR !isset($url[0]))
		{
			if(empty($this->lang))
			{
				if(isset($_COOKIE['lang']) && !empty($_COOKIE['lang']))
				{
					$this->lang = filter($_COOKIE['lang'], 'a');
				}
				elseif(isset($config['lang']))
				{
					$this->lang = $config['lang'];
				}
				else
				{
					$this->lang = 'ru';
				}
			}
			
			return $this->lang;
		}
		else
		{
			return $this->lang = $config['lang'];
		}
	}
	
	function setLang($lang)
	{
		$this->lang = $lang;
	}
	
	function getMod($allowTemp = false)
	{
	global $url;
		if($allowTemp == false)
		{
			return $url[0];
		}
		else
		{
			return empty($this->tempModule) ? $url[0] : $this->tempModule;
		}
	}
	
	function urlLang()
	{
	global $url, $config;
		if(isset($this->langsLang[$url[0]]))
		{
			$this->setLang($url[0]);
			$l = $url[0];
			unset($url[0]);
			if(count($url) == 0) $url[0] = $config['mainModule'];
			foreach($url as $path)
			{
				$s[] = $path;
			}
			unset($url);
			$GLOBALS['url'] = $s;
			
			setcookie('lang', $l, time() + 86400, '/');
		}
	}

	function buildUrls()
	{
	global $config;
		unset($GLOBALS['url']);
		$GLOBALS['url'] = array();
		if(!empty($_GET['url']))
		{
			$getLink = $_GET['url'];
		}
		else
		{
			$getLink = $config['mainModule'];
			define('INDEX_NOW', true);
		}
		
		$tempLink = explode($this->urlSeparator, $getLink);
		
		for ($i = 0, $max = count($tempLink); $i < $max; $i++) 
		{
		    if ($tempLink[$i] == '') 
			{
		        continue;
		    } 
			else 
			{
		        $GLOBALS['url'][] = escape($tempLink[$i]);
		    }
		}	
	}
	
	public function bbDecode($text, $pubId = 0, $html = false) 
	{
	global $smileRepl, $smiles;
		$new_bb = new bb;
		return $new_bb->bbSite($text, $pubId);
	}
	

	function getCat($module, $pid = null, $type = 'short', $limit = 99) 
	{
	    $cats = '';
	    if($pid) 
	    {
	        if(empty($this->catArray))
	        {
				$this->catArray = getcache('categories');
	        }
			
			if(isset($this->catArray[$module]))
			{
				$cat_get = $this->catArray[$module];	
			
			
	        $stop = ($type == 'short') ? true : false;
	        $carr = explode(',', $pid);

	        foreach ($cat_get as $cid => $parseArr) 
	        {
	            if(in_array($cid, $carr)) 
	            {
	                $catsNewArr[$cid] = array('title' => $parseArr['title'], 'altname' => $parseArr['altname']);
	                $sub = $parseArr['parent'];
	                
	                if($stop == true) 
	                {
	                    while ($sub) 
	                    {
	                        $adress[] = $cat_get[$sub]['altname'];
	                        $sub = $cat_get[$sub]['parent'];
	                        $catsNewArr[$cid] = array('title' => $parseArr['title'], 'altname' => $parseArr['altname'], 'adress' => array_reverse($adress));
	                    }
	                }
	                else
	                { 
	                    while ($sub) 
	                    {
	                        $adress[] = $cat_get[$sub]['altname'];
							$breadCrumb[] = array($cat_get[$sub]['title'], $cat_get[$sub]['altname']);
	                        $catsNewArr[$cid] = $cat_get[$sub]['title'] . " » " . $catsNewArr[$cid]['title'];
	                        $sub = $cat_get[$sub]['parent'];
	                        $catsNewArr[$cid] = array('title' => $catsNewArr[$cid], 'altname' => $parseArr['altname'], 'adress' => array_reverse($adress));
	                    }
	                }
	                
	                unset($adress);
	            }
	        }
	       
	        $catNum = 0;
	           
			if(!empty($catsNewArr))
			{
				foreach ($catsNewArr as $cid => $catInfo) 
				{
					$catNum++;
					switch($type)
					{
						case 'full':
						case 'short':
							if($catNum <= $limit) 
							{
								$subAdress = false;
								
								if(isset($catInfo['adress']))
								{
									foreach($catInfo['adress'] as $url)
									{
										$subAdress .= $url . '/';
									}
								}
								
								$cats[] = '<a href="' . $module . '/' . $subAdress . $catInfo['altname'] . '" title="' . $catInfo['title'] . '">' . $catInfo['title'] . '</a>';
								$implodeBy = ', ';
								//unset($subAdress);
							}
							break;
						
						case 'altname':
							return $catInfo['altname'];
							break;
							
							
						case 'development':
							if($catNum < 2)
							{
								$adress = false;

								if(isset($catInfo['adress']))
								{
									foreach($catInfo['adress'] as $url)
									{
										$adress .= $url . '/';
									}
								}
								
								return $adress . $catInfo['altname'];
								unset($adress);
							}
							break;
							
						case 'breadcrumb':
							if(isset($breadCrumb))
							{
								$breadCrumb =  array_reverse($breadCrumb);
							}
							$breadCrumb[] = array($cat_get[$pid]['title'], $cat_get[$pid]['altname']);
							$subAdress = '';
							$cats[] = '<a href="' . $module . '" title="Главная">Главная</a>';
							foreach($breadCrumb as $step)
							{
								$subAdress = $subAdress.$step[1].'/';
								$cats[] = '<a href="' . $module . '/' . $subAdress . '" title="' . $step[0] . '">' . $step[0] . '</a>';
							}
							//$showOpen = true;
							$implodeBy = ' » ';
							break;
					}
				}
			}
			
	        if(isset($showOpen))
			{
			global $core;
				$core->tpl->open();
				echo implode($implodeBy, $cats);
				$core->tpl->close();
			}
			else
			{
				return implode($implodeBy, $cats);
			}
			unset($cats, $implodeBy);
			}
	    }
	}

	function getCatList($parent, $module, $columns)
	{
	global $core;
		$cats = '';
		if(empty($this->catArray))
		{
			$this->catArray = getcache('categories');
		}
		
		if(isset($this->catArray[$module]))
		{
			$cat_get = $this->catArray[$module];
			
			$count = 0;
			$cols = 1;
			$content = '<table border="0" cellspacing="0" cellpadding="7" align="center" width="100%"><tr>';
			foreach($cat_get as $cid => $info)
			{
				if ($info['parent'] != 0 and $info['parent'] != $parent OR $info['parent'] == 0 AND $parent) continue;
				$catLink = $module . '/' . $this->getCat($module, $cid, 'development') . '/';
				$content .= '<td><table border="0" class="_catInfos"><tr><td class="_catIcon"><a href="' . $catLink . '" title="'.$info['title'].'"><img border="0" src="' . (!empty($info['icon']) ? 'media/cats/'.$info['icon'] : 'media/noicon.png') .'" title="'.$info['title'].'"></a></td><td><div class="_catTitle"><a href="' . $catLink . '" title="'.$info['title'].'"><b>'.$info['title'].'</b></a></div><div class="_catDesc"><i>'.$info['description'].'</i></div></td></tr></table></td>'
				.($cols % $columns == 0 ? '</tr><tr>' : '');
				$cols++;
				$count++;
			}
			$content .= '</tr></table>';
			
			if($count > 0)
			{
				$core->tpl->open();
				echo $content;	
				$core->tpl->close();
			}
		}
	}


	function getCatImg($link, $img, $ctitle)
	{
		if(!empty($img))
		{
			return '<a href="'.$link.'" title="'.$ctitle.'"><img src="media/cats/'.$img.'" border="0" alt="'.$ctitle.'" title="'.$ctitle.'" align="right" hspace="10" vspace="5" /></a>';
		}
	}

	function catInfo($module, $cid)
	{
		if(empty($this->catArray))
		{
			$this->catArray = getcache('categories');
		}
		
		$cat_get = $this->catArray[$module];
		
		if(isset($this->catArray[$module]))
		{
			$cArr = explode(',', $cid);
			return $cat_get[$cArr[1]];
		}
	}
	
	function aCatList($module = '')
	{
	global $db;
		if(empty($this->aCatArray))
		{
			$where = '';
			
			if(!empty($module))
			{
				$where = "WHERE module='" . $db->safesql($module) . "'";
			}
			
			$query = $db->query("SELECT id, name, parent_id as pid FROM ".DB_PREFIX."_categories " . $where);
			while($rows = $db->getRow($query)) 
			{
				$cat_get[$rows['id']] = array($rows['name'], $rows['pid']);
			}
			if(isset($cat_get))
			{
				foreach ($cat_get as $cid => $sub_arr) 
				{
					$cats_arr[$cid] = $sub_arr[0];
					$flag = $sub_arr[1];
					while ($flag != "0") 
					{
						$cats_arr[$cid] = $cat_get[$flag][0]." / ".$cats_arr[$cid];
						$flag = $cat_get[$flag][1];
					}
				}
				asort($cats_arr);
				$this->aCatArray = $cats_arr;
			}
		}
		
		if(empty($this->aCatArray)) $this->aCatArray = array();
		
		return $this->aCatArray;
	}

}

$core = new core();