<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}

loadConfig('user');

if($config['plugin'])
{
	$plugin = new plugin;
}
 
function main() 
{
global $db, $config, $core, $url, $user;
	$not_res = array('register', 'forgot_pass', 'login', 'activate');
	
	if(isset($url[1]) && $core->auth->user_info['nick'] !== $url[1] && !in_array($url[1], $not_res))
	{
		$wher = is_numeric($url[1]) ? 'id' : 'nick';
		$rows = $db->getRow($db->query("SELECT u.*, g.name as gname, g.user as isUser, g.moderator as isModer, g.admin as isAdmin, g.showAttach, g.showHide, g.loadAttach, g.addPost, g.addComment, g.allowRating, g.maxWidth, g.maxPms, g.control, g.color, g.icon FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_groups` as g  ON(u.group = g.id) WHERE u." . $wher . " = '" . $db->safesql($url[1]) . "' AND u.active='1'"));
		$nick = $rows['nick'];
	}
	else
	{
		if($core->auth->isUser) 
		{
			$nick = $core->auth->user_info['nick'];
			$rows = $core->auth->user_info;
		}
	}
	
	if(isset($nick))
	{
		set_title(array('Добро пожаловать в профиль: ', $nick));	
	}
	
	if(isset($nick))
	{
		if($rows)
		{
			if($core->auth->isUser && $core->auth->user_info['nick'] != $nick)
			{
				$gQuery = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_user_visitors` WHERE `id`=" . $rows['id'] . " AND `visitor`=" . $core->auth->user_id . "");
				if($db->numRows($gQuery) == 1)
				{
					$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_user_visitors` SET `time` = '" . time() . "' WHERE `id`=" . $rows['id'] . " AND `visitor`=" . $core->auth->user_id . " LIMIT 1 ;");
				}
				else
				{
					$db->query("INSERT INTO `" . USER_DB . "`.`" . USER_PREFIX . "_user_visitors` ( `id` , `visitor` , `time` ) VALUES ('" . $rows['id'] . "', '" . $core->auth->user_id . "', '" . time() . "');");
				}
			}
			elseif($core->auth->isUser && $core->auth->user_info['nick'] == $nick && $user['userFriends'] == 1)
			{
				$newFriends = $db->query("SELECT u.id as uuid, u.nick, u.last_visit, u.regdate, f.* FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_user_friends` as f on(u.id = f.who_invite OR u.id = f.whom_invite) WHERE (f.who_invite = '" . $rows['id'] . "' OR f.whom_invite = '" . $rows['id'] . "') AND u.id != '" . $rows['id'] . "' AND f.confirmed = '0'");
				if($db->numRows($newFriends) > 0)
				{
					$nF = '';
					while($newFr = $db->getRow($newFriends))
					{
						$nF .= '<br /><span class="_userfriends" id="friendDo' . $newFr['uuid'] . '"><a href="profile/'.$newFr['nick'].'" title="На страницу гостя">'.$newFr['nick'].'</a> [ ' . ($newFr['who_invite'] == $rows['id'] ? '<i>Приглашение с вашей стороны:</i> <a href="javascript:void(0)" onclick="delFriend(' . $newFr['uuid'] . ', \'friendDo' . $newFr['uuid'] . '\');">Отозвать приглашение</a>' : '<a href="javascript:void(0)" onclick="acceptFriend(' . $newFr['uuid'] . ', \'friendDo' . $newFr['uuid'] . '\');">Принять приглашение</a> - <a href="javascript:void(0)" onclick="delFriend(' . $newFr['uuid'] . ', \'friendDo' . $newFr['uuid'] . '\');">Отказаться от дружбы</a>') . ' ]</span>';
					}
				}
			}
			
			if($core->auth->isUser && isset($nF))
			{
				$core->tpl->info('Обратите внимание, к вам добавились несколько новых друзей! Обратите внимание на заголовок "Заявки в друзья" и подтвердите дружбу.');
			}
			
			$profile_link = '';
			if($core->auth->isUser && $core->auth->user_info['nick'] == $nick)
			{
				foreach(glob(ROOT.'usr/modules/*/profile_link.php') as $fileLink)
				{
					$arr = explode('/', $fileLink);
					if(modAccess($arr[count($arr)-2]) == 'groupOk') require_once($fileLink);
				}
				
				if(!empty($link))
				{
					$i = count($link);
					$width = ceil(100/$i);
					$profile_link .= '<table  width="100%" border="0" cellspacing="0" cellpadding="0"><tr>';
					$d = 0;
					foreach($link as $id => $href)
					{
						$d++;
						$profile_link .= '<td width="' . $width . '"><div align="center">' . (isset($linkIcon[$id]) ? $linkIcon[$id] : '') . ' ' . $href . '</div></td>';
						if($d > 5)
						{
							echo '</tr><tr>';
							$d = 0;
						}
					}
					$profile_link .= '</tr></table>';
				}
				unset($link);
			}
			
			$user_wall = '';
			if($user['userWall'] == 1)
			{
				ob_start();
				show_comments('profile', $rows['id'], $user['userWallNum'], false, false, false, '_US', $rows['id']); 
				$user_wall = ob_get_contents();
				ob_end_clean();
			}

			carmaInit($rows['id'], $rows['nick']);
			$uFr = getUserFriends($rows['id']);
			$uGu = getUserGuests($rows['id']);
			$readBlog = readBlog($rows['nick']);
			$exgroup = get_exgroup($rows['points'], $rows['exgroup']);
			$geoip = geo_ip::getInstance(ROOT . 'media/geo_ip.dat');
			$flag = $geoip->lookupCountryCode(!empty($rows['ip']) ? $rows['ip'] : '127.0.0.1');
			$core->tpl->loadFile('profile/profile');
			$core->tpl->setVar('AVATAR', avatar($rows['id']));
			$core->tpl->setVar('NICK', $rows['nick']);
			$core->tpl->setVar('UID', $rows['id']);
			$core->tpl->setVar('SURNAME', $rows['surname']);
			$core->tpl->setVar('CARMA', ($rows['carma'] > 0) ? '+' . $rows['carma'] : $rows['carma']);
			$core->tpl->setVar('NAME', $rows['name']);
			$core->tpl->setVar('POINTS', $rows['points']);
			$core->tpl->setVar('COUNTRICON', $flag != 'UN' ? '<img src="media/flags/' . $flag . '.gif" border="0" class="icon" title="' . $flag . '" alt="" />' : '');
			$core->tpl->setVar('ICQ', $rows['icq'] ? $rows['icq'] : 'Не указано');
			$core->tpl->setVar('SKYPE', $rows['skype'] ? $rows['skype'] : 'Не указано');
			$core->tpl->setVar('HOBBY', $rows['hobby'] ? $rows['hobby'] : 'Не указано');
			$core->tpl->setVar('SIG', $rows['signature'] ? $core->bbDecode($rows['signature']) : 'Не указано');
			$core->tpl->setVar('SEX', $rows['sex'] ? ($rows['sex'] == 1) ? 'Мужской' : 'Женский' : 'Не указан');
			$core->tpl->setVar('LASTVIZIT', formatDate($rows['last_visit']));
			$core->tpl->setVar('OTCH', $rows['ochestvo'] ?  $rows['ochestvo'] . '<br />' : '');
			$core->tpl->setVar('AGE', $rows['age'] ? $rows['age'] : 'Не указано');
			$core->tpl->setVar('EXGROUP', '<font color="' . $exgroup['color'] . '">' . $exgroup['name'] . '</font>');
			$core->tpl->setVar('GROUP', '<font color="' . $rows['color'] . '">' . $rows['gname'] . '</font>');
			$core->tpl->setVar('GROUP_ICON', '<img alt="" src="' . $rows['icon'] . '" border="0" />');
			$core->tpl->setVar('EXGROUP_ICON', '<img alt="" src="' . $exgroup['icon'] . '" border="0" />');		
			$core->tpl->setVar('FRIENDS', $user['userFriends'] == 1 ? $uFr[0] : '');
			$core->tpl->setVar('GUESTS', $user['userFriends'] == 1 ? $uGu : '');
			$core->tpl->setVar('BLOG_READ', $user['readBlog'] == 1 ? $readBlog : '');
			$core->tpl->setVar('NEWFRIENDS', isset($nF) ? $nF : '');
			$core->tpl->setVar('USER_COMMENTS', $rows['user_comments']);
			$core->tpl->setVar('USER_WALL', $user_wall);
			$core->tpl->setVar('PROFILE_LINK', $profile_link);
			$core->tpl->setVar('USER_NEWS', $rows['user_news']);
			$core->tpl->setVar('CLEAN_GUESTS', $core->auth->user_info['nick'] == $nick ?  '<a href="profile/cleanGuests" title="Очистить список"><strong>Очистить список гостей</strong></a>' : '');
			$core->tpl->setVar('NEWFRIENDSNUM', isset($nF) ? $db->numRows($newFriends) : '');
			$core->tpl->setVar('ADD_FRIEND', ($core->auth->isUser && $user['userFriends'] == 1 && $core->auth->user_info['nick'] != $nick) ? (in_array($core->auth->user_id, $uFr[1]) ? '<div id="friendDo"><img src="media/edit/cross.png" alt="" border="0" class="icon" /><a href="javascript:void(0)" onclick="delFriend(' . $rows['id'] . ', \'friendDo\');">Удалить из друзей</a></div>' : '<div id="friendDo"><img src="media/edit/plus.png" alt="" border="0" class="icon" /> <a href="javascript:void(0)" onclick="addFriend(' . $rows['id'] . ');">Добавить в друзья</a></div>') : '');
			$array_replace = array(
				"#\\[exgroup\\](.*?)\\[/exgroup\\]#ies" => "if_set('" . (!empty($exgroup) ? 'yes' : '') . "', '\\1')",
				"#\\[friends\\](.*?)\\[/friends\\]#ies" => "if_set('" . ($user['userFriends'] == 1 && $uFr[0] != '' ? 'yes' : '') . "', '\\1')",
				"#\\[userGuests\\](.*?)\\[/userGuests\\]#ies" => "if_set('" . ($user['userGuests'] == 1 && $uGu != '' ? 'yes' : '') . "', '\\1')",
				"#\\[newFriends\\](.*?)\\[/newFriends\\]#ies" => "if_set('" . (isset($nF) && $user['userFriends'] == 1 ? 'yes' : '') . "', '\\1')",
				"#\\[blog\\](.*?)\\[/blog\\]#ies" => "if_set('" . (modAccess('blog') == 'groupOk' ? 'yes' : '') . "', '\\1')",
				"#\\[blogRead\\](.*?)\\[/blogRead\\]#ies" => "if_set('" . ($user['readBlog'] == 1 && $readBlog != '' ? 'yes' : '') . "', '\\1')",
				"#\\[gallery\\](.*?)\\[/gallery\\]#ies" => "if_set('" . (modAccess('gallery') == 'groupOk' ? 'yes' : '') . "', '\\1')",
			);
			$xfield = '';
			if(!empty($rows['fields']))
			{
				$fields = unserialize($rows['fields']);
				foreach($fields as $xId => $xData)
				{
					if(!empty($xData[1]))
					{
						$array_replace["#\\[xfield_value:" . $xId . "\\]#is"] = $xData[1];
						$xfield .= '<b>' . $xData[0] . '</b>: '.$xData[1].'<br />';
					}
				}
			}
			$array_replace["#\\[xfield:([0-9]*?)\\](.*?)\\[/xfield:([0-9]*?)\\]#ies"] = "ifFields('" . $rows['fields'] . "', '\\1', '\\2')";
			$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);			
			$core->tpl->setVar('EDIT', $core->auth->user_info['nick'] == $nick ? '[ <a href="profile/edit">Редактировать профиль</a> ]' : (modAccess('pm') == 'groupOk' || $core->auth->isUser ? '[ <a href="pm/write/' . $nick . '">Отправить сообщение</a> ]' : ''));
			$core->tpl->setVar('FIELDS', $xfield);
			$core->tpl->setVar('ADMIN', $core->auth->isAdmin ? '<div align="center">[ Админу: <a href="' . ADMIN . '/user/edit/' . $rows['id'] . '">Редактировать профиль</a> | <a href="' . ADMIN . '/user/ban/' . $rows['id'] . '">Забанить</a> | Последний IP: ' . $rows['ip'] . ' <img src="media/flags/' . $flag . '.gif" border="0" class="icon" title="' . $flag . '" alt="" /> ]</div>' : '');
			$core->tpl->end();
			
		}
		else
		{
			$core->tpl->info('Пользователь с задаными параметрами не найден!', 'warning');
		}
	}
	elseif(isset($wher) && empty($nick))
	{
		set_title(array('Ошибка профиля'));		
		$core->tpl->info('Пользователь с задаными параметрами не найден!', 'warning');
	}
	else
	{
		set_title(array('Добро пожаловать в профиль'));
		$core->tpl->loadFile('profile/login');
		$core->tpl->end();
	}
}

