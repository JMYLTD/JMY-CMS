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


function adminError($err, $back = false)
{
global $adminTpl, $core;
	$adminTpl->admin_head('Ошибка');
	$adminTpl->info($err . ($back ? '<a href="#" onclick="/'.ADMIN.'/' . $back . '/" >Вернуться.</a>' : ''));
	$adminTpl->admin_foot();
}
	
	
function _mName($mod)
{
global $core;
	return isset($core->tpl->modules[$mod]) ? $core->tpl->modules[$mod]['content'] : $mod;
}

function adminArea($name, $val = null, $rows = 10, $class = 'textarea', $onclick = null, $return = false)
{
global $adminTpl, $core;
	if($core->html_editor == 1)
	{
		$adminTpl->headerIncludes['bb'] = '<script type="text/javascript" src="usr/js/bb_editor.js"></script><script type="text/javascript">var textareaName = \''.$name.'\';</script>';
		$adminTpl->headerIncludes['htmleditor'] = '<script type="text/javascript" src="/usr/plugins/htmleditor/tiny_mce.js"></script><script type="text/javascript">tinyMCE.init({mode : "textareas",	theme : "advanced",	skin : "o2k7",	plugins : "safari,style,layer,table,advhr,advimage,advlink,emotions,iespell,insertdatetime,media,searchreplace,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,inlinepopups,hideBB", theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect", theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",	theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,hideBB",theme_advanced_toolbar_location : "top",theme_advanced_toolbar_align : "left",	theme_advanced_statusbar_location : "bottom",theme_advanced_resizing : true,template_external_list_url : "lists/template_list.js",external_link_list_url : "lists/link_list.js",external_image_list_url : "lists/image_list.js",media_external_list_url : "lists/media_list.js",});</script>';
		$editor = '<textarea id="' . $name . '" name="' . $name . '" rows="' . $rows . '" cols="90" class="' . $class . '" onclick="mainArea(\'' . $name . '\')">' . $val . '</textarea>';

		return $editor;
	}
	else
	{
		return bb_areaADM($name, $val, $rows, $class, $onclick, $return, true);
	}
}

function countPub()
{
//	return ' (+20)';
}

