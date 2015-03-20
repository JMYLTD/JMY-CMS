<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/
 
if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

$modArr[] = '';

$query = $db->query("SELECT * FROM ".DB_PREFIX."_plugins WHERE service='modules'");
while($_mod = $db->getRow($query)) 
{
	$modArr[] = $_mod['title'];
	
	if(!file_exists(ROOT.'usr/modules/'.$_mod['title'].'/index.php'))
	{
		delete($_mod['id']);
	}
}

$path = ROOT.'usr/modules/';
$dh = opendir($path);
while ($file = readdir($dh)) 
{
	if(!in_array($file, $modArr) && file_exists($path.$file.'/index.php')) 
	{
		$db->query("INSERT INTO `" . DB_PREFIX . "_plugins` (`title` , `content` , `service`  , `active` ) VALUES ('" . $file . "', '" . ucfirst($file) . "', 'modules', '1');");
	}
	delcache('plugins');
}
closedir($dh);

function delete($id, $path = '') {
global $adminTpl, $db;
	$db->query("DELETE FROM `" . DB_PREFIX . "_plugins` WHERE `id` = " . $id . " LIMIT 1");
	if($path != '') full_rmdir(ROOT . 'usr/modules/'.$path.'/');
}

function retivate($id) {
global $adminTpl, $db;
	$db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `active` = NOT `active` WHERE `id` = " . $id . " LIMIT 1 ;");
}


