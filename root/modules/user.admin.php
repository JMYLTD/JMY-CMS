<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/


//редакция от 21.01.2015
if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

if($config['plugin'])
{
	$plugin = new plugin;
}

switch(isset($url[2]) ? $url[2] : null) 
{
	default:
		$adminTpl->admin_head(_USER_TITLE);		
		$where = '';
		$query = isset($_POST['query']) ? filter($_POST['query'], 'a') : '';
		$for = isset($_POST['for']) ? filter($_POST['for'], 'a') : '';
		$gr = isset($_POST['gr']) ? intval($_POST['gr']) : '';
		$banned = isset($_POST['banned']) ? true : false;
		$q = isset($_POST['q']) ? filter($_POST['q'], 'a') : '';			
			
		
		if(isset($url[2]) && $url[2] == 'group')
		{
			$where = "WHERE u.`group` = '" . intval($url[3]) . "' ";
		}
		elseif($query)
		{
			$where = "WHERE u.nick LIKE '%" . $db->safesql($query) . "%'";
			echo '<b>Запрос:</b>: ' . $query . '<br style="clear:both" />';
		}
		elseif($for)
		{
			$where = "WHERE u." . $for . " LIKE '%" . $db->safesql($q) . "%'" . ($gr ? "AND u.`group` = '" . $gr . "'" : '');
			$s = true;
			$o = true;
		}
		else
		{
			$s = true;
		}
		echo '<div class="row">
				<div class="col-lg-12">
                    <section class="panel">
                                                <header class="panel-heading">'._USER_NAVIGATION.' 
												<div style="float:right">'._USER_SHORT.' [ <a href="' . ADMIN . '/user/order/abc">'._USER_ABC.'</a> | <a href="' . ADMIN . '/user/order/last">'._USER_LAST_V.'</a> | <a href="' . ADMIN . '/user/order/uid">'._USER_ID.'</a> ]</div></header>
                                                <div class="panel-body">
												<table width=100%>
												<tr>
												<td>
												<button type="button"  onclick="showhide(\'newUser\')" class="btn btn-success btn-outline">'._USER_ADD.'</button>
												<button type="button"  onclick="showhide(\'search\')" class="btn btn-primary btn-outline">'._USER_FULL_SEARCH.'</button>
												</td>
												<td>
                                                    <form class="form-inline" role="form" align="right" method="POST" action="{MOD_LINK}">
													 <div class="form-group">
                                                            <label class="sr-only" for="exampleInputEmail2">'._USER_SEARCH.'</label>
                                                            <input type="text"  name="query"  class="form-control" id="exampleInputEmail2" placeholder="'._USER_INPUT.'">
                                                        </div>
														<button type="submit" class="btn btn-default">'._USER_SEARCH.'</button>
                                                    </form>
												</td>
											</tr>
										</table>
                                                </div>
                                            </section>
                                        </div>
                                    </div>';
				$adminTpl->open();	
		
		if(isset($s))
		{
		
			
			echo '			
			<div id="search" class="row" ' . (!isset($o) ? 'style="display:none"' : '') . '>
                                        <div class="col-lg-12">
                                            <section class="panel">
                                                <header class="panel-heading">'._USER_FULL_SEARCH.'</header>
                                                <div class="panel-body">
											<form class="form-inline" role="form" method="POST" action="{ADMIN}/user">
                                                        <div class="form-group">
                                                            <label class="sr-only" for="exampleInputEmail2">'._USER_SEARCH_BY.'</label>
                                                           <select name="for" class="selinput">
					<option value="nick" ' . ($for == 'nick' ? 'selected' : '') . '>'._USER_NICK.'</option>
					<option value="email" ' . ($for == 'email' ? 'selected' : '') . '>'._USER_EMAIL.'</option>
					<option value="name" ' . ($for == 'name' ? 'selected' : '') . '>'._USER_NAME.'</option>
					<option value="surname" ' . ($for == 'surname' ? 'selected' : '') . '>'._USER_SONAME.'</option>
					<option value="ip" ' . ($for == 'ip' ? 'selected' : '') . '>'._USER_IP.'</option>
				</select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label class="sr-only" for="exampleInputPassword2">Input</label>
                                                            <input type="text" class="form-control" name="q" value="' . $q . '" >
                                                        </div>
														
														 <div class="form-group">
                                                            <label class="sr-only" for="exampleInputEmail2">'._USER_SEARCH_BY.'</label>
															<select name="gr" class="selinput"><option value="">'._USER_G_D.'</option>';
			$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` WHERE special='0' ORDER BY admin DESC,moderator DESC,user DESC,guest DESC,banned DESC");
			while($rows = $db->getRow($query)) 
			{
				$selected = ($rows['id'] == $gr) ? "selected" : "";
				echo '<option value="' . $rows['id'] . '" ' . $selected . '>' . $rows['name'] . '</option>';
			}
			echo '</select>	    </div>			
                                       <div class="checkbox">
                                                            <label>
                                                             '._USER_BANNED.'? ' . checkbox('banned', $banned) . '
                                                            </label>
                                                        </div>
                                                        <button type="submit" class="btn btn-default">'._USER_SEARCH.'</button>
                                                    </form>
                                                </div>
                                            </section>
                                        </div>
                                    </div>';
		
			
			echo '<div class="row"  id="newUser" style="display:none" >
                                        <div class="col-lg-12">
                                            <section class="panel">
                                                <header class="panel-heading">'._USER_ADD.'</header>
                                                <div class="panel-body">
												<form class="form-horizontal parsley-form"  role="form" method="POST" action="{ADMIN}/user/addUsr">
												<div class="form-group">
													<label class="col-sm-3 control-label">'._USER_NICK.'</label>
													<div class="col-sm-4">
														<input type="text" name="name"  class="form-control"   data-parsley-required="true" data-parsley-trigger="change" >
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._USER_PASS.'</label>
													<div class="col-sm-4">
														<input type="password" name="pass"  class="form-control"   data-parsley-required="true" data-parsley-trigger="change" >
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._USER_EMAIL.'</label>
													<div class="col-sm-4">
														<input type="text"  name="mail"  class="form-control"   data-parsley-required="true" data-parsley-trigger="change" >
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">'._USER_GROUP.'</label>
													<div class="col-sm-4">
													<select name="group" class="selinput">';
													$query2 = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups`  WHERE special='0' ORDER BY user DESC");
													while($rows2 = $db->getRow($query2)) 
													{
														echo '<option value="' . $rows2['id'] . '">' . $rows2['name'] . '</option>';
													}
												echo '</select>
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
			
		}		
		$where .= ' ORDER BY regdate DESC';
		$adminTpl->close();
		if(isset($url[2]))
		{
			if($url[2] == 'adderr')
			{
				$adminTpl->info(_USER_ADD_INFO_1, 'error');
			}
			elseif($url[2] == 'addok')
			{
				$adminTpl->info(_USER_ADD_INFO_2);
			}
			elseif($url[2] == 'order')
			{
				switch($url[3])
				{
					case 'abc':
						$where = ' ORDER BY nick ASC';
						break;		
						
					case 'last':
						$where = ' ORDER BY last_visit DESC';
						break;					
						
					case 'uid':
						$where = ' ORDER BY id ASC';
						break;
				}
			}
		}
		$numU = 24;
		$page = init_page();
		$cut = ($page-1)*$numU;		
		$query = $db->query("SELECT u.*, g.name, (SELECT uid FROM " . DB_PREFIX . "_online WHERE u.id=uid LIMIT 1) as online FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_groups` as g on(u.group = g.id) " . $where . " LIMIT " . $cut . ", " . $numU);
		
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Список пользователей</b>						
					</div>';
		
		if($db->numRows($query) > 0) 
		{
		echo '<div class="panel-body no-padding">					
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-4">' . _NICK . '</th>
									<th class="col-md-2">' . _GROUP . '</th>
									<th class="col-md-2">' . _REGDATE . '</th>
									<th class="col-md-2">' . _LASTDATE . '</th>
									<th class="col-md-3">' . _ACTIONS . '</th>
								</tr>
							</thead>
							<tbody>';		
			$adminTpl->open();
			while($adminUser = $db->getRow($query)) 
			{
				
					echo '
					<tr>
						<td><span class="pd-l-sm"></span>' . $adminUser['id'] . '</td>
						<td>
							<a class="tooltip1" href="profile/' . $adminUser['nick'] . '">' . $adminUser['nick'] . '<span><img src="' . avatar($adminUser['id']) . '"/></span></a> - ' . ($adminUser['online'] ? '<font color="green">онлайн</font>' : '<font color="red">оффлайн</font>') . '</td>
						<td>' . $adminUser['name'] . '</td>
						<td>' . formatDate($adminUser['regdate'], true) . '</td>
						<td>' . formatDate($adminUser['last_visit']) . '</td>						
						<td>
							<a href="/administration/user/edit/' . $adminUser['id'] . '">
							<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
							</a>
							<a href="/administration/user/ban/'. $adminUser['id'].'" onClick="return getConfirm(\'Вы действительно хотите забанить - ' . $adminUser['nick'] . '?\')">
							<button type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="Забанить">B</button>
							</a>
							<a href="/administration/user/delete/' . $adminUser['id'] . '" onClick="return getConfirm(\'Вы действительно хотите удалить - ' . $adminUser['nick'] . '?\')">
							<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
							</a>
				</td>
			</tr>';
				

			}
			echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>';		
		
			
			$queryq = $db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` " . str_replace('u.', '', $where));
			
			
			
			echo'</div></section></div></div>';	
		}
		else
		{
			$adminTpl->info('Пользователей не найдено...');
		}
		$adminTpl->pages($page, $numU, $db->numRows($queryq), ADMIN.'/user/{page}');
		$adminTpl->close();
		$adminTpl->admin_foot();
	break;	
	
	
	
	
	case 'edit':
		$usrConf = $user;
		$uid = $url[3];
		$ok = isset($url[4]) ? true : false;
		$query = $db->query('SELECT * FROM `' . USER_DB . '`.`' . USER_PREFIX . '_users` WHERE id='.$uid);
		$user_row = $db->getRow($query);		
		$query2 = $db->query('SELECT * FROM ' . DB_PREFIX . '_board_users WHERE uid='.$uid);
		$forum = $db->getRow($query2);
		$adminTpl->admin_head('Редактирование пользователя');
		
	
		if($user_row['birthday']) 
		{
			$birthday = explode('.', $user_row['birthday']);
		}
		else
		{
			$birthday = explode('.', '0.0.0');
		}
		//$bbp = new bb;		
		//$bb = adminArea('signature', $bbp->htmltobb($user_row['signature']), 5, 'textarea', false, true);
		
		$bb = '<textarea name="signature" id="signature" class="form-control" rows="5" >'.$user_row['signature'].'</textarea>';
		$gender = '<option value="">---</option>';
		$gender .= '<option value="1"' . ($user_row['sex'] == '1' ? ' selected' : '') . '>Мужской</option>';
		$gender .= '<option value="2"' . ($user_row['sex'] == '2' ? ' selected' : '') . '>Женский</option>';
		$day = '<option value="">--</option>';

		for ($i = 1; $i < 32; $i++)
		{
			$day .= '<option value="' . ($i < 10 ? '0' . $i : $i) . '"' . ($birthday[0] == $i ? ' selected' : '') . '>' . $i . '</option>';
		}
				
		$month = '<option value="">---</option>';
		$month .= '<option value="01"' . ($birthday[1] == '1' ? ' selected' : '') . '>Январь</option>';
		$month .= '<option value="02"' . ($birthday[1] == '2' ? ' selected' : '') . '>Февраль</option>';
		$month .= '<option value="03"' . ($birthday[1] == '3' ? ' selected' : '') . '>Март</option>';
		$month .= '<option value="04"' . ($birthday[1] == '4' ? ' selected' : '') . '>Апрель</option>';
		$month .= '<option value="05"' . ($birthday[1] == '5' ? ' selected' : '') . '>Май</option>';
		$month .= '<option value="06"' . ($birthday[1] == '6' ? ' selected' : '') . '>Июнь</option>';
		$month .= '<option value="07"' . ($birthday[1] == '7' ? ' selected' : '') . '>Июль</option>';
		$month .= '<option value="08"' . ($birthday[1] == '8' ? ' selected' : '') . '>Август</option>';
		$month .= '<option value="09"' . ($birthday[1] == '9' ? ' selected' : '') . '>Сентябрь</option>';
		$month .= '<option value="10"' . ($birthday[1] == '10' ? ' selected' : '') . '>Октябрь</option>';
		$month .= '<option value="11"' . ($birthday[1] == '11' ? ' selected' : '') . '>Ноябрь</option>';
		$month .= '<option value="12"' . ($birthday[1] == '12' ? ' selected' : '') . '>Декабрь</option>';
		
		$year = '<option value="">---</option>';
		
		for ($i = 2008; $i > 1935; $i--)
		{
			$year .= '<option value="' . $i . '"' . ($birthday[2] == $i ? ' selected' : '') . '>' . $i . '</option>';
		}
		echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Редактирование пользователя ' . ($ok ? ' - <font color="green">Профиль сохранён</font>' : '') . '</b>						
					</div>
					<div class="panel-body">
					<form class="form-horizontal parsley-form" role="form" action="{ADMIN}/user/save" method="post">
												<div class="form-group">
													<label class="col-sm-3 control-label">Ник</label>
													<div class="col-sm-4">
														<input value="' . $user_row['nick'] . '" type="text" name="nick" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Статус на форуме</label>
													<div class="col-sm-4">
														<input  name="forumStatus" value="' . $forum['specStatus'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Фимилия</label>
													<div class="col-sm-4">
														<input  name="surname" value="' . $user_row['surname'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Имя</label>
													<div class="col-sm-4">
														<input  name="name" value="' . $user_row['name'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Отчество</label>
													<div class="col-sm-4">
														<input name="ochestvo" value="' . $user_row['ochestvo'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Группа</label>
													<div class="col-sm-4">';
													echo "<select name=\"group\" id=\"group\" class=\"textinput\">";
													$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` ORDER BY admin DESC,moderator DESC,user DESC,guest DESC,banned DESC");
													while($rows = $db->getRow($query)) 
													{
														$_groups[$rows['special']][] = $rows;
													}
													foreach($_groups[0] as $r)
													{
														$selected = ($r['id'] == $user_row['group']) ? "selected" : "";
														echo '<option value="' . $r['id'] . '" ' . $selected . '>' . $r['name'] . '</option>';
													}
													echo '</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Спец. группа</label>
													<div class="col-sm-4">';
													if(!empty($_groups[1]))
													{
														echo "<select name=\"exgroup\" id=\"exgroup\" class=\"textinput\"><option value=\"0\">Нет</option>";
														foreach($_groups[1] as $g)
														{
															$selected2 = ($g['id'] == $user_row['exgroup']) ? "selected" : "";
															echo '<option value="' . $g['id'] . '" ' . $selected2 . '>' . $g['name'] . '</option>';
														}
														echo "</select>";
													}
													else
													{
														echo '<p class="form-control-static">Спец. групп нет</p>';
													}
													echo' </div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">День рождения</label>
													<div class="col-sm-4">
														<select name="birthDay" style="width:130px;" >' . $day . '</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Месяц рождения</label>
													<div class="col-sm-4">
														<select name="birthMonth" style="width:130px;" >' . $month . '</select> 
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Год рождения</label>
													<div class="col-sm-4">
														<select name="birthYear" style="width:130px;" >' . $year . '</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Хобби</label>
													<div class="col-sm-4">
														<input  name="hobby" value="' . $user_row['hobby'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Место проживания</label>
													<div class="col-sm-4">
														<input  name="place" value="' . $user_row['place'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Пол</label>
													<div class="col-sm-4">
														<select name="gender" style="width:394px;" class="textinput" >' . $gender . '</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Активен ли пользователь?</label>
													<div class="col-sm-4">
														' . checkbox('active', $user_row['active']) . '
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Ведите url адрес автарки</label>
													<div class="col-sm-4">
														<input  name="avatar_link" value="" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Загрузите автарку</label>
													<div class="col-sm-4">
														<input type="file" name="avatar"  />
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Подпись пользователя</label>
													<div class="col-sm-4">
														' . $bb . '
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">E-mail</label>
													<div class="col-sm-4">
														<input  name="mail" value="' . $user_row['email'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">ICQ</label>
													<div class="col-sm-4">
														<input  name="icq" value="' . $user_row['icq'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Skype</label>
													<div class="col-sm-4">
														<input  name="skype" value="' . $user_row['skype'] . '" type="text" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">Новый пароль</label>
													<div class="col-sm-4">
														<input  name="newpass" value="" type="password" class="form-control" data-parsley-required="true" data-parsley-trigger="change">
													</div>
												</div>
												
												
												
											<input name="uid" value="' . $uid . '" type="hidden" />
											<div class="form-group">
								<label class="col-sm-3 control-label"></label>
								<div class="col-sm-4">
									<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="Обновить">						
								</div>
					</div>';
	$queryF = $db->query("SELECT * FROM ".DB_PREFIX."_xfields WHERE module='profile' and to_user='1'");
	if($db->numRows($queryF) > 0) 
	{
		$fields = unserialize($user_row['fields']);
		$xfileds = '<table width="100%" border="0" cellpadding="0" cellspacing="1" bgcolor="#EEEEEE" style="margin-bottom:5px;" classs="pad_table"><tr bgcolor="#FFFFFF"><th colspan="3" class="in_conf_title">Дополнительные поля</th></tr>';
		while($xfield = $db->getRow($queryF)) 
		{
			if($xfield['type'] == 3)
			{
				$dxfield = array_map('trim', explode("\n", $xfield['content']));
				$xfieldChange = '<select class="textinput" name="xfield[' . $xfield['id'] . ']">';
				foreach($dxfield as $xfiled_content)
				{
					$xfieldChange .= '<option value="' . $xfiled_content . '" ' . (isset($fields[$xfield['id']][1]) && $fields[$xfield['id']][1] == $xfiled_content ? 'selected' : ''). '>' . (!empty($fields[$xfield['id']][1]) ? $fields[$xfield['id']][1] : $xfield['content']) . '</option>';
				}
				$xfieldChange .= '</select>';
			}
			elseif($xfield['type'] == 2)
			{
				$xfieldChange = '<textarea class="textarea" name="xfield[' . $xfield['id'] . ']" >' . (!empty($fields[$xfield['id']][1]) ? $fields[$xfield['id']][1] : $xfield['content']) . '</textarea>';
			}
			else
			{
				$xfieldChange = '<input type="text" class="textinput" name="xfield[' . $xfield['id'] . ']" value="' . (!empty($fields[$xfield['id']][1]) ? $fields[$xfield['id']][1] : $xfield['content']) . '" />';
			}
						
			$xfileds .= '<tr bgcolor="#FFFFFF"><td class="in_conf_input" align="center">' . $xfield['title'] . '</td><td class="in_conf_input"><input type="hidden" name="xfieldT[' . $xfield['id'] . ']" value="' . $xfield['title'] . '" />' . $xfieldChange . '</select></td></tr>';
		}
		$xfileds .= '</table>';
		echo $xfileds;
	}
echo '</form>';
echo '</div>';
			echo'</section></div></div>';	
		$adminTpl->admin_foot();
		break;
		
		case 'save':
		require ROOT . 'etc/user.config.php';
			$surname = !empty($_POST['surname']) ? filter($_POST['surname'], 'a') : '';
			$name = !empty($_POST['name']) ? filter($_POST['name'], 'a') : '';
			$nick = !empty($_POST['nick']) ? filter($_POST['nick'], 'nick') : '';
			$ochestvo = !empty($_POST['ochestvo']) ? filter($_POST['ochestvo'], 'a') : '';
			$forumStatus = !empty($_POST['forumStatus']) ? filter($_POST['forumStatus'], 'a') : '';
			$birthDay = !empty($_POST['birthDay']) ? intval($_POST['birthDay']) : '';
			$birthMonth = !empty($_POST['birthMonth']) ? intval($_POST['birthMonth']) : '';
			$birthYear = !empty($_POST['birthYear']) ? intval($_POST['birthYear']) : '';
			$gender = !empty($_POST['gender']) ? intval($_POST['gender']) : '';
			$avatar_link = !empty($_POST['avatar_link']) ? filter($_POST['avatar_link'], 'dir') : '';
			$signature = !empty($_POST['signature']) ? parseBB(processText(filter($_POST['signature'], 'bb'))) : '';
			$mail = !empty($_POST['mail']) ? filter($_POST['mail'], 'mail') : '';
			$hobby = !empty($_POST['hobby']) ? filter($_POST['hobby'], 'a') : '';
			$icq = !empty($_POST['icq']) ? filter($_POST['icq'], 'a') : '';
			$skype = !empty($_POST['skype']) ? filter($_POST['skype'], 'a') : '';
			$place = !empty($_POST['place']) ? filter($_POST['place'], 'a') : '';
			$newpass = !empty($_POST['newpass']) ? $_POST['newpass'] : '';
			$uid = !empty($_POST['uid']) ? intval($_POST['uid']) : '';
			$group = !empty($_POST['group']) ? intval($_POST['group']) : '';
			$exgroup = !empty($_POST['exgroup']) ? intval($_POST['exgroup']) : '';
			$active = (!empty($_POST['active']) && $_POST['active'] == 'on') ? 1 : 0;

			
			if($birthDay && $birthMonth && $birthYear)
			{
				$birthDate = $birthDay . '.' . $birthMonth . '.' . $birthYear;
				$unixBirth = gmmktime(0, 0, 0, $birthMonth, $birthDay, $birthYear);
				$age = mb_substr((time()-$unixBirth)/31536000, 0, 2);
			}
			else
			{
				$birthDate = '';
				$age = '';
			}
			
			if($newpass)
			{
				$core->auth->updatePassword($newpass, $uid);
				if($config['plugin']) $plugin->updatePassword($newpass, $uid);
			}
			
			if(!empty($forumStatus))
			{
				$db->query("UPDATE `" . DB_PREFIX . "_board_users` SET `specStatus` = '" . $forumStatus . "' WHERE `uid` = " . $uid . " LIMIT 1 ;");
			}
			
			if($mail)
			{
				if(!preg_match('/[a-zA-Z0-9_\.\-]+@[a-zA-Z0-9_\-]+\.[a-zA-Z0-9_\-\.]+$/i', $mail)) 
				{
					$mail = '';
					$error[] = 'E-Mail имеет неверный формат';
				}				
			}
			
			if(empty($nick))
			{
				$error[] = 'Ник не может быть пустым!';
			}
				
				$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `nick` = '" . $db->safesql($nick) . "', `group` = '" . $group . "', `exgroup` = '" . $exgroup . "', `active` = '" . $active . "' WHERE `id` = " . $uid . " LIMIT 1 ;");
				$core->auth->updateProfile($mail, $icq, $skype, $surname, $name, $ochestvo, $place, $age, $gender, $birthDate, $hobby, $signature, '', $uid);
				if($config['plugin']) $plugin->updateProfile($mail, $icq, $skype, $surname, $name, $ochestvo, $place, $age, $gender, $birthDate, $hobby, $signature, '', $uid);

			if($_FILES['avatar']['size'] > 0) 
			{
				deleteAvatar($uid);
				if($foo = new Upload($_FILES['avatar']))
				{
					$foo->file_new_name_body = 'av' .$uid;
					$foo->image_resize = true;
					$foo->image_x = $user['avatar_width'];
					$foo->image_ratio_y = true;
					$foo->file_overwrite = true;
					$foo->file_auto_rename = false;
					$foo->Process(ROOT.'files/avatars/users/');
					$foo->allowed = array("image/*");
						
					if ($foo->processed) 
					{
						$foo->Clean();
					}
				}
			}
			
			if(isset($error))
			{
				$txt = '';
					
				foreach($error as $msg)
				{
					$txt .= $msg . '<br />';
				}
			}
			
			location(ADMIN . '/user/edit/' . $uid . '/ok');
	
			break;
			
	case 'regroup':
		$uid = intval($url[3]);
		delcache('userInfo_'.$uid);
		$query = $db->query('SELECT * FROM `' . USER_DB . '`.`' . USER_PREFIX . '_users` WHERE id='.$uid);
		$user = $db->getRow($query);		
		windowOpen();
		if(!isset($_POST['group']))
		{
			echo '<form action="" method="post" enctype="multipart/form-data">';
			echo "<div align=\"center\"><select name=\"group\" id=\"group\" class=\"textinput\">";
			$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups`  WHERE special='0' ORDER BY admin DESC,moderator DESC,user DESC,guest DESC,banned DESC");
			while($rows = $db->getRow($query)) 
			{
				$selected = ($rows['id'] == $user['group']) ? "selected" : "";
				if($rows['id'] != 5) echo '<option value="' . $rows['id'] . '" ' . $selected . '>' . $rows['name'] . '</option>';
			}
			echo '</select> <input value="Сменить группу" type="submit" size="11" maxlength="20" class="b" /></div></form>';
		}
		else
		{
			$group = !empty($_POST['group']) ? intval($_POST['group']) : '';
			
			if($uid)
			{
				$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `group` = '" . $group . "' WHERE `id` = " . $uid . " LIMIT 1 ;");
				echo '<div align="center"><font color="green"><b>Группа успешно изменена. Окно закроется атоматом.</b></font></div>
				<script type="text/javascript">setTimeout(\'window.close()\', 3000)</script>
				';
			}		
		}
		break;	
		
	case 'repass':
		$uid = intval($url[3]);
		$query = $db->query('SELECT * FROM `' . USER_DB . '`.`' . USER_PREFIX . '_users` WHERE id='.$uid);
		$user = $db->getRow($query);		
		windowOpen();
		if(!isset($_POST['newpass']))
		{
			echo '<form action="" method="post" enctype="multipart/form-data">';
			echo '<div align="center"> <input name="newpass" value="" class="textinput" type="text" size="11" maxlength="20" /> <input value="Изменить пароль" type="submit" size="11" maxlength="20" class="b" /></div></form>';
		}
		else
		{
			$newpass = !empty($_POST['newpass']) ? intval($_POST['newpass']) : '';
			
			if($uid)
			{
				$core->auth->updatePassword($newpass, $uid);
				if($config['plugin']) $plugin->updatePassword($newpass, $uid);
				echo '<div align="center"><font color="green"><b>Пароль успешно изменён, окно закроется атоматически.</b></font></div>
				<script type="text/javascript">setTimeout(\'window.close()\', 3000)</script>
				';
			}		
		}
		break;
		
	case 'delete':
		$uid = intval($url[3]);
		delcache('userInfo_'.$uid);
		$db->query("DELETE FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE `id` = " . $uid . " LIMIT 1");
		$db->query("DELETE FROM `" . DB_PREFIX . "_board_users` WHERE `uid` = " . $uid . " LIMIT 1");
		@unlink("files/avatars/users/av" . $uid . ".jpg");
		location(ADMIN . '/user');
		break;
		
	case 'ban':
		$uid = intval($url[3]);
		if($uid != $core->auth->user_info['id'])
		{
			delcache('userInfo_'.$uid);
			$query = $db->query('SELECT id FROM `' . USER_DB . '`.`' . USER_PREFIX . '_groups` WHERE `banned`=1');
			$group = $db->getRow($query);
			$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `group` = '" . $group['id'] . "' WHERE `id` = " . $uid . " LIMIT 1 ;");
		}
		location(ADMIN . '/user');
		break;
		
	
		
	case 'addUsr':
		$name = filter($_POST['name'], 'nick');
		$pass = $_POST['pass'];
		$mail = filter($_POST['mail'], 'mail');
		$group = intval($_POST['group']);
		list($check) = $db->fetchRow($db->query("SELECT Count(id) FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick='" . $db->safesql($name) . "' OR email='" . $db->safesql($mail) . "'"));
		if($check > 0 && !empty($name) && !empty($pass)) 
		{
			$result = 'adderr';
		}
		else
		{
			$tail = gencode(rand(6, 11));
				
			$core->auth->register($name, $pass, $tail, $mail, '', '', '', '', '', '', '', '', '', 1, '127.0.0.1', $group);
			if($config['plugin']) $plugin->registration($name, $pass, $tail, $mail, '', '', '', '', '', '', '', '', '', 1, '127.0.0.1', $group);
			list($uid) = $db->fetchRow($db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick='" . $db->safesql($name) . "' LIMIT 1"));
			$db->query("INSERT INTO `" . DB_PREFIX . "_board_users` (`uid`) VALUES ('" . $uid . "');", true);
			$result = 'addok';
		}
		
		location(ADMIN.'/user/'.$result);
		break;
}