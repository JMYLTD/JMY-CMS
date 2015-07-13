<?php
define('ACCESS', true);
define('VERSION_ID', '1.6'); 
define('TIMER', microtime(1));
define('ROOT', dirname(__FILE__) . '/');
define('PLUGINS', dirname(__FILE__) . '/usr/plugins/');
define('COOKIE_AUTH', 'auth_jmy');
define('COOKIE_PAUSE', 'pause_jmy');
define('AJAX', true);
define('PAUSE_TIME', 120);
define('COOKIE_TIME', 2592000);
define('ADMIN', 'administration');
define('HACK_SQL', '/SELECT|INSERT|ALTER|DROP|UNION|OUTFILE|WHERE/i');
define('DENIED_HTML', '/<.*?(script|meta|body|object|iframe|frame|applet|style|form|img|onmouseover).*?>/i');
define('DEBUG', false);
define('INDEX', isset($_GET['url']) ? false : true);

function __autoload($class_name) 
{
	$class_path = ROOT.'boot/sub_classes/'.mb_strtolower($class_name).'.class.php';
	if (file_exists($class_path)) require_once($class_path);
}


require ROOT . 'etc/global.config.php';
require ROOT . 'etc/admin.config.php';
require ROOT . 'etc/security.config.php';
require ROOT . 'etc/files.config.php';
require ROOT . 'etc/smiles.config.php';
require ROOT . 'etc/user.config.php';
require ROOT . 'lib/php_funcs.php';
require ROOT . 'lib/global.php';
require ROOT . 'root/ajax_funcs.php';
require ROOT . 'lib/for_ajax.php';
require ROOT . 'boot/db/' . $config['dbType'] . '.db.php';
require ROOT . 'boot/db' . (($config['dbCache'] == 1) ? '_cache' : '') . '.class.php';
require ROOT . 'boot/auth.class.php';
require ROOT . 'boot/template.class.php';

$cache = new cache;

require ROOT . 'boot/core.class.php';
$core->initCore();
$core->LoadLang(false, true);

header('Content-type: text/plain; charset=utf-8');

$do = $_GET['do'];

$GLOBALS['url'][0] = 'ajax';

$tempLink = explode('/', $do);
		
for ($i = 0, $max = count($tempLink); $i < $max; $i++) 
{
    if ($tempLink[$i] == '') 
	{
        continue;
    } 
	else 
	{
		$GLOBALS['url'][] = filter($tempLink[$i], 'url');
    }
}

$op = isset($url[2]) ? $url[2] : 0;
$do = isset($url[3]) ? $url[3] : 0;
$allowFuncs = array('draw_rating', 'set_rating', 'fast_edit', 'fast_save', 'add_comment', 'show_comments', 'checkCommentGuest', 'commentDeleteAjax', 'commentPage', 'comment_savea', 'commentEditAjax', 'commentEditAjaxSave', 'check_login', 'cal', 'online', 'searchList', 'ajaxPoll', 'addCarma', 'editTitle', 'fileList', 'loginList', 'getTranslit', 'getCatByModule', 'blockStatus', 'userDeleteBlock', 'fullAjax', 'blockList', 'setBlockStatus', 'setCommentStatus', 'deleteBlock', 'moveUp', 'moveDown', 'captcha', 'carmaHistory', 'genRating', 'commentSubscribe', 'friendsAjax', 'vote', 'inputTags', 'getPreview', 'carma_vote');

switch(isset($url[1]) ? $url[1] : null) 
{
	default:
	
		if(in_array($url[1], $allowFuncs))
		{
			$url[1]();
		}
		else
		{
			echo $url[1];
		}
		
		break;
	
	case 'calendar':
		cal($op);
		break;
		
	case 'version':
		version_check();
		break;
	
	case 'captcha_reload':
		echo captcha_image(false);
		break;			

	case 'poll':
		ajaxPoll();
		break;			
		
	case 'smiles':
		global $config;
		require_once(ROOT . 'etc/smiles.cofnig.php');
		echo
		'<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
		<base href="' . $config['url'] . '/" />
		</head>
		<body bgcolor="white" leftmargin="0" rightmargin="0" marginwidth="0" marginheight="0" topmargin="0" bottommargin="0">';
		foreach($smiles as $smile => $info)
		{
			echo '<img src="' . $info['url'] . '" border="0" title="' . $info['title'] . '" onclick="javascript:insertBB(\'' . $smile . '\', \'' . $op . '\')" class="_pointer">' . "\n";
		}
		echo '</body></html>';
		break;
}

if(is_array($core->tpl->headerIncludes))
{
	array_unique($core->tpl->headerIncludes);
								
	foreach($core->tpl->headerIncludes as $metas)
	{
		if($metas)
		{
			echo $metas;
		}		
	}
}
//echo formatfilesize(memory_get_peak_usage());