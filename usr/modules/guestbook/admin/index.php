<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @edit 	   03.02.2015
*/

if (!defined('ADMIN_SWITCH')) {
    header('Location: /');
    exit;
}

function content_main() 
{
global $adminTpl, $core, $db, $admin_conf;
	$adminTpl->admin_head(_MODULES .' | '. _G_GUESTBOOK);
	$page = init_page();
	$limit = ($page-1)*$admin_conf['num'];
	echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _LIST . '</b>
					</div>';
	$query = $db->query("SELECT * FROM ".DB_PREFIX."_guestbook ORDER BY id ASC LIMIT " . $limit . ", " . $admin_conf['num'] . "");
	if($db->numRows($query) > 0)
	{	
		echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{MOD_LINK}/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-2">' . _G_COM . '</th>
									<th class="col-md-2">' . _BASE_NAME . '</th>
									<th class="col-md-1">' . _DATE . '</th>
									<th class="col-md-3">' . _G_REPLY .'</th>
									<th class="col-md-3">' . _ACTIONS . '</th>
									<th class="col-md-4"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';
		while($guestbook = $db->getRow($query)) 
		{			
			echo '
				<tr>
				<td><span class="pd-l-sm"></span>' . $guestbook['id'] . '</td>
				<td>' .strip_tags(str($guestbook['comment'], 40)) . '</td>
				<td>' .str($guestbook['name'], 20) . '</td>
				<td>' . formatDate($guestbook['date'], true) . '</td>
				<td>' . (!empty($guestbook['reply']) ? str($guestbook['reply'], 40) : _NO) . '</td>
				<td>				
				<a href="{MOD_LINK}/reply/' . $guestbook['id'] . '">
				<button type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _G_REPLY_EDIT .'">R</button>
				</a>
				<a href="{MOD_LINK}/edit/' . $guestbook['id'] . '">
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
				</a>
				<a href="{MOD_LINK}/delete/' . $guestbook['id'] . '" onClick="return getConfirm(\'' . _G_DEL .' - ' .str($guestbook['comment'], 15) . '?\')">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
				</a>
				</td>
				<td><input type="checkbox" name="checks[]" value="' . $guestbook['id'] . '"><span class="pd-l-sm"></span></td>
			</tr>';	
		}
		
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>';		
	echo '
	<div align="right">
	<table>
	<tr>
	<td valign="top">
	<input name="submit" type="submit" class="btn btn btn-danger" id="sub" value="' . _DELETE . '" /><span class="pd-l-sm"></span>
	</td>
	</tr>
	</table>
	<br>	
	</div>
	</form>
	</div>';		
	} 	
	else 
	{
		echo '<div class="panel-heading">' . _G_EMPTY . '</div></div>';
	}
	echo'</section></div></div>';
	$all_query = $db->query("SELECT * FROM " . DB_PREFIX . "_guestbook ");
	$all = $db->numRows($all_query);
	$adminTpl->pages($page, $admin_conf['num'], $all, ADMIN.'/module/guestbook/{page}');
	
	
	$adminTpl->admin_foot();
} 

