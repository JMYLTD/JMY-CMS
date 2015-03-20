<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   07.03.2015
*/
 
if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

switch(isset($url[2]) ? $url[2] : null) {
	default:
		$adminTpl->admin_head('Группы');
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Группы</b>						
					</div>';			
		$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` ORDER BY name ASC");
		if($db->numRows($query) > 0) 
		{
			echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/module/news/action&moderate">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-6">'._CAPTION.'</th>
									<th class="col-md-1">'._GROUP_NAME_SPECIAL.'</th>
									<th class="col-md-2">'._APANEL.'</th>
									<th class="col-md-1">'._GROUP_NAME_PROTECTED.'</th>
									<th class="col-md-2">' . _ACTIONS . '</th>									
								</tr>
							</thead>
							<tbody>';	
			while($group = $db->getRow($query)) 
			{
				echo '
				<tr>
					<td><span class="pd-l-sm"></span>' . $group['id'] . '</td>
					<td><div id="editTitle_' . $group['id'] . '" onclick="EditTitle(\'editTitle_' . $group['id'] . '\', \'group\', \'' . $group['id'] . '\')">' . $group['name'] . '</div></td>
					<td>' . ($group['special'] ? '<font color="green">' . _YES . '</font>' : '<font color="red">' . _NO . '</font>') . '</td>
					<td>' . ($group['admin'] ? '<font color="green">' . _YES . '</font>' : '<font color="red">' . _NO . '</font>') . '</td>
					<td>' . ($group['protect'] ? '<font color="green">' . _YES . '</font>' : '<font color="red">' . _NO . '</font>') . '</td>
					<td>
						<a href="{ADMIN}/groups/edit/' . $group['id'] . '">
						<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
						</a>'
						. ($group['protect'] ? '' : '<a href="{ADMIN}/groups/delete/' . $group['id'] . '" onClick="return getConfirm(\''._GROUP_DEL.'' . $group['name'] . '?\')" title="' . _DELETE . '" class="delete">
						<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
						</a>') . '				
					</td>
				</tr>';	
			}
			echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>';
			echo "</form></div>";
		} 
		else
		{
			echo '<div class="panel-heading">'._GROUP_EMPTY.'</div>';		
		}
		echo'</section></div></div>';
		$adminTpl->admin_foot();
		break;	
		
	case 'edit':
		$gid = intval($url[3]);
		$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` WHERE id='" . $gid . "'");
		if($db->numRows($query) == 1) 
		{
			$group = $db->getRow($query);
			$control = unserialize($group['control']);
		}
		else
		{
			location(ADMIN);
		}
		$tit = _GROUP_UPDATE;
	case 'add':
		if(!isset($gid))
		{
			$group['name'] = '';
			$group['guest'] = 1;
			$group['user'] = 1;
			$group['moderator'] = 0;
			$group['admin'] = 0;
			$group['banned'] = 0;
			$group['showHide'] = 1;
			$group['showAttach'] = 1;
			$group['loadAttach'] = 0;
			$group['addPost'] = 0;
			$group['addComment'] = 1;
			$group['allowRating'] = 1;
			$group['maxWidth'] = $user['avatar_width'];
			$group['maxPms'] = 50;
			$group['icon'] = 'media/groups/';
			$group['color'] = '';
			$group['points'] = 0;
			$group['special'] = 0;
			$tit = _GROUP_ADD;
		}
		$adminTpl->admin_head($tit);
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . $tit . '</b>						
					</div>
					<div class="panel-body">
						<form class="form-horizontal parsley-form" role="form" action="{ADMIN}/groups/save" method="post">
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_NAME .'</label>
													<div class="col-sm-4">
														<input value="' . $group['name'] . '" type="text" name="title" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_SPECIAL .'</label>
													<div class="col-sm-4">
														'.radio("special", $group['special']).'
														<p class="help-block">'. _GROUP_SPECIAL_DESC .'</p>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_POINTS .'</label>
													<div class="col-sm-4">
														<input value="' . $group['points'] . '" type="text" name="points" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
														<p class="help-block">'. _GROUP_POINTS_DESC .'</p>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_GUEST .'</label>
													<div class="col-sm-4">
														'.radio("guest", $group['guest']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_USER .'</label>
													<div class="col-sm-4">
														'.radio("user", $group['user']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_MODER .'</label>
													<div class="col-sm-4">
														'.radio("moderator", $group['moderator']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_ADMIN .'</label>
													<div class="col-sm-4">
														'.radio("admin", $group['admin']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_BAN .'</label>
													<div class="col-sm-4">
														'.radio("banned", $group['banned']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_FULL .'</label>
													<div class="col-sm-4">
														<table>
															<tr>
																<td valign="top">';										
																
								echo '	<label class="radio radio-custom checked"><input checked type="radio" value="1" onclick="hide(\'aPerm\');" name="aFullPerm" id="aFullPerm"><i class="radio checked"></i>'._YES.'</label>
																</td>
																<td>&nbsp&nbsp</td>
																<td valign="top">
									<label class="radio radio-custom"><input type="radio"  value="0" onclick="show(\'aPerm\');" name="aFullPerm" id="aFullPerm"><i class="radio "></i>'._NO.'</label>
																</td>				
															</tr>
														</table>													
													</div>
												</div>
												<div class="form-group" '.(!empty($group['control']) ? '' : 'style="display:none;"') . ' id="aPerm">
													<label class="col-sm-3 control-label">'._ELEMENTS.'</label>
													<div class="col-sm-8">';
		require ROOT . 'root/list.php';		
		foreach(glob(ROOT.'usr/modules/*/admin/list.php') as $listed) 
		{
			include($listed);
		}
		$mods = '';
		$comp = '';
		$serv = '';
		foreach($module_array as $module => $params) 
			$mods .= '<label class="checkbox checkbox-custom"><input type="checkbox" name="adminAccess[]" value="' . $module . '" ' . ((!isset($gid) || empty($group['control'])) ? 'checked' : (in_array($module, $control) ? 'checked' : '')) . ' /><i class="checkbox"></i> '.$params['name'] . '</label> <br />';

		foreach($component_array as $component => $params) 
		{
			if($component == '') $component = 'index';
			$comp .= '<label class="checkbox checkbox-custom"><input type="checkbox" name="adminAccess[]" value="' . $component . '"  ' . ((!isset($gid) || empty($group['control'])) ? 'checked' : (in_array($component, $control) ? 'checked' : '')) . ' /><i class="checkbox"></i> '.$params['name'] . '</label> <br />';
		}

		foreach($services_array as $sevices => $params) 
			$serv .= '<label class="checkbox checkbox-custom"><input type="checkbox" name="adminAccess[]" value="' . $sevices . '"  ' . ((!isset($gid) || empty($group['control'])) ? 'checked' : (in_array($sevices, $control) ? 'checked' : '')) . ' /><i class="checkbox"></i> '.$params['name'] . '</label> <br />';

		echo '<div style="float:left; width:200px;"><strong>'._COM.'</strong><br />';
		echo $comp .'</div>';
		echo '<div style="float:left; width:200px;"><strong>'._SRVICE.'</strong><br />';
		echo $serv.'</div>';
		echo '<div style="float:left; width:200px;"><strong>'._MODULES.'</strong><br />';
		echo $mods.'</div>';
		echo '</div>
		</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_HIDE .'</label>
													<div class="col-sm-4">
														'.radio("showHide", $group['showHide']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_ATTACH_VIEW .'</label>
													<div class="col-sm-4">
														'.radio("showAttach", $group['showAttach']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_ATTACH_LOAD .'</label>
													<div class="col-sm-4">
														'.radio("loadAttach", $group['loadAttach']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_ADDNEWS .'</label>
													<div class="col-sm-4">
														'.radio("addPost", $group['addPost']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_RIGHT_COMMENT .'</label>
													<div class="col-sm-4">
														'.radio("addComment", $group['addComment']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_VIEW_RAITING .'</label>
													<div class="col-sm-4">
														'.radio("allowRating", $group['allowRating']).'														
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_AVATAR_WIGHT .'</label>
													<div class="col-sm-4">
														<input value="' . $group['maxWidth'] . '" type="text" name="maxWidth" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_PM .'</label>
													<div class="col-sm-4">
														<input value="' . $group['maxPms'] . '" type="text" name="maxPms" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_ICON .'</label>
													<div class="col-sm-4">
														<input value="' .  $group['icon'] . '" type="text" name="icon" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'. _GROUP_COLOR .'</label>
													<div class="col-sm-4">
														<input value="' . $group['color'] . '" type="text" name="color" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>';
												$do_name=_ADD;
												if(isset($gid)) 
												{			
													echo "<input name=\"edit\" type=\"hidden\" class=\"buttons\" id=\"sub\" value=\"" . $gid . "\" />";
													$do_name=_UPDATE;
												}
												echo'<div class="form-group">
														<label class="col-sm-3 control-label"></label>
														<div class="col-sm-4">
															<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'.$do_name.'">						
														</div>
													</div>';
							
		
		echo '</form>';
echo '</div>';
			echo'</section></div></div>';	
		$adminTpl->admin_foot();
		break;
		
	case 'save':
		$adminTpl->admin_head(_GROUP_ADD);
		$title = filter(htmlspecialchars_decode($_POST['title']), 'title');
		$guest = intval($_POST['guest']);
		$user = intval($_POST['user']);
		$moderator = intval($_POST['moderator']);
		$admin = intval($_POST['admin']);
		$aFullPerm = intval($_POST['aFullPerm']);
		$banned = intval($_POST['banned']);
		$showHide = intval($_POST['showHide']);
		$showAttach = intval($_POST['showAttach']);
		$loadAttach = intval($_POST['loadAttach']);
		$addPost = intval($_POST['addPost']);
		$addComment = intval($_POST['addComment']);
		$allowRating = intval($_POST['allowRating']);
		$maxWidth = intval($_POST['maxWidth']);
		$special = intval($_POST['special']);
		$points = intval($_POST['points']);
		$maxPms = intval($_POST['maxPms']);
		$icon = filter($_POST['icon']);
		$color = filter($_POST['color']);
		$control = '';
		
		if($aFullPerm == 0)
		{
			if(!empty($_POST['adminAccess']))
			{
				$control = serialize($_POST['adminAccess']);
			}
		}
		
		if($title && $guest && $user)
		{
			if(!isset($_POST['edit']))
			{
				$db->query("INSERT INTO `" . USER_DB . "`.`" . USER_PREFIX . "_groups` (`name` , `guest` , `user` , `moderator` , `admin` , `banned` , `showHide` , `showAttach` , `loadAttach` , `addPost` , `addComment` , `allowRating` , `maxWidth` , `maxPms` , `control` , `icon` , `color` , `points` , `special` ) VALUES ('" . $title . "', '" . $guest . "', '" . $user . "', '" . $moderator . "', '" . $admin . "', '" . $banned . "', '" . $showHide . "', '" . $showAttach . "', '" . $loadAttach . "', '" . $addPost . "', '" . $addComment . "', '" . $allowRating . "', '" . $maxWidth . "', '" . $maxPms . "', '" . $control . "', '" . $icon . "', '" . $color . "', '" . $points . "', '" . $special . "');");
				$adminTpl->info(_GROUP_INFO_0);
			}
			else
			{
				$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_groups` SET `name` = '" . $title . "', `guest` = '" . $guest . "', `user` = '" . $user . "', `moderator` = '" . $moderator . "', `admin` = '" . $admin . "', `banned` = '" . $banned . "', `showHide` = '" . $showHide . "', `showAttach` = '" . $showAttach . "', `loadAttach` = '" . $loadAttach . "', `addPost` = '" . $addPost . "', `addComment` = '" . $addComment . "', `allowRating` = '" . $allowRating . "', `maxWidth` = '" . $maxWidth . "', `maxPms` = '" . $maxPms . "', `control` = '" . $control . "', `icon` = '" . $icon . "', `color` = '" . $color . "', `points` = '" . $points . "', `special` = '" . $special . "' WHERE `id` =" . intval($_POST['edit']) . " LIMIT 1 ;");
				$adminTpl->info(_GROUP_INFO_1);
			}
		}
		else
		{
			$adminTpl->info(_BASE_ERROR_0, error);
		}		
		$adminTpl->admin_foot();
		break;
	
	case 'delete':
		$id = intval($url[3]);
		$db->query("DELETE FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` WHERE `id` = '" . $id . "'");
		location(ADMIN.'/groups');
		break;
		
	case 'points':
		require (ROOT.'etc/points.config.php');
		
		$configBox = array(
			'points' => array(
				'varName' => 'points_conf',
				'title' => _GROUP_CONFIG_NAME,
				'groups' => array(
					'main' => array(
						'title' => _GROUP_CONFIG_NAME,
						'vars' => array(
							'add_news' => array(
								'title' => _GROUP_CONFIG_ADDNEWS,
								'description' => _GROUP_CONFIG_ADDNEWS_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'add_comment' => array(
								'title' => _GROUP_CONFIG_ADDCOMM,
								'description' => _GROUP_CONFIG_ADDCOMM_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'register' => array(
								'title' => _GROUP_CONFIG_REG,
								'description' => _GROUP_CONFIG_REG_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'carma' => array(
								'title' => _GROUP_CONFIG_CARMA,
								'description' => _GROUP_CONFIG_CARMA_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),					
							'add_friend' => array(
								'title' => _GROUP_CONFIG_FRIENDS,
								'description' => _GROUP_CONFIG_FRIENDS_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),					
							'rating' => array(
								'title' => _GROUP_CONFIG_VOTE,
								'description' => _GROUP_CONFIG_VOTE_DESC,
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
		
		generateConfig($configBox, 'points', '{ADMIN}/groups/points', $ok);
		break;
}