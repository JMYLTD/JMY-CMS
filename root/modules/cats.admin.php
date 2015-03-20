<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   27.02.2015
*/
 
if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

function main() 
{
global $adminTpl, $config, $core, $admin_conf, $db;
	$adminTpl->admin_head(_CAT_PATH);
	echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _LIST . '</b>
					</div>';	
	$query = $db->query("SELECT * FROM ".DB_PREFIX."_categories ORDER BY id ASC, parent_id ASC");
	if($db->numRows($query) > 0) 
	{
	echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/cats/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-3">' . _TITLE . '</th>
									<th class="col-md-1">' . _MODULE . '</th>
									<th class="col-md-3">' . _DESCRIPTION .'</th>
									<th class="col-md-1">' . _TRANSLIT . '</th>
									<th class="col-md-1">' . _POSITION . '</th>
									<th class="col-md-2">' . _ICON . '</th>
									<th class="col-md-2">' . _ACTIONS . '</th>
									<th class="col-md-1"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';
		while($cat = $db->getRow($query)) 
		{
			echo '
			<tr>
				<td><span class="pd-l-sm"></span>' . $cat['id'] . '</td>
				<td>' . $core->getCat($cat['module'], $cat['id'], 'full', 1) . '</td>
				<td>' . _mName($cat['module']) . '</td>
				<td>' . (empty($cat['description']) ? _NO : str($cat['description'], 17)) . '</td>
				<td>' . $cat['altname'] . '</td>
				<td>' . $cat['position'] . '</td>
				<td>' . (empty($cat['icon']) ? _NO : _THERE_IS) . '</td>
				<td>
				<a href="{ADMIN}/cats/edit/' . $cat['id'] . '">
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
				</a>
				<a href="{ADMIN}/cats/delete/' . $cat['id'] . '" onClick="return getConfirm(\'' . str_replace('[cat]', $cat['name'], _CAT_DELETE) .  '\')">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
				</a>
				</td>
				<td> <input type="checkbox" name="checks[]" value="' . $cat['id'] . '"><span class="pd-l-sm"></span></td>
			</tr>';	
			
		}
	echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>		
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
	</form></div>';	
	} 	
	else 
	{
	echo '<div class="panel-heading">'  . _NO_CATS . '</div>';		
	}
	echo'</section></div></div>';
	$adminTpl->admin_foot();
}

