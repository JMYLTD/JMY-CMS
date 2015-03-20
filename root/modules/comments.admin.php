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

switch(isset($url[2]) ? $url[2] : null) 
{
	default:
		$page = init_page();
		$cut = ($page-1)*$admin_conf['num'];
		$where = '';
		$textup =_COM_LIST;
		$adminTpl->admin_head(_COM_COM);
		if(isset($url[2]) && $url[2] == 'ok')
		{
			$adminTpl->info(_COM_OK);
		}
		elseif(isset($url[2]) && $url[2] == 'moder')
		{
			$where = ' WHERE c.status=\'0\'';
			$textup =_COM_LIST_M;
		}
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . $textup . '</b>
					</div>';			
		$query = $db->query("SELECT c.*, u.nick, u.group, u.last_visit FROM ".DB_PREFIX."_comments as c LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on (c.uid=u.id) " . $where . " ORDER BY date DESC LIMIT $cut,".$admin_conf['num']);
		if($db->numRows($query) > 0) 				
		{
		echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/comments/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-3">' . _COMMENT . '</th>
									<th class="col-md-1">' . _MODULE . '</th>
									<th class="col-md-2">' . _DATE . '</th>
									<th class="col-md-3">' . _USER .'</th>
									<th class="col-md-1">' . _LINKS . '</th>
									<th class="col-md-3">' . _ACTIONS . '</th>
									<th class="col-md-3"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';
			while($commment = $db->getRow($query)) 
			{
				$active = ($commment['status'] == 0) ? '<a href="{ADMIN}/comments/activate/' . $commment['id'] . '" onClick="return getConfirm(\'' ._ACTIVATE .' - ' . str(htmlspecialchars(strip_tags($commment['text'])), 40) . '?\')"><button  type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _ACTIVATE .'">A</button></a>' : '<a href="{ADMIN}/comments/deactivate/' . $commment['id'] . '" onClick="return getConfirm(\'' . _DEACTIVATE .' - ' . str(htmlspecialchars(strip_tags($commment['text'])), 40) . '?\')" ><button  type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' ._DEACTIVATE .'">A</button></a>';
				$tt = str(htmlspecialchars(strip_tags($commment['text'])), 30);
				echo '
				<tr '.(($commment['status'] == 0) ? 'class="danger"' : '' ).'>
					<td><span class="pd-l-sm"></span>' . $commment['id'] . '</td>
					<td>' . (($tt != '') ? $tt : '<font color="red">'._NO_TEXT.'</font>') . '</td>
					<td>' . commentLink($commment['module'], $commment['post_id']) . '</td>
					<td>' . formatDate($commment['date'], true) . '</td>
					<td>' . (($commment['uid'] != 0) ? '<a href="profile/' . $commment['nick'] . '" title="' . $commment['nick'] . '">' . $commment['nick'] . '</a>' : $commment['gname']) . '</td>
					<td>' . (eregStrt('href', $commment['text']) ? '<font color="red">'._YES.'</font>' : '<font color="green">'._NO.'</font>') . '</td>
					<td>' . $active . '<a href="{ADMIN}/comments/edit/' . $commment['id'] . '">
					<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
					</a>
					<a href="{ADMIN}/comments/delete/' .$commment['id'] . '" onClick="return getConfirm(\'' . _COM_DELETE .' - ' . str(htmlspecialchars(strip_tags($commment['text'])), 40) . '?\')">
					<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .	'">X</button>
					</a>
					<td> <input type="checkbox" name="checks[]" value="' . $commment['id'] . '"><input type="hidden" name="module[' . $commment['id'] . ']" value="' . $commment['module'] . '"></td>
				</tr>';
					
			}
				echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>
				<div align="right">
	<table>
	<tr>
	<td valign="top">
	<select name="act">
		<option value="activate">' . _ACTIVATE . '</option>
		<option value="deActivate">' . _DEACTIVATE . '</option>
		<option value="reActivate">' . _REACTIVATE . '</option>
		<option value="delete">' . _DELETE . '</option>
	</select>
	</td>
	<td>&nbsp&nbsp</td>
	
	<td valign="top">
	<input name="submit" type="submit" class="btn btn-success" id="sub" value="' . _DOIT . '" /><span class="pd-l-sm"></span>
	</td>
	</tr>
	</table>		
	</div>
	</form></div>';
		} 
		else 
		{
			echo '<div class="panel-heading">'  . _COM_NO . '</div>';
		}
		echo'</section></div></div>';
		$all_query = $db->query("SELECT * FROM " . DB_PREFIX . "_comments " . str_replace('c.', '', $where));
		$all = $db->numRows($all_query);
		$adminTpl->pages($page, $admin_conf['num'], $all, ADMIN.'/comments/{page}');
		$adminTpl->admin_foot();
		break;
		
	case 'edit':
		$commId = intval($url[3]);
		if($commId != 0)
		{
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_comments WHERE id = '" . $commId . "'");
			$comment = $db->getRow($query);
			
			if($comment['uid'] != 0)
			{
				list($nick) = $db->fetchRow($db->query("SELECT nick FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE id = " . $comment['uid'] . " LIMIT 1"));
			}
		}
		else
		{
			location(ADMIN);
		}
		$bb = new bb;
		$adminTpl->admin_head(_COM_COM . ' | ' ._COM_EDIT);
		echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. _COM_EDIT .'</b></div><div class="panel-body"><div class="switcher-content">
		<form action="{ADMIN}/comments/save" method="post" name="news" role="form" class="form-horizontal parsley-form" data-parsley-validate>';		
		if($comment['uid'] == 0)
		{
			echo '<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_GUEST_NAME .'</label>
					<div class="col-sm-4">
						<input type="text" name="gname"  value="'. $comment['gname'] .'" class="form-control" id="gname"  data-parsley-required="true" data-parsley-trigger="change">
					</div>
				  </div>
				  <div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_GUEST_MAIL .'</label>
					<div class="col-sm-4">
						<input type="text" name="gemail"  value="'. $comment['gemail'] .'" class="form-control" id="exampleInputEmail2"  data-parsley-required="true" data-parsley-trigger="change">
					</div>
				  </div>';			
		}
		else
		{
			echo '<div class="form-group">
					<label class="col-sm-3 control-label">'. _BASE_USER_NAME .'</label>
					<div class="col-sm-4">
						<a target="_blank" href="profile/' . $nick . '"><p class="form-control-static">' . $nick .'</p></a>
					</div>
				  </div>';			
		}
		echo '<div class="form-group">
					<label class="col-sm-3 control-label">'. _COM_ID .'</label>
					<div class="col-sm-4">
						<p class="form-control-static">' . $comment['post_id'] .'</p>
					</div>
			  </div>
			  <div class="form-group">
					<label class="col-sm-3 control-label">'. _MODULE .'</label>
					<div class="col-sm-4">
						<select name="module" id="module" onchange="updateCatList(this.value, \'category\');">';		
		foreach ($core->getModList() as $module) {
			$selected = ($module == $comment['module']) ? "selected" : "";
			echo '<option value="' . $module . '" ' . $selected . '>' . _mName($module) . '</option>';
		}
		echo '			</select>
					</div>
			  </div>
			  <div class="form-group">
					<label class="col-sm-3 control-label">'. _COM_ACT .'</label>
					<div class="col-sm-4">
						'.checkbox('active', $comment['status']).'
					</div>
			  </div>
			  </div></div>
			  </section></div></div>
			  <div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>'. _COM_TEXT .'</b></div><div class="panel-body"><div class="switcher-content">'.adminArea('text', $bb->htmltobb($comment['text']), 10, 'textarea', false, true).'
			  <br><input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="' . _UPDATE . '" />';		
		echo '<input type="hidden" name="cid" value="' . $commId . '">
			  <input type="hidden" name="userid" value="' . $comment['uid'] . '">';
		echo '</form>';
		echo '</div></div>
		</section></div></div>';
		$adminTpl->admin_foot();

		break;
		
	case 'save':
		$cid = isset($_POST['cid']) ? intval($_POST['cid']) : '';
		$userid = isset($_POST['userid']) ? intval($_POST['userid']) : '';
		$module = isset($_POST['module']) ? filter($_POST['module'], 'module') : '';
		$text = isset($_POST['text']) ? filter($_POST['text']) : '';
		$gname = isset($_POST['gname']) ?  filter($_POST['gname']) : '';
		$gemail = isset($_POST['gemail']) ?  filter($_POST['gemail']) : '';
		$active = isset($_POST['active']) ? 1 : false;
		if($cid != 0)
		{
			if((!empty($text) && $userid!=0) || (!empty($text) && !empty($gname) && !empty($gemail)))
			{		
				$bb = new bb;
				$db->query("UPDATE `" . DB_PREFIX . "_comments` SET `module` = '" . $module . "', `text` = '" . $db->safesql($bb->parse(processText($text))) . "', `gemail` = '" . $gemail . "', `gname` = '" . $db->safesql(processText($gname)) . "', `status` = '" . $active . "' WHERE `id` =" . $cid . ";");
				$adminTpl->admin_head(_COM_COM . ' | ' ._COM_EDIT);
				$adminTpl->info(_COM_UPDATE);
				$adminTpl->admin_foot();
			}
			else
			{					
				$bb = new bb;
				$adminTpl->admin_head(_COM_COM . ' | ' ._COM_EDIT);
				$adminTpl->info(_BASE_ERROR_0, 'error');
				$adminTpl->admin_foot();
			}
		}
		else
		{
			location(ADMIN);
		}
		break;
		
	case 'delete':
		$commId = intval($url[3]);
		list($mod) = $db->fetchRow($db->query("SELECT module FROM `" . DB_PREFIX . "_comments` WHERE id = " . $commId . " LIMIT 1"));
		if($commId != 0 && $mod)
		{
			deleteComment($commId, $mod);
			location(ADMIN . '/comments/ok');
		}
		else
		{
			location(ADMIN);
		}
		break;
		
	case "activate":
		$id = intval($url[3]);
		$db->query("UPDATE `" . DB_PREFIX . "_comments` SET `status` = '1' WHERE `id` = " . $id . " LIMIT 1 ;");
		header('Location: /'.ADMIN.'/comments');
	break;	
	
	case "deactivate":
		$id = intval($url[3]);
		$db->query("UPDATE `" . DB_PREFIX . "_comments` SET `status` = '0' WHERE `id` = " . $id . " LIMIT 1 ;");
		header('Location: /'.ADMIN.'/comments');
	break;
		
	case "action":
		$type = $_POST['act'];
		if(is_array($_POST['checks'])) {
			switch($type) {
				case "activate":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("UPDATE `" . DB_PREFIX . "_comments` SET `status` = '1' WHERE `id` =" . $id . " LIMIT 1 ;");
					}
					break;			
				
				case "deActivate":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("UPDATE `" . DB_PREFIX . "_comments` SET `status` = '0' WHERE `id` =" . $id . " LIMIT 1 ;");
					}
					break;			
					
				case "reActivate":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("UPDATE `" . DB_PREFIX . "_comments` SET `status` = NOT `status` WHERE `id` =" . $id . " LIMIT 1 ;");
					}
					break;

				case "delete":
					foreach($_POST['checks'] as $id) 
					{
						deleteComment($id, $_POST['checks'][$id]);
					}
					break;				
			}
		}
		location(ADMIN . '/comments/ok');
		break;
}

function deleteComment($id, $mod)
{
global $db;
	add_point($mod, $id, '-');
	$db->query("DELETE FROM `" . DB_PREFIX . "_comments` WHERE `id` = ".$id);
}