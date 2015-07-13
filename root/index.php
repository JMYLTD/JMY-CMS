<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   25.02.2015
*/
 
 
if (!defined('ACCESS') && !$core->auth->isAdmin && $url[0] !== ADMIN) {
    header('Location: /');
	exit;
}

define('ADMIN_ACCESS', true);
define('COOKIE_VISIT', md5(getenv("REMOTE_ADDR")) . '-admin_visit');
define('SESS_AUTH', md5(getenv("REMOTE_ADDR")) . '-auth');
define('SESS_COUNT', md5(getenv("REMOTE_ADDR")) . '-counter');
session_start();

require ROOT . 'etc/admin.config.php';
require ROOT . 'root/functions.php';
require ROOT . 'root/ajax_funcs.php';
require ROOT . 'root/admin_tpl.class.php';

$core->loadLangFile('root/langs/{lang}.navigation.php');

if(!empty($admin_conf['ipaccess']))
{
	$IPs_arr = explode("\n", $admin_conf['ipaccess']);
	$parse_ip = @ip2long(getRealIpAddr()); 
	foreach($IPs_arr as $IPs) 
	{ 
		$IPs = explode('|', $IPs);
		if(count($IPs) == 2)
		{
			if($parse_ip <= @ip2long($IPs[0]) && $parse_ip <= @ip2long($IPs[1]))
			{
				$_SESSION[SESS_AUTH] = null;
				$_SESSION[SESS_COUNT] = 0;
				setcookie(COOKIE_AUTH, '', time(), '/');
				setcookie(COOKIE_PAUSE, '', time(), '/');
				location();
			}
		}
	}
}