function add($cat = null) 
{
global $adminTpl, $config, $core, $admin_conf, $db;
	if(isset($cat)) 
	{
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_categories WHERE id = '" . $cat . "'");
		$ccid = $cat;
		$cat = $db->getRow($query);
		$name = prepareTitle($cat['name']);
		$altname = $cat['altname'];
		$description = $cat['description'];
		$keywords = $cat['keywords'];	
		$icon = !isset($cat['icon']) ? $cat['icon'] : 'no.png';
		$pid = $cat['parent_id'];
		$mod = $cat['module'];
		$lang = _CAT_UPDATE;
	} 
	else 
	{
		$name = isset($_POST['name']) ? filter(trim($_POST['name'])) : '';
		$altname = isset($_POST['altname']) ? ($_POST['altname'] == '') ? translit($name) : translit($_POST['altname']) : '';
		$description = isset($_POST['description']) ? filter($_POST['description']) : '';
		$keywords = isset($_POST['keywords']) ? filter($_POST['keywords'], 'a') : '';	
		$icon = isset($_POST['icon']) ? filter($_POST['icon'], 'a') : 'no.png';		
		$pid = false;
		$mod = 'news';
		$lang = _CAT_ADD;
	}
	$adminTpl->admin_head(_CAT_PATH.' | '.$lang);
	echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. $lang .'</b></div><div class="panel-body"><div class="switcher-content">';
	echo '<script language="javascript">
	var errsConf = new Array();
	errsConf[0] = new Array(\'name\',\'nameErr\',\''. _CAT_ERROR_1 .'\');
	</script>';
	echo '<div id="currentErrors"></div>';
	echo '<form action="{ADMIN}/cats/save" onsubmit="return caa(false);" method="post" name="cats" role="form" class="form-horizontal parsley-form" data-parsley-validate>
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _CAT_NAME .'</label>
					<div class="col-sm-4">
						<input type="text" name="name"  value="'. $name .'" class="form-control" id="name"  data-parsley-required="true" data-parsley-trigger="change" '. (!isset($cat) ? "onchange=\"getTranslit(gid('name').value, 'altname'); caa(this);\"" : "" ) .'\">
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _CAT_URL .'</label>
					<div class="col-sm-4">
						<input type="text" name="altname"  value="'. $altname .'" class="form-control" id="altname"  data-parsley-required="true" data-parsley-trigger="change" \">
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _CAT_DESCR .'</label>
					<div class="col-sm-4">
						<input type="text" name="description"  value="'. $description .'" class="form-control" id="description"  data-parsley-required="true" data-parsley-trigger="change" \">
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _CAT_KEYS .'</label>
					<div class="col-sm-4">
						<input type="text" name="keywords"  value="'. $keywords .'" class="form-control" id="keywords"  data-parsley-required="true" data-parsley-trigger="change" \">
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _CAT_ICON .'</label>
					<div class="col-sm-4">';		
	$path = 'media/cats/';
	$dh = opendir(ROOT . $path);
	echo '<table width=100%><tr><td  width="190"><select name="icon" id="icon" onchange="changeIcon(\'' . $path . '\' + this.value, \'iconImg\')" ><option value="">' . _CAT_ICON_NO . '</option>';
	while ($file = readdir($dh)) 
	{
		if(is_file(ROOT . $path.$file) && $file != '.' && $file != '..' && $file != 'no.png') 
		{
			$selected = ($icon == $file) ? "selected" : "";
			echo '<option value="' . $file . '" ' . $selected . '>' . $file . '</option>';
		}
	}
	echo '</select></td><td valign="top"><span class="pd-l-sm"></span><img width="32" height="32" src="' . $path . $icon . '" border="0" id="iconImg" /></tr>
	</table></div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _CAT_INCAT .'</label>
					<div class="col-sm-4">
						<select name="category" id="category" ><option value="">' . _CAT_INCAT_NO . '</option>';
	$cats_arr = $core->aCatList();
	foreach ($cats_arr as $cid => $name) {
		$selected = ($pid == $cid) ? "selected" : "";
		echo '<option value="' . $cid . '" ' . $selected . '>' . $name . '</option>';
	}		
	echo'</select></div>
		 </div>';
	echo '<div class="form-group">
					<label class="col-sm-3 control-label">'. _CAT_MODULE .'</label>
					<div class="col-sm-4">
						<select name="module" id="module" onchange="updateCatList(this.value, \'category\');" >';
	$exceMods = array('blog', 'board', 'feed', 'sitemap', 'feedback', 'gallery', 'pm', 'profile', 'search', 'poll','mainpage','guestbook');
	foreach ($core->getModList() as $module) 
	{
		if(!in_array($module, $exceMods))
		{
			$selected = ($module == $mod) ? "selected" : "";
			echo '<option value="' . $module . '" ' . $selected . '>' . _mName($module) . '</option>';
		}
	}
	echo'</select></div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'. (isset($cat) ? _UPDATE : _ADD) .'" />						
					</div>
		</div>';
	if(isset($ccid)) {
	echo '<input type="hidden" name="edit" value="1">';
	echo '<input type="hidden" name="cid" value="' . $ccid . '">';
	}
	echo '</form></div></div>';  
	echo'</section></div></div>';
	$adminTpl->admin_foot();
}

function cat_save() {
global $adminTpl, $db, $core;
	$adminTpl->admin_head(_CAT_PATH.' | '._CAT_ADD);
	$cid = isset($_POST['cid']) ? intval($_POST['cid']) : 0;
	$name = filter($_POST['name'], 'a');
	$altname = ($_POST['altname'] == '') ? translit($name) : str_replace(array('-', ' '), array('_', '_'), $_POST['altname']);
	$description = filter($_POST['description']);
	$keywords = filter($_POST['keywords'], 'a');
	$icon = filter($_POST['icon'], 'a');
	$module = filter($_POST['module'], 'module');
	$pid = intval($_POST['category']);
	if(!empty($name) && !empty($altname)) 
	{
		if(isset($_POST['edit'])) 
		{
			$db->query("UPDATE `" . DB_PREFIX . "_categories` SET `name` = '" . $db->safesql(processText($name)) . "', `altname` = '" . $altname . "', `description` = '" . $db->safesql(processText($description)) . "', `keywords` = '" . $db->safesql(processText($keywords)) . "', `module` = '" . $module . "', `icon` = '" . $icon . "', `parent_id` = '" . $pid . "' WHERE `id` =".$cid."  LIMIT 1");
			$adminTpl->info(_CAT_UPDATESUCCESS);
		} 
		else 
		{
		if($db->query("INSERT INTO `" . DB_PREFIX . "_categories` ( `id` , `name` , `altname` , `description` , `keywords` , `module` , `icon` , `position` , `parent_id` ) 
	VALUES (
	NULL, '" . $db->safesql(processText($name)) . "', '" . $altname . "', '" . $db->safesql(processText($description)) . "', '" . $keywords . "', '" . $module . "', '" . $icon . "', '0', '" . $pid . "'
	);")) $adminTpl->info(_CAT_ADDSUCCESS);
		}
		@unlink(ROOT . 'tmp/cache/categories.cache');
	} 
	else 
	{
		$adminTpl->info(_GLOBAL_ERROR_0, 'error');
	}
	$adminTpl->admin_foot();
}

function scan($cat = null) {
	global $adminTpl, $config, $core, $admin_conf, $db;
	$adminTpl->admin_head(_CAT_PATH.' | '._CAT_FAST_ADD);
	echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. _CAT_FAST_ADD .'</b></div><div class="panel-body"><div class="switcher-content">';
	
	echo '<form action="{ADMIN}/cats/save_scan" method="post" name="news" role="form" class="form-horizontal parsley-form" data-parsley-validate>';
	echo '<div class="form-group">
					<label class="col-sm-3 control-label">'. _CAT_MODULE .'</label>
					<div class="col-sm-4">
						<select name="module" id="module" onchange="updateCatList(this.value, \'category\');" >';
	$exceMods = array('blog', 'board', 'feed', 'sitemap', 'feedback', 'gallery', 'pm', 'profile', 'search', 'poll','mainpage', 'guestbook');
	foreach ($core->getModList() as $module) 
	{
		if(!in_array($module, $exceMods))
		{
			$selected = ($module == $mod) ? "selected" : "";
			echo '<option value="' . $module . '" ' . $selected . '>' . _mName($module) . '</option>';
		}
	}
	echo'</select></div>
		</div>	
		<div class="form-group">
			<label class="col-sm-3 control-label">'. _CAT_LIST .'</label>
			<div class="col-sm-4">
				<textarea name="full" id="full" class="form-control" rows="10">' . $default . '</textarea>
			</div>
		</div>
		<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'. _ADD .'" />						
					</div>
		</div>';	
	echo '</form></div></div>';  
	echo'</section></div></div>';
	$adminTpl->admin_foot();
}

function save_scan() {

global $adminTpl, $db, $core;
	$adminTpl->admin_head(_CAT_PATH . '|' . _CAT_ADDS);
	$module = filter($_POST['module'], 'module');
	$full = explode("\n", $_POST['full']);	
	$info = '';
	if (!empty($full)) {
	foreach($full as $cat) {
		if($cat !== '' && !is_array($cat)) {
		$name = filter($cat);
		$altname = translit($name);
		$db->query("INSERT INTO `" . DB_PREFIX . "_categories` (`name` , `altname` ,  `module` ) VALUES ('" . $db->safesql($name) . "', '" . $altname . "', '" . $module . "');");
		$info .= str_replace(array('[name]', '[altname]'), array($name, $altname), _CAT_LIST_SUCCESS);	
		}	
		}
		$adminTpl->info($info);	
		}else {
			$info .= _BASE_ERROR_0;
			$adminTpl->info($info, 'error');
			}
		
	@unlink(ROOT . 'tmp/cache/categories.cache');	
	$adminTpl->admin_foot();
}

function delete($id) {
global $adminTpl, $db;
	$db->query("DELETE FROM `" . DB_PREFIX . "_categories` WHERE `id` = " . $id . " LIMIT 1");
	$db->query("DELETE FROM `" . DB_PREFIX . "_news` WHERE `cat` like '%," . $id . ",%'");
	
	if(file_exists(ROOT . 'tmp/cache/categories.cache'))
	{
		@unlink(ROOT . 'tmp/cache/categories.cache');
	}
}

switch(isset($url[2]) ? $url[2] : null) {
	default:
		main();
	break;	
	
	case "add":
		add();
	break;
	
	case "save":
		cat_save();
	break;
	
	case "delete":
		$id = intval($url[3]);
		delete($id);
		header('Location: /'.ADMIN.'/cats');
	break;
	
	case "edit":
		$id = intval($url[3]);
		add($id);
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
		header('Location: /'.ADMIN.'/cats');
	break;
	
	case "scan":
		scan();
	break;
	
	case "save_scan":
		save_scan();
	break;
}