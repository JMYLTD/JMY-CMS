<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   28.02.2015
*/

if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}


function main() 
{
global $adminTpl, $config, $core, $admin_conf, $db;
	$adminTpl->admin_head(_DOP_DOPS);
	echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _LIST . '</b>
					</div>';	
	$query = $db->query("SELECT * FROM ".DB_PREFIX."_xfields ORDER BY id ASC");
	if($db->numRows($query) > 0) 
	{	
		echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/xfields/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-2">' . _TITLE . '</th>
									<th class="col-md-2">' . _DESCRIPTION . '</th>
									<th class="col-md-3">' . _DOP_TEMP . '</th>
									<th class="col-md-2">' . _MODULE .'</th>
									<th class="col-md-3">' . _ACTIONS . '</th>
									<th class="col-md-4"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';
							
		while($xfield = $db->getRow($query)) 
		{
			echo '
			<tr>
				<td><span class="pd-l-sm"></span> '. $xfield['id'] . '</td>
				<td>' . $xfield['title'] . '</td>
				<td>' . $xfield['description'] . '</td>
				<td>[xfield:' . $xfield['id'] . '][xfield_value:' . $xfield['id'] . '][/xfield:' . $xfield['id'] . ']</td>
				<td>' . _mName($xfield['module']) . '</td>
				<td>
				<a href="{ADMIN}/xfields/edit/' . $xfield['id'] . '">
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
				</a>
				<a href="{ADMIN}/xfields/delete/' . $xfield['id'] . '" onClick="return getConfirm(\'' . _DOP_DEL .' - ' . $xfield['title'] . '?\')">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
				</a>
                </td>
				<td> <input type="checkbox" name="checks[]" value="' . $xfield['id'] . '"><span class="pd-l-sm"></span></td>
			</tr>';	
		}
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>
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
	echo '<div class="panel-heading">' . _DOP_EMPTY . '</div>';
	}
	echo'</section></div></div>';
	$adminTpl->admin_foot();
}

