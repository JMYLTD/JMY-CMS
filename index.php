<?php
//a:9:{s:4:"lang";s:2:"en";s:9:"auth_pass";s:32:"d41d8cd98f00b204e9800998ecf8427e";s:8:"quota_mb";i:0;s:17:"upload_ext_filter";a:0:{}s:19:"download_ext_filter";a:0:{}s:15:"error_reporting";i:1;s:7:"fm_root";s:0:"";s:17:"cookie_cache_time";i:2592000;s:7:"version";s:5:"0.9.8";}
/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/


define('ACCESS', true);
define('TIMER', microtime(1));
define('ROOT', dirname(__FILE__) . '/');
define('PLUGINS', dirname(__FILE__) . '/usr/plugins/');
define('COOKIE_AUTH', 'auth_jmy');
define('COOKIE_PAUSE', 'pause_jmy');
define('PAUSE_TIME', 120);
define('VERSION_ID', '1.7'); 
define('COOKIE_TIME', 2592000);
define('ADMIN', 'administration');
define('HACK_SQL', '/SELECT|INSERT|ALTER|DROP|UNION|OUTFILE|WHERE/i');
define('DEBUG', true);
define('INDEX', isset($_GET['url']) ? false : true);
@ini_set('allow_url_fopen', 1);
header('Content-type: text/html; charset=utf-8');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0'


error_reporting(E_ALL|E_STRICT);
ini_set('display_errors', 1);

mb_internal_encoding("UTF-8");

require ROOT . 'etc/global.config.php';
require ROOT . 'etc/admin.config.php';
require ROOT . 'etc/security.config.php';
require ROOT . 'etc/files.config.php';
require ROOT . 'etc/cache.config.php';
require ROOT . 'etc/smiles.config.php';
require ROOT . 'etc/user.config.php';
require ROOT . 'etc/log.config.php';
require ROOT . 'lib/php_funcs.php';
require ROOT . 'lib/global.php';


if(isset($_COOKIE['theme']))
{
	if(file_exists(ROOT . 'usr/tpl/' . $_COOKIE['theme'] . '/index.tpl'))
		$config['tpl'] = filter($_COOKIE['theme']);
	else
		setcookie('theme', false, time(), '/');
		
}

if($config['timezone'] !== "")
{
	date_default_timezone_set($config['timezone']);
}

if($config['gzip'] && !DEBUG) 
{
	ob_start("ob_gzhandler");
}

function __autoload($class_name) 
{
	$class_path = ROOT.'boot/sub_classes/'.mb_strtolower($class_name).'.class.php';
	if (file_exists($class_path)) require_once($class_path);
}

$cache = new cache;

require ROOT . 'boot/db/' . $config['dbType'] . '.db.php';
require ROOT . 'boot/db' . (($config['dbCache'] == 1) ? '_cache' : '') . '.class.php';
require ROOT . 'boot/auth.class.php';
require ROOT . 'boot/template.class.php';
require ROOT . 'boot/core.class.php';
require ROOT . 'boot/loader.php';


if(DEBUG && $url[0] != 'ajax' && !isset($_SERVER['HTTP_AJAX_ENGINE'])) 
{
	echo "\n<!-- Time generate: " . mb_substr(microtime(1) - TIMER, 0, 5) . " seconds -->\n";
	echo "<!-- Time queries: " . mb_substr($db->timeQueries, 0, 5) . " seconds -->\n";
	echo "<!-- Time tpls: " . mb_substr($core->tpl->time_compile, 0, 5) . " seconds -->\n";
	echo "<!-- Num queries: " . $db->numQueries . " queries -->";
}