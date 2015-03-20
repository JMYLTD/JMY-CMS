<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ACCESS')) {
	header('Location: /');
	exit;
}

if(!defined('_TODAY'))
{
}

function parseBB($text, $id = false, $html = false)
{
	$bb = new bb;
	return $bb->parse($text, $id, $html);
}

function html2bb($text)
{
	$bb = new bb;
	return $bb->htmltobb($text);
}

function processText($str)
{
	if(function_exists( "get_magic_quotes_gpc" ) && get_magic_quotes_gpc()) $str = stripslashes($str);  
	$str = addslashes($str);
	return $str;
}

function prepareTitle($title)
{
	$title = htmlspecialchars( stripslashes( $title ), ENT_QUOTES );
	$title = str_replace("&amp;","&", $title );
	
	return $title;
}

function get_exgroup($points, $exgroup)
{
global $core;
	if($exgroup > 0 && isset($core->auth->groups_array[$exgroup]))
	{
		return $core->auth->groups_array[$exgroup];
	}
	elseif($points > 0 && $exgroup == 0)
	{
		foreach($core->auth->groups_array as $id => $arr)
		{
			if($arr['special'] == 1 && $points >= $arr['points'] && $arr['points'] > 0) return $arr;
		}
	}
}

function user_points($uid, $conf)
{
global $user;
	if($user['count_points'] == 1)
	{
		require_once(ROOT.'etc/points.config.php');
		$where = is_numeric($uid) ? "WHERE `id`='" . intval($uid) . "'" : "WHERE `nick`='" . filter($uid, 'nick') . "'";
		if(isset($points_conf[$conf]))
		{
		global $db;
			$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `points` = `points`+'" . intval($points_conf[$conf]) . "' " . $where . " LIMIT 1 ;");
		}
	}
}

function engine_encode($str = '')
{
	global $config;
	$result = '';

	for ($i = 0, $len = strlen($str); $i < $len; $i++)
	{
		$result .= '#' . strtr(ord($str[$i]), $config['uniqKey'], strrev($config['uniqKey']));
	}

	return $result;
}

function engine_decode($str = '')
{
global $config;
	$result = '';
	$str = explode('#', $str);

	for ($i = 0, $len = count($str); $i < $len; $i++)
	{
		if (empty($str[$i]))
		{
			continue;
		}

		$result .= chr(strtr($str[$i], strrev($config['uniqKey']), $config['uniqKey']));
	}

	return $result;
}

/*
* Сохраняем кэш
* $file - файл(без разрещения .cache) который находится в tmp/cache
* $data - то что запишем в кэш
*/
function setcache($file, $data, $conf = false) 
{
global $allowCahce;
	if(isset($allowCahce[$file]) && $allowCahce[$file] == 0) return true;
	if(isset($allowCahce[$conf]) && $allowCahce[$conf] == 0) return true;
	
	if($data)
	{
		$data = serialize($data);
		$fp = @fopen(ROOT . 'tmp/cache/' . trim($file) . '.cache', 'w');
		fwrite($fp, $data);
		fclose($fp);
		@chmod(ROOT . 'tmp/cache/' . trim($file) . '.cache', 0777 );
		return true;
	}
}

function is_cache($file)
{
	return file_exists(ROOT . 'tmp/cache/' . trim($file) . '.cache');
}
	
/*
* Получаем кэш из файла
* $file - файл(без разрещения .cache) который находится в tmp/cache
*/
function getcache($file) 
{
global $core;
	if(empty($core->cacheContent[md5($file)]))
	{
		$path = ROOT . 'tmp/cache/' . trim($file) . '.cache';
		if ($core->cacheContent[md5($file)] = unserialize(@file_get_contents($path)))	
		{
			return $core->cacheContent[md5($file)];
		}
	}
	else
	{
		return $core->cacheContent[md5($file)];
	}
}
	
function delcache($file)
{
	$path = ROOT . 'tmp/cache/' . trim($file) . '.cache';
	@unlink($path);
}

/*
* Сжимаем файлики
* $scr - полный адресс к файлу( ROOT.'/')
*/
function compress($src)
{
global $log_conf;
	if(file_exists($src) && filesize($src) > $log_conf['compressSize']) 
	{
		$fp = fopen($src, "r");
		$data = fread($fp, filesize($src));
		fclose($fp);

		$name = explode('.', basename($src));
		$dst = ROOT . 'tmp/archives/' . $name[0] . '_' . time() . '.gz';
		$zp = gzopen($dst, "w9");
		if(gzwrite($zp, $data)) 
		{
			unlink($src);
		}
		gzclose($zp);
	}
}

function checkType($type, $obj = 'all')
{
global $files_conf;
echo $files_conf['attachFormats'].'df';
	switch($obj)
	{
		case 'all':
			$parseStr = $files_conf['imgFormats'].','.$files_conf['attachFormats'];
			$typeArr = explode(',', $parseStr);
			foreach($typeArr as $type)
			{
				if(trim($type) != '') $types[] = $type;
			}
			
			if(in_array($type, $types))
			{
				echo 'ok';
			}
			break;
		
		case 'image':
			$parseStr = $files_conf['imgFormats'];
			$typeArr = explode(',', $parseStr);
			foreach($typeArr as $type)
			{
				if(trim($type) != '') $types[] = $type;
			}
			
			if(in_array($type, $types))
			{
				echo 'ok';
			}
			break;
			
		case 'attach':
			$parseStr = $files_conf['attachFormats'];
			$typeArr = explode(',', $parseStr);
			foreach($typeArr as $type)
			{
				if(trim($type) != '') $types[] = $type;
			}
			
			if(in_array($type, $types))
			{
				echo 'ok';
			}
			break;
	}

}

/*
* Красиво выводим массивы
* $var - переменную которую будем обрабатывать
*/
function prt($var)
{
	echo '<pre>';
	print_r($var);
	echo '</pre>';
}

function getTime($startTime)
{
	$result = '';
	$time = time() - $startTime;

	$s = intval($time % 60);
	$m = intval($time / 60 % 60);
	$h = intval($time / 3600 % 24);
	$d = intval($time / 86400 % 30);

	if ($m > 0)
	{
		$result = $m . _MINUTE_AGO;
		if($m < 4)
		{
			$result = _NOW;
		}
	}

	if ($h > 0)
	{
		$result = $h . _HOUR_AGO;
	}

	if ($d > 0)
	{
		$result = $d . _DAY_AGO;
	}

	if(emptY($result))
	{
		if($m == 0)
		{
			$result = _NOW;
		}
		else
		{
			$result = $s . _SEC_AGO;
		}
	}

	return $result;
}

/*
* Форматируем юникс дату
* $time - это время в юникс формате(time();)
* $simple - если true то даты будут выглядеть просто
*/
function formatDate($time, $simple = false) 
{
	if((time()-$time) < 43200 && $time < time()) return getTime($time);
	$format = '';
	$months = array(null, _MJAN, _MFEB, _MMAR, _MAPR, _MMAY, _MJUN, _MJUL, _MAUG, _MSEP, _MOCT, _MNOV, _MDEC);
	$month = $months[gmdate('n', $time)];

	if ($simple || $time > time()) 
	{
		$format .= 'd ' . $month;
	} 
	else 
	{
		$showYear = true;
		if (gmdate('d.m.y', $time) == gmdate('d.m.y', time())) 
		{
			$format .= _TODAY;
		} 
		elseif (gmdate('d.m.y', $time) == gmdate('d.m.y', time()-86400)) 
		{
			$format .= _YESTERDAY;
		} 
		else
		{
			$format .= 'd ' . $month . ' Y';
		}
	}

	$format .= ', H:i';

	return gmdate($format, $time);
}

/*
* Обрезаем текст
* $text - это собственно текст который режем
* $max - максимальное кол-во символов
*/
function str($text, $max) 
{
	if (mb_strlen($text, 'UTF-8') > $max) 
	{
		return mb_substr($text, 0, $max, 'utf-8') . '...';
	} 
	else 
	{
		return $text;
	}
}