function content_add($id = null) 
{
global $adminTpl, $core, $db, $core, $config;		
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_guestbook WHERE id = '" . $id . "' ");
		$guestbook = $db->getRow($query);
		$id = $guestbook['id']; 
		$name = $guestbook['name']; 
		$email = $guestbook['email']; 
		$website = $guestbook['website']; 
		$gender = $guestbook['gender']; 
		$text = $guestbook['comment']; 	
	
	$adminTpl->admin_head( _MODULES .' | '. _G_EDIT );	
	echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. _G_EDIT .'</b></div><div class="panel-body"><div class="switcher-content">
			<form action="{MOD_LINK}/save" onsubmit="return caa(false);" method="post" name="content" role="form" class="form-horizontal parsley-form" data-parsley-validate>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_NAME .':</label>
					<div class="col-sm-4">
						<input type="text" name="name" value="' . $name . '" class="form-control" id="name"  data-parsley-required="true" data-parsley-trigger="change">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_EMAIL .':</label>
					<div class="col-sm-4">
						<input type="text" name="email" value="'. $email .'" class="form-control" id="email"  data-parsley-required="true" data-parsley-trigger="change">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_SITE .':</label>
					<div class="col-sm-4">
						<input type="text" name="website"  value="' . $website . '" class="form-control" id="website"  data-parsley-trigger="change">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_GENDER .':</label>
					<div class="col-sm-4">
						<select name="gender" style="width:auto;">
							<option value="1" ' . ($gender == 1 ? 'selected' : '') . ">". _BASE_GENDER_MALE .'</option>
							<option value="2" ' . ($gender == 2 ? 'selected' : '') . ">". _BASE_GENDER_FEMALE .'</option>
						</select>
					</div>
				</div>';	
	echo '</div></div></section></div></div>';
	echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. _TEXT .'</b></div><div class="panel-body"><div class="switcher-content">';	
	echo adminArea('text', $text, 10, 'textarea', false, true);
	echo '<input name="id" value="' . $id . '" type="hidden" />
			<input name="act" value="edit" type="hidden" />
	<br><input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="' . _UPDATE . '" />';		
		echo '</form>';
		echo '</div></div>
		</section></div></div>';
		$adminTpl->admin_foot();
}

function reply_edit($id = null) 
{
global $adminTpl, $core, $db, $core, $config;
	
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_guestbook WHERE id = '" . $id . "'");
		$guestbook = $db->getRow($query);
		$id = $guestbook['id']; 
		$name = $guestbook['name']; 
		$email = $guestbook['email']; 		
		$website = (!empty($guestbook['website']) ? $guestbook['website'] : _NO); 
		$gender = $guestbook['gender']; 
		$text = $guestbook['comment']; 	
		$reply = $guestbook['reply']; 	
	
	$adminTpl->admin_head( _MODULES .' | '. _G_EDIT );	
	echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. _G_INFO .'</b></div><div class="panel-body"><div class="switcher-content">
			<form action="{MOD_LINK}/save" onsubmit="return caa(false);" method="post" name="content" role="form" class="form-horizontal parsley-form" data-parsley-validate>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_NAME .':</label>
					<div class="col-sm-4">
						<p class="form-control-static">' . $name . '</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_EMAIL .':</label>
					<div class="col-sm-4">
						<p class="form-control-static">'. $email .'</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_SITE .':</label>
					<div class="col-sm-4">
						<p class="form-control-static">' . $website . '</p>
					</div>
				</div>
				
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_GENDER .':</label>
					<div class="col-sm-4">
						<p class="form-control-static">' . ($gender == 1 ? _BASE_GENDER_MALE : _BASE_GENDER_FEMALE) . '</p>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _G_TEXT .':</label>
					<div class="col-sm-4">
						<p class="form-control-static">' . $text . '</p>
					</div>
				</div>';	
	echo '</div></div></section></div></div>';
	echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. _G_REPLY .'</b></div><div class="panel-body"><div class="switcher-content">';	
	echo adminArea('reply', $reply, 10, 'textarea', false, true);
	echo '<input name="id" value="' . $id . '" type="hidden" />
			<input name="act" value="reply" type="hidden" />
	<br><input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'. (!empty($reply) ? _UPDATE : _ADD) .'" />';		
		echo '</form>';
		echo '</div></div>
		</section></div></div>';
		$adminTpl->admin_foot();
}