function carmaInit($uid, $uname)
{
global $core;
	require_once(ROOT . 'usr/plugins/modal_box/init.php');
	if($core->auth->isUser == false)
	{
		$content = '<div class="mbmest">Изменение кармы доступно только зарегистрированным пользователям сайта!</div>';
	}
	elseif($uid == $core->auth->user_info['id'])
	{
		$content = '<div class="mbmest">Вы не можете изменять карму самому себе!</div>';
	}
	elseif(isset($_COOKIE['carma-' . $uid]))
	{
		$content = '<div class="mbmest">Вы уже совершали действия над кармой этого пользователя!</div>';
	}
	else
	{
		$content = '<table border="0" cellspacing="3" cellpadding="3" style="width:100%;"><tr><td style="width:30%;" valign="top">Действие:</td><td><select id="carmaDo"><option value="p">Поднять репутацию</option><option value="m">Опустить репутацию</option><option value="n">Нейтрально</option></select></td></tr><tr><td valign="top">Сообщение пользователю: </td><td><textarea name="textarea" style="width:80%;" rows="4" id="carmaText"></textarea><br /><sup>* Максимум 200 символов, осталось 200</sup></td></tr><tr><td>&nbsp;</td><td><input type="button" value="Отправить" class="inputsubmit" onclick="addCarma(\'' . $uid . '\', \'carma\')" /></td></tr></table>';
	}
	
	modal_box('Изменение кармы пользователя', 'Выберите действие, которое хотите совершить над кармой пользователя "<b>'.$uname.'</b>"', $content, 'carma');
	$core->tpl->headerIncludes[] = '<script>function carmaHistory() { modal_box(\'carmaHistory\'); AJAXEngine.showedLoadBar = \'\'; AJAXEngine.sendRequest(\'ajax.php?do=carmaHistory&uid=' . $uid . '\', \'carmaHistory\'); }</script>';
	modal_box('История кармы пользователя', 'Просмотр истории изменения кармы пользователя "<b>'.$uname.'</b>"', '<div id="carmaHistory"><div class="mbmest">Загрузка информации о карме пользователя..</div></div>', 'carmaHistory');
	
	
}