/*
* Функция транслита текста
* $text - это собственно текст который будем преобразовывать
*/
function translit($string, $tochka = '')
{
	$string = filter($string, 'a');
    $arr = array('ж' => 'zh', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shh', 'э' => 'je', 'ю' => 'ju', 'я' => 'ja', 'ъ' => '', 'ь' => '', '.' => $tochka);
	$str = array("а", "б", "в", "г", "д", "е", "з", "и", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "ц", "ъ", "ы", "ь");
	$str_to = array("a", "b", "v", "g", "d", "e", "z", "i", "k", "l", "m", "n", "o", "p", "r", "s", "t", "u", "f", "с", "", "y", "");
    $result = mb_strtolower(trim(strip_tags($string)), 'UTF-8');
    $result = preg_replace('/\s+/ms', '-', $result);
    $result = str_replace($str, $str_to, $result);
    $result = str_replace(array_keys($arr), array_values($arr), $result);
    $result = preg_replace('/[^a-z0-9\_\-.]+/mi', '', $result);
    $result = preg_replace('#[\-]+#i', '-', $result);

	return mb_substr($result, 0, 40);
}

/*
* Отображаем превью картинки
*/
function img_preview($img, $type) 
{
	switch($type)
	{
		case 'box':
			return $img;
			break;
	}
}

/*
* Парсер шаблона bb редактора
* $name - имя формы например: <textarea name='этот параметр'.....
* $val - возможно в форму чтото нада пихнуть это будет <textarea>тут</textarea>
* $rows - количество строк в форме
* $class - возможно захотите задать уникальный css класс
* $onlick - дополнительное поле на разнообразные нужды
*/

function bb_area($name, $val = null, $rows = 5, $class = 'textarea', $onclick = null, $return = false, $html = false) {
global $core, $smileList, $smiles, $user, $url;
static $initArea;
	if($name) 
	{	
		$i_smile=0;
		foreach($smiles as $smile => $info)
		{
			  if($url[0] == ADMIN)
        {
            
        
        
			if ($i_smile%4== 0) {
			$smileList .= '<li>';
			}
			$smileList .= '<img src="' . $info['url'] . '" border="0" title="' . $info['title'] . '" onclick="javascript:insertBB(\'' . $smile . '\', \'smile\');" class="_pointer" alt="" />';
			if ($i_smile<>0) {
			if (($i_smile+1)%4== 0) {
			$smileList .='</li>';
			}
			}
			$i_smile=$i_smile+1;
		}
		else
		{
		$smileList .= '<span><img src="' . $info['url'] . '" border="0" title="' . $info['title'] . '" onclick="javascript:insertBB(\'' . $smile . '\', \'smile\');" class="_pointer" alt="" /></span>';
		}
		}
		if($return) ob_start();
		$core->tpl->loadFile('bb_area');
		$core->tpl->setVar('NAME', $name);
		$core->tpl->setVar('TEXT', $val);
		$core->tpl->setVar('ROWS', $rows);
		$core->tpl->setVar('CLASS', $class);
		$core->tpl->setVar('ONCLICK', $onclick);
		$core->tpl->setVar('SMILE_LIST', $smileList);
		
		$core->tpl->sources = preg_replace( "#\\[loadAttach](.*?)\\[/loadAttach]#ise", "isLoadAttach('\\1')", $core->tpl->sources);
		
		if(isset($user['activeFlash']) && $user['activeFlash'] == 1)
			$core->tpl->sources = preg_replace( "#\\[activeFlash](.*?)\\[/activeFlash]#is", "\\1", $core->tpl->sources);
		else
			$core->tpl->sources = preg_replace( "#\\[activeFlash](.*?)\\[/activeFlash]#is", "", $core->tpl->sources);
		
		if(isset($user['activeVideo']) && $user['activeVideo'] == 1)
			$core->tpl->sources = preg_replace( "#\\[activeVideo](.*?)\\[/activeVideo]#is", "\\1", $core->tpl->sources);
		else
			$core->tpl->sources = preg_replace( "#\\[activeVideo](.*?)\\[/activeVideo]#is", "", $core->tpl->sources);
				
		if(isset($user['activeAudio']) && $user['activeAudio'] == 1)
			$core->tpl->sources = preg_replace( "#\\[activeAudio](.*?)\\[/activeAudio]#is", "\\1", $core->tpl->sources);
		else
			$core->tpl->sources = preg_replace( "#\\[activeAudio](.*?)\\[/activeAudio]#is", "", $core->tpl->sources);
			
		if($html)
			$core->tpl->sources = preg_replace( "#\\[activeHTML](.*?)\\[/activeHTML]#is", "\\1", $core->tpl->sources);
		else
			$core->tpl->sources = preg_replace( "#\\[activeHTML](.*?)\\[/activeHTML]#is", "", $core->tpl->sources);
		
		if(!isset($initArea))
		{
			$core->tpl->sources = preg_replace( "#\\[initArea](.*?)\\[/initArea]#is", "\\1", $core->tpl->sources);
			$initArea = true;
		}
		else
			$core->tpl->sources = preg_replace( "#\\[initArea](.*?)\\[/initArea]#is", "", $core->tpl->sources);

		$core->tpl->end();
		
		if($return) 
		{
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
	}
}

function bb_areaADM($name, $val = null, $rows = 5, $class = 'textarea', $onclick = null, $return = false, $html = false) {
global $core, $smileList, $smiles, $user;
static $initArea;
	if($name) 
	{	
		$i_smile=0;
		foreach($smiles as $smile => $info)
		{
			if ($i_smile%4== 0) {
			$smileList .= '<li>';
			}
			$smileList .= '<img src="' . $info['url'] . '" border="0" title="' . $info['title'] . '" onclick="javascript:insertBB(\'' . $smile . '\', \'smile\');" class="_pointer" alt="" />';
			if ($i_smile<>0) {
			if (($i_smile+1)%4== 0) {
			$smileList .='</li>';
			}
			}
			$i_smile=$i_smile+1;
		}
		if($return) ob_start();
		$core->tpl->loadFileADM('bb_area');
		$core->tpl->setVar('NAME', $name);
		$core->tpl->setVar('TEXT', $val);
		$core->tpl->setVar('ROWS', $rows);
		$core->tpl->setVar('CLASS', $class);
		$core->tpl->setVar('ONCLICK', $onclick);
		$core->tpl->setVar('SMILE_LIST', $smileList);
		
		$core->tpl->sources = preg_replace( "#\\[loadAttach](.*?)\\[/loadAttach]#ise", "isLoadAttach('\\1')", $core->tpl->sources);
		
		if(isset($user['activeFlash']) && $user['activeFlash'] == 1)
			$core->tpl->sources = preg_replace( "#\\[activeFlash](.*?)\\[/activeFlash]#is", "\\1", $core->tpl->sources);
		else
			$core->tpl->sources = preg_replace( "#\\[activeFlash](.*?)\\[/activeFlash]#is", "", $core->tpl->sources);
		
		if(isset($user['activeVideo']) && $user['activeVideo'] == 1)
			$core->tpl->sources = preg_replace( "#\\[activeVideo](.*?)\\[/activeVideo]#is", "\\1", $core->tpl->sources);
		else
			$core->tpl->sources = preg_replace( "#\\[activeVideo](.*?)\\[/activeVideo]#is", "", $core->tpl->sources);
				
		if(isset($user['activeAudio']) && $user['activeAudio'] == 1)
			$core->tpl->sources = preg_replace( "#\\[activeAudio](.*?)\\[/activeAudio]#is", "\\1", $core->tpl->sources);
		else
			$core->tpl->sources = preg_replace( "#\\[activeAudio](.*?)\\[/activeAudio]#is", "", $core->tpl->sources);
			
		if($html)
			$core->tpl->sources = preg_replace( "#\\[activeHTML](.*?)\\[/activeHTML]#is", "\\1", $core->tpl->sources);
		else
			$core->tpl->sources = preg_replace( "#\\[activeHTML](.*?)\\[/activeHTML]#is", "", $core->tpl->sources);
		
		if(!isset($initArea))
		{
			$core->tpl->sources = preg_replace( "#\\[initArea](.*?)\\[/initArea]#is", "\\1", $core->tpl->sources);
			$initArea = true;
		}
		else
			$core->tpl->sources = preg_replace( "#\\[initArea](.*?)\\[/initArea]#is", "", $core->tpl->sources);

		$core->tpl->end();
		
		if($return) 
		{
			$contents = ob_get_contents();
			ob_end_clean();
			return $contents;
		}
	}
}

function isLoadAttach($content)
{
global $core, $user;
	if($core->auth->user_info['loadAttach'] == 1 && $user['activeAttach'] == 1)
	{
		return stripslashes($content);
	}
}

/*
* Устанавливаем титл <title></title> страницы сайта
* $array - массив из титлов (array('Главная', 'Новости'))
*/
function set_title($array) 
{
global $core, $config;
	if(is_array($array)) 
	{
		$title_massiv = array_reverse($array);
		$core->tpl->title = false;
		foreach($title_massiv as $title) 
		{
			if($title) $core->tpl->title .= filter($title) . $config['divider'];
		}
	}
}

/*
* Форматируем размер файла в байтах
* $size - размер в байтах
*/
function formatfilesize($size, $short = false) 
{
	if(is_numeric($size))
	{
		if($short)
		{
			$filesizename = array(" b", " kb", " mb", " gb", " tb", " pb", " eb", " ZB", " YB");
		}
		else
		{
			$filesizename = array(" "._BYTE, " "._KBYTE, " "._MBYTE, " "._GBYTE, ' ' . _TBYTE, " PB", " EB", " ZB", " YB");
		}
		
		return $size ? round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $filesizename[$i] : '0 '._BYTE;
	}
	else
	{
		return $size;
	}
}

/*
* Инитилизируем странички ищем в массивах совпадения если таковы есть выводим
*/
function init_page($pref = 'page') 
{
global $url;
	if(array_search($pref, $url)) 
	{
		$prepage = array_search($pref, $url);
		return intval($url[$prepage+1]);
		unset($url[$prepage]);
		unset($url[$prepage+1]);
	} 
	else 
	{
		return 1;
	}
}

/*
* Обработка фатальных ошибок
* $title - титл ошибки
* $error - текст ошибки
*/
function fatal_error($title, $error) 
{
global $core, $config;
	if(!defined('_'))
	{
		require(ROOT.'usr/langs/ru.system.php');
	}
	
	if($title && $error) 
	{
		die('<!DOCTYPE html>
<html class="no-js">
	<head>
		<meta charset="utf-8">
		<base href="' . $config['url'] . '/">
		<meta name="viewport" content="width=device-width, user-scalable=1, initial-scale=1, maximum-scale=1">
		<title>' . _FPCLATER . '</title>		
		<link rel="stylesheet" href="usr/tpl/admin/assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="usr/tpl/admin/assets/css/palette.1.css" id="skin">
		<link rel="stylesheet" href="usr/tpl/admin/assets/css/main.css">
		<link rel="stylesheet" href="usr/tpl/admin/assets/css/animate.min.css">
		<link rel="stylesheet" href="usr/tpl/admin/assets/css/style.1.css" id="font">		
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="usr/tpl/admin/assets/css/panel.css">
		<script src="usr/tpl/admin/assets/js/modernizr.js"></script>
	</head>
	<body class="bg-white app-error">
		<div class="error-container text-center">
			<div class="error-number">error</div>
			<div class="mg-b-lg">' . $title . '</div>
			<p>' . $error . '<br>' . _FPCSORRY . ' </p>
			<ul class="mg-t-lg error-nav">
				<li><a href="http://cms.jmy.su/">&copy; 2010 - <span id="year" class="mg-r-xs"></span>JMY CMS</a></li>
			</ul>
		</div>
		<script type="text/javascript">var el=document.getElementById("year"),year=(new Date().getFullYear());el.innerHTML=year;</script>
	</body>
</html>');
	}
}

/*
* Редиукреты
* $path - куда редикретим
*/
function location($path = '/') 
{
global $config, $core;
	if($path != '/' && mb_substr($path, 0, 1) != '/' && !eregStrt('http', $path))
	{
		if($core->lang != $config['lang'] && $url[0] != ADMIN)
		{
			$path = $config['lang'].'/'.$path;
		}
		
		$path = $config['url'].'/'.$path;
	}
	
	$initFullAjax = (isset($_SERVER['HTTP_AJAX_ENGINE']) OR isset($_REQUEST['fullajax'])) ? true : false;

	if($initFullAjax == true) 
	{
		if(eregStrt('#', $path))
		{
			$pathMass = explode('#', $path);
			header('Location: ' . $pathMass[0] . '&fullajax=ok');
		}
		else
		{
			header('Location: ' . $path . '&fullajax=ok');
		}
		
	//	header('Location: ' . str_replace('#', ':@', $path) . '; FULL_AJAX');
	} 
	else 
	{
		header('Location: ' . $path);
	}
}

/*
* Шлём письмо
* $to - кому
* $title - тема письма
* $msg - текст
*/
function sendMail($to, $title, $msg, $for_header = '', $content_type = 'text/html; charset=utf-8;')
{
global $config;
	$headers = 'From: '.$config['name'].' Mail Robot <robot@' . $_SERVER['HTTP_HOST'] . '>' . "\r\n";
	$headers .= "Content-Type: " . $content_type . " \r\n";
	$headers .= 'Reply-To: noreply@' . $_SERVER['HTTP_HOST'] . "\r\n";
	$headers .= 'X-Mailer: '.$config['name'].' Mail Robot' . "\r\n";
	$headers .= $for_header;
	
	if (mail($to, $title, $msg, $headers))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/*
* Инициализируем Ajax
*/
function ajaxInit()
{
	header('Content-type: text/html; charset=utf-8');
}

/*
* Получаем адресс без http и www
* $uri - полный адресс
*/
function getHost($uri) 
{
    $url = str_replace('http://', '', $uri);
    $url = str_replace('www.', '', $url);
    $url = explode('/', $url);

    return mb_strtolower($url[0]);
}

/*
* Подсвечиваем слова поиска)
* $textShort = highlightSearch('php ajax', $news['short']);
* $words - слова разделить пробелом пример "php ajax css"
* $text - то что обработаем
*/
function highlightSearch($words, $text) 
{ 
	$searchWords = explode(' ', $words);
	foreach($searchWords as $word)
	{
		if(!preg_match('/([^<>]+)/i', $word))
		{
			$text = str_ireplace($word, "<span class=\"highlightSearch\">$word</span>", $text); 
		}
	}
	return $text;
}

function editor_area($name, $value, $rows, $add = '', $class = 'textarea', $return = false)
{
	bb_area($name, $value, $rows, $class, $add, $return);
}

function windowOpen()
{
	echo '<style>body{background:#ffffff;}</style>';
}

function fileInit($module, $id, $type = 'dir', $content = '', $what = 'temp')
{
global $db;
	$temp = 'files/' . $module . '/' . $what;
	$new = 'files/' . $module . '/' . $id;
	
	switch($type)
	{
		case 'dir':
			if(is_dir($temp))
			{
				rename($temp, $new);
				$q = $db->query("SELECT * FROM `" . DB_PREFIX . "_attach` WHERE `pub_id`='0' OR `pub_id`='" . $what . "'");
				while($rows = $db->getRow($q))
				{
					$db->query("UPDATE `" . DB_PREFIX . "_attach` SET `url` = '" . $db->safesql(str_replace($temp, $new, $rows['url'])) . "', `pub_id` = '" . $id . "'  WHERE `id` = '" . $rows['id'] . "'");
				}
			}
			break;
			
		case 'content':
			return str_replace($temp, $new, $content);
			break;
	}
}

function mjsEnd($array)
{
	$page = array_search('page', $array);
	if($page)
	{
		return $array[$page-1];
	}
	else
	{
		return end($array);
	}
}

function initDC($module, $altname)
{
	return 'files/' . $module . '/' . translit($altname);
}

function configMatch($reg)
{
	return str_replace(array('*', '%'), array('.+', '.+'), $reg);
}

function modAccess($mod)
{
global $core;
	if(isset($core->tpl->modules[$mod]))
	{
		$groups = explode(',', $core->tpl->modules[$mod]['groups']);
		if(empty($groups[0]) OR in_array($core->auth->group, $groups))
		{
			return 'groupOk';
		}
		else
		{
			return 'groupError';
		}		
	}
	
	return 'nonMod';
}

function writeInLog($msg, $type) 
{
    $logPath = ROOT . 'tmp/' . $type . '.log';

    if (file_exists($logPath)) 
	{
        $data = unserialize(@file_get_contents($logPath));
    }

    $data[] = array('msg'   => $msg,
                    'ip'    => $_SERVER['REMOTE_ADDR'],
                    'url'   => isset($_REQUEST['url']) ? filter($_REQUEST['url']) : '',
                    'agent' => filter($_SERVER['HTTP_USER_AGENT']),
                    'time'  => time(),
                   );

    $data = serialize($data);
    $fp = @fopen($logPath, 'w');
    fwrite($fp, $data);
    fclose($fp);
}

function mysqlFatalError($title, $text, $query)
{
global $log_conf;
	if($log_conf['dbError'] == 1) writeInLog('[Ошибка в базе данных] - запрос: ' .  $query, 'db_query');
	if(file_exists(ROOT.'install.php') && !file_exists(ROOT.'install/lock.install'))
	{	
		header('Location: install.php');
	}
	else
	{
		fatal_error($title, $text);
	}
}

function pmNew()
{
global $core;
	if(!empty($core->auth->newPms))
	{
		$pms = '';
		foreach($core->auth->newPms as $num => $info)
		{
			if($num == 1)
			{
				$pms .= '<hr /><strong>' . _PMALSO . ':</strong><br />';
			}
			
			if($num == 0)
			{
				$pms .= '<i><a href="pm/view/' . $info['id'] . '" onclick="clearModal_box(\\\'newPm\\\');">' . formatDate($info['time']) . '</a> (От: <a href="profile/' . $info['nick'] . '">' . $info['nick'] . '</a>)</i> [ <a href="pm/write/' . $info['nick'] . '">' . _PMREPLY . '</a> | <a href="pm/view/' . $info['id'] . '" onclick="clearModal_box(\\\'newPm\\\');">' . _PMREAD . '</a> ]<hr />' . str($core->bbDecode($info['message']), 200) . '';
			}
			else
			{
				$pms .= '<a href="pm/view/' . $info['id'] . '" onclick="clearModal_box(\\\'newPm\\\');">' . formatDate($info['time']) . '</a> (От: <a href="profile/' . $info['nick'] . '">' . $info['nick'] . '</a>) [ <a href="pm/write/' . $info['nick'] . '">' . _PMREPLY . '</a> | <a href="pm/view/' . $info['id'] . '" onclick="clearModal_box(\\\'newPm\\\');">' . _PMREAD . '</a> ]<br />';
			}
		}

		require_once(ROOT . 'usr/plugins/modal_box/init.php');
		ob_start();
		modal_box(_PMNEW, str_replace('[num]', count($core->auth->newPms), _PMINFO), $pms, 'newPm');
		$c = ob_get_contents();
		ob_end_clean();
		$core->tpl->bodyIncludes = $c.'<script>modal_box(\'newPm\');</script>';
	}
}

function getExt($file)
{
	$arr = explode('.', $file);
	return mb_strtolower(end($arr));
}

function createThumb($fname, $thumb_fname, $max_x=99, $max_y=99, $resp = false) 
{
	$ext = getExt($thumb_fname);

	switch ($ext) {
		case 'jpg':
		case 'jpeg':
			$im = imagecreatefromjpeg($fname);
			break;

		case 'gif':
			$im = imagecreatefromgif($fname);
			break;
		 
		case 'png':
			$im = imagecreatefrompng($fname);
			break;
			
		default:
			return false;
			break;
	}

	if (@$im) 
	{
		list($width, $height, $type, $attr) = getimagesize($fname);
		if (($width > $max_x) or ($height > $max_y)) 
		{
			if ($width > $height) 
			{
				$nw = $max_x;
				$nh = ($max_x / $width) * $height;
			}
			else 
			{
				$nw = ($max_y / $height) * $width;
				$nh = $max_y;
			}
			
			$thumb = imagecreatetruecolor($nw, $nh);
			imagecopyresampled($thumb, $im, 0, 0, 0, 0, $nw, $nh, $width, $height);
			imagejpeg($thumb, $thumb_fname, 90);
			imagedestroy($thumb);
		}
		else 
		{
			if($resp == true)
				return 'orig';
			else
				copy($fname, $thumb_fname);
		}
	}
	else 
	{
		return false;
	}
}

function adminBar()
{
global $core, $admin_conf, $url;
	if($core->auth->isAdmin)
	{
		if($admin_conf['bar'])
		{
			$core->loadLangFile('root/langs/{lang}.navigation.php');
			$module_array = array();
			require ROOT . 'root/list.php';
			foreach(glob( ROOT.'usr/modules/*/admin/list.php') as $file)
			{
				require_once $file;
			}
			
			$adm = ADMIN;
			$cook = (isset($_COOKIE['fixAP']) && !empty($_COOKIE['fixAP'])) ? true : false;
			$bar = '<style type="text/css" >._adminBar { z-index:4000; width:100%; height:42px; background:#4F9BCA url(\'usr/tpl/admin/images/barBg.gif\') repeat-x;  left:0; top:0px; right:0; } ._adminBar #_barTrans { opacity:0.5;filter:alpha(opacity=50); -moz-opacity:0.5; background-color:#ececec; width:100%; left:0px; z-index:-1; } ._adminBar #_barContent {position:relative; color:#666666;  padding-right:10px; height:25px;} ._adminBarC {float:right; width:24px; height:16px; margin-right:10px; background:url(\'/media/adminBar/open.png\') bottom;}.floatleft {float:left; z-index:5000;}.ddheader {height:42px; line-height:42px; padding-right:15px; padding-left:15px; background:url(\'usr/tpl/admin/images/barLi.gif\') right no-repeat; font-weight:bold;  cursor:pointer; color:#fff;} .ddheader:hover {text-decoration:underline;} .ddcontent {position:absolute; overflow:hidden; width:202px; display:none; z-index:5000;}.ddinner {width:200px; border:1px solid #3F6F8D; border-bottom:none;border-top:none; z-index:5000;}.ddinner ul {display:block; list-style:none; margin:0; padding:0; z-index:5000;}.ddinner li {height:26px; background:url(\'usr/tpl/admin/images/barUl.gif\'); margin:0; line-height:26px; z-index:5000; padding-left:5px;}.ddinner li:hover {background:#85B81D}.underline {border-bottom:1px solid #3F6F8D;} .underline a {font-size:11px; color:#fff; text-decoration:none; font-weight:bold;} .underline a:hover {font-size:11px; color:#fafafa; text-decoration:underline; font-weight:bold;} ._ablink a {color:#fff; text-decoration:none;} ._ablink a:hover {color:#fff; text-decoration:underline;}</style>';
			$bar .= '<div class="_adminBar" id="_adminBar" style="' . ($cook ? 'position: fixed;' : 'position: absolute;') . '">';
			$bar .= '<div id="_barContent"><div style="float:left;"><img src="usr/tpl/admin/images/barLogo.gif" width:150px; height:42px; border="0" title="Быстрая навигация" class="icon"></div>';
			$bar .= "<script type=\"text/javascript\">var DDSPEED=5;var DDTIMER=7;function ddMenu(id,dir){var head=document.getElementById(id+'-ddheader');var cont=document.getElementById(id+'-ddcontent');clearInterval(cont.timer);if(dir==1){clearTimeout(head.timer);if(cont.maxh&&cont.maxh<=cont.offsetHeight){return}else if(!cont.maxh){cont.style.display='block';cont.style.height='auto';cont.maxh=cont.offsetHeight;cont.style.height='0px'}cont.timer=setInterval(\"ddSlide('\"+id+\"-ddcontent', 1)\",DDTIMER)}else{head.timer=setTimeout('ddCollapse(\''+id+'-ddcontent\')',50)}}function ddCollapse(id){var cont=document.getElementById(id);cont.timer=setInterval(\"ddSlide('\"+id+\"', -1)\",DDTIMER)}function cancelHide(id){var head=document.getElementById(id+'-ddheader');var cont=document.getElementById(id+'-ddcontent');clearTimeout(head.timer);clearInterval(cont.timer);if(cont.offsetHeight<cont.maxh){cont.timer=setInterval(\"ddSlide('\"+id+\"-ddcontent', 1)\",DDTIMER)}}function ddSlide(id,dir){var cont=document.getElementById(id);var currheight=cont.offsetHeight;var dist;if(dir==1){dist=(Math.round((cont.maxh-currheight)/DDSPEED))}else{dist=(Math.round(currheight/DDSPEED))}if(dist<=1){dist=1}cont.style.height=currheight+(dist*dir)+'px';cont.style.opacity=currheight/cont.maxh;cont.style.filter='alpha(opacity='+(currheight*100/cont.maxh)+')';if((currheight<2&&dir!=1)||(currheight>(cont.maxh-2)&&dir==1)){clearInterval(cont.timer)}}</script>";
			$bar .= '<div class="floatleft"><div class="ddheader" onclick="window.open(\''.$adm.'\')">Админ панель</div></div><div class="floatleft"><div class="ddheader" id="one-ddheader" onmouseover="ddMenu(\'one\',1)" onmouseout="ddMenu(\'one\',-1)">' . _AP_COMPONENTS . '</div><div class="ddcontent" id="one-ddcontent" onmouseover="cancelHide(\'one\')" onmouseout="ddMenu(\'one\',-1)"><div class="ddinner"><ul>';
			foreach($component_array as $component => $params) 
			{
				$bar .= "<li class=\"underline\"><a href='" . ADMIN . '/' . $component . "' title=\"" . $params['name'] . "\" class='menu' target=\"_blank\">" . $params['name'] . "</a></li>";
			}	
			$bar .= '</ul></div></div></div><div class="floatleft"><div class="ddheader" id="two-ddheader" onmouseover="ddMenu(\'two\',1)" onmouseout="ddMenu(\'two\',-1)">' . _AP_MODULES . '</div><div class="ddcontent" id="two-ddcontent" onmouseover="cancelHide(\'two\')" onmouseout="ddMenu(\'two\',-1)"><div class="ddinner"><ul>';
			foreach($module_array as $module => $params) 
			{
				$bar .= "<li class=\"underline\"><a href='" . ADMIN . '/module/' . $module . "' title=\"" . $params['name'] . "\" class='menu' target=\"_blank\">" . $params['name'] . "</a></li>";
			}
			$bar .= '</ul></div></div></div><div class="floatleft"><div class="ddheader" id="tree-ddheader" onmouseover="ddMenu(\'tree\',1)" onmouseout="ddMenu(\'tree\',-1)">' . _AP_SEVICES . '</div><div class="ddcontent" id="tree-ddcontent" onmouseover="cancelHide(\'tree\')" onmouseout="ddMenu(\'tree\',-1)"><div class="ddinner"><ul>';
			foreach($services_array as $sevices => $params) 
			{
				$bar .= "<li class=\"underline\"><a href='" . ADMIN . '/' . $sevices . "' title=\"" . $params['name'] . "\" class='menu' target=\"_blank\">" . $params['name'] . "</a></li>";
			}	
			$bar .= '</ul></div></div></div>';
			if(isset($module_array[$url[0]]) && isset($module_array[$url[0]]['subAct']))
			{
				$params = $module_array[$url[0]];
				$bar .= '<div class="floatleft"><div class="ddheader" id="' . $url[0] . '-ddheader" onmouseover="ddMenu(\'' . $url[0] . '\',1)" onmouseout="ddMenu(\'' . $url[0] . '\',-1)">'."<img src='" . (isset($params['icon']) ? $params['icon'] : 'media/edit/li.png') . "' border='0' class='icon' alt=\"" . $params['name'] . "\" style=\"padding-right:3px; \">" . $module_array[$url[0]]['name'] . '</div><div class="ddcontent" id="' . $url[0] . '-ddcontent" onmouseover="cancelHide(\'' . $url[0] . '\')" onmouseout="ddMenu(\'' . $url[0] . '\',-1)"><div class="ddinner"><ul>';
					foreach($module_array[$url[0]]['subAct'] as $comAct => $comActLink)
					{
						$bar .= "<li class=\"underline\"><a href='" . ADMIN . '/module/' . $url[0] . '/' . $comActLink . "' title=\"" . $comAct . "\" class='menu' target=\"_blank\">" . $comAct . "</a></li>";
					}
				$bar .= '</ul></div></div></div>';
				$bar .= (!empty($core->tpl->adminBar) ? '<div class="floatleft _ablink" style="height:42px; line-height:42px; padding-left:10px;"><b>'.$core->tpl->adminBar.'</b></div>' : '');
			}
			
			$bar .= '<div style="float:right; height:42px; line-height:42px; color:#fafafa;">' . _AB_HI . ', <b>' . $core->auth->user_info['nick'] . '</b>! [<span class="_ablink"><a href="' . ADMIN . '/do/logout">' . _AB_EXIT . '</a></span>] [<span class="_ablink"><a href="javascript:void(0)" onclick="fixAP();" id="fixAP">' . ($cook ? _AB_FIX : _AB_UNFIX) . '</a></span>]</div><br style="clear:both;" />';
			$bar .= '</div>';
			$bar .= "\n" . '</div>' . "\n";
			
			//return $bar;
		}
	}
}

function avatar($uid) {
    static $avatar = array();
    global $user;

    if(empty($avatar[$uid]))
    {
        if(glob(ROOT . 'files/avatars/users/av' . $uid . '.*')) 
        {
            foreach (glob(ROOT . 'files/avatars/users/av' . $uid . '.*') as $av) $avatar[$uid] =  'files/avatars/users/' . basename($av);
        }
        else
        {
            $avatar[$uid] = $user['noAvatar'];
        }
    }

    return $avatar[$uid];
}

function deleteAvatar($uid)
{
    if(glob(ROOT . 'files/avatars/users/av' . $uid . '.*')) 
    {
        foreach (glob(ROOT . 'files/avatars/users/av' . $uid . '.*') as $av) @unlink($av);
    }
}

function groupName($gid = -1)
{
    global $core;
    static $groupNames = array();
    if($gid == -1) $gid = $core->auth->group;

    if(empty($groupNames[$gid]))
    {
        if($core->auth->group_info['color'] != '')
        {
            $groupNames[$gid] = '<span style="color:' . $core->auth->group_info['color'] . ';">'.$core->auth->group_info['gname'].'</span>';
        }
        else
        {
            $groupNames[$gid] = $core->auth->group_info['gname'];
        }
    }

    return $groupNames[$gid];
}

function draw_rating($id, $module, $score, $votes, $blocked = null) 
{
global $core, $news_conf;
	if($core->auth->group_info['allowRating'] == 0) return;
	if($module == 'news' && empty($news_conf))
	{
		require ROOT . 'etc/news.config.php';
	}
	
	if(isset($news_conf['carma_rate']) && $news_conf['carma_rate'] == 1)
	{
		$button_plus = defined('AJAX') ? '' : '<img src="{%THEME%}/assest/images/engine/up.gif" border="0" alt="' . _PLUS_CARMA . '" title="' . _PLUS_CARMA . '" align="middle" class="_pointer" onclick="do_carma(' . $id . ', 1)" />';
		$button_minus = defined('AJAX') ? '' : '<img src="{%THEME%}/assest/images/engine/down.gif" border="0" alt="' . _MINUS_CARMA . '" title="' . _MINUS_CARMA . '" align="middle" class="_pointer" onclick="do_carma(' . $id . ', 2)" />';
		$summ = $news_conf['carma_summ'] ? ((intval($score)-$votes) > 0 ? '<span class="rating_plus">+' . (intval($score)-$votes) . '</span>' : ((intval($score)-$votes) == 0 ? '<span class="rating_zero">0</span>' : '<span class="rating_minus">' . (intval($score)-$votes) . '</span>')) : (($score+$votes) > 0 ? '<span class="rating_plus">' . ($score > 0 ? '+'.intval($score) : intval($score)) . '</span> <span class="rating_minus">' . ($votes > 0 ? '-'.$votes : $votes) . '</span>' : '<span class="rating_zero">0</span>');
		
		$rating = '<div id="rating' . $id . '" class="rating_layer">' . $button_plus . $summ . $button_minus . '</div>';
		return $rating;
	}
	else
	{
		static $limitStar, $starStyle;
		
		if(empty($limitStar) && empty($starStyle))
		{
			if(file_exists(ROOT . 'etc/' . mb_strtolower($module) . '.config.php'))
			{
				require ROOT . 'etc/' . mb_strtolower($module) . '.config.php';
				$confName = mb_strtolower($module) . '_conf';
				if(isset($$confName))
				{
					$subArray = $$confName;
				}
			}
			
			if(isset($subArray['limitStar']) && $subArray['limitStar'] > 3)
			{
				$limitStar = intval($subArray['limitStar']);
			}
			else
			{
				$limitStar = 5;
			}
			
			if(isset($subArray['starStyle']) && !empty($subArray['starStyle']))
			{
				$starStyle = $subArray['starStyle'];
			}
			else
			{
				$starStyle = 'star-rating';
			}
		}
		
		$starWidth = $core->tpl->starWidth;
		if(!defined('AJAX'))
		{
			$rating = '<div><div id="rating' . $id . '"></div></div>';
			if($blocked == 0) echo '<div id="rat' . $id . '" style="display:none;"></div>';
			$rating .= '<script type="text/javascript">genRating(\'' . $starStyle . '\',\'' . $starWidth . '\',\'' . $limitStar . '\',\'' . $score . '\',\'' . $votes . '\',\'' . $blocked . '\',\'' . $id . '\',\'' . $module . '\', \'' . _RATING . ': ' . mb_substr(($votes == 0) ? 0 : ($score/$votes), 0, 3) . ' ' . _VOTES . ': ' . $votes . '\');</script>';
		}
		else
		{
			$rating = '\'' . $starStyle . '\',\'' . $starWidth . '\',\'' . $limitStar . '\',\'' . $score . '\',\'' . $votes . '\',\'' . $blocked . '\',\'' . $id . '\',\'' . $module . '\', \'' . _RATING . ': ' . mb_substr(($votes == 0) ? 0 : ($score/$votes), 0, 3) . ' ' . _VOTES . ': ' . $votes . '\'';
		}
		return $rating;
	}
}


function show_comments($module, $id, $comment_num = 10, $ajax = false, $newPage = false, $warning = false, $langPref = '', $adminUid = false) 
{
global $db, $core, $config, $user, $adminUid, $page;
	if($config['comments'] == 0) return;
	$captcha = captcha_image();
	$id = intval($id);
	$page = $newPage ? intval($newPage) : init_page();
	$cut = ($page-1)*$comment_num;
	$query = $db->query("SELECT c.*, u.nick, u.group, u.last_visit, u.signature, u.user_comments, u.user_news  FROM ".DB_PREFIX."_comments as c LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on (c.uid=u.id) WHERE c.post_id='" . intval($id) . "' AND c.module='" . filter($module, 'module') . "' AND c.status='1' ORDER BY date DESC " . ($user['commentTree'] == 0 ? "LIMIT " . $cut . ", " . $comment_num : ''));
	$i = 0;	
	if($user['commentSubscribe'] == 1)
	{
		list($all, $isSubscribe) = $db->fetchRow($db->query("SELECT Count(id), (SELECT COUNT(*) FROM " . DB_PREFIX . "_com_subscribe WHERE id='" . intval($id) . "' AND module='" . filter($module, 'module') . "' AND uid='" . $core->auth->user_id . "') as isSubscribe FROM " . DB_PREFIX . "_comments WHERE post_id='" . $id . "' AND module='" . filter($module, 'module') . "' AND status='1'"));
	}
	else
	{
		list($all) = $db->fetchRow($db->query("SELECT Count(id) FROM " . DB_PREFIX . "_comments WHERE post_id='" . $id . "' AND module='" . filter($module) . "' AND status='1'"));
	}
	
	
	
	
	echo '<div id="commentError"></div>';
	echo '<div id="hideCommNum" style="display:none;">' . $comment_num . '</div>';
	echo '<div id="commentBox">' . "\n";
	$nowNumber = $all-$cut;
	if($db->numRows($query) > 0)
	{
		$core->tpl->loadFile('comments/comments.view.top');
		$core->tpl->end('comments/comments.view.top');
		while($rows = $db->getRow($query)) 
		{
			$comment_array[$rows['parent']][] = $rows;
		}
		
		if(!empty($comment_array[0]))
		{
			foreach($comment_array[0] as $comment)
			{
				$i++;
				build_comment($comment, '', $nowNumber);
			
				if($user['commentTree'] == 1)
				{
					$newNumber = build_tree($comment['id'], $comment_array, 3, $nowNumber);
					$nowNumber = ($newNumber > 0 ? $newNumber : $nowNumber);
				}
				$nowNumber--;
			}
		}
		
		if($user['commentTree'] == 0)
		{
			$core->tpl->pages($page, $comment_num, $all, 'javascript:void(0)', 'onclick="commentPage(\'news\', ' . $id . ', {num});"');
		}
		$core->tpl->loadFile('comments/comments.view.down');
	$core->tpl->end('comments/comments.view.down');
		
		
	}
	else
	{
		$core->tpl->info(constant($langPref.'_NO_COMMENT'));
	}

	if($core->auth->group_info['addComment'] == 1)
	{
		$text = '';
		
		if(is_array($warning))
		{
			$core->tpl->info(implode('<br />', $warning), 'warning');
			$text = filter(utf_decode($_REQUEST['text']));
		}
		elseif($warning == true)
		{
			$core->tpl->info(constant($langPref.'_COMMENT_ADDED'));
			$text = '';
		}
		
		$bb = bb_area('text', $text, 5, 'textarea', false, true);

		$core->tpl->loadFile('comments/comments.add');
		$core->tpl->setVar('ID', $id);
		$core->tpl->setVar('COOKIE_NAME', isset($_COOKIE['commentator']) && !$core->auth->isUser ? $_COOKIE['commentator'] : $core->auth->user_info['nick']);
		$core->tpl->setVar('COOKIE_MAIL', isset($_COOKIE['email']) && !$core->auth->isUser ? $_COOKIE['email'] : $core->auth->user_info['email']);
		$core->tpl->setvar('BB_AREA', $bb);
		$core->tpl->setVar('CAPTCHA', $captcha);
		$core->tpl->sources = preg_replace("#\\[noSubscribe\\](.*?)\\[/noSubscribe\\]#ies", "if_set('" . ($user['commentSubscribe'] == 1 && $core->auth->isUser && $isSubscribe == 0 ? 'yes' : '') . "', '\\1')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\[yesSubscribe\\](.*?)\\[/yesSubscribe\\]#ies", "if_set('" . ($user['commentSubscribe'] == 1 && $core->auth->isUser && $isSubscribe == 1 ? 'yes' : '') . "', '\\1')", $core->tpl->sources);
		$core->tpl->setVar('MOD', $module);
		$core->tpl->end();
	}
	echo '</div>' . "\n";
	
	
}

function build_tree($id_tree, $comment_array, $space = 0, $now_numb = 0)
{
global $core, $user, $nowNumber, $adminUid, $page;
	if(isset($comment_array[$id_tree]))
	{
		foreach($comment_array[$id_tree] as $comment)
		{
			$now_numb--;
			build_comment($comment, $space, $now_numb);
			$new_numb = build_tree($comment['id'], $comment_array, ($space+3), $now_numb);
		}
		
		return $new_numb > 0 ? $new_numb : $now_numb;
	}
}

function build_comment($comment, $space = '', $commNumber = 0)
{
global $core, $user, $adminUid, $page;
	$yrAdmin = false;
	

	if($adminUid > 0 && $core->auth->user_id == $adminUid) $yrAdmin = true;
	echo '<div id="commentBox_' . $comment['id'] . '">' . "\n";
	$core->tpl->loadFile('comments/comments.view');
	$core->tpl->setVar('ID', $comment['id']);
	$core->tpl->setVar('NAME', ($comment['uid'] != 0) ? $comment['nick'] : $comment['gname']);
	$core->tpl->setVar('NID', $comment['post_id']);
	$core->tpl->setVar('AVATAR', avatar($comment['uid']));
	$core->tpl->setVar('BODY', '<div id="comment_' . $comment['id'] . '">' . $core->bbDecode(stripslashes($comment['text'])) . '</div>'.($comment['signature'] ? str_replace('[sig]', $core->bbDecode($comment['signature']), $user['commentSignature']) : ''));
	$core->tpl->setVar('UID', $comment['uid']);
	$core->tpl->setVar('URL', $comment['gurl']);
	$core->tpl->setVar('E-MAIL', $comment['gemail']);
	$core->tpl->setVar('NUMBER', $commNumber);
	$core->tpl->setVar('U_COMM', $comment['user_comments']);
	$core->tpl->setVar('SPACE', ($space*10));
	$core->tpl->setVar('SPACE_BG', ($space*10-15));
	$core->tpl->setVar('U_NEWS', $comment['user_news']);
	$core->tpl->setVar('DATE', formatDate($comment['date']));
	$replace = array(
		"#\\[moder\\](.*?)\\[/moder\\]#ies" => "if_set('" . ($core->auth->isAdmin OR ($core->auth->isUser && $core->auth->user_id == $comment['uid']) OR $yrAdmin == true ? 'yes' : '') . "', '\\1')",
		"#\\[edit\\](.*?)\\[/edit\\]#is" => '<a href="javascript:void(0);" onclick="commentEdit(\'' . $comment['id'] . '\', \'comment_' . $comment['id'] . '\');" title="\\1">\\1</a>',
		"#\\[delete\\](.*?)\\[/delete\\]#is" => '<a href="javascript:void(0);" onclick="commentDelete(\'' . $comment['id'] . '\', \'commentBox_' . $comment['id'] . '\', \'' .$comment['module'] . '\', \'' . $comment['post_id']  . '\', \'' . $page . '\');" title="\\1">\\1</a>',
		"#\\[writeGuest\\](.*?)\\[/writeGuest\\]#is" => ($comment['uid'] == 0 ? '\\1' : ''),
		"#\\[writeUser\\](.*?)\\[/writeUser\\]#is" => ($comment['uid'] > 0 ? '\\1' : ''),
		"#\\[reply\\](.*?)\\[/reply\\]#is" => (/*$comment['uid'] != $core->auth->user_id &&*/ $user['commentTree'] == 1 ? '\\1' : ''),
		"#\\[space\\](.*?)\\[/space\\]#is" => ($space > 0 ? '\\1' : ''),
	);
	$core->tpl->sources = preg_replace(array_keys($replace), array_values($replace), $core->tpl->sources);
	$core->tpl->end('comments/comments.view');
	echo '</div>' . "\n";
}

function checkCommentGuest($content, $uid)
{
	if($uid == 0)
	{
		return stripslashes($content);
	}
}

function ajaxPoll()
{
global $url, $db, $core;
	require ROOT . 'usr/plugins/poll.plugin.php';
	
	switch($url[2])
	{
		case 'results':
			show_poll('results', intval($url[3]));
			break;
		
		case 'back_vote':
			show_poll(false, intval($url[3]));
			break;
		
		case 'doVote':
			$votes = explode('|', $_REQUEST['checks']);
			$pid = intval(str_replace('poll_', '', $_REQUEST['pid']));
			$nums = 0;
			foreach($votes as $id)
			{
				$id = intval($id);
				if(!empty($id))
				{
					$nums++;
					$db->query("UPDATE `" . DB_PREFIX . "_poll_questions` SET vote = vote+1 WHERE `id` = '" . $id . "' AND `pid` = '" . $pid . "' LIMIT 1 ;");
				}
			}
			
			$db->query("UPDATE `" . DB_PREFIX . "_polls` SET votes = votes+" . $nums . " WHERE `id` = '" . $pid . "' LIMIT 1 ;");
			$db->query("INSERT INTO `" . DB_PREFIX . "_poll_voting` ( `id` , `uid` , `pid` , `ip` , `time` ) VALUES (NULL, '" . $core->auth->user_id . "', '" . $pid . "', '" . getenv('REMOTE_ADDR') . "', '" . time() . "');");
			
			show_poll('voted', $pid);
			break;
	}
}

function add_point($mod, $id, $do = '+') 
{
global $adminTpl, $db, $core;
	if(!file_exists(ROOT.'usr/modules/' . $mod . '/comments.php'))
	{
		$db->query("UPDATE `" . DB_PREFIX . "_" . $mod . "` SET comments=comments" . $do . "1 WHERE `id` =" . $id . " LIMIT 1", true);
		if($core->auth->isUser)
		{
			$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET user_comments=user_comments" . $do . "1 WHERE `id` =" . $core->auth->user_id . " LIMIT 1", true);
		}
	}
	else
	{
		include(ROOT.'usr/modules/' . $mod . '/comments.php');
	}
}

/*
* Отслеживаем ботов
* $agent - $_SERVER['HTTP_USER_AGENT']
* $ret_id - если true то функция вернёт ид бота
* $id - если известен ид бота то скрипт вернёт его имя
*/
function SpiderDetect($agent, $ret_id = false, $id = null) {
    $engines = array(
    array('Aport', 'Aport robot'),
    array('Google', 'Google'),
    array('msnbot', 'MSN'),
    array('Rambler', 'Rambler'),
    array('Yahoo', 'Yahoo'),
    array('AbachoBOT', 'AbachoBOT'),
    array('accoona', 'Accoona'),
    array('AcoiRobot', 'AcoiRobot'),
    array('ASPSeek', 'ASPSeek'),
    array('CrocCrawler', 'CrocCrawler'),
    array('Dumbot', 'Dumbot'),
    array('FAST-WebCrawler', 'FAST-WebCrawler'),
    array('GeonaBot', 'GeonaBot'),
    array('Gigabot', 'Gigabot'),
    array('Lycos', 'Lycos spider'),
    array('MSRBOT', 'MSRBOT'),
    array('Scooter', 'Altavista robot'),
    array('AltaVista', 'Altavista robot'),
    array('WebAlta', 'WebAlta'),
    array('IDBot', 'ID-Search Bot'),
    array('eStyle', 'eStyle Bot'),
    array('Mail.Ru', 'Mail.Ru Bot'),
    array('Scrubby', 'Scrubby robot'),
    array('Yandex', 'Yandex'),
    array('VoidSearch', 'VoidSearch'),
    array('YaDirectBot', 'Yandex Direct')
    );

    if($id) {
        return $engines[$id][1];
    } else {
        foreach ($engines as $key => $engine) {
            if (strstr($agent, $engine[0])) {
                if($ret_id == false) {
                    return($engine[1]);
                } else {
                    return($key);
                }
            }
        }
    }

    return (false);
}

/*
* Инитилизация системы учётов реффера
*/
function init_reffer() {
    global $db;
    require ROOT . 'etc/reffer.config.php';

    $reffer = isset($_SERVER['HTTP_REFERER']) ? filter($_SERVER['HTTP_REFERER']) : getenv("http_referer");
    $reqUri = isset($_SERVER['REQUEST_URI']) ? filter($_SERVER['REQUEST_URI'], 'reqUri') : '';
    $ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? filter($_SERVER['HTTP_X_FORWARDED_FOR']) : filter($_SERVER['REMOTE_ADDR']);
    $time = time();	
    $engineName = '';
    $sQuery = '';
    $query = array();

    $refferDomen = getHost($reffer);
    $myDomen = getHost($_SERVER['HTTP_HOST']);

    if(trim($reffer) && $refferDomen != $myDomen)
    {
        $uri = parse_url($reffer);
        if(isset($uri['query']))
        {
            parse_str($uri['query'], $query);
        }

        foreach($engines as $engine => $info)
        {
            if(eregStrt($info['serv_url'], $refferDomen))
            {
                $fromEngine = true;
                $engineName = $info['name'];
                if(isset($query[$info['get']])) $sQuery = utf_decode($query[$info['get']]);
            }			
        }

        $type = isset($fromEngine) ? 2 : 1;

        $compressRef = @base64_encode($reffer);
        //@base64_decode(gzinflate('bla', 9))

        $exists = $db->getRow($db->query("SELECT COUNT(*) as count FROM " . DB_PREFIX . "_reffers WHERE url = '" . $compressRef . "'"));

        if($exists['count'] > 0)
        {
            $db->query("UPDATE `" . DB_PREFIX . "_reffers` SET `url` = '" . $db->safesql($compressRef) . "', `search_query` = '" . $db->safesql($sQuery) . "', `time` = '" . $time . "', `ip` = '" . $ip . "', count = count+1 WHERE `url` = '" . $db->safesql($compressRef) . "' LIMIT 1 ;");
        }
        else
        {
            $db->query("INSERT INTO `" . DB_PREFIX . "_reffers` ( `url` , `referer` , `to_url` , `search_query` , `time` , `ip` , `count` , `host` , `type` ) VALUES ('" . $db->safesql($compressRef) . "', '" . $db->safesql($engineName) . "', '" . $db->safesql($reqUri) . "', '" . $db->safesql($sQuery) . "', '" . $time . "', '" . $ip . "', '1', '" . $db->safesql($refferDomen) . "', '" . $type . "');", true);
        }

    }
}

function setHeaders($url, $cookies, $reffer = false)
{
    global $config;
    static $cookie;
    $reffer = $reffer == false ? $config['url'] : $reffer;
    if(trim($cookies) != '' && get_loaded_extensions('curl'))
    {
        if(!isset($cookie))
        {
            $cookie = '';
            $bufferCookie = array_unique(explode("\n", $cookies));

            foreach($bufferCookie as $cook)
            {
                if(trim($cook) != '' && eregStrt('=', $cook))
                {
                    $cookie .= $cook . '; ';
                }
            }
        }

        $curl_handle = curl_init($url);
        curl_setopt($curl_handle, CURLOPT_COOKIE, $cookie);
        curl_setopt($curl_handle, CURLOPT_REFERER, $reffer);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, randomUserAgent());
        curl_exec($curl_handle);
        curl_close($curl_handle);
    }
}

function randomUserAgent()
{
    $agents = array ('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)', 'Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; ru) Opera 8.01', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows 98)', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.6) Gecko/20050225 Firefox/1.0.1', 'Mozilla/4.0 (compatible; MSIE 5.01; Windows 98)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; MyIE2; .NET CLR 1.1.4322)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Maxthon)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1) Opera 7.50 [en]', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.7.10) Gecko/20050717 Firefox/1.0.6', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 3.0 (build 00614))', 'Mozilla/5.0 (Windows; U; Windows NT 5.0; ru-RU; rv:1.7.10) Gecko/20050717 Firefox/1.0.6', 'Opera/6.01 (Windows 2000; U) [ru]', 'Mozilla/5.0 (Windows; U; Windows NT 5.0; ru-RU; rv:1.7.7) Gecko/20050414 Firefox/1.0.3', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FunWebProducts-MyWay; MRA 4.2 (build 01102))', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; snprtz|dialno; HbTools 4.7.0)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; MRA 4.0 (build 00768))', 'Mozilla/4.0 (compatible; MSIE 5.5; Windows 98; Win 9x 4.90; OptusIE55-31)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.1 (build 00961); .NET CLR 1.1.4322)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; .NET CLR 1.1.4322)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.1 (build 00975); .NET CLR 1.1.4322)', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; MRA 4.2 (build 01102))', 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; Hotbar 4.6.1)', 'Mozilla/4.0 (compatible; MSIE 5.0; Windows 98)', 'Mozilla/4.0 (compatible; MSIE 6.0; MSIE 5.5; Windows 2000) Opera 7.0 [en]', 'Mozilla/4.0 (compatible; MSIE 5.5; Windows NT 5.0)', 'Mozilla/4.0 (compatible; MSIE 5.0; Windows 98; DigExt)', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru-RU; rv:1.8a5) Gecko/20041122');
    return $agents[rand(0, count($agents)-1)];
}

