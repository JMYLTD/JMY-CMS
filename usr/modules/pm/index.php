<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/ 
 
//редакция 19.01.2015

if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}


if($core->auth->isUser == true)
{
	$mod = $url[0];

	function menu($title = 'Приватные сообщения')
	{
	global $core;
		$core->tpl->loadFile('pm/menu');
		//$core->tpl->setVar('TITLE', $title);
		return $core->tpl->return_end();
	}

	switch(isset($url[1]) ? $url[1] : null) 
	{
		default:
			set_title(array('Приватные сообщения', 'Входящие'));
			$core->tpl->loadFile('pm/main');
			$core->tpl->sources = preg_replace("#\\[menu:(.*?)\\]#ies", "menu('\\1')", $core->tpl->sources);	
			$core->tpl->end();
			break;
			
		case 'inbox':
			$result = $db->query("SELECT pm.*, u.nick FROM `" . DB_PREFIX . "_pm` as pm LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on (pm.fromid = u.id) WHERE pm.toid = '" . $core->auth->user_id . "' ORDER BY time DESC");
			$messNo = 'входящих сообщений';
			$th1 = 'Отправитель';
			$init = 'fromid';
			
		case 'sentbox':
			if(!isset($messNo))
			{
				$result = $db->query("SELECT pm.*, u.nick FROM `" . DB_PREFIX . "_pm` as pm LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on (pm.toid = u.id) WHERE pm.fromid = '" . $core->auth->user_id . "' ORDER BY time DESC");
				$messNo = 'исходящих сообщений';
				$th1 = 'Получатель';
				$init = 'toid';
			}
			
		case 'draftcopy':
			if(!isset($messNo))
			{
				$result = $db->query("SELECT pm.*, u.nick FROM `" . DB_PREFIX . "_pm` as pm LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on (pm.toid = u.id) WHERE pm.fromid = '" . $core->auth->user_id . "' AND pm.status='2' ORDER BY time DESC");
				$messNo = 'черновиков';
				$th1 = 'Получатель';
				$init = 'toid';
			}
			
			ajaxInit();
			
			$no_head = true;
			$numRes = $db->numRows($result);
			$core->tpl->loadFile('pm/list');
			$core->tpl->setVar('TH1', $th1);
			$core->tpl->setVar('MESSNO', $messNo);
			$core->tpl->sources = preg_replace("#\\[initfrom\\](.*?)\\[/initfrom\\]#ies", "if_set('" . ($init == 'fromid' ? 1 : 0) . "', '\\1')", $core->tpl->sources);	
			preg_match("#\\[list\\](.*?)\\[/list\\]#si", $core->tpl->sources, $matches);
			$list = $matches[1];			
			preg_match("#\\[messEmpty\\](.*?)\\[/messEmpty\\]#si", $core->tpl->sources, $matchEmpty);
			$messEmpty = $matchEmpty[1];
			$messList = '';
			if($numRes > 0) 
			{
				while($message = $db->getRow($result))
				{
					$replace = array(
						'@{%AVATAR%}@' => avatar($message[$init]),
						'@{%NICK%}@' => $message['nick'],
						'@{%MESSID%}@' => $message['id'],
						'@{%MOD_NAME%}@' => $mod,
						'@{%DATE%}@' => formatDate($message['time']),
						'@{%MESSAGE%}@' =>  $core->bbDecode($message['message']),
						"@\\[status\=0\\](.*?)\\[else\\](.*?)\\[/status\\]@is" =>  ($message['status'] == 0 ? '\\1' : '\\2'),
					);
					$tpl = preg_replace(array_keys($replace), array_values($replace), $list);	
					$messList .= $tpl;
					unset($tpl);
				}
			}
			$core->tpl->sources = preg_replace(array("#\\[messEmpty\\](.*?)\\[/messEmpty\\]#si", "#\\[list\\](.*?)\\[/list\\]#si"), array(($numRes == 0 ? '\\1' : ''), $messList), $core->tpl->sources);
			$core->tpl->end();
			break;
			
		case 'write':
			set_title(array('Приватные сообщения', 'Написать'));
			$nick = isset($url[2]) ? $url[2] : '';
			$message = '';
			if(!empty($nick)) $result = $db->getRow($db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick = '" . $db->safesql($nick) . "'"));
			if(!empty($nick) && !empty($result) && isset($url[3]))
			{
				$q = $db->getRow($db->query("SELECT * FROM " . DB_PREFIX . "_pm WHERE id = '" . intval($url[3]) . "' AND fromid='" . intval($result['id']) . "' AND toid='" . intval($core->auth->user_info['id']) . "' LIMIT 1"));
				$message = '[quote]'.$q['message'].'[/quote]';
			}
			$write = "<form enctype=\"multipart/form-data\" action=\"" . $mod . "/sent\" method=\"post\">"
			."<table width=\"100%\" border=\"0\" cellpadding=\"3\" cellspacing=\"0\" align=\"center\">"
			."<tr><td valign=\"top\">" . _USERNAME . ":</td><td><input type=\"text\" name=\"author\" id=\"author\" value=\"" . (isset($result) ? $nick : '') . "\" onkeyup=\"checkLogin(gid('author').value, 'check_result', 'author')\"><br /><sup>Ник зарегистрированного пользователя</sup><div id=\"check_result\" class=\"results\" style=\"display:none;\"></div></td></tr>";
			if($user['userFriends'] == 1)
			{
				$uid = $core->auth->user_info['id'];
				$fq = $db->query("SELECT u.id as uuid, u.nick, u.last_visit, f.* FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_user_friends` as f on(u.id = f.who_invite OR u.id = f.whom_invite) WHERE (f.who_invite = '" . $uid . "' OR f.whom_invite = '" . $uid . "') AND u.id != '" . $uid . "' AND f.confirmed = '1'");
				$friends = '';
				$yourFriends = array();
				if($db->numRows($fq) > 0)
				{
					while($frows = $db->getRow($fq))
					{	
						$friends .= '<option value="'.$frows['nick'].'">'.$frows['nick'].'</option>';
						$yourFriends[] = $frows['uuid'];
					}
					$write .= "<tr><td>Выбрать из друзей:</td><td><select name=\"friend\"><option value=\"\">" . _NO . "</option>" . $friends  . "</select></td></tr>";
				}
			}
			$write .= "<tr><td>" . _TEXT . ":</td><td>";
			$write .= bb_area('question', html2bb($message), 5, 'textarea', null, true);
			$write .= "</td></tr>";
			$write .= "<tr><td colspan=\"2\" style=\"text-align:center\"><br /><input type=\"hidden\" name=\"id\" value=\"\"><input type=\"submit\" value=\"" . _SEND . "\" ></td></tr></form></table>";
			
			$core->tpl->loadFile('pm/write');
			$core->tpl->sources = preg_replace("#\\[menu:(.*?)\\]#ies", "menu('\\1')", $core->tpl->sources);	
			$core->tpl->setVar('WRITE', $write);
			$core->tpl->end();
			break;

		case 'sent':
			if (!empty($_POST['author']) && !empty($_POST['question']))
			{
				echo menu();
				echo '<div id="pm_content">' . "\n";
				$friend = filter($_POST['friend'], 'nick');
				$to = empty($friend) ? filter($_POST['author'], 'nick') : $friend;
				if($core->auth->user_info['nick'] != $to)
				{
					$result = $db->getRow($db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick = '" . $db->safesql($to) . "'"));
					if ($result)
					{
						if ($db->query("INSERT INTO " . DB_PREFIX . "_pm VALUES (NULL, " . $result['id'] . ", " . $core->auth->user_id . ", '" . $db->safesql(parseBB(processText(filter($_POST['question'])))) . "', " . time() . ", 0)"))
						{
							location('pm/sent_ok');
						}
						else
						{
							$core->tpl->info('Произошла ошибка отправки', 'warning');
						}
					}
					else
					{
						$core->tpl->info('Выбранный пользователь не найден!', 'warning');
					}
				}
				else
				{
					$core->tpl->info('Вы не можете отправлять сообщение самому себе!', 'warning');
				}
				echo '</div>';
			}
			else
			{
				echo menu();
				$core->tpl->info('Вы не заполнили обязательные поля формы отправки!', 'warning');
			}
			break;

		case 'sent_ok':
			echo menu();
			echo '<div id="pm_content">' . "\n";
			$core->tpl->info('Сообщение успешно отправлено');
			echo '</div>';
			break;

		 case 'view':
			$id = intval($url[2]);
			set_title(array('Приватные сообщения', 'Просмотр сообщения'));
			$result = $db->getRow($db->query("SELECT pm.*, u.nick FROM " . DB_PREFIX . "_pm as pm LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on(pm.toid=u.id) WHERE pm.id = '" . $id . "'"));
			if ($result)
			{
				if($result['toid'] == $core->auth->user_id && $result['status'] == 0)
				{
					$db->query("UPDATE `" . DB_PREFIX . "_pm` SET `status` = '1' WHERE `id` =" . $id . " LIMIT 1");
					$result['status'] = 1;
				}

				$from = $db->getRow($db->query("SELECT nick FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE id = '" . $result['fromid'] . "'"));
				$core->tpl->loadFile('pm/view');
				$core->tpl->setVar('MESSAGE', $core->bbDecode($result['message']));
				$core->tpl->setVar('AVATAR', ($result['toid'] == $core->auth->user_id ? avatar($result['fromid']) : avatar($result['toid'])));
				$core->tpl->setVar('FROM', $from['nick']);
				$core->tpl->setVar('TO', $result['nick']);
				$core->tpl->setVar('ID', $result['id']);
				$core->tpl->setVar('DATE', formatDate($result['time']));
				$core->tpl->sources = preg_replace(array("@\\[status\=0\\](.*?)\\[else\\](.*?)\\[/status\\]@is", "@\\[actions\\](.*?)\\[/actions\\]@is", "#\\[menu:(.*?)\\]#ies"), array(($result['status'] == 0 ? '\\1' : '\\2'), ($result['toid'] == $core->auth->user_id ? '\\1' : ''), "menu('\\1')"), $core->tpl->sources);	
				$core->tpl->end();
			}
			else
			{
				location('pm');
			}
			break;


		case 'del':
			ajaxInit();
			$no_head = true;

			if (!empty($url[2]))
			{
				$db->query("DELETE FROM " . DB_PREFIX . "_pm WHERE id = '" . $db->safesql($url[2]) . "' AND toid = '" . $core->auth->user_id . "'");

				echo 'Удалено';
			}
			break;
			
		case 'action':
			$checks = isset($_POST['checks']) ? $_POST['checks'] : '';
			if(!empty($checks))
			{
				if(isset($_POST['read']))
				{
						foreach($checks as $id)
						{
							$id = intval($id);
							if($id > 0)	$db->query("UPDATE `" . DB_PREFIX . "_pm` SET `status` = '1' WHERE `id` =" . $id . " AND toid = '" . $core->auth->user_id . "' LIMIT 1");
						}
				}
				elseif(isset($_POST['del']))
				{
					foreach($checks as $id)
					{
						$id = intval($id);
						if($id > 0)	$db->query("DELETE FROM " . DB_PREFIX . "_pm WHERE id = '" . $db->safesql($id) . "' AND (toid = '" . $core->auth->user_id . "' OR fromid='" . $core->auth->user_id . "') LIMIT 1");
					}
				}
			}
			location('pm');
			break;
	}
}
else
{
	location();
}