function getUserFriends($uid, $limit = 9999)
{
global $db, $user, $core;
	$friends = '';
	if($user['userFriends'] == 1)
	{
		$fq = $db->query("SELECT u.id as uuid, u.nick, u.last_visit, f.* FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_user_friends` as f on(u.id = f.who_invite OR u.id = f.whom_invite) WHERE (f.who_invite = '" . $uid . "' OR f.whom_invite = '" . $uid . "') AND u.id != '" . $uid . "' AND f.confirmed = '1'");
		$yourFriends = array();
		if($db->numRows($fq) > 0)
		{
			while($frows = $db->getRow($fq))
			{	
				$friends .= '<span class="_userfriends"><a href="profile/'.$frows['nick'].'" title="На страницу друга">'.$frows['nick'].'</a></span> ';
				$yourFriends[] = $frows['uuid'];
			}
		}
	}
	return array($friends, $yourFriends);
}

function readBlog($nick)
{
global $db, $user, $core;
	if($user['readBlog'] == 1)
	{
		$query = $db->query("SELECT title, altname FROM `" . DB_PREFIX . "_blogs` WHERE readers LIKE '%," . $db->safesql($nick) . ",%'");
		if($db->numRows($query) > 0)
		{
			$blogs = '';
			while($blog = $db->getRow($query))
			{
				$blogs .= '<span class="_bloglist"><a href="blog/view/'.$blog['altname'].'" title="На страницу блога">'.$blog['title'].'</a></span> ';
			}
			
			return $blogs;
		}
	}
}

