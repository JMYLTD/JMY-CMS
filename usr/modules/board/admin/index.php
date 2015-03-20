<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ADMIN_SWITCH')) {
    header('Location: /');
    exit;
}

switch(isset($url[3]) ? $url[3] : null) 
{
	default:
		$adminTpl->admin_head('Модули | Форум');
		$query = $db->query("SELECT id, title, pid, position FROM ".DB_PREFIX."_board_forums ORDER BY pid");
		while($rows = $db->getRow($query)) 
		{
			$cat_get[$rows['id']] = array($rows['title'], $rows['pid'], $rows['position']);
		}
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Список форумов</b>
					</div>';	
		if(isset($cat_get))
		{
			foreach ($cat_get as $cid => $sub_arr) 
			{
				if($cid != $sub_arr[1])
				{
					$cats_arr[$cid] = array($sub_arr[0], $sub_arr[0]);
					$flag = $sub_arr[1];
					while ($flag > "0") 
					{
						$cats_arr[$cid] = array($cat_get[$flag][0]." / ".$cats_arr[$cid][0], $sub_arr[0]);
						$flag = $cat_get[$flag][1];
					}
				}
				else
				{
					$core->tpl->info('Обнаружено фатальное несоответсвие! Форум ' . $cid . ' является своим же подфорумом!');
				}
			}
			asort($cats_arr);		
		echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{MOD_LINK}/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-5">Форум</th>
									<th class="col-md-4">Разметка</th>	
									<th class="col-md-2">Положение</th>											
									<th class="col-md-3">' . _ACTIONS . '</th>
									<th class="col-md-4"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';			

			foreach ($cats_arr as $cid => $arrName) 
			{
				$name = $arrName[0];
				$massa = explode(' / ', $name);
				echo '<tr>
				<td><span class="pd-l-sm"></span>' . $cid . '</td>
				<td>'.$name.'</td>
				<td>[<a href="{MOD_LINK}/add/' . $cid . '">Создать подфорум</a> | <a href="{MOD_LINK}/rules/' . $cid . '">Прикрепить правила</a>]</td>				
				<td>' . (count($massa) < 3 ? '<div><input name="posit[' . $cid . ']" value="' . $cat_get[$cid][2] . '" style="width:20px; ' . (!eregStrt(' / ', $name) ? 'font-weight: bold;' : '') . '" /></div>' : '<div>Нет опций</div>' ). '</td>
				<td>
					<a href="{MOD_LINK}/edit/' . $cid . '">
					<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
					</a>
					<a href="{MOD_LINK}/delete/' .$cid . '" onClick="return getConfirm(\'Вы ддействиетльно хотите удалить форум - ' . str(htmlspecialchars(strip_tags($name)), 40) . '?\')">
					<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .	'">X</button>
					</a>
				</td>
				<td><input type="checkbox" name="checks[]" value="' . $cid . '"></td>
				</tr>';
			}
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>
				<div align="right">
				<table>
				<tr>
				<td valign="top">
				<select name="do">
					<option value="sort">Сотртировать</option>
						<option value="delete">Удалить</option>
						<option value="permis">Сменить права</option>
				</select>
				</td>
				<td>&nbsp&nbsp</td>
				
				<td valign="top">
				<input name="submit" type="submit" class="btn btn-success" id="sub" value="' . _EDIT . '" /><span class="pd-l-sm"></span>
				</td>
				</tr>
				</table>		
				</div>
				</form></div>';	
		}
		else
		{
			echo '<div class="panel-heading">Вы ещё не добавили ниодного форума!</div>';			
		}
		echo'</section></div></div>';
		$adminTpl->admin_foot();
		break;
		
	case 'add':
		$adminTpl->admin_head('Модули | Форум | Создать форум');
		$fid = isset($url[4]) ? intval($url[4]) : 0;
		$adminTpl->open();
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Добавление форума</b>						
					</div>					
					<div class="panel-body">
						<form class="form-horizontal parsley-form" role="form" action="{MOD_LINK}/save" method="post"  data-parsley-validate>
												<div class="form-group">
													<label class="col-sm-3 control-label">Название форума</label>
													<div class="col-sm-4">
														<input value="" type="text" name="title" id="title" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Тип форума</label>
													<div class="col-sm-4">
														<select name="ftype">
															<option value="c">Категория</option>
															<option value="f" selected>Форум</option>
														</select>														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Описание форума</label>
													<div class="col-sm-4">
														<textarea cols="30" rows="5" name="descr" class="form-control" data-parsley-required="true" data-parsley-trigger="change"></textarea>														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Родительский форум</label>
													<div class="col-sm-4">
														<select name="pid"><option value="0">Без форума</option>';
										$query = $db->query("SELECT id, title, pid FROM ".DB_PREFIX."_board_forums ORDER BY pid");
										while($rows = $db->getRow($query)) 
										{
											$cat_get[$rows['id']] = array($rows['title'], $rows['pid']);
										}
										
										if(isset($cat_get))
										{
											foreach ($cat_get as $cid => $sub_arr) 
											{
												if($cid != $sub_arr[1])
												{
													$cats_arr[$cid] = $sub_arr[0];
													$flag = $sub_arr[1];
													while ($flag != "0") 
													{
														$cats_arr[$cid] = $cat_get[$flag][0]." / ".$cats_arr[$cid];
														$flag = $cat_get[$flag][1];
													}
												}
											}
											
											asort($cats_arr);
											
											foreach ($cats_arr as $cid => $name) 
											{
												$selected = ($cid == $fid) ? "selected" : "";
												echo '<option value="' . $cid . '" ' . $selected . '>' . $name . '</option>';
											}
										}
										echo '			</select>													
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Права групп</label>
													<div class="col-sm-4">';
		$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` ORDER BY name");
		echo '<table width="100%" cellpadding="3" cellspacing="1" class="table no-margin">
		<thead>
		<tr>
			<th width="200">Группа</th>
			<th >Просмотр</th>
			<th >Чтение</th>
			<th >Создание</th>
			<th >Ответ</th>
			<th >Редактирование</th>
			<th >Модерирование</th>
			<th >Файлы</th>
			<th >Всё</th>
		</tr>
		</thead>
		<tbody>';
		while($rows = $db->getRow($query)) 
		{
			echo '<tr>
			<td align="left">' . $rows['name'] . '</td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowView]" style="border:none;" ' . ($rows['guest'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowRead]" style="border:none;" ' . ($rows['guest'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowCreate]" style="border:none;" ' . ($rows['user'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowReply]" style="border:none;" ' . ($rows['user'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowEdit]" style="border:none;" ' . ($rows['user'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowModer]" style="border:none;" ' . ($rows['moderator'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowAttach]" style="border:none;" ' . ($rows['user'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][all]" style="border:none;" /><i class="checkbox"></i></label></td>
			</tr>';
		}
		echo '</tbody></table></div></div>
		
		<div class="form-group">
														<label class="col-sm-3 control-label"></label>
														<div class="col-sm-4">
															<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="Добавить форум">						
														</div>
												</div>';
		echo '</form>';
echo '</div>';
			echo'</section></div></div>';	
		$adminTpl->close();
		$adminTpl->admin_foot();
		break;
		
	case 'save':
		$adminTpl->admin_head('Модули | Форум | Сохранение форума');
		$title = filter($_POST['title'], 'title');
		$descr = filter($_POST['descr']);
		$pid = intval($_POST['pid']);
		$ftype = filter($_POST['ftype'], 'a');
		if($title)
		{
			$db->query("INSERT INTO `" . DB_PREFIX . "_board_forums` ( `title` , `description` , `pid` , `type` , `position`) VALUES ('" . $db->safesql($title) . "', '" . $db->safesql($descr) . "', '" . $pid . "', '" . $ftype . "', '1');");
			list($fid) = $db->fetchRow($db->query("SELECT id FROM " . DB_PREFIX . "_board_forums WHERE title='" . $db->safesql($title) . "' AND description = '" . $db->safesql($descr) . "' AND pid = '" . $pid . "' LIMIT 1"));
			foreach($_POST['permissions'] as $gid => $infos)
			{
				$allowView[$gid] = isset($infos['allowView']) ? 1 : 0;
				$allowRead[$gid] = isset($infos['allowRead']) ? 1 : 0;
				$allowCreate[$gid] = isset($infos['allowCreate']) ? 1 : 0;
				$allowReply[$gid] = isset($infos['allowReply']) ? 1 : 0;
				$allowEdit[$gid] = isset($infos['allowEdit']) ? 1 : 0;
				$allowModer[$gid] = isset($infos['allowModer']) ? 1 : 0;
				$allowAttach[$gid] = isset($infos['allowAttach']) ? 1 : 0;
				$db->query("INSERT INTO `" . DB_PREFIX . "_board_permissions` ( `id` , `fid` , `gid` , `allowView` , `allowRead` , `allowCreate` , `allowReply` , `allowEdit` , `allowModer` , `allowAttach` ) VALUES (NULL, '" . $fid . "', '" . $gid . "', '" . $allowView[$gid] . "', '" . $allowRead[$gid]. "', '" . $allowCreate[$gid] . "', '" . $allowReply[$gid] . "', '" . $allowEdit[$gid] . "', '" . $allowModer[$gid] . "', '" . $allowAttach[$gid] . "');");
			}
			
			$adminTpl->info('Форум успешно создан. <a href="{MOD_LINK}/add">Создать ешё</a> или <a href="{MOD_LINK}">к списку</a>');
		}
		else
		{
			$adminTpl->info('Не заполнено поле с названием форума! <a href="{MOD_LINK}">К списку форумов</a>', 'error');
		}
		$adminTpl->admin_foot();
		break;
		
	case 'action':
		$adminTpl->admin_head('Модули | Форум | Действия');
		$do = filter($_POST['do'], 'a');
	
		switch($do)
		{
			case 'sort':
				foreach($_POST['posit'] as $id => $posit)
				{
					if($id > 0)
					{
						$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET `position` = '" . intval($posit) . "' WHERE `id` =" .intval($id) . " LIMIT 1 ;");
					}
				}
				
				$adminTpl->info('Форумы успешно отсортированы.  <a href="{MOD_LINK}">К списку</a>');
				break;
				
			case 'delete':
				foreach($_POST['posit'] as $id)
				{
					if($id > 0)
					{
						list($count) = $db->fetchRow($db->query("SELECT COUNT(id) FROM " . DB_PREFIX . "_board_forums WHERE pid='" . intval($id) . "' LIMIT 1"));
						if($count < 1)
						{
							$db->query("DELETE FROM `" . DB_PREFIX . "_board_forums` WHERE `id` = " . intval($id) . " LIMIT 1");
							$db->query("DELETE FROM `" . DB_PREFIX . "_board_threads` WHERE `forum` = " . intval($id) . " LIMIT 1");
							$adminTpl->info('Форум успешно удален.  <a href="{MOD_LINK}">К списку</a>');
						}
						else
						{
							$adminTpl->info('Для начала удалите все подфорумы!  <a href="{MOD_LINK}">К списку</a>');
						}
					}
				}
				break;
				
			case 'savePerms':
				foreach($_POST['fid'] as $fid)
				{
					$db->query("DELETE FROM `" . DB_PREFIX . "_board_permissions` WHERE `fid` = '" . intval($fid) . "'");
				}
				
				foreach($_POST['permissions'] as $gid => $infos)
				{
					$allowView[$gid] = isset($infos['allowView']) ? 1 : 0;
					$allowRead[$gid] = isset($infos['allowRead']) ? 1 : 0;
					$allowCreate[$gid] = isset($infos['allowCreate']) ? 1 : 0;
					$allowReply[$gid] = isset($infos['allowReply']) ? 1 : 0;
					$allowEdit[$gid] = isset($infos['allowEdit']) ? 1 : 0;
					$allowModer[$gid] = isset($infos['allowModer']) ? 1 : 0;
					$allowAttach[$gid] = isset($infos['allowAttach']) ? 1 : 0;
					
					foreach($_POST['fid'] as $fid)
					{
						$db->query("INSERT INTO `" . DB_PREFIX . "_board_permissions` ( `id` , `fid` , `gid` , `allowView` , `allowRead` , `allowCreate` , `allowReply` , `allowEdit` , `allowModer` , `allowAttach` ) VALUES (NULL, '" . $fid . "', '" . $gid . "', '" . $allowView[$gid] . "', '" . $allowRead[$gid]. "', '" . $allowCreate[$gid] . "', '" . $allowReply[$gid] . "', '" . $allowEdit[$gid] . "', '" . $allowModer[$gid] . "', '" . $allowAttach[$gid] . "');");
					}
				}
				$adminTpl->info('Права успешно установлены.');
				break;
				
			case 'permis':
				$fid = isset($url[4]) ? intval($url[4]) : 0;				
				$adminTpl->info('Внимание! Права будут изменены у всех выделенных форумов и подфорумов! Будте внимательны!');
				$adminTpl->open();
				echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Глобальная смена прав:</b>						
					</div>
					<div class="panel-body no-padding">';
				echo "<form action=\"{MOD_LINK}/action\"  method=\"post\" name=\"forum\">";
				$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` ORDER BY name");
		echo '<table width="100%" cellpadding="3" cellspacing="1" class="table no-margin">
		<thead>
		<tr>
			<th width="200"><span class="pd-l-sm"></span>Группа</th>
			<th style="text-align: center;" >Просмотр</th>
			<th style="text-align: center;" >Чтение</th>
			<th style="text-align: center;" >Создание</th>
			<th style="text-align: center;" >Ответ</th>
			<th style="text-align: center;" >Редактирование</th>
			<th style="text-align: center;" >Модерирование</th>
			<th style="text-align: center;" >Файлы</th>
			<th style="text-align: center;" >Всё</th>
		</tr>
		</thead>
		<tbody>';
		while($rows = $db->getRow($query)) 
		{
			echo '<tr>
			<td ><span class="pd-l-sm"></span>' . $rows['name'] . '</td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowView]" style="border:none;" ' . ($rows['guest'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowRead]" style="border:none;" ' . ($rows['guest'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowCreate]" style="border:none;" ' . ($rows['user'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowReply]" style="border:none;" ' . ($rows['user'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowEdit]" style="border:none;" ' . ($rows['user'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowModer]" style="border:none;" ' . ($rows['moderator'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowAttach]" style="border:none;" ' . ($rows['user'] == 1 ? 'checked' : '' ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][all]" style="border:none;" /><i class="checkbox"></i></label></td>
			</tr>';
		}
		foreach($_POST['posit'] as $id)
				{
					echo '<input type="hidden" name="fid[]" value="' . $id . '" />';
				}
				echo '<input type="hidden" name="do" value="savePerms" />';
		
			echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>		
		<div align="right">
		<table>
		<tbody><tr>
		<td valign="top">
		<input name="submit" type="submit" class="btn btn-success" id="sub" value="Сменить права"><span class="pd-l-sm"></span>
		</td>
		</tr>
		</tbody></table>
		<br>	
		</div>
		</form></div>';				
				
				$adminTpl->close();
				break;
		}
		$adminTpl->admin_foot();
		break;
		
	case 'delete':
		$adminTpl->admin_head('Модули | Форум | Удаление форума');
		if(isset($url[4]))
		{
			list($count) = $db->fetchRow($db->query("SELECT COUNT(id) FROM " . DB_PREFIX . "_board_forums WHERE pid='" . intval($url[4]) . "' LIMIT 1"));
			if($count < 1)
			{
				$db->query("DELETE FROM `" . DB_PREFIX . "_board_forums` WHERE `id` = " . intval($url[4]) . " LIMIT 1");
				$db->query("DELETE FROM `" . DB_PREFIX . "_board_threads` WHERE `forum` = " . intval($url[4]) . " LIMIT 1");
				$adminTpl->info('Форум успешно удален.  <a href="{MOD_LINK}">К списку</a>');
			}
			else
			{
				$adminTpl->info('Для начала удалите все подфорумы!  <a href="{MOD_LINK}">К списку</a>');
			}
		}
		else
		{
			$adminTpl->info('ОШИБКА НЕИЗВЕСТНОГО ТИПА!.  <a href="{MOD_LINK}">К списку</a>', 'error');
		}
		$adminTpl->admin_foot();
		break;
		
	case 'edit':
		$adminTpl->admin_head('Модули | Форум | Редактировать форум');
		if(isset($_POST['title']))
		{
			$title = filter($_POST['title']);
			$descr = filter($_POST['descr']);
			$pid = intval($_POST['pid']);
			$ftype = filter($_POST['ftype'], 'a');
			$fid = intval($_POST['fid']);
			
			if($title && $fid && $fid != $pid)
			{
				$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET `title` = '" . $db->safesql($title) . "', `description` = '" . $db->safesql($descr) . "', `pid` = '" . $pid . "', `type` = '" . $db->safesql($ftype) . "' WHERE `id` =" . $fid . " LIMIT 1 ;");
				if(isset($_POST['permissions']))
				{
					$db->query("DELETE FROM `" . DB_PREFIX . "_board_permissions` WHERE `fid` = '" . $fid . "'");
					foreach($_POST['permissions'] as $gid => $infos)
					{
						$allowView[$gid] = isset($infos['allowView']) ? 1 : 0;
						$allowRead[$gid] = isset($infos['allowRead']) ? 1 : 0;
						$allowCreate[$gid] = isset($infos['allowCreate']) ? 1 : 0;
						$allowReply[$gid] = isset($infos['allowReply']) ? 1 : 0;
						$allowEdit[$gid] = isset($infos['allowEdit']) ? 1 : 0;
						$allowModer[$gid] = isset($infos['allowModer']) ? 1 : 0;
						$allowAttach[$gid] = isset($infos['allowAttach']) ? 1 : 0;
						$db->query("INSERT INTO `" . DB_PREFIX . "_board_permissions` ( `id` , `fid` , `gid` , `allowView` , `allowRead` , `allowCreate` , `allowReply` , `allowEdit` , `allowModer` , `allowAttach` ) VALUES (NULL, '" . $fid . "', '" . $gid . "', '" . $allowView[$gid] . "', '" . $allowRead[$gid]. "', '" . $allowCreate[$gid] . "', '" . $allowReply[$gid] . "', '" . $allowEdit[$gid] . "', '" . $allowModer[$gid] . "', '" . $allowAttach[$gid] . "');");
					}
				}
				
				$adminTpl->info('Форум успешно обновлён. <a href="{MOD_LINK}">К списку</a>');
			}
			else
			{
				if($pid == $fid)
				{
					$adminTpl->info('Форум не может являться своим же подфорумом!', 'error');
				}
				else
				{
					$adminTpl->info('Не заполнено поле с названием форума! <a href="{MOD_LINK}">К списку форумов</a>', 'error');
				}
			}
		}
		else
		{
			$fid = isset($url[4]) ? intval($url[4]) : 0;
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_board_forums WHERE id = '" . $fid . "'");
			$forum = $db->getRow($query);
			$adminTpl->open();
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Добавление форума</b>						
					</div>					
					<div class="panel-body">
						<form class="form-horizontal parsley-form" role="form" action="{MOD_LINK}/edit" method="post"  data-parsley-validate>
												<div class="form-group">
													<label class="col-sm-3 control-label">Название форума</label>
													<div class="col-sm-4">
														<input value="'. $forum['title'] .'" type="text" name="title" id="title" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Тип форума</label>
													<div class="col-sm-4">
														<select name="ftype">
															<option value="c" '. ($forum['type'] == 'c' ? 'selected' : '') .'>Категория</option>
															<option value="f" '. ($forum['type'] == 'f' ? 'selected' : '') .'>Форум</option>
														</select>														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Описание форума</label>
													<div class="col-sm-4">
														<textarea cols="30" rows="5" name="descr" class="form-control" data-parsley-required="true" data-parsley-trigger="change">'. $forum['description'] .'</textarea>														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Родительский форум</label>
													<div class="col-sm-4">
														<select name="pid"><option value="0">Без форума</option>';
										$query = $db->query("SELECT id, title, pid FROM ".DB_PREFIX."_board_forums ORDER BY pid");
										while($rows = $db->getRow($query)) 
										{
											$cat_get[$rows['id']] = array($rows['title'], $rows['pid']);
										}
										
										foreach ($cat_get as $cid => $sub_arr) 
										{
											if($cid != $sub_arr[1])
											{
												$cats_arr[$cid] = $sub_arr[0];
												$flag = $sub_arr[1];
												while ($flag != "0") 
												{
													$cats_arr[$cid] = $cat_get[$flag][0]." / ".$cats_arr[$cid];
													$flag = $cat_get[$flag][1];
												}
											}
										}
										
										asort($cats_arr);
										
										foreach ($cats_arr as $cid => $name) 
										{
											$selected = ($cid == $forum['pid']) ? "selected" : "";
											echo '<option value="' . $cid . '" ' . $selected . '>' . $name . '</option>';
										}
										echo '			</select>													
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Права групп</label>
													<div class="col-sm-4">';
		$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` ORDER BY name");
		echo '<table width="100%" cellpadding="3" cellspacing="1" class="table no-margin">
		<thead>
		<tr>
			<th width="200">Группа</th>
			<th >Просмотр</th>
			<th >Чтение</th>
			<th >Создание</th>
			<th >Ответ</th>
			<th >Редактирование</th>
			<th >Модерирование</th>
			<th >Файлы</th>
			<th >Всё</th>
		</tr>
		</thead>
		<tbody>';
		while($rows = $db->getRow($query)) 
		{
			echo '<tr>
			<td align="left">' . $rows['name'] . '</td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowView]" style="border:none;" ' . (isset($newPerm[$rows['id']]) && $newPerm[$rows['id']]['allowView'] == 1 ? 'checked' : ($rows['guest'] == 1 ? 'checked' : '' ) ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowRead]" style="border:none;" ' . (isset($newPerm[$rows['id']]) && $newPerm[$rows['id']]['allowRead'] == 1 ? 'checked' : ($rows['guest'] == 1 ? 'checked' : '' ) ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowCreate]" style="border:none;" ' .(isset($newPerm[$rows['id']]) && $newPerm[$rows['id']]['allowCreate'] == 1 ? 'checked' : ($rows['user'] == 1 ? 'checked' : '' ) ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowReply]" style="border:none;" ' . (isset($newPerm[$rows['id']]) && $newPerm[$rows['id']]['allowReply'] == 1 ? 'checked' : ($rows['user'] == 1 ? 'checked' : '' ) ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowEdit]" style="border:none;" ' . (isset($newPerm[$rows['id']]) && $newPerm[$rows['id']]['allowEdit'] == 1 ? 'checked' :  ($rows['user'] == 1 ? 'checked' : '' ) ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowModer]" style="border:none;" ' .(isset($newPerm[$rows['id']]) && $newPerm[$rows['id']]['allowModer'] == 1 ? 'checked' : ($rows['moderator'] == 1 ? 'checked' : '' ) ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][allowAttach]" style="border:none;" ' . (isset($newPerm[$rows['id']]) && $newPerm[$rows['id']]['allowAttach'] == 1 ? 'checked' : ($rows['user'] == 1 ? 'checked' : '' ) ) . ' /><i class="checkbox"></i></label></td>
			<td align="center"><label class="checkbox checkbox-custom"><input type="checkbox" name="permissions[' . $rows['id'] . '][all]" style="border:none;" /><i class="checkbox"></i></label></td>
			</tr>';
		}
		echo '</tbody></table></div></div>
		<input type="hidden" name="fid" value="' . $fid . '" />
		<div class="form-group">
														<label class="col-sm-3 control-label"></label>
														<div class="col-sm-4">
															<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="Редактировать форум">						
														</div>
												</div>';
		echo '</form>';
echo '</div>';
			echo'</section></div></div>';	
		$adminTpl->close();
			
			
		}
		$adminTpl->admin_foot();
		break;
		
	case 'rules':
		$adminTpl->admin_head('Модули | Форум | Правила форума');
		$fid = isset($url[4]) ? intval($url[4]) : 0;
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_board_forums WHERE id = '" . $fid . "'");
		$forum = $db->getRow($query);		
		$adminTpl->open();
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Правила форума</b>						
					</div>
					<div class="panel-body">
						<form class="form-horizontal parsley-form" role="form" action="{MOD_LINK}/rulesSave" method="post"  data-parsley-validate>
												<div class="form-group">
													<label class="col-sm-3 control-label">Имя правил</label>
													<div class="col-sm-4">
														<input value="'. $forum['rulestitle'] .'" type="text" name="title" id="title" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Правила</label>
													<div class="col-sm-4">
														<textarea cols="30" rows="5" name="vars" class="form-control" id="vars" data-parsley-required="true" data-parsley-trigger="change">'. $forum['rules'] .'</textarea>
													</div>
												</div>
												<input type="hidden" name="fid" value="' . $fid . '" />
												<div class="form-group">
														<label class="col-sm-3 control-label"></label>
														<div class="col-sm-4">
															<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="Сохранить">						
														</div>
												</div>';
												echo '</form>';
echo '</div>';
			echo'</section></div></div>';	
		
		
		
		
		
		
		
	
		$adminTpl->close();
		$adminTpl->admin_foot();
		break;
	
	case 'rulesSave':
		$title = filter($_POST['title']);
		$descr = filter($_POST['descr']);
		$fid = intval($_POST['fid']);
		$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET `rulestitle` = '" . $db->safesql($title) . "', `rules` = '" . $db->safesql($descr) . "' WHERE `id` =" . $fid . " LIMIT 1 ;");

		$adminTpl->admin_head('Модули | Форум | Правила форума');
		$adminTpl->info('Правила форума обновлены. <a href="{MOD_LINK}">К списку</a>');
		$adminTpl->admin_foot();
		break;
		
	case 'config':
		require (ROOT.'etc/board.config.php');
		
		$configBox = array(
			'board' => array(
				'varName' => 'board_conf',
				'title' => 'Настройки модуля "Форум"',
				'groups' => array(
					'main' => array(
						'title' => 'Основные настройки',
						'vars' => array(
							'posts_num' => array(
								'title' => 'Постов на страницу',
								'description' => 'Сколько отображать постов на одну страницу',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),						
							'threads_num' => array(
								'title' => 'Тем на страницу',
								'description' => 'Количество топиков на одну страницу',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'loadFiles' => array(
								'title' => 'Разрешить загрузку файлов',
								'description' => 'Активация штатного загрузчика файлов',
								'content' => radio("loadFiles", $board_conf['loadFiles']),
							),							
						)
					),
					'files_formats' => array(
						'title' => 'Файловый редактор',
						'vars' => array(
		
							'maxWH' => array(
								'title' => 'Ширина превью',
								'description' => 'Картинки автоматически сжимаются до указанного размера(указывается в пикселях)',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'maxSize' => array(
								'title' => 'Максимальный вес файла',
								'description' => 'Максимальный вес файла в Байтах',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'formats' => array(
								'title' => 'Допустимые форматы файлов',
								'description' => 'Допустимые форматы для загрузки',
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
		
		generateConfig($configBox, 'board', '{MOD_LINK}/config', $ok);
		break;
}