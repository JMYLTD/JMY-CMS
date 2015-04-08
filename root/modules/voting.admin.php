<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   01.03.2015
*/ 
 
if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

switch(isset($url[2]) ? $url[2] : null) {
	default:
		$adminTpl->admin_head(_POLL_POLLS);
		$query = $db->query("SELECT id as ppid, title, votes, max, (SELECT COUNT(id) FROM ".DB_PREFIX."_poll_questions WHERE ppid = pid) as variants FROM ".DB_PREFIX."_polls ORDER BY title");	
		echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>'._POLL_LIST.'</b>						
					</div>';
		if($db->numRows($query) > 0) 
		{
					echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/voting/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-3">' . _POLL_VOTE . '</th>
									<th class="col-md-1">' . _POLL_VAR . '</th>
									<th class="col-md-3">' . _POLL_ANS . '</th>
									<th class="col-md-2">' . _POLL_MAX . '</th>
									<th class="col-md-2">' . _ACTIONS . '</th>								
									<th class="col-md-1"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';				
			while($poll = $db->getRow($query)) 
			{
				echo "
				<tr>
					<td><span class=\"pd-l-sm\"></span>" . $poll['ppid'] . "</td>
					<td>" . $poll['title'] . "</td>
					<td>" . $poll['variants'] . "</td>
					<td>" . $poll['votes'] . "</td>
					<td>" . $poll['max'] . "</td>
					<td>";
					echo $status_icon .'
					<a href="{ADMIN}/voting/edit/'. $poll['ppid'] .'">
					<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="'. _EDIT.'">E</button>
					</a>
					<a href="{ADMIN}/voting/delete/'. $poll['ppid'] .'" onclick="return getConfirm(\''._POLL_DEL.' - '. $poll['title'] .'?\')">
					<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
					</a>';
					echo "</td>
					<td> <input type=\"checkbox\" name=\"checks[]\" value=\"" . $poll['ppid'] . "\"></td>
				</tr>";	
			}
			
		echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>		
		<div align="right">
		<table>
		<tbody><tr>
		<td valign="top">
		<input name="submit" type="submit" class="btn btn-danger" id="sub" value="' . _DELETE .'"><span class="pd-l-sm"></span>
		</td>
		</tr>
		</tbody></table>
		<br>	
		</div>
		</form></div>';
			
		} 
		else 
		{
		echo '<div class="panel-heading">'  . _POLL_EMPTY . '</div>';		
		}
		echo'</section></div></div>';		
		$adminTpl->admin_foot();
		break;	
		
	case 'add':
		$adminTpl->admin_head(_POLL_ADD);		
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>'._POLL_ADD.'</b>						
					</div>
					<div class="panel-body">
						<form class="form-horizontal parsley-form" role="form" action="{ADMIN}/voting/save" method="post"  data-parsley-validate>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._POLL_NAME.'</label>
													<div class="col-sm-4">
														<input value="" type="text" name="title" id="title" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._POLL_VARS.'</label>
													<div class="col-sm-4">
														<textarea cols="30" rows="10" name="vars" class="form-control" id="vars" data-parsley-required="true" data-parsley-trigger="change"></textarea>
														<p class="help-block">'._POLL_VARS_DESC.'</p>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._POLL_MAXS.'</label>
													<div class="col-sm-4">
														<div id="ex-spinner" class="spinner input-group">
															<input type="text" value="0" name="max" class="form-control spinner-input">
															<div class="spinner-buttons input-group-btn  btn-group btn-group-vertical">
																<button type="button" class="btn btn-default spinner-up">
																<i class="fa fa-angle-up"></i></button>
																<button type="button" class="btn btn-default spinner-down">
																<i class="fa fa-angle-down"></i></button>
															</div>
														</div>
														<p class="help-block">'._POLL_MAXS_DESC.'</p>
													</div>
												</div>
												<div class="form-group">
														<label class="col-sm-3 control-label"></label>
														<div class="col-sm-4">
															<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'._ADD.'">						
														</div>
												</div>
											</form>
										</div>
									</section>
								</div>
							</div>';		
		$adminTpl->admin_foot();
		break;
		
	case 'save':
		$adminTpl->admin_head(_POLL_ADD);
		$title = filter($_POST['title'], 'title');
		$vars = filter($_POST['vars'], 'html');
		$max = intval($_POST['max']);
		$variants = explode("\n", $vars);
		if($title && $vars)
		{
			$db->query("INSERT INTO `" . DB_PREFIX . "_polls` ( `id` , `title` , `votes` , `max` ) VALUES (NULL, '" . $db->safesql(processText($title)) . "', '0', '" . $max . "');");
			list($id) = $db->fetchRow($db->query("SELECT id FROM `" . DB_PREFIX . "_polls` WHERE title = '" . $db->safesql(processText($title)) . "' AND max = '" . $max . "'"));
		
			foreach($variants as $var)
			{
				if($var !== '')
				{
					$db->query("INSERT INTO `" . DB_PREFIX . "_poll_questions` ( `id` , `pid` , `variant` , `position` , `vote` ) VALUES (NULL, '" . $id . "', '" . str_replace(',', '||', trim($db->safesql($var))) . "', '', '0');");
				}
			}
			
			$adminTpl->info(_POLL_INFO_0);
		}
		else
		{
			$adminTpl->info(_BASE_ERROR_0, 'error');
		}
		$adminTpl->admin_foot();
		break;
	
	case 'edit':
		$id = intval($url[3]);
		$rows = $db->getRow($db->query("SELECT * FROM `" . DB_PREFIX . "_polls` WHERE id = '" . $id . "'"));
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_poll_questions` WHERE pid = '" . $id . "'");
		$adminTpl->admin_head(_POLL_EDIT);
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>'._POLL_EDIT.'</b>						
					</div>
					<div class="panel-body">
						<form class="form-horizontal parsley-form" role="form" action="{ADMIN}/voting/save_edit" method="post"  data-parsley-validate>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._POLL_NAME.'</label>
													<div class="col-sm-4">
														<input value="'. prepareTitle($rows['title']) .'" type="text" name="title" id="title" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._POLL_VARS.'</label>
													<div class="col-sm-4">
														<textarea rows="10" name="vars" class="form-control" id="vars">';
														while($rowsq = $db->getRow($query))
														{	
															$text = $rowsq['variant'];															
															$text=rtrim($text,"\n\r");
															echo $text . "|" . $rowsq['vote'] . "\n";		
														}
												echo'	</textarea>
														<p class="help-block">'._POLL_VARS_DESC_E.'</p>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._POLL_MAXS.'</label>
													<div class="col-sm-4">
														<div id="ex-spinner" class="spinner input-group">
															<input type="text" value="'.$rows['max'].'" name="max" class="form-control spinner-input">
															<div class="spinner-buttons input-group-btn  btn-group btn-group-vertical">
																<button type="button" class="btn btn-default spinner-up">
																<i class="fa fa-angle-up"></i></button>
																<button type="button" class="btn btn-default spinner-down">
																<i class="fa fa-angle-down"></i></button>
															</div>
															<p class="help-block">'._POLL_MAXS_DESC.'</p>
														</div>
													</div>
												</div>
												<input name="id" type="hidden" id="sub" value="' . $rows['id'] . '" />
												<input name="votes" type="hidden" id="sub" value="' . $rows['votes'] . '" />
												<div class="form-group">
														<label class="col-sm-3 control-label"></label>
														<div class="col-sm-4">
															<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'._UPDATE.'">						
														</div>
												</div>';
												echo '</form>';
											echo '</div>';
										echo'</section></div></div>';			
		$adminTpl->admin_foot();
		
		break;
		
	case 'save_edit':
		$adminTpl->admin_head(_POLL_EDIT);
		$id = intval($_POST['id']);
		$title = filter($_POST['title']);
		$vars = filter($_POST['vars']);
		$max = intval($_POST['max']);
		$votes = intval($_POST['votes']);
		$variants = explode("\n", $vars);
		if($title && $vars)
		{
			$db->query("DELETE FROM `" . DB_PREFIX . "_poll_questions` WHERE `pid` = '" . $id . "'");
			$db->query("DELETE FROM `" . DB_PREFIX . "_poll_voting` WHERE `pid` = '" . $id . "'");
			$allVote = 0;
			foreach($variants as $var)
			{
				if($var !== '')
				{
					$lo = explode('|', $var);
					$db->query("INSERT INTO `" . DB_PREFIX . "_poll_questions` ( `id` , `pid` , `variant` , `position` , `vote` ) VALUES (NULL, '" . $id . "', '" . str_replace(',', '||', trim($db->safesql($lo[0]))) . "', '', '" . $lo[1] . "');");
					$allVote = $allVote+$lo[1];
				}
			}
			$db->query("UPDATE `" . DB_PREFIX . "_polls` SET `title` = '" . $title . "', `votes` = '0', `max` = '" . $max . "' WHERE `id` = '" . $id . "' LIMIT 1 ;");

			
			$adminTpl->info(_POLL_INFO_1);
		}
		else
		{
			$adminTpl->info(_BASE_ERROR_0, 'error');
		}
		$adminTpl->admin_foot();
		break;
		
	case 'delete':
		$id = intval($url[3]);
		deleteVot($id);
		location(ADMIN.'/voting');
		break;
		
	case 'action':
		$checks = $_POST['checks'];
		foreach($checks as $check)
		{
			deleteVot(intval($check));
		}
		location(ADMIN . '/voting/del');
		break;
}

function deleteVot($id)
{
global $adminTpl, $db;
	$db->query("DELETE FROM `" . DB_PREFIX . "_poll_questions` WHERE `pid` = '" . $id . "'");
	$db->query("DELETE FROM `" . DB_PREFIX . "_poll_voting` WHERE `pid` = '" . $id . "'");
	$db->query("DELETE FROM `" . DB_PREFIX . "_polls` WHERE `id` = '" . $id . "'");
}