function gencode($lenght)
{
    $symbols = array('a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','8','9');
    for($i=0;$i<$lenght;$i++)
    {
        $code[] = $symbols[rand(0,sizeof($symbols)-1)];
    }

    return implode('', $code);
}

function captcha_image($button = true) 
{
    global $security;
	session_start();
	
	$captcha = "<style type=\"text/css\">/* <![CDATA[ */.captcha{width: " . $security['captcha_width'] . "px; height: " . $security['captcha_height'] . "px; background: url('captcha') top left no-repeat;}/* ]]> */</style>"
    ."<a href=\"javascript:reloadCaptcha();\" title=\"' . _REFRESH . '\"><div class=\"captcha\" id=\"captcha\"> </div></a>";

    return $captcha;
}

function captcha_check($post_name) 
{
    global $core;
    session_start();

    if($core->auth->isUser) return true;

    if(isset($_REQUEST[$post_name])) 
    {
        if(isset($_SESSION['securityCode']))
        {
            if($_SESSION['securityCode'] == mb_strtolower($_POST[$post_name]))
            {
                unset($_SESSION['securityCode']);
				return true;
            }
        }
		else
		{
			echo 'session failed';
		}
    }

    return false;
}

function parse_req($value) 
{
    global $log_conf;
    if(!is_array($value)) 
    {
        if(preg_match("#UNION|OUTFILE|SELECT|ALTER|INSERT|DROP|TRUNCATE#i", base64_decode($value))) 
        {
            if($log_conf['queryError'] == 1) writeInLog('Попытка произвести SQL-Inj текст: '.$value, 'sql');
            //fatal_error(_ERROR, _UNKNOWN_ERROR);	
			die();
        }
    }
    else
    {
        foreach($value as $val) 
        {
            parse_req($val);
        }
    }
}