function getUserGuests($uid)
{
global $db, $user, $core;
	$guests = '';
	if($user['userGuests'] == 1)
	{
		$gq = $db->query("SELECT u.id as uuid, u.nick, u.last_visit, g.* FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_user_visitors` as g on(u.id = g.visitor) WHERE g.id=" . $uid . " ORDER BY g.time DESC");
		if($db->numRows($gq) > 0)
		{
			while($frows = $db->getRow($gq))
			{
				$guests .= '<span class="_userfriends"><a href="profile/'.$frows['nick'].'" title="На страницу гостя">'.$frows['nick'].'</a></span> ';
			}
		}
		else
		{
			$guests .= '';
		}
	}
	return $guests;
}

if(!$core->auth->isUser) 
{
	switch(isset($url[1]) ? $url[1] : null) 
	{
		default:
			if(isset($url[1]))
			{
				main();
			}
			else
			{
				set_title(array('Добро пожаловать в профиль'));
				$core->tpl->loadFile('profile/login');
				$core->tpl->end();
			}
			break;
		
		case 'login':
			if (!isset($_POST['nick']) || !isset($_POST['password'])) 
			{
				location('profile/');
			}
	
			$no_head = true;
			
			if ($core->auth->login($_POST['nick'], $_POST['password'])) 
			{
				$core->tpl->redicret('Вы успешно вошли сейчас вас переместит обратно!', $_SERVER['HTTP_REFERER']);
				if($config['plugin']) $plugin->login($_POST['nick'], $_POST['password']);
			} 
			else 
			{
				$core->tpl->redicret('Вход не выполнен! Проверьте имя пользователя, пароль и попробуйте снова!', $_SERVER['HTTP_REFERER'], 'Ошибка!');
			}
			
			break;
	
		case 'register':			
			set_title(array('Регистрация'));
			require ROOT . 'etc/user.config.php';
			if(!isset($_POST['user_login'])) 
			{
				$core->tpl->loadFile('profile/register');
				$core->tpl->setVar('AVATAR', avatar($core->auth->user_id));
				$core->tpl->setVar('CAPTCHA', captcha_image());
				$core->tpl->end();
				
			} 
			else 
			{
				$user_login = !empty($_POST['user_login']) ? filter($_POST['user_login'], 'nick') : '';
				$password = !empty($_POST['password']) ? $_POST['password'] : '';
				$repassword = !empty($_POST['repassword']) ? $_POST['repassword'] : '';
				$email = !empty($_POST['email']) ? filter($_POST['email'], 'mail') : '';
				$icq = !empty($_POST['icq']) ? filter($_POST['icq'], 'a') : '';
				$skype = !empty($_POST['skype']) ? filter($_POST['skype'], 'a') : '';
				$family = !empty($_POST['family']) ? filter($_POST['family'], 'a') : '';
				$name = !empty($_POST['name']) ? filter($_POST['name'], 'a') : '';
				$ochestvo = !empty($_POST['ochestvo']) ? filter($_POST['ochestvo'], 'a') : '';
				$age = !empty($_POST['age']) ? intval($_POST['age']) : '';
				$sex = !empty($_POST['sex']) ? intval($_POST['sex']) : '';
				$about = !empty($_POST['about']) ? filter($_POST['about'], 'a') : '';
				$signature = !empty($_POST['signature']) ? filter($_POST['signature']) : '';
				$activate = ($user['with_activate'] == 1) ? '0' : '1';
				
				if(!empty($user_login) && !empty($password) && !empty($repassword) && !empty($email) && ($password == $repassword) && !empty($email)) 
				{
					if(eregStrt('/', $user_login))
					{
						$error = 'Логин содержит недопустимый символ "/"! Вернитесь назад и исправте ошибку.';
					}
					
					if(captcha_check('securityCode') && empty($error)) 
					{
						list($check) = $db->fetchRow($db->query("SELECT Count(id) FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick='" . $db->safesql($user_login) . "' OR email='" . $db->safesql($email) . "'"));
						
						if($check > 0) 
						{
							$error = 'Пользователь с такими данными уже существует!';
						}
						else
						{
							$tail = gencode(rand(6, 11));
							
							$core->auth->register($user_login, $password, $tail, $email, $icq, $skype, $family, $name, $ochestvo, $age, $sex, $about, $signature, $activate, filter($_SERVER['REMOTE_ADDR']));
							
							if($config['plugin']) $plugin->registration($user_login, $password, $tail, $email, $icq, $skype, $family, $name, $ochestvo, $age, $sex, $about, $signature, $activate, filter($_SERVER['REMOTE_ADDR']));
							
							$info = '<b>Благодарим Вас!</b><br />Теперь вы можете войти на сайт используя своё имя и пароль.';
							
							if($user['with_activate'] == 1)
							{
								$formatemail = str_replace(array('@', '.'), array('[gav]', '[dot]'), $email);
								$regMsg = 'Здравствуйте, <b>' . $user_login . '</b>!' . "<br /><br />";
								$regMsg .= 'Вы получили это письмо, так как этот е-мэйл адрес был использован при регистрации на ' . $config['name'] . "<br />";
								$regMsg .= 'Если Вы не регистрировались на этом сайте, просто проигнорируйте это письмо и удалите его.' . "<br /><br />";
								$regMsg .= 'Чтобы завершить процесс регистрации, перейдите по ссылке(заменять [gav], [dot] не требуется!):' . "<br />";
								$regMsg .= '<a href="'.$config['url'] . '/profile/activate/' . $formatemail . '/' . mb_substr(md5($formatemail . 'eduard_laas_loh'), 0, 10).'">'.$config['url'] . '/profile/activate/' . $formatemail . '/' . mb_substr(md5($formatemail . 'eduard_laas_loh'), 0, 10) . "</a><br /><br />";
								$regMsg .= '---' . "<br />";
								$regMsg .= '<i>С уважением, администрация ' . $config['name'] . "</i><br />";
								sendMail($email, 'Подтверждение регистрации на ' . $config['name'], $regMsg);
								$info = '<b>Благодарим Вас!</b><br />На указанный Вами электронный адрес выслано письмо с инструкциями по завершению регистрации';
							}
							else
							{
								list($uid) = $db->fetchRow($db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick='" . $db->safesql($user_login) . "' LIMIT 1"));
								user_points($user_login, 'register');
								$db->query("INSERT INTO `" . DB_PREFIX . "_board_users` (`uid`) VALUES ('" . $uid . "');", true);
							}
							
							if(isset($_FILES['avatar']) && $_FILES['avatar']['size'] > 0) 
							{
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
						}
					} 
					else 
					{
						if(empty($error)) $error = 'Код каптчи введён неверно!';
					}
				}
				else 
				{
					$error = 'Некоторые из обязательный полей не введены, вернитесь назад и попробуйте снова!';
				}
				$core->tpl->title('Регистрация');
				if(isset($error)) $core->tpl->info($error, 'warning');
				if(isset($info)) $core->tpl->info($info);
			}
			break;
		
		case 'forgot_pass':
		
			set_title(array('Восстановление пароля'));
			
			if(!isset($_POST['email'])) 
			{
				$core->tpl->loadFile('profile/password');
				$core->tpl->setVar('CAPTCHA', captcha_image());
				$core->tpl->end();
			} 
			else 
			{
				$email = filter($_POST['email'], 'mail');
				
				if(!empty($email))
				{
					list($uid, $nick) = $db->fetchRow($db->query("SELECT id, nick FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE email='" . $db->safesql($email) . "' LIMIT 1"));
					
					if($uid > 0) 
					{
						if(captcha_check('securityCode')) 
						{
							$formatemail = str_replace(array('@', '.'), array('[gav]', '[dot]'), $email);
							$regMsg = 'Здравствуйте, ' . $nick . '!' . "<br />";
							$regMsg .= 'Вы получили это письмо, так как этот е-мэйл адрес был использован при восстановлении пароля на ' . $config['name'] . "<br />";
							$regMsg .= 'Если Вы не делали этого на этом сайте, просто проигнорируйте это письмо и удалите его.' . "<br />";
							$regMsg .= 'Перейдите по ссылке: ' . $config['url'] . '/profile/forgotPass/' . $formatemail . '/' . mb_substr(md5($formatemail . 'eduard_laas_loh'), 0, 10) . '/' . "<br />";
							$regMsg .= '---' . "<br />";
							$regMsg .= 'С уважением, администрация ' . $config['name'] . "\n";
							
							sendMail($email, 'Восстановление пароля на сайте ' . $config['name'], $regMsg);
							
							$core->tpl->info('Благодарим вас!<br />На указанный Вами электронный адрес выслано письмо с потверждением.');
						} 
						else 
						{
							$core->tpl->info('Код каптчи введён неверно!', 'warning');
						}
					} 
					else 
					{
						$core->tpl->info('Ваш e-mail не найден в базе.', 'warning');
					}
				}
				else
				{
					location('profile/forgot_pass');
				}
			}
			break;
			
		case 'forgotPass':
			if(isset($url[1]) && isset($url[2]))
			{
				$email = $url[2];
				$formatemail = filter(str_replace(array('[gav]', '[dot]'), array('@', '.'), $email), 'mail');
				
				if(!empty($formatemail))
				{
					$md5 = $url[3];
					list($uid, $nick) = $db->fetchRow($db->query("SELECT id, nick FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE email='" . $db->safesql($formatemail) . "' LIMIT 1"));
					if ($md5 == mb_substr(md5($email . 'eduard_laas_loh'), 0, 10) && $nick) 
					{
						$new_pass = gencode(10);			
							
						$tail = gencode(rand(6, 11));
							
						$core->auth->forgotPass($new_pass, $tail, $uid);
						if($config['plugin']) $plugin->forgot_pass($new_pass, $tail, $uid);
						
						$regMsg = 'Здравствуйте, ' . $nick . '!' . "<br />";
						$regMsg .= 'Вы получили это письмо, так как этот е-мэйл адрес был использован при восстановлении пароля на ' . $config['name'] . "<br />";
						$regMsg .= 'Если Вы не делали этого на этом сайте, просто проигнорируйте это письмо и удалите его.' . "<br />";
						$regMsg .= 'Ващ пароль: ' .$new_pass . "<br />";
						$regMsg .= '---' . "\n";
						$regMsg .= 'С уважением, администрация ' . $config['name'] . "\n";
						
						sendMail($formatemail, 'Восстановление пароля на сайте ' . $config['name'], $regMsg);
						
						$core->tpl->info('Благодарим вас!<br />На указанный Вами электронный адрес выслано письмо с новым паролем.');
					}
					else
					{
						location();
					}
				}
				else
				{
					location('profile');
				}
			}
			else
			{
				location();
			}
			break;
		
		case 'activate':
			if(isset($url[1]) && isset($url[2]))
			{
				$email = $url[2];
				$md5 = $url[3];
				if ($md5 == mb_substr(md5($email . 'eduard_laas_loh'), 0, 10)) 
				{
					$formatemail = filter(str_replace(array('[gav]', '[dot]'), array('@', '.'), $email), 'mail');
					
					if(!empty($formatemail))
					{
						$core->auth->activate($formatemail);
						
						list($uid) = $db->fetchRow($db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE email='" . $db->safesql($formatemail) . "' LIMIT 1"));
						user_points($uid, 'register');
						$db->query("INSERT INTO `" . DB_PREFIX . "_board_users` (`uid`) VALUES ('" . $uid . "');", true);
						$core->tpl->info('Ваш аккаунт успешно активирован, используете свои имя и пароль для входа на сайт.');
					}
					else
					{
						location('profile');
					}
				}
				else
				{
					$core->tpl->info('Ваш аккаунт не активирован, проверьте правильность ссылки!', 'warning');
				}
			}
			break;
			
	}
} else {
	switch(isset($url[1]) ? $url[1] : null) {
		default:
			main();
		break;

		case 'edit':
			set_title(array('Редактировать профиль'));
	
			if(isset($_POST['surname']))
			{
				$surname = !empty($_POST['surname']) ? filter($_POST['surname'], 'a') : '';
				$name = !empty($_POST['name']) ? filter($_POST['name'], 'a') : '';
				$ochestvo = !empty($_POST['ochestvo']) ? filter($_POST['ochestvo'], 'a') : '';
				$birthDay = !empty($_POST['birthDay']) ? intval($_POST['birthDay']) : '';
				$birthMonth = !empty($_POST['birthMonth']) ? intval($_POST['birthMonth']) : '';
				$birthYear = !empty($_POST['birthYear']) ? intval($_POST['birthYear']) : '';
				$gender = !empty($_POST['gender']) ? intval($_POST['gender']) : '';
				$avatar_link = !empty($_POST['avatar_link']) ? filter($_POST['avatar_link'], 'url') : '';
				$signature = !empty($_POST['signature']) ? filter($_POST['signature'], 'html') : '';
				$mail = !empty($_POST['mail']) ? filter($_POST['mail'], 'mail') : '';
				$hobby = !empty($_POST['hobby']) ? filter($_POST['hobby'], 'a') : '';
				$icq = !empty($_POST['icq']) ? filter($_POST['icq'], 'a') : '';
				$skype = !empty($_POST['skype']) ? filter($_POST['skype'], 'a') : '';
				$place = !empty($_POST['place']) ? filter($_POST['place'], 'a') : '';
				$newpass = !empty($_POST['newpass']) ? $_POST['newpass'] : '';
				$renewpass = !empty($_POST['renewpass']) ? $_POST['renewpass'] : '';
				$xfield = isset($_POST['xfield']) ? $_POST['xfield'] : '';
				$xfieldT = isset($_POST['xfieldT']) ? ($_POST['xfieldT']) : '';
			
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
				
				if($newpass && $renewpass)
				{
					if($newpass == $renewpass)
					{
						$core->auth->updatePassword($newpass);
						if($config['plugin']) $plugin->updatePassword($newpass);
					}
					else
					{
						$error[] = 'Пароли не совпадают, изменение невозможно!';
					}
				}
				
				if(isset($_POST['deleteAvatar']))
				{
					deleteAvatar($core->auth->user_id);
				}
				
				if(empty($mail))
				{
					$mail = '';
					$error[] = 'E-Mail имеет неверный формат';
				}
				
			
				$fieldsSer = '';
				if(!empty($xfield))
				{
					foreach($xfield as $xId => $xContent)
					{
						if(!empty($xContent) && $xId > 0 && !empty($xfieldT[$xId]))
						{
							$xContent = processText(filter($xContent, 'html'));
							$xId = intval($xId);
							$xfieldT[$xId] = processText(filter($xfieldT[$xId], 'title'));
							$fileds[$xId] = array($xfieldT[$xId], $xContent);
						}
					}
					
					$fieldsSer = serialize($fileds);
				}
				
				
				$core->auth->updateProfile($mail, $icq, $skype, $surname, $name, $ochestvo, $place, $age, $gender, $birthDate, $hobby, parseBB(processText($signature)), $fieldsSer);
				if($config['plugin']) $plugin->updateProfile($mail, $icq, $skype, $surname, $name, $ochestvo, $place, $age, $gender, $birthDate, $hobby, parseBB(processText($signature)), $fieldsSer);
				
				if($_FILES['avatar']['size'] > 0) 
				{
					deleteAvatar($core->auth->user_id);
					if($foo = new Upload($_FILES['avatar']))
					{
						$foo->file_new_name_body = 'av' .$core->auth->user_id;
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
				elseif(!empty($avatar_link) && eregStrt('http://', $avatar_link) && file_get_contents($avatar_link))
				{
					/*deleteAvatar($core->auth->user_id);
					if($foo = new Upload($avatar_link))
					{
						$foo->file_new_name_body = 'av' .$core->auth->user_id;
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
					$type = getExt($avatar_link);
					deleteAvatar($core->auth->user_id);
					if($type == 'jpg' || $type == 'png' || $type == 'jpeg')
					{
						
						$thumb = new Thumbnail($avatar_link);
						$thumb->size_width($user['avatar_width']);
						$thumb->quality = 100;  
						$thumb->process();
						$thumb->save("files/avatars/users/av" . $core->auth->user_id . ".".$type);
					}*/
				}
				
				$core->tpl->info('Ваш профиль успешно сохранён!');
				
				if(isset($error))
				{
					$txt = '';
					
					foreach($error as $msg)
					{
						$txt .= $msg . '<br />';
					}
					
					$core->tpl->info($txt, 'warning');
				}
				
				$userInfo = $db->getRow($db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE id = '" . $core->auth->user_info['id'] . "'"));
			} //Сохранение профиля
			else
			{
				$userInfo = $core->auth->user_info;
			}
			
			if($userInfo['birthday']) 
			{
				$birthday = explode('.', $userInfo['birthday']);
			}
			else
			{
				$birthday = explode('.', '0.0.0');
			}
			$bb = bb_area('signature', html2bb($userInfo['signature']), 5, 'textarea', false, true);
			$gender = '<option value="">---</option>';
			$gender .= '<option value="1"' . ($userInfo['sex'] == '1' ? ' selected' : '') . '>Мужской</option>';
			$gender .= '<option value="2"' . ($userInfo['sex'] == '2' ? ' selected' : '') . '>Женский</option>';
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
			
			$core->tpl->loadFile('profile/edit');
			$core->tpl->sources = preg_replace("#\\{%AVATAR_LOAD%\\}(.*?)\\{%/AVATAR_LOAD%\\}#ies","if_set('" . $user['avatar_load'] . "', '\\1')", $core->tpl->sources);
			$core->tpl->setvar('BB_AREA', $bb);
			preg_match("#\\[xfield_tpl\\](.*?)\\[/xfield_tpl\\]#si", $core->tpl->sources, $matchEmpty);
			if(isset($matchEmpty[1]))
			{
				$xfiled_tpl = $matchEmpty[1];
				$fields = unserialize($core->auth->user_info['fields']);
				//$core->tpl->sources = preg_replace(array("#\\[xfield_tpl\\](.*?)\\[/xfield_tpl\\]#si", "#\\[list\\](.*?)\\[/list\\]#si"), array(($numRes == 0 ? '\\1' : ''), $messList), $core->tpl->sources);
				$xfileds = '';
				$queryF = $db->query("SELECT * FROM ".DB_PREFIX."_xfields WHERE module='profile' and to_user='1'");
				if($db->numRows($queryF) > 0) 
				{
					while($xfield = $db->getRow($queryF)) 
					{
						if($xfield['type'] == 3)
						{
							$dxfield = array_map('trim', explode("\n", $xfield['content']));
							$xfieldChange = '<select class="textinput" {addition} name="xfield[' . $xfield['id'] . ']"><option value="">Пусто</option>';
							foreach($dxfield as $xfiled_content)
							{
								$xfieldChange .= '<option value="' . $xfiled_content . '" ' . (!empty($fields[$xfield['id']][1]) ? 'selected' : ''). '>' . $xfiled_content . '</option>';
							}
							$xfieldChange .= '</select>';
						}
						elseif($xfield['type'] == 2)
						{
							$xfieldChange = '<textarea class="textarea" {addition} name="xfield[' . $xfield['id'] . ']" >' . (!empty($fields[$xfield['id']][1]) ? $fields[$xfield['id']][1] : $xfield['content']) . '</textarea>';
						}
						else
						{
							$xfieldChange = '<input type="text" class="textinput" {addition} name="xfield[' . $xfield['id'] . ']" value="' . (!empty($fields[$xfield['id']][1]) ? $fields[$xfield['id']][1] : $xfield['content']) . '" />';
						}
						
						$xfileds .= preg_replace(array('@{%XTITLE%}@', '@{%XBODY:\[(.*?)\]%}@ise'), array($xfield['title'], "str_replace('{addition}', stripslashes('\\1'), '<input type=\"hidden\" name=\"xfieldT[" . $xfield['id'] . "]\" value=\"" . $xfield['title'] . "\" />'.\$xfieldChange);"), $xfiled_tpl);
					}
					$yesField = true;
					$core->tpl->setVar('XFIELDS', $xfileds);
				}
			}

			$core->tpl->sources = preg_replace(array("#\\[fields\\](.*?)\\[/fields\\]#si", "#\\[xfield_tpl\\](.*?)\\[/xfield_tpl\\]#si"), array((isset($yesField) ? '\\1' : ''), ''), $core->tpl->sources);
			$core->tpl->setvar('AWIDTH', $user['avatar_width']);
			$core->tpl->setvar('AHEIGHT', $user['avatar_height']);
			$core->tpl->setVar('AVATAR', avatar($userInfo['id']));
			$core->tpl->setVar('AVATAR', avatar($userInfo['id']));
			$core->tpl->setvar('HOBBY', $userInfo['hobby']);
			$core->tpl->setvar('GENDER_LIST', $gender);
			$core->tpl->setvar('DAY_LIST', $day);
			$core->tpl->setvar('MONTH_LIST', $month);
			$core->tpl->setvar('YEAR_LIST', $year);
			$core->tpl->setvar('SURNAME', $userInfo['surname']);
			$core->tpl->setvar('PLACE', $userInfo['place']);
			$core->tpl->setvar('NAME', $userInfo['name']);
			$core->tpl->setvar('OCH', $userInfo['ochestvo']);
			$core->tpl->setvar('EMAIL', $userInfo['email']);
			$core->tpl->setvar('ICQ', $userInfo['icq']);
			$core->tpl->setvar('SKYPE', $userInfo['skype']);
			$core->tpl->setvar('ASIZE', formatfilesize($user['avatar_size']));
			$core->tpl->end();
			break;
		
		case 'logout':
			$core->auth->logout();
			
			if($config['plugin']) 
			{
				$plugin->logout();
			}
			
			$no_head = true;
			
			$core->tpl->redicret('Вы успешно вышли сейчас вас переместит обратно!', $_SERVER['HTTP_REFERER']);
		break;
		
		case 'cleanGuests':
			$db->query("DELETE FROM `" . USER_DB . "`.`" . USER_PREFIX . "_user_visitors` WHERE `id` = " . $core->auth->user_id);
			location('profile');
			break;
	}
}