function admin_main() 
{
	global $adminTpl,  $db, $core, $config;
	$last_visit = time();
	$last_ip = $_SERVER['REMOTE_ADDR'];
	$query = '';
	
	if(!isset($_COOKIE[COOKIE_VISIT]) && !isset($_SESSION[SESS_AUTH])) 
	{
		if($db->query("INSERT INTO " . DB_PREFIX . "_logs VALUES ('" . time() . "', '" . filter($_SERVER['REMOTE_ADDR']) . "', '" . $core->auth->user_id . "', '" . str_replace('{nick}', $core->auth->user_info['nick'], _LOG_WRITE) . "', '1')")) 
		{
			setcookie(COOKIE_VISIT, time(), time() + 86400, '/');
		}		
		$last_visit = time();
		$last_ip = $_SERVER['REMOTE_ADDR'];
	} 
	else 
	{
		$query = $db->query("SELECT * FROM " . DB_PREFIX . "_logs ORDER BY time DESC");
		$i = 0;
		while($log = $db->getRow($query)) {
			$i++;			
			if($i == 1)
			{
				$last_visit = $log['time'];
				$last_ip = $log['ip'];
			}			
			$logs[$log['level']][$log['uid']][$log['time']] = $log['ip'] . '-' . $log['history'];
		}
	}	
	$adminTpl->admin_head(_MAIN_PAGE);
	echo '<div class="row mg-b">
						<div class="col-xs-6">
							<h3 class="no-margin">' ._MAIN_MAIN . '</h3>
							<small>version {VERSION}</small>
						</div>
						<div class="col-xs-6 text-right">
<a href="javascript:;" class="fa fa-cog pull-right pd-sm toggle-sidebar" data-toggle="off-canvas" data-move="rtl"></a>
</div>
					</div>
					
					<div class="row">
						<div class="col-md-3 col-sm-6 col-xs-12">
							<section style="cursor:pointer" class="panel" onclick="location.href=\'{ADMIN}/module/news/add\';">
								<div class="panel-body">
									<div class="circle-icon">
										<img src="usr/tpl/admin/assets/images/new.png" class="img-circle" alt="">
									</div>
									<div>
										<h3 class="no-margin">'._MAIN_CREATE.'</h3>'._MAIN_CREATE_DESC.'
									</div>
								</div>
							</section>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<section style="cursor:pointer" class="panel" onclick="location.href=\'{ADMIN}/module/news/\';">
								<div class="panel-body">
									<div class="circle-icon">
										<img src="usr/tpl/admin/assets/images/edit.png" class="img-circle" alt="">
									</div>
									<div>
										<h3 class="no-margin">'._MAIN_LIST.'</h3>'._MAIN_LIST_DESC.'
									</div>
								</div>
							</section>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<section style="cursor:pointer" class="panel" onclick="location.href=\'{ADMIN}/module/content\';">
								<div class="panel-body">
									<div class="circle-icon">
										<img src="usr/tpl/admin/assets/images/page.png" class="img-circle" alt="">
									</div>
									<div>
										<h3 class="no-margin">'._MAIN_STATIC.'</h3>'._MAIN_STATIC_DESC.'
									</div>
								</div>
							</section>
						</div>
						<div class="col-md-3 col-sm-6 col-xs-12">
							<section style="cursor:pointer" class="panel" onclick="location.href=\'administration/config\';">
								<div class="panel-body">
									<div class="circle-icon">
										<img src="usr/tpl/admin/assets/images/settings.png" class="img-circle" alt="">
									</div>
									<div>
										<h3 class="no-margin">'._MAIN_CONF.'</h3>'._MAIN_CONF_DESC.'
									</div>
								</div>
							</section>
						</div>
					</div>';	
	foreach(glob(ROOT.'usr/modules/*/admin/moderation.php') as $listed) require_once($listed);
	unset($component_array);
	if ($config['dbCache']== 1 OR $config['cache'] == 1) 
	{
		echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>' . _CACHE . '</b></div><div class="panel-body"><div class="switcher-content"><p>' . _MAIN_CACHE_INFO . '<br><div style="float:right"><small>[ <a href="index.php?url='.ADMIN.'/do/clearCache">'. _MAIN_CLEARCACHE .'</a> ]</small></div></p></div></div></section></div></div>';
	}		
	echo '<div class="row">
		<div class="col-lg-12">
			<section>
			<ul id="myTab" class="nav nav-tabs">				
				<li class="pull-right">
					<a href="#profile2" data-toggle="tab">'._MAIN_LAST_COMM.'</a>
				</li>
				<li class="active pull-right">
					<a href="#home2" data-toggle="tab">'._MAIN_LAST_USER.'</a>
				</li>
			</ul>
			<section class="panel">
				<div class="panel-body no-padding">
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane active" id="home2">';	
	$query = $db->query("SELECT c.*, u.nick, u.group, u.last_visit FROM ".DB_PREFIX."_comments as c LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on (c.uid=u.id) ORDER BY date DESC LIMIT 5");
	if($db->numRows($query) > 0) 
	{
	echo '<div class="panel-heading">'._MAIN_LAST_USER.'</div>
							<table class="table table-striped no-margin">
								<thead>
									<tr>
										<th><span class="pd-l-sm"></span>ID</th>
										<th class="col-md-4">' . _COMMENT . '</th>
										<th class="col-md-1">' . _MODULE . '</th>
										<th class="col-md-2">' . _DATE . '</th>
										<th class="col-md-2">' . _USER . '</th>
										<th class="col-md-1">' . _LINKS . '</th>
										<th class="col-md-4">' . _ACTIONS . '</th>
									</tr>
								</thead>
								<tbody>';
		while($commment = $db->getRow($query)) 
		{
			$tt = str(htmlspecialchars(strip_tags($commment['text'])), 30);
			$active = ($commment['status'] == 1) ? '<a href="javascript:void(0)" onclick="adminCommentStatus(\'' . $commment['id'] . '\', 0);" title="' . _DEACTIVATE . '"><button type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DEACTIVATE . '">D</button></a>' : '<a href="javascript:void(0)" onclick="adminCommentStatus(\'' . $commment['id'] . '\', 1);" title="' . _ACTIVATE . '"><button type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _ACTIVATE . '">A</button></a>';
			echo '
			<tr>
				<td><span class="pd-l-sm"></span>' . $commment['id'] . '</td>
				<td>' . (($tt != '') ? $tt : '<font color="red">'._NO_TEXT.'</font>') . '</td>
				<td>' . commentLink($commment['module'], $commment['post_id']) . '</td>
				<td>' . formatDate($commment['date'], true) . '</td>
				<td>' . (($commment['uid'] != 0) ? '<a href="profile' . $commment['nick'] . '" title="' . $commment['nick'] . '">' . $commment['nick'] . '</a>' : $commment['gname']) . '</td>
				<td>' . (eregStrt('href', $commment['text']) || eregStrt('\[url', $commment['text']) ? "<font color=\"red\">"._YES."</font>" : "<font color=\"green\">"._NO."</font>") . '</td>
				<td>
				'.$active.'
				<button onclick="location.href=\'{ADMIN}/comments/edit/'.$commment['id'].'\';" type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="'._EDIT.'">E</button>
				<button onclick="location.href=\'{ADMIN}/comments/delete/'.$commment['id'].'\';" type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="'._DELETE.'">D</button>				
				</td>
				</tr>
				';
				
		}
		echo '</tbody></table>';
	} 
	else 
	{
		echo '<div class="panel-heading">'._MAIN_EMPTY_COMM.'</div>';
	}
	echo '</div><div class="tab-pane" id="profile2">';		
	$query = $db->query('SELECT u.*, g.name FROM `' . USER_DB . '`.`' . USER_PREFIX . '_users` as u LEFT JOIN `' . USER_DB . '`.`' . USER_PREFIX . '_groups` as g on(u.group = g.id) ORDER BY regdate DESC LIMIT 5');
	if($db->numRows($query) > 0) 
	{
	echo '<div class="panel-heading">'._MAIN_LAST_COMM.'</div>
							<table class="table table-striped no-margin">
								<thead>
									<tr>
										<th><span class="pd-l-sm"></span>ID</th>
										<th class="col-md-2">' . _NICK . '</th>
										<th class="col-md-2">' . _GROUP . '</th>
										<th class="col-md-3">' . _REGDATE . '</th>
										<th class="col-md-3">' . _LASTDATE . '</th>										
										<th class="col-md-4">' . _ACTIONS . '</th>
									</tr>
								</thead>
								<tbody>';
		while($user = $db->getRow($query)) 
		{
			echo '			
			<tr>
				<td><span class="pd-l-sm"></span>' . $user['id'] . '</td>
				<td> <a href="profile/' . $user['nick'] . '">' . $user['nick'] .'</a></td>
				<td>' . $user['name'] . '</td>
				<td>' . formatDate($user['regdate'], true) . '</td>
				<td>' . formatDate($user['last_visit']) . '</td>
				<td>
				<a href="administration/user/edit/'.$user['id'].'">
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Редактировать">E</button>
				</a>
				<a href="administration/user/ban/'.$user['id'].'" onclick="return getConfirm(\'Забанить пользователя?\')"><button type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Забанить">B</button></a>				
				<a href="administration//user/delete/'.$user['id'].'" onclick="return getConfirm(\'Удалить пользователя?\')">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Удалить">X</button>
				</a>
				</td>
				</tr>';
		}
	echo "</tbody></table>";
	} 
	echo "</div></div></div></section></section></div></div>";
	echo '<div class="row">
			<div class="col-lg-8">			
				<section>
					<ul id="myTab" class="nav nav-tabs">
						<li class="pull-right">
							<a href="#false2" data-toggle="tab">' . _FALSELOG . '</a>
						</li>
						<li class="active pull-right">
							<a href="#log2" data-toggle="tab">'. _LASTLOG .'</a>
						</li>
					</ul>
				<section class="panel">
					<div class="panel-body no-padding">
						<div id="myTabContent" class="tab-content">
							<div class="tab-pane active" id="log2">';
	if(isset($logs[1]))
	{
		$log1 = 0;
		echo '<div class="panel-heading">'. _LASTLOG .'</div>
							<table class="table table-striped no-margin">
								<thead>
									<tr>										
										<th class="col-md-2"><span class="pd-l-sm"></span>' . _NICK . '</th>
										<th class="col-md-2">' . _GROUP . '</th>
										<th class="col-md-2">' . _REGDATE . '</th>										
									</tr>
								</thead>
								<tbody>';
		foreach($logs[1] as $uid => $arr) 
		{
			foreach($arr as $time => $info) 
			{
				$log1++;
				if($log1 <= 7) 
				{			
					$log_true = explode('-', $info);
					echo '
					<tr>		
						<td><span class="pd-l-sm"></span>' . $log_true[1] .'</td>
						<td>' . $log_true[0] . '</td>
						<td>' . formatDate($time, true) . '</td>
					</tr>';
				}
			}
			
			
		}
			echo '</tbody></table>';
			echo '<br><div align="right"><a href="/index.php?url={ADMIN}/logs/clear" class="btn btn-warning btn-xs">' . _CLEAN . '</a><span class="pd-l-sm"></span></div><br>';
	} 
	else 
	{
		echo '<div class="panel-heading">Информация отсутствует.</div>';
	}	
	echo '</div><div class="tab-pane" id="false2">';   
   
   if(isset($logs[2])) 
   {
		$log2 = 0;
		echo '<div class="panel-heading">' . _FALSELOG . '/div>
							<table class="table table-striped no-margin">
								<thead>
									<tr>										
										<th class="col-md-2"><span class="pd-l-sm"></span>' . _NICK . '</th>
										<th class="col-md-2">' . _GROUP . '</th>
										<th class="col-md-2">' . _REGDATE . '</th>										
									</tr>
								</thead>
								<tbody>';
		foreach($logs[2] as $uid => $arr) 
		{
			foreach($arr as $time => $info)
			{
				$log2++;
				if($log2 <= 7)
				{			
					$log_true = explode('-', $info);
					echo '
					<tr>			
						<td><span class="pd-l-sm"></span>' . $log_true[1] .'</td>
						<td>' . $log_true[0] . '</td>
						<td>' . formatDate($time, true) . '</td>
					</tr>';
				}
			}
			
			
		}
			echo "</tbody></table>";
			echo '<br><div align="right"><a href="/index.php?url={ADMIN}/logs/clear" class="btn btn-warning btn-xs">' . _CLEAN . '</a><span class="pd-l-sm"></span></div><br>';
	} 
	else 
	{	
		echo '<div class="panel-heading">Информация отсутствует.</div>';
	}	
	echo '</div></div></div></section></section></div>';
	list($weekUsrs) = $db->fetchRow($db->query("SELECT Count(id) FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE regdate > '" . (time()-604900) . "'"));
	list($weekComm) = $db->fetchRow($db->query("SELECT Count(id) FROM " . DB_PREFIX . "_comments WHERE date > '" . (time()-604900) . "'"));
	echo '<div class="col-lg-4">
			<section class="panel">
				<div class="panel-heading no-border">
					<b>Статистика</b>
				</div>
				<div class="panel-body">
					<div class="switcher-content">
						<p>
							<b>Комментариев на этой неделе:</b> ' . $weekComm . '<br>
							<b>Пользователей на этой неделе:</b> ' . $weekUsrs . '
						</p>
					</div>
				</div>
			</section>';
	echo "</div>";	
	echo "</div></div>";
	$adminTpl->admin_foot($last_visit, $last_ip);
	
}
function init_login() 
{
global $adminTpl, $admin_conf, $core;
	if($core->auth->isUser && $core->auth->isAdmin)
	{
		if(isset($_SESSION[SESS_AUTH]) && $_SESSION[SESS_AUTH] == 'ok' OR $admin_conf['sessions'] == 0)
		{
			return false;
		} 
		else 
		{
			return true;
		}
	}
	else
	{
		return true;
	}
}

function login() 
{
global $adminTpl, $core, $config, $db, $admin_conf;

	$adminTpl->sep = '';
	if(isset($_POST['nick']))
	{
		$nick = filter($_POST['nick'], 'nick');
		$password = md5(md5($_POST['password']));
		if(!empty($nick) && !empty($_POST['password']))
		{
			$access = $db->getRow($db->query("SELECT id, password, tail FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE `nick` = '" . $db->safesql($nick) . "' AND `group`='1'"));
			$no_head = true;
			
			if (md5(mb_substr($password, 0, -mb_strlen($access['tail'])) . $access['tail']) == $access['password']) 
			{
				if($core->auth->isUser && $core->auth->isAdmin)
				{
					$_SESSION[SESS_AUTH] = 'ok';
				}
				else
				{
					$_SESSION[SESS_AUTH] = 'ok';
					$newHash = md5(@$_SERVER['HTTP_USER_AGENT'].$config['uniqKey']);
					setcookie(COOKIE_AUTH, engine_encode(serialize(array('id' => $access['id'], 'nick' => $nick, 'password' => md5(mb_substr($password, 0, -mb_strlen($access['tail'])) . $access['tail']), 'hash' => $newHash))), time() + COOKIE_TIME, '/');
				}
				
				if(isset($_SESSION[SESS_AUTH])) {
					$db->query("INSERT INTO " . DB_PREFIX . "_logs VALUES ('" . time() . "', '" . filter($_SERVER['REMOTE_ADDR'], 'ip') . "', '" . $core->auth->user_id . "', '" . str_replace('[nick]', $nick, _GOOD_LOGIN) . "', '1')");
					if(eregStrt(ADMIN, $_SERVER['HTTP_REFERER']))
					{
						location($_SERVER['HTTP_REFERER']);
					}
					else
					{
						location(ADMIN);
					}
				}
			}
			else
			{
				if (!isset($_SESSION[SESS_COUNT])) 
				{
					$_SESSION[SESS_COUNT] = 0;
				}
				
				$counter = $_SESSION[SESS_COUNT]++;
				$turns = 5-$counter;
				$adminTpl->loadFile('login');
				
				if($counter == 3) 
				{
					$db->query("INSERT INTO " . DB_PREFIX . "_logs VALUES ('" . time() . "', '" . filter($_SERVER['REMOTE_ADDR'], 'ip') . "', '" . $core->auth->user_id . "', '" . str_replace(array('[nick]', '[pass]'), array($nick, str($_POST['password'], 4)), _BAD_LOGIN) . "', '2')");
				}
				
				if($turns <= 0) 
				{
					$adminTpl->setVar('STOP', '<div id="stop">' . _NO_TURNS . '</div>');
				} 
				else 
				{
					$adminTpl->setVar('STOP', '<div id="stop">' . str_replace('{turns}', $turns, _FALSE_TURN) . '</div>');
				}
				
				$adminTpl->setVar('URL', $config['url']);
				$adminTpl->setVar('ADM_THEME', 'usr/tpl/admin');
				$adminTpl->end();
			}
		}
		else
		{
			$adminTpl->loadFile('login');
			$adminTpl->setVar('STOP', '<div id="stop">' . _EMPTY_LOGIN . '</div>');
			$adminTpl->setVar('URL', $config['url']);
			$adminTpl->setVar('ADM_THEME', 'usr/tpl/admin');
			$adminTpl->end();
		}
	}
	else
	{
		$adminTpl->loadFile('login');
		$adminTpl->setVar('STOP', '');
		$adminTpl->setVar('URL', $config['url']);
		$adminTpl->setVar('ADM_THEME', 'usr/tpl/admin');
		$adminTpl->end();
	}
}

if(init_login()) 
{
	login();
} 
else 
{
	require ROOT . 'root/list.php';	
	switch(isset($url[1]) ? $url[1] : null) {
		default:
			if(isset($url[1]))
			{
				if(isset($component_array[$url[1]]) OR isset($services_array[$url[1]]))
				{
					if(checkAdmControl($url[1]))
					{
						require ROOT . 'root/modules/' . $url[1] . '.admin.php';
					}
					else
					{
						noadmAccess();
					}
				}
				else
				{
					if(checkAdmControl('index'))
					{
						admin_main();
					}
					else
					{
						noadmAccess();
					}
				}
			}
			else
			{
				if(checkAdmControl('index'))
				{
					admin_main();
				}
				else
				{
					noadmAccess();
				}
			}
		break;
		
		case 'do':
			$switch = filter($url[2]);
			switch($switch) {
				case 'logout':
					$_SESSION[SESS_AUTH] = null;
					$_SESSION[SESS_COUNT] = 0;
					$core->auth->logout();
					header('Location: /');
					break;
				
				case 'tic':
					echo yandex_tic($_SERVER['HTTP_HOST']);
					break;
				
				case 'pr':
					echo getPageRank($_SERVER['HTTP_HOST']);
					break;					
					
				case 'clearCache':
					if(checkAdmControl('index'))
					{
						ajaxInit();
						full_rmdir(ROOT . 'tmp/mysql');
						full_rmdir(ROOT . 'tmp/cache');
						@mkdir(ROOT . 'tmp/mysql', 0777);
						@mkdir(ROOT . 'tmp/cache', 0777);
						echo _CACHE_CLEANED;
						header('Location: /' . ADMIN);
					}
					break;
				
				
			}
		break;
		
		case 'module':
			define('ADMIN_SWITCH', true);
			$mod = $url[2];
			if(file_exists(ROOT . 'usr/modules/' . $mod . '/admin/index.php')) 
			{
				if(checkAdmControl($mod))
				{
					require ROOT . 'usr/modules/' . $mod . '/admin/index.php';
				}
				else
				{
					noadmAccess();
				}
			} 
			else 
			{
				header('Location: /' . ADMIN);
			}
			break;
		
		case 'logs':
		global $adminTpl,  $db;
			ajaxInit();
			$type = $url[2];
			$num = isset($url[3]) ? intval($url[3]) : '';
			
			switch($type) 
			{
				case "clear":
					$db->query("TRUNCATE TABLE " . DB_PREFIX . "_logs");
					echo _TABLECLEANED;
					header('Location: /' . ADMIN);
					break;
			}
			break;
				
		case 'addition':
			$type = $url[2];
			switch($type) 
			{
				case "tic":
					echo yandex_tic('http://'.$_SERVER['HTTP_HOST']);
					break;
			}
		break;			
	}
}