function loadConfig($file)
{
global $config, $core;
	$mainFile = ROOT.'etc/'.$file.'.config.php';
	require_once($mainFile);
	if($config['multiLang'] && $config['lang'] != $core->lang && file_exists(ROOT.'etc/' . $core->lang . '.'.$file.'.config.php'))
	{
		require ROOT.'etc/' . $core->lang . '.'.$file.'.config.php';
	}
}

function filter($text, $type = false) 
{
global $core, $security;
    $allowArr = explode(',', $security['allowHTML']);
    $allowHTML = '<'.implode('>,<', $allowArr).'>';
    $stopWords = explode(',', $security['stopWords']);
    foreach($stopWords as $word)
    {
        $wordReplace[$word] = $security['stopReplace'];
    }

    $text = str_replace('||men||', '<', $text);
    $text = str_replace('||and||', '&', $text);
    $text = str_replace('||bol||', '>', $text);
    $text = str_replace(array_keys($wordReplace), array_values($wordReplace), $text);
	
    switch($type)
    {
		case 'bb':
		default:
			$text = (htmlspecialchars(strval($text))); 
			break;
			
		case 'title':
			$text = (htmlspecialchars(strval(htmlspecialchars_decode($text)), ENT_QUOTES)); 
			break;
			
		case 'int':
			$text = intval($text);
			break;
			
		case 'str':
			$text = (strval($text));
			break;
			
		case 'mail':
			$text = ((preg_match("/^[A-Z0-9_.-]+@([A-Z0-9][A-Z0-9-]+.)+[A-Z]{2,6}$/iu", $text)) ? mb_strtolower($text) : '');
			break;
			
		case 'url':
			$text = preg_replace("'[^A-ZА-Яa-zа-я0-9_ -.,\[\]\)\(-\@!?\$&*~]'ius", '', $text);
			break;
			
		case 'html':
			$text = (preg_replace(array_keys($core->deniedHTML), array_values($core->deniedHTML), strval($text)));
			break;
			
		case 'module':
		case 'a':
		case 'alphanum':
			$text = preg_replace("'[^A-ZА-Яa-zа-я0-9_ -.,\[\]]'ius", '', $text);
			break;
			
		case 'nick':
			$text = preg_replace("'[^A-ZА-Яa-zа-я0-9_ -.,\[\]\)\(-\@!?\$&*~]'ius", '', $text);
			break;
			
			
		case 'no_html':
			$text = (htmlspecialchars(strip_tags(urldecode(strval($text)))));
			break;
			
		
		case 'dir':
			$text = preg_replace ("'([^a-z0-9_/-]|[/]*$)'iu", '', $text);
			$text = preg_replace ("'[/]{2,}'", '/', $text);
			break;
			
		case 'ip':
			if (!preg_match("'[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}.[0-9]{1,3}'", $text)) $text = null;
			break;
			
        case 'reqUri':
            if(eregStrt('index.php', $text))
            {
                $text = filter($text);
            }
            else
            {
                $text = 'index.php';
            }
            break;

		case 'forBB':
            $text = str_replace("\n", '<br/>', $text);
            break;
			
		
	}
	
	if($type != 'template')
    {
        $text = preg_replace('/{%([^%]*)%}/i', '&#123;%\\1%}', $text);
    }
	
    return $text;
}