$server_domain = 'http://cms.jmy.su/';
switch(isset($url[2]) ? $url[2] : null) {
	default:
		$adminTpl->admin_head('Модули системы');
		if(isset($url[2]) && $url[2] == 'ok')
		{
			$adminTpl->info('Действия успешно выполнены!');
		}
		echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Список модулей:</b>						
					</div>
					<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/modules/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th class="col-md-1"><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-3">Название</th>
									<th class="col-md-1">Описание</th>
									<th class="col-md-3">Админка</th>
									<th class="col-md-2">Группы</th>
									<th class="col-md-2">Действия</th>								
									<th class="col-md-1"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_plugins WHERE service='modules' ORDER BY title ASC");
		if($db->numRows($query) > 0) 
		{
			while($mod = $db->getRow($query)) 
			{
				$status_icon = ($mod['active'] == 0) ? '<a href="{ADMIN}/modules/retivate/' . $mod['id'] . '" onClick="return getConfirm(\'' . _ACTIVATE . ' - ' . $mod['title'] . '?\')" title="' . _ACTIVATE . '" class="activate"><button type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Активировать">A</button></a>' : '<a href="{ADMIN}/modules/retivate/' . $mod['id'] . '" onClick="return getConfirm(\'' . _DEACTIVATE . ' - ' . $mod['title'] . '?\')" title="' . _DEACTIVATE . '" class="deactivate"><button type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Деактивировать">D</button></a></a>';
				echo "
				<tr>
					<td><span class=\"pd-l-sm\"></span>" . $mod['id'] . "</td>
					<td>" . $mod['title'] . "</td>
					<td>" . $mod['content'] . "</td>
					<td>" . (file_exists(ROOT.'usr/modules/'.$mod['title'].'/admin/index.php') ? '<font color="green">Да</font>' : '<font color="red">Нет</font>') . "</td>
					<td>" . ($mod['groups'] == '' ? '<i>Все</i>' : $mod['groups']) . "</td>
					<td>";
				echo $status_icon .'
				<a href="{ADMIN}/modules/edit/'. $mod['id'] .'">
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Редактировать">E</button>
				</a>
				<a href="{ADMIN}/modules/delete/'. $mod['id'] .'" onclick="return getConfirm(\'Удалить модуль - '. $mod['title'] .'?\')">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Удалить">X</button>
				</a>';
				echo "</td>
					<td> <input type=\"checkbox\" name=\"checks[]\" value=\"" . $mod['id'] . "\"></td>
				</tr>";	
			}
		} 
		else 
		{
			echo '<tr><td colspan="8" align="center">Модулей нет.. это явный сбой в системе.</td></tr>';
		}
	
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>
		
		<div align="right">
	<table>
	<tbody><tr>
	<td valign="top">
	<input name="submit" type="submit" class="btn btn-success" id="sub" value="Де/Активировать"><span class="pd-l-sm"></span>
	</td>
	</tr>
	</tbody></table>
	<br>	
	</div>
</form></div>
</section></div></div>';
		$adminTpl->admin_foot();
	break;	
		
	case 'ajax':
		$versions[1] = 'RC3';
		switch(isset($url[3]) ? $url[3] : '')
		{							
			case 'delete':
				if(isset($url[4]) && isset($url[5]))
				{
					delete(intval($url[5]), $url[4]);
					echo '<div class="_module_cat">Удаление модуля</div>';
					echo '<div class="_inff" style="margin:0;"><span style="font-size:14px; font-weight:bold;">Действие выполнено</span><br />Модуль успешно удалён с сайта.</div>';
				}
				break;
		}
		break;
	
	case 'edit':
		if(isset($url[3])) 
		{
			$modId = $url[3];
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_plugins WHERE id = '" . $modId . "'");
			$module = $db->getRow($query);
			$title = $module['content'];
			$groups = explode(',', $module['groups']);
			$unshow = explode(',', $module['unshow']);
		} 
		else
		{
			location();
		}

		$adminTpl->admin_head('Модули | Редактировать модуль');
		echo '<div class="row"><div class="col-lg-12"><section class="panel"><div class="panel-heading no-border"><b>Редактирование модуля: '.$title.'</b></div><div class="panel-body"><div class="switcher-content"><form action="{ADMIN}/modules/save" method="post" name="news" role="form" class="form-horizontal parsley-form" data-parsley-validate="" novalidate="">
		
		<div class="form-group">
					<label class="col-sm-3 control-label">Описание модуля:</label>
					<div class="col-sm-4">
					<input type="text" size="20" name="title" class="textinput" value="'.$title.'" maxlength="100" maxsize="100" />
					</div>
		</div>	
		<div class="form-group">
			<label class="col-sm-3 control-label">'._GROUP_ACCESS.'</label>
			<div class="col-sm-4">
			<select name="groups[]" class="cat_select" multiple ><option value="" ' . (empty($groups) ? 'selected' : '') . '>Все группы</option>';
			$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` ORDER BY admin DESC,moderator DESC,user DESC,guest DESC,banned DESC");
			while($rows = $db->getRow($query)) 
			{
				$selected = in_array($rows['id'], $groups) ? "selected" : "";
				echo '<option value="' . $rows['id'] . '" ' . $selected . '>' . $rows['name'] . '</option>';
			}
		echo '</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label"><b>НЕ</b>отображать блоки:</label>
			<div class="col-sm-4">
			<select name="type[]" class="cat_select" multiple>';
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_blocks_types ORDER BY type");
			while($rows = $db->getRow($query)) 
			{
				$selected = in_array($rows['type'], $unshow) ? "selected" : "";
				echo '<option value="' . $rows['type'] . '" ' . $selected . '>' . $rows['title'] . ' [' . $rows['type'] . ']</option>';
			}
		echo '</select>
			</div>
		</div>';
		if(isset($modId)) 
		{
			echo '<input type="hidden" name="id" value="' . $modId . '">';
		}
		echo '<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'. _UPDATE .'">						
					</div>
		</div></form></div></div></section></div></div>';		
		
		$adminTpl->admin_foot();
		break;
		
		
	case 'save':
		$id = isset($_POST['id']) ? intval($_POST['id']) : '';
		$title = isset($_POST['title']) ? filter($_POST['title'], 'title') : '';
		$type = isset($_POST['type']) ? $_POST['type'] : '';
		$groups = isset($_POST['groups']) ? $_POST['groups'] : false;
		
		$g = 0;
		$groupList = '';
		if(!empty($groups))
		{
			foreach($groups as $group)
			{
				if(trim($group) !== '')
				{
					$g++;
					if($g == 1)
					{
						$groupList = $group;
					}
					else
					{
						$groupList .= ',' . $group;
					}
				}
			}		
		}
		
		$d = 0;
		$deList = '';
		if(!empty($type))
		{
			foreach($type as $typ)
			{
				if(trim($typ) !== '')
				{
					$d++;
					if($d == 1)
					{
						$deList = $typ;
					}
					else
					{
						$deList .= ',' . $typ;
					}
				}
			}
		}
		
		$adminTpl->admin_head('Модули системы | Редактирование');
		if(!empty($title))
		{
			$db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `content` = '" . $title . "' , `unshow` = '" . $deList . "', `groups` = '" . $groupList . "' WHERE `id` =" . $id . " LIMIT 1 ;");
			delcache('plugins');
			$adminTpl->info('Модуль успешно обновлён. <a href="{ADMIN}/modules">Просмотреть список модулей</a>');
		}
		else
		{

		}
		
		$adminTpl->admin_foot();
			
		break;
	
	
	case "delete":
		$id = intval($url[3]);
		$path = filter($url[4]);
		delete($id, $path);
		delcache('plugins');
		location(ADMIN.'/modules/ok');
	break;
	
	case "retivate":
		$id = intval($url[3]);
		retivate($id);
		delcache('plugins');
		location(ADMIN.'/modules/ok');
	break;	
	
	case 'action':
		foreach($_POST['checks'] as $id) 
		{
			retivate(intval($id));
		}
		delcache('plugins');
		location(ADMIN.'/modules/ok');
		break;
	
}