function xfields_add($id = null) 
{
global $adminTpl, $config, $core, $admin_conf, $db;
	if(isset($id)) 
	{
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_xfields WHERE id = '" . $id . "'");
		$xfield = $db->getRow($query);
		$title = prepareTitle($xfield['title']);
		$description = $xfield['description'];
		$default = $xfield['content'];
		$type = $xfield['type'];
		$to_user = $xfield['to_user'];
		$mod = $xfield['module'];
		$lang = _DOP_EDIT_DOP;
		$compl = _DOP_EDIT;
	} 
	else 
	{
		$title = '';
		$description = '';
		$default = '';
		$to_user = 1;
		$mod = 'news';
		$lang = _DOP_ADD_DOP;
		$compl = _DOP_ADD;
	}
	$adminTpl->admin_head($lang);
	
	echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. $lang .'</b></div><div class="panel-body"><div class="switcher-content">';
	echo '<script type="text/javascript">
	function xfieldType(val)
	{
		if(val == \'\')
		{
			gid(\'typeExp\').innerHTML = \'\';
		}
		else if(val == 1)
		{
			gid(\'typeExp\').innerHTML = \'<div class="form-group"><label class="col-sm-3 control-label">'. _DOP_ADD_DEF .'</label><div class="col-sm-4"><input type="text" size="20" name="default" class="form-control" value="' . $default . '" maxlength="100" maxsize="100" /></div></div>\';
		}
		else if(val == 2)
		{
			gid(\'typeExp\').innerHTML = \'<div class="form-group"><label class="col-sm-3 control-label">'. _DOP_ADDDEF .'</label><div class="col-sm-4"><textarea name="default" class="form-control" rows="3">' . $default . '</textarea></div></div>\';
		}
		else if(val == 3)
		{
			gid(\'typeExp\').innerHTML = \'<div class="form-group"><label class="col-sm-3 control-label">'. _DOP_ADDDEF .'</label><div class="col-sm-4"><textarea name="default" class="form-control" rows="5">' . $default . '</textarea></div></div>\';
		}
	}	
	
	var errsConf = new Array();
	errsConf[0] = new Array(\'title\',\'titleErr\',\''. _DOP_ERROR_1 .'\');
	errsConf[1] = new Array(\'description\',\'descrErr\',\''. _DOP_ERROR_2 .'\');
	errsConf[2] = new Array(\'type\',\'typeErr\',\''. _DOP_ERROR_3 .'\');
	</script>';
	echo '<div id="currentErrors"></div>';
	
	
	echo '<form action="{ADMIN}/xfields/save" onsubmit="return caa(false);" method="post" name="xfields" role="form" class="form-horizontal parsley-form" data-parsley-validate>
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DOP_ADD_TITLE .'</label>
					<div class="col-sm-4">
						<input type="text" name="title"  value="'. $title .'" class="form-control" id="title"  data-parsley-required="true" data-parsley-trigger="change" onchange=\"caa(this);\">
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _DOP_ADD_DESC .'</label>
					<div class="col-sm-4">
						<input type="text" name="description"  value="'. $description .'" class="form-control" id="description"  data-parsley-required="true" data-parsley-trigger="change" onchange=\"caa(this);\">
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _DOP_ADD_MODULE .'</label>
					<div class="col-sm-4">
					
	<select name="module" id="module" >';
	
	$exceMods = array('blog', 'board', 'feed', 'gallery', 'pm', 'search', 'feedback', 'guestbook', 'content','mainpage','sitemap');
	foreach ($core->getModList() as $module) 
	{
		if(!in_array($module, $exceMods))
		{
			$selected = ($module == $mod) ? "selected" : "";
			echo '<option value="' . $module . '" ' . $selected . '>' . _mName($module) . '</option>';
		}
	}				
	echo'	</select>			</div>
		 </div>
		 
	<div class="form-group">
					<label class="col-sm-3 control-label">'. _DOP_ADD_TYPE .'</label>
					<div class="col-sm-4">';	
	if(empty($id))
	{
		echo "<select name=\"type\" id=\"type\" onchange=\"xfieldType(this.value); caa(this);\"><option value=\"\">"._DOP_TYPE_0."</option><option value=\"1\">"._DOP_TYPE_1."</option><option value=\"2\">"._DOP_TYPE_2."</option><option value=\"3\">"._DOP_TYPE_3."</option></select>";
	}
	else
	{
		echo "<select name=\"type\"  id=\"type\" onchange=\"xfieldType(this.value); caa(this);\"><option value=\"\">"._DOP_TYPE_0."</option><option value=\"1\" " . ($type == 1 ? 'selected' : '') . ">"._DOP_TYPE_1."</option><option value=\"2\" " . ($type == 2 ? 'selected' : '') . ">"._DOP_TYPE_2."</option><option value=\"3\" " . ($type == 3 ? 'selected' : '') . ">"._DOP_TYPE_3."</option></select>";

	}
	echo '</div></div>';
	if(empty($id))
	{
		echo "<div id=\"typeExp\"></div>";
	}
	else
	{
		switch ($type) {
					case 1:
						echo '<div id="typeExp"><div class="form-group"><label class="col-sm-3 control-label">'. _DOP_ADD_DEF .'</label><div class="col-sm-4"><input type="text" size="20" name="default" class="form-control" value="' . $default . '" maxlength="100" maxsize="100" /></div></div></div>';
						break;
					case 2:
						echo '<div id="typeExp"><div class="form-group"><label class="col-sm-3 control-label">'. _DOP_ADD_DEF .'</label><div class="col-sm-4"><textarea name="default" class="form-control" rows="3">' . $default . '</textarea></div></div></div>';
						break;
					case 3:
						echo '<div id="typeExp"><div class="form-group"><label class="col-sm-3 control-label">'. _DOP_ADD_DEF .'</label><div class="col-sm-4"><textarea name="default" class="form-control" rows="5">' . $default . '</textarea></div></div></div>';
						break;
						}	
		
	}
	echo '<div class="form-group">
					<label class="col-sm-3 control-label">'. _DOP_ADD_USER .'</label>
					<div class="col-sm-4">
						'.checkbox('to_user', $to_user).'
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'.$compl.'" />						
					</div>
		</div>';
	if(isset($id)) {
		echo '<input type="hidden" name="edit" value="1">';
		echo '<input type="hidden" name="fid" value="' . $id . '">';
	}
	echo '</form></div></div>';  
	echo'</section></div></div>';
	$adminTpl->admin_foot();
}

function xfields_save() {
global $adminTpl, $db, $core;
if(isset($_POST['edit'])) 
		{
		$adminTpl->admin_head(_DOP_DOPS.' | '._DOP_EDITS);
		} 
		else 
		{
		$adminTpl->admin_head(_DOP_DOPS.' | '._DOP_ADDS);
	}
	$fid = isset($_POST['fid']) ? intval($_POST['fid']) : 0;
	$title = filter($_POST['title'], 'title');
	$description = filter($_POST['description'], 'a');
	$type = intval($_POST['type']);
	$to_user = isset($_POST['to_user']) ? 1 : 0;
	$default = filter($_POST['default']);
	$module = filter($_POST['module'], 'module');
	$back = '{ADMIN}/xfields/';
	if(!empty($title) && !empty($description) && !empty($type)) 
	{
		if(isset($_POST['edit'])) 
		{
			$db->query("UPDATE `" . DB_PREFIX . "_xfields` SET `title` = '" . $title . "',`description` = '" . $description . "',`type` = '" . $type . "',`content` = '" . $default . "',`to_user` = '" . $to_user . "',`module` = '" . $module . "' WHERE `id` = " . $fid . ";");
			$adminTpl->info(_DOP_INFO_1);
		} 
		else 
		{
			$db->query("INSERT INTO `" . DB_PREFIX . "_xfields` (`title` ,`description` ,`type` ,`content` ,`to_user` ,`module` ) VALUES ('" . $db->safesql(processText($title)) . "', '" . $db->safesql(processText($description)) . "', '" . $type . "', '" . $default . "', '" . $to_user . "', '" . $module . "');");
			$adminTpl->info(_DOP_INFO_2);
		}
	} 
	else 
	{
		$adminTpl->info(_BASE_ERROR_0, 'error');
	}
	$adminTpl->admin_foot();
}

switch(isset($url[2]) ? $url[2] : null) {
	default:
		main();
	break;	
	
	case "add":
		xfields_add();
	break;
	
	case "save":
		xfields_save();
	break;
	
	case "delete":
		$id = intval($url[3]);
		$db->query("DELETE FROM `" . DB_PREFIX . "_xfields` WHERE `id` = " . $id . " LIMIT 1");
		location(ADMIN.'/xfields');
	break;
	
	case "edit":
		$id = intval($url[3]);
		xfields_add($id);
	break;
	
	case "action":
	$type = $_POST['submit'];
	if(is_array($_POST['checks'])) {
		switch($type) {
			case _DELETE:
				foreach($_POST['checks'] as $id) 
				{
					$db->query("DELETE FROM `" . DB_PREFIX . "_xfields` WHERE `id` = " . intval($id) . " LIMIT 1");
				}
			break;
		}
	}
		location(ADMIN.'/xfields');
	break;

}