/*
* Ограничение групп для шаблонов
* $group - группы для которых закрыть доступ через ,
* $content - то что прячем
*/
function noGroup($group, $content) 
{
global $core;
	$gr_array = explode(',', $group);
	if(in_array($core->auth->group, $gr_array)) 
	{
		return false;
	} 
	else 
	{
		return stripslashes($content);
	}
}

/*
* Условия для шаблонов и не только ;)
* $content - то что проверям
* $data - то что выводим
*/
function if_set($content, $data) {
	return empty($content) ? '' : stripslashes($data);
}

function checkUser($content)
{
global $core;
	if($core->auth->isUser)
	{
		return stripslashes($content);
	}
}

function checkGuest($content)
{
global $core;
	if($core->auth->isUser == false)
	{
		return stripslashes($content);
	}
}

/*
* Генерация ссылок для страничной навы
* $link - ссылка модуля
* $content - то что выводим
* $type - номер страницы
*/
function pageLink($link, $content, $type, $onClick = false) {
	return '<a href="' . str_replace('{page}', 'page/' . $type, $link) . '" title="' . $content . '" ' . ($onClick ? str_replace('{num}', $type, $onClick) : '') . '><b>' . $content . '</b></a> ';
}

/*
* Форматинг ссылки для новости
* $title - имя ссылки
* $format - адресс сылки
*/
function format_link($title, $format) {
	return "<a href=\"" . $format . "\" title=\"{%TITLE%}\">" . stripslashes($title) . "</a>";
}

