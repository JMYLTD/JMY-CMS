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
global $config;

if(file_exists(ROOT . 'usr/langs/'. $config['lang'] . '.blocks.php'))
{
	include(ROOT . 'usr/langs/'. $config['lang'] . '.blocks.php');
}
		

switch(isset($url[2]) ? $url[2] : null) {
	default:
		$adminTpl->admin_head(_AP_BLOCKS);
		echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _AP_BLOCKS . '</b>						
					</div>';
	$queryTypes = $db->query("SELECT * FROM ".DB_PREFIX."_blocks_types ORDER BY title ASC");
	while ($_type = $db->getRow($queryTypes)) $_types[$_type['type']] = $_type['title']; 
	$query = $db->query("SELECT * FROM ".DB_PREFIX."_plugins WHERE service='blocks' ORDER BY type ASC, priority ASC");
	if($db->numRows($query) > 0) 
	{
		echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/blocks/action">
						<table class="table no-margin"  id="blockBox">';		
		blockList();	
		echo '</table>
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
		<input name="submit" type="submit" class="btn btn-success" id="sub" value="' . _EDIT . '" /><span class="pd-l-sm"></span>
		</td>
		</tr>
		</table>		
	</div>
	</form></div>';
	}
	else
		{
			echo '<div class="panel-heading">'._BLOCK_EMPTY.'</div>';					
		}
		echo'</section></div></div>';	
		$adminTpl->admin_foot();
		break;

	case 'add':
		if(isset($url[3]))
		{
			$bid = intval($url[3]);
			$bquery = $db->query("SELECT * FROM ".DB_PREFIX."_plugins WHERE id = '" . $bid . "'");
			$bl = $db->getRow($bquery);
			if(!$bl)
			{
				location('/' . ADMIN . '/blocks');
			}
			$bb = new bb;
			$title = prepareTitle($bl['title']);
			$bfile = $bl['file'];
			$type = $bl['type'];
			$content = $bb->htmltobb($bl['content']);
			$active = $bl['active'];
			$posit = $bl['priority'];
			$free = $bl['free'];
			$unshow = $bl['unshow'];
			$tit = _BLOCK_EDIT;
		
			if($unshow)
			{
				$modArrDb = explode(',', $bl['unshow']);
			}
			else
			{
				$modArrDb = explode(',', $bl['showin']);
			}
			
			$grroups = explode(',', $bl['groups']);
		}
		else
		{
			$title = '';
			$bfile = '';
			$type = '';
			$content = '';
			$modArrDb = array('_all');
			$active = true;
			$free = 0;
			$unshow = '';
			$posit = '';
			$grroups = array();
			$tit = _BLOCK_ADD;
		}
		$adminTpl->admin_head(_AP_BLOCKS . ' | ' . (isset($bid) ? _EDIT : _ADD) . ' '._BBLOCK);	
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . $tit . '</b>						
					</div>
					<div class="panel-body">
						<form class="form-horizontal name="blocks" parsley-form" role="form" action="{ADMIN}/blocks/save" method="post">
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _TITLE . '</label>
													<div class="col-sm-4">
														<input value="' . $title . '" id="title" type="text" name="name" class="form-control" data-parsley-required="true" data-parsley-trigger="change" \>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _BLOCK_FILE . '</label>
													<div class="col-sm-4">
														<select name="file" id="file">
														<option value="" ' . ($bfile == '' ? 'selected' : '') . ">" . _BLOCK_FILE_WITHOUT . '</option>';
														$path = ROOT.'usr/blocks/';
														$dh = opendir($path);
														$c=0;
														while ($file = readdir($dh)) 
														{
															if(is_file($path.$file) && $file != '.' && $file != '..' && $file != '.htaccess') 
															{
																$select = ($bfile == $file) ? 'selected' : '';
																echo '<option value="'.$file.'" '.$select.'>'.$file.'</option>';
															}
														}
														closedir($dh);
														echo '</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _BLOCK_DESTANATION . '</label>
													<div class="col-sm-4">
														<select name="type" id="type">';
														$query = $db->query("SELECT * FROM ".DB_PREFIX."_blocks_types ORDER BY type");
														while($rows = $db->getRow($query)) 
														{
															$select = ($type == $rows['type']) ? 'selected' : '';
															echo '<option value="' . $rows['type'] . '" ' . $select . '>' . $rows['title'] . ' [' . $rows['type'] . ']</option>';
														}
														echo '</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _BLOCK_GROUPS . '</label>
													<div class="col-sm-4">
														<select name="groups[]" id="group" multiple ><option value="" ' . (empty($grroups) ? 'selected' : '') . '>Все группы</option>';
														$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` ORDER BY admin DESC,moderator DESC,user DESC,guest DESC,banned DESC");
														while($rows = $db->getRow($query)) 
														{
															$selected = in_array($rows['id'], $grroups) ? "selected" : "";
															echo '<option value="' . $rows['id'] . '" ' . $selected . '>' . $rows['name'] . '</option>';
														}
														echo '</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._MODULES.'</label>
													<div class="col-sm-8">';													
													foreach(glob(ROOT.'usr/modules/*/index.php') as $dir)
													{
														$dir_a = explode('/', $dir);
														$fileArr[] = $dir_a[count($dir_a)-2];
													}
													$limitCheck = ceil(count($fileArr)/3);		
													$i = 0;
													echo '<div style="float:left; width:150px;">';
													foreach($fileArr as $file)
													{
														$i++;
														$check = in_array($file, $modArrDb) ? 'checked' : '';
														echo '
														<label class="checkbox checkbox-custom"><input name="mod[]" value="' . $file . '" type="checkbox" ' . $check . '><i class="checkbox ' . $check . '"></i> ' . _mName($file) . '</label><br />';
														if($i == $limitCheck)
														{
															$i = 0;
															echo '</div>';
															echo '<div style="float:left; width:150px;">';
														}
													}
													echo '</div><br style="clear:both" /><hr />';
													echo '<table width=100%>
													<tr>
													<td>';
													echo '<label class="checkbox checkbox-custom"><input type="checkbox" name="mod[]" value="_free" class="checkbox" ' . ($free == 1 ? 'checked' : '') . ' /><i class="checkbox ' . $check . '"></i> ' . _BLOCK_FREE . '</label></td><td> ';
													echo '<label class="checkbox checkbox-custom"><input type="checkbox" name="mod[]" value="_index" class="checkbox" ' . (in_array('_index', $modArrDb) ? 'checked' : '') . ' /><i class="checkbox ' . $check . '"></i> ' . _BLOCK_INDEX . ' </label></td><td>';
													echo '<label class="checkbox checkbox-custom"><input type="checkbox" name="mod[]" value="_noChecked" class="checkbox" ' . ($unshow != '' ? 'checked' : '') . ' /><i class="checkbox ' . $check . '"></i> ' . _BLOCK_UNSHOW . '</label></div></td></tr></table>';
													if(isset($bid)) echo '<input name="bid" value="' . $bid . '" type="hidden" />';
													echo '
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _BLOCK_POS . '</label>
													<div class="col-sm-4">
														<input value="' . $posit . '" id="posit" type="text" name="posit" class="form-control" data-parsley-required="true" data-parsley-trigger="change" \>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _BLOCK_ACTIVE  . '</label>
													<div class="col-sm-4">
														'.checkbox('active', $active).'
													</div>
												</div>
											</div>
										</section></div></div>';
										echo '<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<div class="panel-heading no-border"><b>'._TEXT.'</b></div>
							<div class="panel-body">
								<div class="switcher-content">
								'.adminArea('contents', $content, 10, 'textarea', '', true).'
									<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'.(isset($bid) ? _EDIT : _ADD).'">
									</form>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>';	
		
		$adminTpl->admin_foot();
		break;
	
	case 'save':
		$allowArray = array('_all', '_index');
		$title = isset($_POST['name']) ? filter(htmlspecialchars_decode($_POST['name']), 'title') : '';
		$file = isset($_POST['file']) ? filter($_POST['file'], 'a') : '';
		$type = isset($_POST['type']) ? filter($_POST['type'], 'a') : '';
		$content = isset($_POST['contents']) ? filter($_POST['contents'], 'html') : '';
		$posit = isset($_POST['posit']) ? intval($_POST['posit']) : '';
		$mod = isset($_POST['mod']) ? $_POST['mod'] : '';
		$active = isset($_POST['active']) ? 1 : false;
		$groups = isset($_POST['groups']) ? $_POST['groups'] : false;
		$bid = isset($_POST['bid']) ? intval($_POST['bid']) : false;
		$unList = '';
		$modList = '';
		$free = 0;
		if(!empty($title) && (!empty($file) OR !empty($content)))
		{
			
			if(!empty($mod))
			{
				$i = 0;
				
				if(array_search('_noChecked', $mod)) $unSearch = true;
				if(is_numeric(array_search('_free', $mod))) $free = 1;

				if(isset($unSearch))
				{
					foreach($mod as $module)
					{
						if(trim($module) !== '' && in_array($module, $allowArray))
						{
							$i++;
							if($i == 1)
							{
								$modList = $module;
							}
							else
							{
								$modList .= ',' . $module;
							}
						}
					}
					
					$u = 0;
					foreach($mod as $module)
					{
						if(trim($module) !== '' && !in_array($module, $allowArray) && $module != '_noChecked')
						{
							$u++;
							if($u == 1)
							{
								$unList = $module;
							}
							else
							{
								$unList .= ',' . $module;
							}
						}
					}
				}
				else
				{
					foreach($mod as $module)
					{
						if(trim($module) !== '')
						{
							$i++;
							if($i == 1)
							{
								$modList = $module;
							}
							else
							{
								$modList .= ',' . $module;
							}
						}
					}
				}
			}
			else
			{
				$modList = '_all';
			}
			
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
			
			if(!$posit)
			{
				$query = $db->query("SELECT priority FROM `" . DB_PREFIX . "_plugins` WHERE `type`='" . $db->safesql($type) . "' ORDER BY `priority` DESC LIMIT 1");
				$rows = $db->getRow($query);
				if(isset($rows['priority']))
				{
					$posit = $rows['priority']+1;
				}
				else
				{
					$posit = 0;
				}
			}
			
			$bb = new bb;
			if($bid)
			{
				$db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `title` = '" . $db->safesql(processText($title)) . "', `content` = '" . $db->safesql($bb->parse(processText($content), false, true)) . "', `file` = '" . $file . "', `priority` = '" . $posit . "', `type` = '" . $type . "', `showin` = '" . $modList . "', `unshow` = '" . $unList . "', `groups` = '" . $groupList . "', `free` = '" . $free . "', `template` = '', `active` = '" . $active . "' WHERE `id` =" . $bid . " LIMIT 1 ;");
			}
			else
			{
				$db->query("INSERT INTO `" . DB_PREFIX . "_plugins` ( `id` , `title` , `content` , `file` , `priority` , `type` , `service` , `showin` , `unshow` , `groups` , `free` , `template` , `active` ) VALUES ('', '" . $db->safesql(processText($title)) . "', '" . $db->safesql($bb->parse(processText($content), false, true)) . "', '" . $file . "', '" . $posit . "', '" . $type . "', 'blocks', '" . $modList . "', '" . $unList . "', '" . $groupList . "', '" . $free . "', '', '" . $active . "');");
			}
			delcache('plugins');
			location('/'.ADMIN.'/blocks/ok');
		}
		else
		{
			location('/'.ADMIN.'/blocks/error');
		}
		break;
		
	case "action":
		$type = $_POST['act'];
		if(is_array($_POST['checks'])) {
			switch($type) {
				case "activate":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `active` = '1' WHERE `id` =" . $id . " LIMIT 1 ;");
					}
					break;			
				
				case "deActivate":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `active` = '0' WHERE `id` =" . $id . " LIMIT 1 ;");
					}
					break;			
					
				case "reActivate":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `active` = NOT `active` WHERE `id` =" . $id . " LIMIT 1 ;");
					}
					break;

				case "delete":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("DELETE FROM `" . DB_PREFIX . "_plugins` WHERE `id` = " . $id . " LIMIT 1");
					}
					break;				
					
				case "deleteType":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("DELETE FROM `" . DB_PREFIX . "_blocks_types` WHERE `type` = '" . $id . "' LIMIT 1");
					}
					break;				
				
				case "deleteBlockType":
					foreach($_POST['checks'] as $id) 
					{
						$db->query("DELETE FROM `" . DB_PREFIX . "_plugins` WHERE `type` = '" . $id . "'");
					}
					break;
			}
		}
		delcache('plugins');
		location(ADMIN . '/blocks');
		break;
		
	case 'resort':
		$query = $db->query("SELECT id, type FROM ".DB_PREFIX."_plugins ORDER BY type ASC, priority ASC");
		if($db->numRows($query) > 0) 
		{
			while ($result = $db->getRow($query)) 
			{
				$blocks[$result['type']][] = $result;
			}

			foreach($blocks as $type => $inf)
			{
				$count[$type] = count($blocks[$type]);
				foreach($inf as $number => $result)
				{
					$db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `priority` = '" . $number . "' WHERE `id` =" . $result['id'] . " LIMIT 1 ;");
				}
			}
		}
		delcache('plugins');
		location(ADMIN . '/blocks');
		break;
		
		
	case 'standard':
		$adminTpl->admin_head(_AP_BLOCKS.' | '._BLOCK_STANDART);
		echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _BLOCK_STANDART . '</b>						
					</div>
					<div class="panel-body">
				<div class="switcher-content">
';		
		
		 foreach(glob(ROOT.'usr/blocks/config/*.config.php') as $inFile)
        {
            $name = explode('usr/blocks/config/', $inFile);
            $name = $name[1];
            $subDir = explode('/', $name);
            $inDirs[$subDir[0]][] = $inFile;
        }
		$zeroDirs = glob(ROOT.'usr/blocks/config/*.config.php');		
		$_names['cats.config.php'] = _BLOCK_CATS;	
		$_names['online.config.php'] = _BLOCK_ONLINE;	
		$_names['poll.config.php'] = _BLOCK_POLL;
		if(!empty($zeroDirs))
		{			
			foreach($zeroDirs as $file) 
			{
				$name = explode('usr/blocks/config/', $file);
				$name = end($name);
				$_a = explode('usr/blocks/config/', $file);
				$absolute = str_replace(array('/', '.config.php'), array('=', ''), end($_a));
				echo '<div style="cursor:pointer" onclick="document.location.href = \'{ADMIN}/blocks/standard_edit/' . $absolute . '\';">
					<label style="cursor:pointer" class="control-label">' . (isset($_names[$name]) ? $_names[$name] : $name) . '</label><br>
					Настройки блока: ' . (isset($_names[$name]) ? $_names[$name] : $name) . '
				<br>				
				</div><br>';
			}
			
		}
		echo'</div></div></section></div></div>';	
		
		delcache('plugins');
		$adminTpl->admin_foot();
		break;
		
	case 'standard_edit':
		$adminTpl->admin_head(_AP_BLOCKS.' | '._BLOCK_STANDART);
		require (ROOT.'etc/blocks/'.$url[3].'.config.php');		
		require (ROOT.'usr/blocks/config/'.$url[3].'.config.php');
		$ok = false;		
		if(isset($_POST['conf_file']))
		{
			$ok = true;
		}
		generateConfigBLOCK($configBox, $url[3], '{ADMIN}/blocks/standard_edit/'.$url[3], $ok);		
		$adminTpl->admin_foot();
		break;
		
		
	case 'types':
		$adminTpl->admin_head(_AP_BLOCKS.' | '._AP_BLOCKS_TYPE);
		echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _AP_BLOCKS_TYPE . '</b>						
					</div>';		
		
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_blocks_types ORDER BY title ASC");
		if($db->numRows($query) > 0) 
		{
			echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/blocks/action">
						<table class="table no-margin">
							<thead>
								<tr>									
									<th class="col-md-4"><span class="pd-l-sm"></span>' . _TITLE . '</th>
									<th class="col-md-3">'._TYPE.'</th>	
									<th class="col-md-3">' . _BLOCK_FOR_TPL . '</th>									
									<th class="col-md-3">' . _ACTIONS . '</th>	
									<th class="col-md-1"> 
										<input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;" />
									</th>								
								</tr>
							</thead>
							<tbody>';	
			while ($result = $db->getRow($query)) 
			{
				echo '<tr>				
				<td><span class="pd-l-sm"></span>' . $result['title'] . '</td>
				<td>' . $result['type'] . '</td>
				<td>{%BLOCKS:TYPE:' . $result['type'] . '%}</td>
				<td>
						<a href="{ADMIN}/blocks/typeAdd/' . $result['type'] .  '">
						<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
						</a>
						<a href="{ADMIN}/blocks/delType/' . $result['type'] .  '" onClick="return getConfirm(\''._BLOCK_TYPE_DEL.' - ' . $result['title'] . '?\')" title="' . _DELETE . '" class="delete">
						<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
						</a>			
				</td>
				<td align="center"> <input type="checkbox" name="checks[]" value="' . $result['type'] . '"></td>
				</tr>';
			}
		
		echo '<tr><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>
		<div align="right">
	<table>
	<tr>
	<td valign="top">
	<select name="act">
		<option value="deleteType">' . _DELETE . '</option>
		<option value="deleteBlockType">' . _BLOCK_DELETEFROM_TYPE . '</option>
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
echo '<div class="panel-heading">'._BLOCK_TYPE_EMPTY.'</div>';
}
echo'</section></div></div>';	
		
		delcache('plugins');
		$adminTpl->admin_foot();
		break;
		
	case 'typeAdd':
		if(isset($url[3]))
		{
			$id = filter($url[3]);
			$tquery = $db->query("SELECT * FROM ".DB_PREFIX."_blocks_types WHERE type = '" . $id . "'");
			$t = $db->getRow($tquery);
			if(!$t)
			{
				location('/' . ADMIN . '/blocks');
			}
			$tit = _BLOCK_EDIT_TYPE;
			$type = $t['type'];
			$title = $t['title'];
		}
		else
		{
			$tit = _BLOCK_ADD_TYPE;
			$type = '';
			$title = '';
		}		
		$adminTpl->admin_head(_AP_BLOCKS.' | ' . $tit);		
		$adminTpl->open();
			echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . $tit . '</b>						
					</div>
					<div class="panel-body">
						<form class="form-horizontal parsley-form" action="{ADMIN}/blocks/saveType" method="post">
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _TITLE . '</label>
													<div class="col-sm-4">
														<input value="' . $title . '" id="name" type="text" name="name" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' .  _TYPE . '</label>
													<div class="col-sm-4">
														<input value="' . $type . '" id="type" type="text" name="type" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>';
												if(isset($id)) echo '<input name="tid" value="' . $id . '" type="hidden" />';
												echo'<div class="form-group">
														<label class="col-sm-3 control-label"></label>
														<div class="col-sm-4">
															<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'.(isset($id) ? _EDIT : _ADD).'">						
														</div>
													</div>
												</form>
											</div>
										</section>
									</div>
								</div>';	
		$adminTpl->close();
		delcache('plugins');
		$adminTpl->admin_foot();
		break;
		
	case 'saveType':
		$name = isset($_POST['name']) ? filter($_POST['name'], 'a') : '';
		$type = isset($_POST['type']) ? filter(translit($_POST['type']), 'a') : '';
		$tid = isset($_POST['tid']) ? filter($_POST['tid'], 'a') : '';
		if($name && $type)
		{
			if(!$tid)
			{
				$db->query("INSERT INTO `" . DB_PREFIX . "_blocks_types` ( `title` , `type` ) VALUES ('" . $name . "', '" . $type . "');");	
			}
			else
			{
				$db->query("UPDATE `" . DB_PREFIX . "_blocks_types` SET `title` = '" . $name . "', `type` = '" . $type . "' WHERE `type` = '" . $tid . "' LIMIT 1 ;");
			}
			delcache('plugins');
			location('/' . ADMIN . '/blocks/types');
		}
		else
		{
			adminError(_NOT_FILLED, 'blocks/types');
		}
		break;
		
	case 'delType':
		$id = filter($url[3]);
		$db->query("DELETE FROM `" . DB_PREFIX . "_blocks_types` WHERE `type` = '" . $id . "' LIMIT 1");
		delcache('plugins');
		location('/' . ADMIN . '/blocks/types');
		break;
}