function content_save() 
{
require (ROOT.'etc/guestbook.config.php');
global $adminTpl, $core, $db, $cats, $groupss, $config;
		$act = isset($_POST['act']) ? filter($_POST['act']) : '';		
		$id = isset($_POST['id']) ? intval($_POST['id']) : '';	
		if($id != 0) 
		{
		if((!empty($act) && $act=='edit'))
			{				
				$gender = isset($_POST['gender']) ? intval($_POST['gender']) : '';
				$website = isset($_POST['website']) ? filter($_POST['website']) : '';
				$text = isset($_POST['text']) ? filter($_POST['text']) : '';
				$name = isset($_POST['name']) ?  filter($_POST['name']) : '';
				$email = isset($_POST['email']) ?  filter($_POST['email']) : '';
				$adminTpl->admin_head(_COM_COM . ' | ' ._COM_EDIT);
				if(!empty($text) && !empty($name) && !empty($email) && !empty($gender))
				{	
					$db->query("UPDATE `" . DB_PREFIX . "_guestbook` SET `website` = '" . $db->safesql(processText($website)) . "', `comment` = '" . $db->safesql(parseBB(processText($text), $id, true)) . "', `email` = '" . $email . "', `name` = '" . $db->safesql(processText($name)) . "', `gender` = '" . $gender . "' WHERE `id` =" . $id . ";");				
					$adminTpl->info(_COM_UPDATE);					
				}
				else
				{					
					$adminTpl->info(_BASE_ERROR_0, 'error');					
				}
				$adminTpl->admin_foot();				
			}
		else
			{	
				$reply = isset($_POST['reply']) ?  filter($_POST['reply']) : '';
				$adminTpl->admin_head(_COM_COM . ' | ' ._COM_EDIT);			
				if ($guestbook_conf['reply_mail']==1) 
				{
					sendMail($config['support_mail'], 'Вам ответили в гостевой книге - '.$config['name'], $reply.'<br />'.$config['url'].'/guestbook');
				}
				$db->query("UPDATE `" . DB_PREFIX . "_guestbook` SET `reply` = '" . $db->safesql(parseBB(processText($reply), $id, true)) . "' WHERE `id` =" . $id . ";");				
				$adminTpl->info(_G_REPLY_UPDATE);			
				$adminTpl->admin_foot();				
			}
		}
		else
		{
			location(ADMIN);
		}	
		
}

function delete($id) {
global $adminTpl, $db;
	$db->query("DELETE FROM `" . DB_PREFIX . "_guestbook` WHERE `id` = " . $id . " LIMIT 1");	
}

switch(isset($url[3]) ? $url[3] : null) {
	default:
		content_main();
	break;
	
	case "reply":
		$id = intval($url[4]);
		if (!empty($id)){
		reply_edit($id);
		}
		else {
		header('Location: /'.ADMIN.'/module/guestbook/');
		}
	break;
	
	case "save":
		content_save();
	break;
	
	case "edit":	
		$id = intval($url[4]);
		if (!empty($id)){
		content_add($id);
		}
		else {
		header('Location: /'.ADMIN.'/module/guestbook/');
		}
	break;
	
	case "delete":
		$id = intval($url[4]);
		delete($id);
		header('Location: /'.ADMIN.'/module/guestbook/');
	break;
	
	case "action":
	$type = $_POST['submit'];
	if(is_array($_POST['checks'])) {
		switch($type) {			
			case _DELETE:
				foreach($_POST['checks'] as $id) {
					delete(intval($id));
				}
				break;
		}
	}
		header('Location: /'.ADMIN.'/module/guestbook');
	break;
	
	case 'config':
		require (ROOT.'etc/guestbook.config.php');
		
		$configBox = array(
			'guestbook' => array(
				'varName' => 'guestbook_conf',
				'title' => 'Настройки модуля "Гостевая книга"',
				'groups' => array(
					'main' => array(
						'title' => 'Основные настройки',
						'vars' => array(							
							'comments_num' => array(
								'title' => 'Количество комментариев на страницу',
								'description' => 'Количество пользовательских комментариев на одну страницу',
								'content' => '<input type="text" name="{varName}"  value="{var}" class="form-control" id="{varName}"  data-parsley-trigger="change">',
							),							
							'reply_mail' => array(
								'title' => 'Отправлять оповещения о ответах',
								'description' => 'Будет ли пользователь получать уведомления, по почте о появлении ответа на его пост?',
								'content' => radio("reply_mail", $guestbook_conf['reply_mail']),
							),	
							'keywords' => array(
								'title' => 'Keywords модуля',
								'description' => 'Для SEO оптимизации сайта',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),		
							'description' => array(
								'title' => 'Description модуля',
								'description' => 'Для SEO оптимизации сайта',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),									
						)
					),

				),
			),
		);

		$ok = false;
		
		if(isset($_POST['conf_file']))
		{
			$ok = true;
		}
		
		generateConfig($configBox, 'guestbook', '{MOD_LINK}/config', $ok);
		break;

}