function truncate_words($text, $limit)
{
    $text=mb_substr($text,0,$limit);    
    if(mb_substr($text,mb_strlen($text)-1,1) && mb_strlen($text)==$limit)
    {
        $textret=mb_substr($text,0,mb_strlen($text)-mb_strlen(strrchr($text,' ')));
        if(!empty($textret))
        {
            return $textret;
        }
    }    
    if(strlen($text)>$limit) {
        $text=mb_substr($text,0,$limit);
    }
    return $text;
}


function short($count, $text) {
	$text = strip_tags($text);
	$text_f = $text;
	if (strlen($text) > $count)
	{
		$text = truncate_words($text, $count);
		if ($text_f!=$text)
		{
			$text = $text. '...';
		}
	}
	return $text;
}

/*
* Показывать только на главной или везде кроме главной? :)
* $is - true или false отображать ТОЛЬКО на главной или КРОМЕ главной)
* $content - контент
*/
function indexShow($is, $content)
{
global $url;
	$is = intval($is);

	if($is > 0)
	{
		if(defined('INDEX_NOW'))
		{
			return stripslashes($content);
		}
	}
	else
	{
		if(!defined('INDEX_NOW'))
		{
			return stripslashes($content);
		}
	}
}

/*
* Показывать контент только в определённых моудлях
* $modules - модули в которых показывать через ,(запятую)
* $content - контент
*/
function moduleShow($modules, $content)
{
global $url;
	$modulesArray = explode(',', $modules);
	
	if(in_array($url[0], $modulesArray)) 
	{
		return stripslashes($content);
	}
	else
	{
		return false;
	}
}