function checkAdmControl($mod = 'index')
{
global $core;
	if(!empty($core->auth->user_info['control']))
	{
		$access = array_map('trim', unserialize($core->auth->user_info['control']));
		if(in_array($mod, $access))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	else
	{
		return true;
	}
}

function noadmAccess()
{
global $adminTpl;
	$adminTpl->admin_head('Нет доступа!');
	$adminTpl->info('Извините но вам закрыт доступ в данный раздел!');
	$adminTpl->admin_foot();
}



function dirsize($dir, $buf = 2)
{
	static $buffer;
	if(isset($buffer[$dir]))
	{
		return $buffer[$dir];
	}
	
	if(is_file($dir))
	{
		return filesize($dir);
	}
	
	if($dh=opendir($dir))
	{
		$size=0;
		while(($file=readdir($dh))!==false)
		{
			if($file=='.' || $file=='..')
			{
				continue;
			}
			$size+=dirsize($dir.'/'.$file,$buf-1);
		}
		closedir($dh);
		if($buf>0)
		{
			$buffer[$dir]=$size;
		}
		return $size;
	}
	return false;
}

/*
* Получаем категории из массива ЭКСПЕРЕМЕНТАЛЬНАЯ ФУНКЦИЯ :D
* $fp - файл
* $content - то что запишем
*/
function save_conf($fp, $content) 
{
	$file_name = basename($fp);
	if(!file_exists($fp))
	{
		file_put_contents($fp,'');
		@chmod(ROOT . $fp, 0666 );
	}
	if (file_exists($fp) && $content) 
	{
		$fp = fopen($fp, "wb");
		$content = "<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}
\n\n".$content."\n";
		fwrite($fp, $content);
		fclose($fp);
	}
}




/*
* Выводим чекбоксы :D
* $name - имя инпута чеки
* $val - тру ор фалсе
*/
function checkbox($name, $val) 
{
	$checked = !empty($val) ? 'checked ' : false;
	return "<label class=\"checkbox checkbox-custom\"><input name=\"" . $name . "\" type=\"checkbox\" " . $checked . "><i class=\"checkbox\"></i></label>";
}


/*
* Выводим радио выбор Да Нет
* $name - имя инпута радио
* $val - тру ор фалсе
*/
function radio($name, $val) 
{
	$but_1 = ($val) ? "checked" : "";
	$but_2 = (!$val) ? "checked" : "";
	return '
	<table>
	<tr>
	<td valign="top">
				<label class="radio radio-custom ' . $but_1 . '"><input type="radio" ' . $but_1 . ' value="1" name="' . $name . '" id="' . $name . '"><i class="radio ' . $but_1 . '"></i>'._YES.'</label>
			</td>
			<td>&nbsp&nbsp</td>
			<td valign="top">
				<label class="radio radio-custom ' . $but_2 . '"><input type="radio" ' . $but_2 . ' value="0" name="' . $name . '" id="' . $name . '"><i class="radio ' . $but_2 . '"></i>'._NO.'</label>
				</td>
				
	</tr>
	</table>
				
			';
}

/*
* Функция рекурсивного сканирования дерикторий шаба
* $rootDir - директория
*/
function scanDirectories($rootDir, $allData=array()) 
{
	$invisibleFileNames = array(".", "..", ".htaccess", ".htpasswd");
	$dirContent = scandir($rootDir);
	foreach($dirContent as $key => $content) 
	{
		$path = $rootDir.'/'.$content;
		if(!in_array($content, $invisibleFileNames)) 
		{
			if(is_file($path) && is_readable($path) && eregStrt('.tpl', $content)) 
			{
				$allData[] = $path;
			}
			elseif(is_dir($path) && is_readable($path)) 
			{
				$allData = scanDirectories($path, $allData);
			}
		}
	}
	return $allData;
}

function dircopy($srcdir, $dstdir, $offset, $verbose = false) {
if(!isset($offset)) $offset=0;
  $num = 0;
  $fail = 0;
  $sizetotal = 0;
  $fifail = '';
  if(!is_dir($dstdir)) {mkdir($dstdir, 0777); @chmod_R($dstdir, 0777);}
  if($curdir = opendir($srcdir)) {
    while($file = readdir($curdir)) {
      if($file != '.' && $file != '..') {
        $srcfile = $srcdir . '\\' . $file;
        $dstfile = $dstdir . '\\' . $file;
        if(is_file($srcfile)) {
          if(is_file($dstfile)) $ow = filemtime($srcfile) - filemtime($dstfile); else $ow = 1;
          if($ow > 0) {
            if($verbose) echo "Copying '$srcfile' to '$dstfile'...";
            if(copy($srcfile, $dstfile)) {
              touch($dstfile, filemtime($srcfile)); $num++;
              $sizetotal = ($sizetotal + filesize($dstfile));
              if($verbose) echo "OK\n";
            }
            else {
                 echo "Error: File '$srcfile' could not be copied!\n";
                 $fail++;
                 $fifail = $fifail.$srcfile."|";
            }
          }                   
        }
        else if(is_dir($srcfile)) {
          $res = explode(",",$ret);
          $ret = dircopy($srcfile, $dstfile, $verbose);
          $mod = explode(",",$ret);
          $imp = array($res[0] + $mod[0],$mod[1] + $res[1],$mod[2] + $res[2],$mod[3].$res[3]);
          $ret = implode(",",$imp);
        }
      }
    }
    closedir($curdir);
  }
  $red = explode(",",$ret);
  $ret = ($num + $red[0]).",".(($fail-$offset) + $red[1]).",".($sizetotal + $red[2]).",".$fifail.$red[3];
  return $ret;
}


function rmdir_rf($dirname) 
{
    if ($dirHandle = opendir($dirname)) {
        chdir($dirname);
        while ($file = readdir($dirHandle)) {
            if ($file == '.' || $file == '..') continue;
            if (is_dir($file)) rmdir_rf($file);
            else unlink($file);
        }
        chdir('..');
        rmdir($dirname);
        closedir($dirHandle);
    }
}

function jsCalendar($id, $format = 'd.m.Y')
{
	if(is_array($id))
	{
		$i = 0;
		foreach($id as $k => $subId)
		{
			$i++;
			$subJs = "
			  <script type=\"text/javascript\">
			    window.addEvent('domready', function() { 
					myCal".$i." = new Calendar({ ".$subId.": '".$format."' }, { classes: ['dashboard'], direction: 0 });
				});
			  </script>
			";
		}
	}
	else
	{
		$subJs = "
		  <script type=\"text/javascript\">
		    window.addEvent('domready', function() { 
				myCal2 = new Calendar({ ".$id.": '".$format."' }, { classes: ['dashboard'], direction: 0 });
			});
		  </script>
		";
	}

	return '<script type="text/javascript" src="usr/plugins/calendar/mootools.js"></script><script type="text/javascript" src="usr/plugins/calendar/calendar.js"></script>' . $subJs . '<link rel="stylesheet" type="text/css" href="usr/plugins/calendar/dashboard.css" media="screen" />';

}


function deleteComments($mod, $id)
{
global $adminTpl, $db;
	$db->query("DELETE FROM `" . DB_PREFIX . "_comments` WHERE `post_id` = " . $id . " AND `module` = '" . $mod . "' LIMIT 1");
}


/*
* the part of this by zhilinsky (zhilinsky.ru) [sps] :D
*/

function nooverflow($a) { 
	while ($a<-2147483648) $a+=2147483648+2147483648; 
	while ($a>2147483647) $a-=2147483648+2147483648; 
	return $a; 
}

function zeroFill ($x, $bits) { 
   if ($bits==0) return $x; 
   if ($bits==32) return 0; 
   $y = ($x & 0x7FFFFFFF) >> $bits; 
   if (0x80000000 & $x) { 
       $y |= (1<<(31-$bits)); 
   } 
   return $y; 
}

function mix($a,$b,$c) {
	$a=(int)$a; $b=(int)$b; $c=(int)$c; 
	$a -= $b; $a -= $c; $a=nooverflow($a); $a ^= (zeroFill($c,13)); 
	$b -= $c; $b -= $a; $b=nooverflow($b); $b ^= ($a<<8); 
	$c -= $a; $c -= $b; $c=nooverflow($c); $c ^= (zeroFill($b,13)); 
	$a -= $b; $a -= $c; $a=nooverflow($a); $a ^= (zeroFill($c,12)); 
	$b -= $c; $b -= $a; $b=nooverflow($b); $b ^= ($a<<16); 
	$c -= $a; $c -= $b; $c=nooverflow($c); $c ^= (zeroFill($b,5)); 
	$a -= $b; $a -= $c; $a=nooverflow($a); $a ^= (zeroFill($c,3)); 
	$b -= $c; $b -= $a; $b=nooverflow($b); $b ^= ($a<<10); 
	$c -= $a; $c -= $b; $c=nooverflow($c); $c ^= (zeroFill($b,15)); 
	return array($a,$b,$c); 
}

function GCH($url, $length = null, $init = 0xE6359A60) {
    if(is_null($length)) { 
        $length = sizeof($url); 
    } 
    $a = $b = 0x9E3779B9; 
    $c = $init; 
    $k = 0; 
    $len = $length; 
    while($len >= 12) { 
        $a += ($url[$k+0] +($url[$k+1]<<8) +($url[$k+2]<<16) +($url[$k+3]<<24)); 
        $b += ($url[$k+4] +($url[$k+5]<<8) +($url[$k+6]<<16) +($url[$k+7]<<24)); 
        $c += ($url[$k+8] +($url[$k+9]<<8) +($url[$k+10]<<16)+($url[$k+11]<<24)); 
        $mix = mix($a,$b,$c); 
        $a = $mix[0]; $b = $mix[1]; $c = $mix[2]; 
        $k += 12; 
        $len -= 12; 
    } 
    $c += $length; 
    switch($len) { 
        case 11: $c+=($url[$k+10]<<24); 
        case 10: $c+=($url[$k+9]<<16); 
        case 9 : $c+=($url[$k+8]<<8); 
        case 8 : $b+=($url[$k+7]<<24); 
        case 7 : $b+=($url[$k+6]<<16); 
        case 6 : $b+=($url[$k+5]<<8); 
        case 5 : $b+=($url[$k+4]); 
        case 4 : $a+=($url[$k+3]<<24); 
        case 3 : $a+=($url[$k+2]<<16); 
        case 2 : $a+=($url[$k+1]<<8); 
        case 1 : $a+=($url[$k+0]); 
    } 
    $mix = mix($a,$b,$c); 
    return $mix[2]; 
}

function strord($string) { 
    for($i=0;$i<mb_strlen($string);$i++) { 
        $result[$i] = ord($string{$i}); 
    } 
    return $result; 
} 

/*
* Определение pr сайта по урл
* $aUrl - урл сайта которого определяем
*/
function getPageRank($aUrl) {
	if($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
		return 'Local PR 0';
	} else {
		$url = 'info:'.$aUrl; 
		$ch = GCH(strord($url)); 
		$url = 'info:'.urlencode($aUrl); 
		$pr = @file("http://www.google.com/search?client=navclient-auto&ch=6$ch&ie=UTF-8&oe=UTF-8&features=Rank&q=http://digits.ru/"); 
		$pr_str = @implode("", $pr); 
		$pr = mb_substr($pr_str,strrpos($pr_str, ":")+1); 
		if($pr > 1) {
			return $pr;
		} else {
			return 0;
		}
	}
}

/*
* Определение тиц сайта по урл
* $url - урл сайта которого определяем
*/
function yandex_tic($url){
	if($_SERVER['SERVER_ADDR'] == '127.0.0.1') {
		return 'Local ТИЦ 0';
	} 
	else 
	{
		$ci_url = "http://bar-navig.yandex.ru/u?ver=2&show=32&url=http://".$url."/"; 
		$ci_data = implode("", file("$ci_url"));
		preg_match("/value=\"(.\d*)\"/", $ci_data, $ci); 
		if ($ci[1] == "") 
			$str = 0;
		else 
			$str = $ci[1];	
		return trim($str);
	}
}

function commentLink($mod, $id)
{
	$linked = array('profile' => 'profile/{id}', 'news' => 'news/view/{id}', 'blog' => 'blog/read/{id}', 'gallery' => 'gallery/photo/{id}');
	if(isset($linked[$mod]))
	{
		return '<a target="_blank" href="' . str_replace('{id}', $id, $linked[$mod]) . '">' . _mName($mod) . '</a>';
	}
	else
	{
		return _mName($mod);
	}
}

//настроки в модулях
function generateConfig($configBox, $type, $link, $ok = false)
{
global $adminTpl, $url, $core;
	require (ROOT.'etc/'.$type.'.config.php');
	$parseConf = $configBox[$type];
	$varName = $configBox[$type]['varName'];
	$confArr = $$varName;
	$adminTpl->admin_head(_BASE_CONFIG .' | ' . $parseConf['title']);
	if($ok)
	{
		$adminTpl->info(_SUCCESS_SAVE);
		$adminTpl->admin_foot();
		$file = 'etc/'.$_POST['conf_file'].'.config.php';
		$conf_arr_name = $_POST['conf_arr_name'];
		$content = "global $$conf_arr_name;\n";
		$content .= "\$$conf_arr_name = array();\n";
		foreach($_POST as $k => $val) {
			if($k != 'conf_arr_name' && $k != 'conf_file') {
				if(!is_array($val)) {
					if($k != 'illFormat')
					{
						$content .= "\$".$conf_arr_name."['".$k."'] = \"".htmlspecialchars(str_replace('"', '\"', stripslashes($val)), ENT_QUOTES)."\";\n";
					}
					else
					{
						$content .= "\$".$conf_arr_name."['".$k."'] = \"".str_replace('"', '\"', stripslashes($val))."\";\n";
					}
				} else {
					foreach($val as $karr => $varr) {
						$content .= "\$".$conf_arr_name."['".$k."']['".$karr."'] = \"".htmlspecialchars(stripslashes($varr), ENT_QUOTES)."\";\n";
					}
				}
			}
		}
		save_conf($file, $content);

		echo '<br />';
		
		require (ROOT.'etc/'.$type.'.config.php');
		$confArr = $$varName;
	}
	else
	{
	$adminTpl->open();
	echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading no-border">
						<b>'. $parseConf['title'] .'</b>
					</div>
				<div class="panel-body">
				<div class="switcher-content">
					<form action="' . $link . '"  method="post"  role="form" class="form-horizontal parsley-form" data-parsley-validate>';	
	foreach($parseConf['groups'] as $group)
	{
	  foreach($group['vars'] as $var => $varArr)
	  {
		echo '<div class="form-group">
					<label class="col-sm-3 control-label">'. $varArr['title'] .':</label>
					<div class="col-sm-4">
					    ' . (isset($confArr[$var]) ? str_replace(array('{varName}', '{var}'), array($var, $confArr[$var]), $varArr['content']) : $varArr['content']) . '
						<p class="help-block">'. $varArr['description'] .'</p>
					</div>
				</div>';
		
	
	  }
	}
	
	echo '<input type="hidden" size="20" name="conf_file" class="textinput" value="' . $type . '" maxlength="100" maxsize="100" />
	<input type="hidden" size="20" name="conf_arr_name" class="textinput" value="' . $varName . '" maxlength="100" maxsize="100" />
	<div align="right" style="padding-bottom:5px;"><input type="submit" class="btn btn-success" value="' . _SAVE . '" /></div>
	</form>';
	$adminTpl->close();
	$adminTpl->admin_foot();
	}
}