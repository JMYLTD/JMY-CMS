<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   28.03.2015
*/

if (!defined('ADMIN_SWITCH')) {
    header('Location: /');
    exit;
}

function content_main() 
{
global $adminTpl, $core, $db, $admin_conf;
	$adminTpl->admin_head(_MODULES .' | '. _N_PAGE);
	$page = init_page();
	$cut = ($page-1)*$admin_conf['num'];
	echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _N_LIST . '</b>
					</div>';
	$query = $db->query("SELECT c.*, l.* FROM ".DB_PREFIX."_content as c  LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=c.id and l.module='content') WHERE l.lang = '" . $core->InitLang() . "' LIMIT " . $cut . ", " . $admin_conf['num'] . "");
	if($db->numRows($query) > 0) {	
	echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{MOD_LINK}/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-3">' . _TITLE . '</th>
									<th class="col-md-1">' . _N_LINK . '</th>
									<th class="col-md-2">' . _DATE . '</th>
									<th class="col-md-4">' . _N_WORD .'</th>
									<th class="col-md-2">' . _ACTIONS . '</th>
									<th class="col-md-1"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';
		while($content = $db->getRow($query)) 
		{
			$contentLink = $content['cat'] !== ',0,' ? 'content/' . $core->getCat('content', $content['cat'], 'development') . '/' : 'content/';			
			$status_icon = ($content['active'] == 0) ? '<a href="{MOD_LINK}/activate/' . $content['id'] . '" onClick="return getConfirm(\'' . _N_ACTIV .' - ' . $content['title'] . '?\')"><button  type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _N_ACTIVE .'">A</button></a>' : '<a href="{MOD_LINK}/deactivate/' . $content['id'] . '" onClick="return getConfirm(\'' . _N_DACTIV .' - ' . $content['title'] . '?\')" ><button  type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _N_DEACTIVE .'">A</button></a>';
			echo '
				<tr '.(($content['active'] == 0) ? 'class="danger"' : '' ).'>
				<td><span class="pd-l-sm"></span>' . $content['id'] . '</td>
				<td>' . $content['title'] . '</td>
				<td><a target="_blank" href="' . $contentLink . $content['translate'] . '.html">'._N_REFER.'</a></td>
				<td>' . formatDate($content['date'], true) . '</td>
				<td>' . (!empty($content['keywords']) ? str($content['keywords'], 20) : _NO) . '</td>
				<td>
				'.$status_icon.'				
				<a href="{MOD_LINK}/edit/' . $content['id'] . '">
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
				</a>
				<a href="{MOD_LINK}/delete/' . $content['id'] . '" onClick="return getConfirm(\'' . _N_DEL .' - ' . $content['title'] . '?\')">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _N_DELITE .'">X</button>
				</a>
				</td>
				<td> <input type="checkbox" name="checks[]" value="' . $content['id'] . '"><span class="pd-l-sm"></span></td>
			</tr>';	
		}		
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>';		
	echo '
	<div class="_tableBottom">
	<div align="right">
	<table>
	<tr>
	<td valign="top">
	<select name="act">
		<option value="activate">' . _N_ACTIVE . '</option>
		<option value="deActivate">' . _N_DEACTIVE . '</option>
		<option value="reActivate">' . _EDIT . '</option>
		<option value="delete">' . _N_DELITE . '</option>
	</select>
	</td>
	<td>&nbsp&nbsp</td>	
	<td valign="top">
	<input name="submit" type="submit" class="btn btn-success" id="sub" value="' . _N_COMPLITE . '" /><span class="pd-l-sm"></span>
	</td>
	</tr>
	</table>	
	</div></div>
	</form></div>';		
	} 	
	else {
		echo '<div class="panel-heading">' . _N_EMPTY . '</div>';
	}
	echo'</section></div></div>';
	
	$all_query = $db->query("SELECT * FROM " . DB_PREFIX . "_content ");
	$all = $db->numRows($all_query);
	$adminTpl->pages($page, $admin_conf['num'], $all, ADMIN.'/module/content/{page}');
	$adminTpl->admin_foot();
} 

function content_add($nid = null) 
{
global $adminTpl, $core, $db, $core, $config;
	if(isset($nid)) 
	{
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_content WHERE id = '" . $nid . "'");
		$content = $db->getRow($query);
		$id = $content['id']; 
		$keywords = $content['keywords']; 
		$theme = $content['theme']; 
		$active = $content['active']; 
		$altname = $content['translate']; 
		$cat = $content['cat']; 
		$catttt = explode(',', $cat);
		$firstCat = $catttt[1];
		$deleteKey = array_search($firstCat, $catttt);
		unset($catttt[$deleteKey]);		
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_langs WHERE postId = '" . $id . "' AND module='content'");
		while($langs = $db->getRow($query))
		{
			$title[$langs['lang']] = prepareTitle($langs['title']);
			$text[$langs['lang']] = html2bb($langs['short']);
		}
		$lln = _N_EDITPAGE;
		$dosave = _UPDATE;
	} 
	else 
	{
		$id = false; 
		$title = false; 
		$keywords = false; 
		$cat = false; 
		$altname = false; 
		$catttt = false; 
		$active = 1;
		$text = '';
		$theme = '';
		$firstCat = '';
		$lln = _N_ADDPAGE;
		$dosave = _ADD;		
	}	
	if (empty($theme)) {  $placeholder = 'placeholder="'._N_DEFAULT.'"';}
	$cats_arr = $core->aCatList('content');	
	$adminTpl->admin_head(_MODULES . ' | ' . $lln);
	require ROOT . 'usr/plugins/ajax_upload/init.php';
	$cats_arr = $core->aCatList('content');
	echo '<section>
			<ul id="myTab2" class="nav nav-tabs">
				<li class="active">
					<a href="#home" data-toggle="tab">'. _MAIN .'</a>
				</li>';
	echo'		<li class="">
					<a href="#file" data-toggle="tab" onclick="uploaderStart();">'. _N_FILE .'</a>
				</li>
			</ul>		
			<section class="panel">
				<div class="panel-body">
					<div id="myTabContent2" class="tab-content">				
						<div class="tab-pane active" id="home">					
							<div class="panel-heading no-border"><b>'. $lln .'</b></div>
							<div class="panel-body">
								<div class="switcher-content">
									<form action="{MOD_LINK}/save" onsubmit="return caa(false);" method="post" name="content" role="form">
									<div class="form-horizontal parsley-form" data-parsley-validate>
										<div class="form-group">
					<label class="col-sm-3 control-label">'. _N_ADDTITLE .'</label>
					<div class="col-sm-4">
						<input type="text" name="title" '. (!isset($nid) ? 'onchange="getTranslit(gid(\'title\').value, \'translit\'); caa(this);"' : '').'  value="' . (isset($title[$config['lang']]) ? $title[$config['lang']] : '') . '" class="form-control" id="title"  data-parsley-required="true" data-parsley-trigger="change">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _N_ADDALT .'</label>
					<div class="col-sm-4">
						<input type="text" name="altname" value="'.$altname.'" class="form-control" id="translit"  data-parsley-required="true" data-parsley-trigger="change">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _N_ADDTAG .'</label>
					<div class="col-sm-4">
						<input type="text" name="keywords"  value="' . $keywords . '" class="form-control" id="tags"  data-parsley-trigger="change">
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _N_THEME .'</label>
					<div class="col-sm-4">
						<input type="text" name="theme"  value="' . $theme . '" class="form-control" id="tags"  data-parsley-trigger="change"'.$placeholder.'>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">'. _N_ADDCAT .'</label>
					<div class="col-sm-4">
						<select name="category[]" id="maincat" style="width:auto;" onchange="if(this.value != \'0\') {show(\'catSub\');}" >
							<option value="0">'._N_ADD_NOCAT.'</option>';	
	foreach ($cats_arr as $cid => $name) 
	{
		$selected = ($cid == $firstCat) ? "selected" : "";
		echo '<option value="' . $cid . '" ' . $selected . '>' . $name . '</option>';
	}
	echo '</select>
	</div>
	</div>
	<div class="form-group" id="catSub" style="' . (isset($nid) ? '' : 'display:none;') . '">
					<label class="col-sm-3 control-label">'. _N_ADDALTCAT .'</label>
					<div class="col-sm-4">
	<select name="category[]" id="category"  style="width:auto;" multiple >';
	foreach ($cats_arr as $cid => $name) 
	{
		if($catttt) $selected = in_array($cid, $catttt) ? "selected" : "";
		echo '<option value="' . $cid . '" ' . $selected . ' id="cat_' . $cid . '">' . $name . '</option>';
	}
	echo '</select></div></div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _N_ACTIVATE .'</label>
											<div class="col-sm-4">
												'.checkbox('status', $active).'
											</div>
										</div>	
									</div>
								</div>
							</div>
						</div>						
						<div class="tab-pane" id="file">
							<div class="panel-heading no-border">
								<b>'. _N_FILE .'</b>
							</div>
							<div class="panel-body">
								<div class="switcher-content">
									<div class="form-horizontal parsley-form">';									
echo file_upload('content', $id);							
echo'								</div>		
								</div>			
							</div>
						</div>
					</div>
				</section>
			</section> ';
	echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. _TEXT .'</b></div><div class="panel-body"><div class="switcher-content">';	
	echo adminArea('text[' . $config['lang'] . ']', (isset($text[$config['lang']]) ? $text[$config['lang']] : ''), 10, 'textarea', "onchange=\"caa(this);\"").(isset($nid) ? '<input name="edit" value="' . $nid . '" type="hidden" />' : '');
	echo '	
	<div>
		<input  name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="' . $dosave . '" />
			</div>
	</div></div>
		</section></div></div>';
	$adminTpl->admin_foot();
} 

function content_save() 
{
global $adminTpl, $core, $db, $cats, $groupss, $config;
	$word_counter = new Counter();
	$gen_tag = $word_counter->get_keywords($_POST['text'][$config['lang']]);
	$title = filter(trim($_POST['title']), 'title');
	$langTitle = isset($_POST['langtitle']) ? $_POST['langtitle'] : '';
	$langTitle[$config['lang']] = $title;
	$short = $_POST['text'];
	$theme = $_POST['theme'];
	$tags = isset($_POST['tags']) ? mb_strtolower(filter($_POST['tags'], 'a')) : mb_strtolower(filter($gen_tag, 'a'));
	$translit = ($_POST['altname'] !== '') ? mb_strtolower(str_replace(array('-', ' '), array('_', '_'), $_POST['altname'])) : translit($_POST['title']);
	$category = isset($_POST['category']) ? array_unique($_POST['category']) : '0';	
	if(is_array($category)) 
	{
		$firstCat = $category[0];
		unset($category[0]);
		$deleteCat = array_search($firstCat, $category);
		unset($category[$deleteCat]);
		$category[0] = $firstCat;
		ksort($category);
		foreach($category as $cid) 
		{
			$cats .= intval($cid) . ",";
		}
	}
	else 
	{
		$cats  = $category . ',';
	}	
	$cats = ',' . $cats;	
	$status = isset($_POST['status']) ? 1 : 0;
	$fix = isset($_POST['fix']) ? 1 : 0;	
	if($title && $short[$config['lang']] && $translit) 
	{
		if(isset($_POST['edit'])) 
		{
			$adminTpl->admin_head(_MODULES . ' | ' . _N_UPDATE);
			$edit = intval($_POST['edit']);
			foreach($langTitle as $k => $v)
			{
				$ntitle = filter(trim($v), 'title');
				$text = filter(fileInit('content', $edit, 'content', $short[$k]), 'html');
				if(isset($_POST['empty'][$k]) && trim($v) != '' && trim($short[$k]) != '')
				{
					$db->query("INSERT INTO `" . DB_PREFIX . "_langs` ( `postId` , `module` , `title` , `short` , `lang` ) 
	VALUES ('" . $edit . "', 'content', '" . $db->safesql(processText($ntitle)) . "', '" . $db->safesql(parseBB(processText($text), $edit, true)) . "', '" . $k . "');");
				}
				elseif(!isset($_POST['empty'][$k])  && (trim($v) == '' OR trim($short[$k]) == ''))
				{
					$db->query("DELETE FROM `" . DB_PREFIX . "_langs` WHERE `postId` ='" . $edit . "' AND `module` ='content' AND `lang`='" . $k . "' LIMIT 1");
				}
				elseif(!isset($_POST['empty'][$k]) && trim($v) != '' && trim($short[$k]) != '')
				{
					$db->query("UPDATE `" . DB_PREFIX . "_langs` SET `title` = '" . $db->safesql(processText($ntitle)) . "', `short` = '" . $db->safesql(parseBB(processText($text), $edit, true)) . "' WHERE `postId` ='" . $edit . "' AND `module` ='content' AND `lang`='" . $k . "' LIMIT 1 ;");
				}
			}			
			$db->query("UPDATE `" . DB_PREFIX . "_content` SET `translate` = '" . $translit . "', `cat` = '" . $cats . "', `keywords` = '" . $tags . "', `theme` = '" . $theme . "', `active` = '" . $status . "' WHERE `id` =" .$edit . " LIMIT 1 ;");
			$adminTpl->info(_BASE_INFO_0);
		} 
		else 
		{
			$adminTpl->admin_head(_MODULES . ' | ' . _N_ADD);
			if($db->query("INSERT INTO `" . DB_PREFIX . "_content` ( `id` , `translate`, `cat` , `keywords` , `active` , `date` , `theme` ) VALUES (NULL, '" . $translit . "', '" . $cats . "', '" . $tags . "', '" . $status . "', '" . time() . "', '" . $theme . "');")) 
			{
				$adminTpl->info(_BASE_INFO_0);
			}			
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_content WHERE translate = '" . $translit . "'");
			$content = $db->getRow($query);
			foreach($langTitle as $k => $v)
			{
				if(trim($v) != '' && trim($short[$k]) != '')
				{
					$ntitle = filter(trim($v), 'title');
					$text = fileInit('content', $content['id'], 'content', parseBB(processText(filter($short[$k], 'html'), $content['id'], true)));
					$db->query("INSERT INTO `" . DB_PREFIX . "_langs` ( `postId` , `module` , `title` , `short` , `lang` ) 
	VALUES ('" . $content['id'] . "', 'content', '" . $db->safesql(processText($ntitle)) . "', '" . $db->safesql($text) . "' , '" . $k . "');");
				}
			}
			fileInit('content', $content['id']);
		}
	} else {
		$adminTpl->admin_head(_MODULES . ' | ' . _ERROR);
		$adminTpl->info(_BASE_ERROR_0, 'error');
	}
	$adminTpl->admin_foot();
}


function delete($id) {
global $adminTpl, $db;
	$db->query("DELETE FROM `" . DB_PREFIX . "_content` WHERE `id` = " . $id . " LIMIT 1");
	$db->query("DELETE FROM `" . DB_PREFIX . "_langs` WHERE `postId` = '" . $id . "' AND `module` = 'content'");
}


function activate($id) {
global $adminTpl, $db;
	$db->query("UPDATE `" . DB_PREFIX . "_content` SET `active` = '1' WHERE `id` = " . $id . " LIMIT 1 ;");
}

switch(isset($url[3]) ? $url[3] : null) {
	default:
		content_main();
	break;
	
	case "add":
		content_add();
	break;
	
	case "save":
		content_save();
	break;
	
	case "edit":
		$id = intval($url[4]);
		content_add($id);
	break;
	
	case "delete":
		$id = intval($url[4]);
		delete($id);
		header('Location: /'.ADMIN.'/module/content');
	break;
	
	case "activate":
		$id = intval($url[4]);
		activate($id);
		header('Location: /'.ADMIN.'/module/content');
	break;	
	
	case "deactivate":
		$id = intval($url[4]);
		$db->query("UPDATE `" . DB_PREFIX . "_content` SET `active` = '0' WHERE `id` = " . $id . " LIMIT 1 ;");
		header('Location: /'.ADMIN.'/module/content');
	break;

	case "action":
	$type = $_POST['act'];
	if(is_array($_POST['checks'])) {
		switch($type) {
			case "activate":
				foreach($_POST['checks'] as $id) {
					activate(intval($id));
				}
				break;			
			
			case "deActivate":
				foreach($_POST['checks'] as $id) {
					$db->query("UPDATE `" . DB_PREFIX . "_content` SET `active` = '0' WHERE `id` = " . $id . " LIMIT 1 ;");
				}
				break;			
				
			case "reActivate":
				foreach($_POST['checks'] as $id) {
					$db->query("UPDATE `" . DB_PREFIX . "_content` SET `active` = NOT `active` WHERE `id` = " . $id . " LIMIT 1 ;");
				}
				break;
			
			case "delete":
				foreach($_POST['checks'] as $id) {
					delete(intval($id));
				}
				break;
		}
	}
		header('Location: /'.ADMIN.'/module/content');
	break;
	
	case 'config':
		require (ROOT.'etc/content.config.php');
		
		$configBox = array(
			'content' => array(
				'varName' => 'content_conf',
				'title' => _N_CONFIG_TITLE,
				'groups' => array(
					'main' => array(
						'title' => _N_CONFIG_MAIN,
						'vars' => array(
							'num' => array(
								'title' => _N_CONFIG_POST,
								'description' => _N_CONFIG_POST_I,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'comments_num' => array(
								'title' => _N_CONFIG_COM,
								'description' => _N_CONFIG_COM_I,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'allowComm' => array(
								'title' => _N_CONFIG_COMP,
								'description' => _N_CONFIG_COMP_I,
								'content' => radio("allowComm", $content_conf['allowComm']),
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
		generateConfig($configBox, 'content', '{MOD_LINK}/config', $ok);
		break;
}