function modulesShow($modules, $tag, $content)
{
global $url;
	$modulesArray = explode(',', $modules);
	
	if(in_array($url[0], $modulesArray)) 
	{
		if($tag == 1) return stripslashes($content);
	}
	else
	{
		if($tag == 0) return stripslashes($content);
	}
}

function buildCustom($category = '', $template = '', $aviable = '', $limit = '', $module = '', $order = '', $short = '', $notin = false)
{

	$category = filter($category, 'a');
	$template = filter($template, 'a');
	$aviable = filter($aviable, 'a');
	$limit = intval($limit);
	$module = filter($module, 'module');
	$order = filter($order, 'a');
	$short = filter($short, 'a');	

	
	if(!empty($category) && !empty($template) && !empty($aviable) && ($limit > 0) && !empty($module) && file_exists(ROOT.'usr/modules/'.$module.'/custom.php'))
	{
		if($aviable == 'all' || ($aviable != 'all' && modulesShow($aviable, 1, 'on') == 'on'))
		{
			require(ROOT.'usr/modules/'.$module.'/custom.php');
			
			return $custom;
		}
	}
}

function _getCustomImg($html)
{
	preg_match_all('#<img[^>]+src=[\'"]([^\s>]*)[\'"][^>]*>#is', $html, $images);
	return $images[1];
}

function clearTEXT($text)
{

	$search = array ("'ё'",
                     "'<script[^>]*?>.*?</script>'si",  // Вырезается javascript
                     "'<[\/\!]*?[^<>]*?>'si",           // Вырезаются html-тэги
                     "'([\r\n])[\s]+'",                 // Вырезается пустое пространство
                     "'&(quot|#34);'i",                 // Замещаются html-элементы
                     "'&(amp|#38);'i",
                     "'&(lt|#60);'i",
                     "'&(gt|#62);'i",
                     "'&(nbsp|#160);'i",
                     "'&(iexcl|#161);'i",
                     "'&(cent|#162);'i",
                     "'&(pound|#163);'i",
                     "'&(copy|#169);'i",
                     "'&#(\d+);'e");
    $replace = array ("е",
                      " ",
                      " ",
                      "\\1 ",
                      "\" ",
                      " ",
                      " ",
                      " ",
                      " ",
                      chr(161),
                      chr(162),
                      chr(163),
                      chr(169),
                      "chr(\\1)");
    $text = preg_replace ($search, $replace, $text);
	$del_symbols = array(",", ".", ";", ":", "\"", "#", "\$", "%", "^",
                         "!", "@", "`", "~", "*", "-", "=", "+", "\\",
                         "|", "/", ">", "<", "(", ")", "&", "?", "¹", "\t",
                         "\r", "\n", "{","}","[","]", "'", "“", "”", "•"
                  
                         );
    $text = str_replace($del_symbols, array(" "), $text);
    $text = ereg_replace("( +)", " ", $text);
    return $text;
}

function ifFields($xfields, $id, $content)
{
	if(empty($xfields))
	{
		return '';
	}
	else
	{
		$fields = unserialize($xfields);
		if(!empty($fields[$id][1]))
		{
			return stripslashes($content);
		}
		else
		{
			return '';
		}
	}
}

parse_req($_REQUEST);

$_SERVER["REQUEST_URI"] = filter($_SERVER["REQUEST_URI"], 'reqUri');
$_SERVER['REMOTE_ADDR'] = filter($_SERVER['REMOTE_ADDR'], 'ip');

 
