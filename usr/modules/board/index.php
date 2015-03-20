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

require_once ROOT . 'etc/board.config.php';
require_once ROOT . 'usr/modules/board/forum_funcs.php';

function main()
{
global $core, $db;
	set_title(array(_FORUM_TITLE));
	menu();		
	get_forums();	
	$online = 0;
	list($topics, $posts) = $db->fetchRow($db->query("SELECT COUNT(id), (SELECT COUNT(id) FROM " . DB_PREFIX . "_board_posts) as posts FROM " . DB_PREFIX . "_board_threads"));
	list($users, $last) = $db->fetchRow($db->query("SELECT COUNT(id), (SELECT nick FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` ORDER BY id DESC LIMIT 1) as last FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users`"));
	
	$on = $db->query("SELECT o.*, p.nick FROM " . DB_PREFIX . "_online AS o LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` AS p ON (p.id=o.uid) WHERE url LIKE '%board%'");

	$i = 0;
	while($online = $db->getRow($on)) 
	{
		$i++;
		if(!empty($online['nick']))
		{
			$onn[] = '<a href="profile/'.$online['nick'].'" >'.$online['nick'].'</a>';
		}
	}
	$cookie = isset($_COOKIE['Block_stats']) ? true : false;	
	$core->tpl->open('forum.stat');
	$core->tpl->loadFile('board/forum.stat');
	$core->tpl->setVar('COOKIE_IMG',  ($cookie ? 'close' : 'open'));
	$core->tpl->setVar('COOKIE_DIS',  ($cookie ? 'none' : 'block'));
	$core->tpl->setVar('NUMB_USER', $users);
	$core->tpl->setVar('NUMB_TOPICS', $topics);
	$core->tpl->setVar('NUMB_POSTS',  $posts);
	$core->tpl->setVar('NUMB_ONLINE',  ($i == 0 ? ($core->auth->isUser ? 1 : 0) : $i));
	$core->tpl->setVar('ONLINE_USER',  (empty($onn) ? ($core->auth->isUser ? '<a href="profile/' . $core->auth->user_info['nick'] . '" title="' . $core->auth->user_info['nick'] . '">' . $core->auth->user_info['nick'] . '</a>' : '...') : implode(', ', $onn)));
	$core->tpl->setVar('NEW_USER', '<a href="profile/' . $last . '" title="' . $last . '">' . $last . '</a>');	
	$core->tpl->end();
	$core->tpl->close();	
}

function showForum($tid)
{
global $db, $core, $board_conf;
	$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_forums` WHERE `id` = '" . $tid . "'");
	$name = $db->getRow($query);
	$orderType = isset($_POST['order']) ? filter($_POST['order'], 'a') : '';
	
	if($name && permission($tid, 'View'))
	{
		switch($orderType)
		{
			default:
				$orderBy = 'lastTime';
				break;			
				
			case 'subject':
				$orderBy = 'title';
				break;			
				
			case 'lastpost':
				$orderBy = 'lastTime';
				break;			
				
			case 'starter':
				$orderBy = 'poster';
				break;		
				
			case 'started':
				$orderBy = 'startTime';
				break;			
				
			case 'replies':
				$orderBy = 'replies';
				break;			
				
			case 'views':
				$orderBy = 'views';
				break;
		}

		if(isset($_POST['sort']) && $_POST['sort'] == 'ASC')
		{
			$orderHow = 'ASC';
		}
		else
		{
			$orderHow = 'DESC';
		}
		
		set_title(array(_FORUM_TITLE, $name['title']));		
		menu();
		get_forums($tid);
		
		/*
		$core->tpl->open();
		echo stremyanka($name['id'], 'linked');
		$core->tpl->close();
		*/		
		
		if($name['type'] == 'f')
		{
			if($name['rules'])
			{
					$cookie = isset($_COOKIE['Block_rules']) ? true : false;
					$core->tpl->open('forum.rules');
					$core->tpl->loadFile('board/forum.rules');
					$core->tpl->setVar('TITLE', ($name['rulestitle'] ? $name['rulestitle'] : 'Правила:'));
					$core->tpl->setVar('RULES', $name['rules']);
					$core->tpl->setVar('COOKIE_IMG',  ($cookie ? 'close' : 'open'));
					$core->tpl->setVar('COOKIE_DIS',  ($cookie ? 'none' : 'block'));		
					$core->tpl->end();
					$core->tpl->close();	
			}	
			$num = $board_conf['threads_num'];
			$page = init_page();
			$cut = ($page-1)*$num;
			list($all) = $db->fetchRow($db->query("SELECT COUNT(id) FROM " . DB_PREFIX . "_board_threads WHERE forum='" . $tid . "'"));	
			
			$result = $db->query("SELECT t.*, u.nick, (SELECT count(id) FROM `" . DB_PREFIX . "_board_posts` WHERE tid = t.id AND uid = '" . $core->auth->user_id . "') as isUserPost FROM `" . DB_PREFIX . "_board_threads` as t LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on(t.poster = u.id) WHERE `forum` = '" . $tid . "' ORDER BY " . $orderBy . " " . $orderHow . " LIMIT " . $cut . "," . $num . "");
			if($db->numRows($result) > 0) 
			{
				while($row = $db->getRow($result))
				{
					$topics[$row['important']][$row['id']] = $row;
				}
				
				if(isset($topics[1]))
				{
					$warn = 0;
					$topic_important = '';
					foreach($topics[1] as $id => $row)
					{
						$warn++;
						$topic_important = $topic_important.'<tr>
					       <td style="text-align:center" ><img alt="" src="media/board/icons/yellow.png" border="0"/></td>
					       <td style="text-align:center" >' . ($row['icon'] ? '<img alt="" src="media/board/theme_icon/' . $row['icon'] . '" border="0" />' : '') . '</td>
					       <td><b><a href="board/topic-' . $row['id'] . '" title="' . _FORUM_IN_SECTION . ' ' . $row['title'] . '">' .$row['title'] . '</a></b></td>
					        <td style="text-align:center" >' . $row['replies'] . '</td>
					        <td style="text-align:center" ><a href="profile/' . $row['nick'] . '" title="' . _FORUM_AUTHOR_THEME . ' ' . $row['nick'] . '">' . $row['nick'] . '</a></td>
					        <td style="text-align:center" >' . $row['views'] . '</td>
					        <td  nowrap="nowrap">' . formatDate($row['lastTime']) . ' <br />
					        <b>' . _FORUM_LAST . ':</b> <a href="profile/' . $row['lastPoster'] . '" title="' . _FORUM_LAST_OTVETEVSHY . '">' . $row['lastPoster'] . '</a></td>';
						if(permission($tid, 'Moder')) $topic_important = $topic_important.'<td style="text-align:center"><input type="checkbox" name="checks[]" value="' . $id . '" /></td>';
						$topic_important = $topic_important.'</tr>';
					}
					
				}				
				$i = 0;
				if(isset($topics[0]))
				{
					$topic_last = '';
					foreach($topics[0] as $id => $row)
					{
						$i++;
						if($row['closed'] == 1)
						{
							$icon = 'closed.png';
						}
						elseif($row['replies'] > 20 && $row['views'] > 400)
						{
							if($row['lastTime'] > time()-86400)
							{
								$icon = 'pop_new.png';
							}
							else
							{
								$icon = 'pop.png';
							}
						}
						elseif(($row['lastTime'] > time()-86400) && $row['replies'] < 20 && $row['views'] < 400)
						{
							$icon = 'mess_new.png';
						} 
						elseif($row['isUserPost'] > 0)
						{
							$icon = 'your_mess.png';
						}
						else
						{
							$icon = 'no_mess.png';
						}
						
						$topic_last = $topic_last.'<tr>
					       <td style="text-align:center" ><img alt="" src="media/board/icons/yellow.png" border="0"/></td>
					       <td style="text-align:center" >' . ($row['icon'] ? '<img alt="" src="media/board/theme_icon/' . $row['icon'] . '" border="0" />' : '') . '</td>
					       <td><b><a href="board/topic-' . $row['id'] . '" title="' . _FORUM_IN_SECTION . ' ' . $row['title'] . '">' .$row['title'] . '</a></b></td>
					        <td style="text-align:center" >' . $row['replies'] . '</td>
					        <td style="text-align:center" ><a href="profile/' . $row['nick'] . '" title="' . _FORUM_AUTHOR_THEME . ' ' . $row['nick'] . '">' . $row['nick'] . '</a></td>
					        <td style="text-align:center" >' . $row['views'] . '</td>
					        <td  nowrap="nowrap">' . formatDate($row['lastTime']) . ' <br />
					        <b>' . _FORUM_LAST . ':</b> <a href="profile/' . $row['lastPoster'] . '" title="' . _FORUM_LAST_OTVETEVSHY . '">' . $row['lastPoster'] . '</a></td>';
						if(permission($tid, 'Moder')) $topic_last = $topic_last.'<td style="text-align:center"><input type="checkbox" name="checks[]" value="' . $id . '" /></td>';
						$topic_last = $topic_last.'</tr>';
					}
				}
			}
			$core->tpl->open('topic.list');
			$core->tpl->loadFile('board/topic.list');
			$core->tpl->setVar('TITLE', $name['title']);
			$core->tpl->setVar('NEW_TOPIC', ((permission($tid, 'Reply') && $name['open'] == 0) ? '<a href="board/newTopic/' . $tid . '" >Новая тема</a>': ''));	
			$array_replace["#\\[moder\\](.*?)\\[/moder\\]#is"] = ((permission($tid, 'Moder')) ? '\\1' : '');	
			$array_replace["#\\[nomoder\\](.*?)\\[/nomoder\\]#is"] = ((!permission($tid, 'Moder')) ? '\\1' : '');			
			$array_replace["#\\[important\\](.*?)\\[/important\\]#is"] = ((isset($topics[1])) ? '\\1' : '');							
			$array_replace["#\\[last\\](.*?)\\[/last\\]#is"] = ((isset($topics[0])) ? '\\1' : '');	
			$array_replace["#\\[admin\\](.*?)\\[/admin\\]#is"] = (($core->auth->isAdmin) ? '\\1' : '');	
			$array_replace["#\\[search\\](.*?)\\[/search\\]#is"] = (!isset($_POST['query']) ? '\\1' : '');	
			$array_replace["#\\[noempty\\](.*?)\\[/noempty\\]#is"] = ((isset($topics[0]) && count($topics[0]) == $i OR isset($topics[1]) && count($topics[1]) == $warn) ? '\\1' : '');	
			$array_replace["#\\[empty\\](.*?)\\[/empty\\]#is"] = ((!(isset($topics[0]) && count($topics[0]) == $i OR isset($topics[1]) && count($topics[1]) == $warn)) ? '\\1' : '');
			$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);		
			$core->tpl->setVar('TOPIC_IMPORTANT', $topic_important);	
			$core->tpl->setVar('TOPIC_LAST', $topic_last);	
			$core->tpl->setVar('QUERY', '');	
			$core->tpl->setVar('MASSAGE', 'Тем не найдено!');	
			$core->tpl->setVar('T_ID', $tid);	
			$core->tpl->setVar('ID', $id);				
			$core->tpl->end();
			$core->tpl->close();	
		}
		if($name['type'] == 'f') $core->tpl->pages($page, $num, $all, 'board/forum-' . $tid.'/{page}');	
	
		
					$cookie = isset($_COOKIE['Block_sort']) ? true : false;
					$core->tpl->open('forum.short');
					$core->tpl->loadFile('board/forum.short');					
					$core->tpl->setVar('COOKIE_IMG',  ($cookie ? 'close' : 'open'));
					$core->tpl->setVar('COOKIE_DIS',  ($cookie ? 'none' : 'block'));			
					$short = '
						<form method="post" name="forumSearch" action="board/forum-' . $tid . '">
							<select name="order">
								<option value="subject" ' . (isset($_POST['order']) && $_POST['order'] == 'subject' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_THEME.'</option>
								<option value="lastpost" ' . ((!isset($_POST['order']) OR $_POST['order'] == 'lastpost') ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_LAST_POST.'</option>
								<option value="starter" ' . (isset($_POST['order']) && $_POST['order'] == 'starter' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_AUTHOR.'</option>
								<option value="started" ' . (isset($_POST['order']) && $_POST['order'] == 'started' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_CREATE.'</option>
								<option value="replies" ' . (isset($_POST['order']) && $_POST['order'] == 'replies' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_ANSWERS.'</option>
								<option value="views" ' . (isset($_POST['order']) && $_POST['order'] == 'views' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_VIEWS.'</option>
							</select>
							<select name="sort">
								<option value="ASC" ' . (isset($_POST['sort']) && $_POST['sort'] == 'ASC' ? 'selected="selected"' : '') . '>'._FORUM_VOZRASTANIE.'</option>
								<option value="DESC" ' . ((!isset($_POST['sort']) OR $_POST['sort'] == 'DESC') ? 'selected="selected"' : '') . '>'._FORUM_UBYVANIE.'</option>
							</select>
							<input type="submit" value="'._FORUM_SORT.'" />
						</form>';
					$core->tpl->setVar('SHORT',  $short);	
					$core->tpl->end();
					$core->tpl->close();
					
					$cookie = isset($_COOKIE['Block_help']) ? true : false;
					$core->tpl->open('forum.help');
					$core->tpl->loadFile('board/forum.help');					
					$core->tpl->setVar('COOKIE_IMG',  ($cookie ? 'close' : 'open'));
					$core->tpl->setVar('COOKIE_DIS',  ($cookie ? 'none' : 'block'));					
					$permis =   _FORUM_YOU.' <strong>' . (permission($tid, 'View') ? _FORUM_ALLOWED : _FORUM_BANNED) . '</strong> ' . _FORUM_VIEW_THIS . '<br />
								'._FORUM_YOU.' <strong>' . (permission($tid, 'Read') ? _FORUM_ALLOWED : _FORUM_BANNED) . '</strong> ' . _FORUM_READING_ORDER_OF_THE . '<br />
								'._FORUM_YOU.' <strong>' . (permission($tid, 'Create') ? _FORUM_ALLOWED : _FORUM_BANNED) . '</strong> ' . _FORUM_CREATE_TOPICS_IN_THIS . '<br />
								'._FORUM_YOU.' <strong>' . (permission($tid, 'Reply') ? _FORUM_ALLOWED : _FORUM_BANNED) . '</strong> ' . _FORUM_RESPOND_TO_THE_THEME_OF_THIS . '<br />
								'._FORUM_YOU.' <strong>' . (permission($tid, 'Edit') ? _FORUM_ALLOWED : _FORUM_BANNED) . '</strong> ' . _FORUM_EDITING_THEIR_POSTS . '<br />
								' . (permission($tid, 'Moder') ? _FORUM_YOU_MODERATOR : _FORUM_YOU_ARE_NOT_MODERATOR);			
					$core->tpl->setVar('PERMISSION',  $permis);	
					$core->tpl->end();
					$core->tpl->close();	
	}
	else
	{
		location('/board');
	}
}

//продолжить

function showTopic($id, $toLast = false)
{
global $db, $core, $board_conf, $url, $config;
	$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_threads` WHERE `id` = '" . $id . "'");
	$name = $db->getRow($query);	
	if($name)
	{
		$num = $board_conf['posts_num'];		
		list($all) = $db->fetchRow($db->query("SELECT COUNT(*) FROM " . DB_PREFIX . "_board_posts WHERE tid='" . $id . "'"));
		if(isset($url[2]) && $url[2] == 'getlastpost')
		{
			$page = ceil($all/$num);
			if($page > 1)
			{
				location('/board/topic-' . $id . '/page/' . $page .'#lastPost');
			}
			else
			{
				location('/board/topic-' . $id . '#lastPost');
			}
		}
		elseif(isset($url[2]) && $url[2] == 'errorPost')
		{
			$page = ceil($all/$num);
			if($page > 1)
			{
				location('/board/topic-' . $id . '/page/' . $page .'&err#lastPost');
			}
			else
			{
				location('/board/topic-' . $id . '&err#lastPost');
			}
			
			$showErr = true;
		}
		else
		{
			$page = init_page();
		}
		
		$cut = ($page-1)*$num;		
		
		if($cut == 0)
		{
			$link = 'board/topic-' . $id;
		}
		else
		{
			$link = 'board/topic-' . $id . '/page/' . $page;
		}
		menu();		
		//$core->tpl->open();
		//echo stremyanka($name['forum'], 'linked') . '&nbsp;&gt;&nbsp;' . $name['title'];
		//$core->tpl->close();		
		
		$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET views = views+1 WHERE `id` = '" . $id . "' LIMIT 1 ;");
		set_title(array(_FORUM_TITLE, $name['title']));

		$core->tpl->pages($page, $num, $all, 'board/topic-' . $id . '/{page}');				
			$core->tpl->open('showTopic');
				
		$result = $db->query("SELECT p.*, u.id as uidd, u.place, u.signature, u.points, u.carma, g.name as gname, g.icon as gicon, g.color as gcolor, fu.thanks, fu.messages, fu.specStatus FROM `" . DB_PREFIX . "_board_posts` as p LEFT JOIN `" . DB_PREFIX . "_board_users` as fu ON(p.uid = fu.uid) LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u ON(p.uid = u.id) RIGHT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_groups` as g ON(u.group = g.id) WHERE p.`tid` = '" . $id . "' ORDER BY p.`time` LIMIT " . $cut . "," . $num . "");		
		$i = 0;	
			$topic_v ='';
		if(permission($name['forum'], 'Read'))
		{			
			$allowEdit = permission($name['forum'], 'Edit');
			$carmaUsers = array();
			while($row = $db->getRow($result))
			{
				if(!in_array($row['username'], $carmaUsers))
				{
					boardCarmaInit($row['uidd'], $row['username']);
				}
				
				$carmaUsers[] = $row['username'];
				$i++;
				$topic_v= $topic_v.'<tr  id="entry' . $row['id'] . '">
				    <th style="padding:7px 7px 7px 7px;" width="20%">';
					if(($i+$cut) == 1)	{
						$topic_v = $topic_v.($name['icon'] ? '<img alt="" src="media/board/theme_icon/' . $name['icon'] . '" border="0" class="icon" />' : '');
					}
					else
					{
						$topic_v = $topic_v.'<img alt="" src="media/board/user.png" class="icon" border="0" />';
					}
					$topic_v = $topic_v.'<a href="profile/' . $row['username'] . '" title="'._PROFILE.': ' . $row['username'] . '" >' . $row['username'] . '</a></th>
				    <th style="padding:7px 7px 7px 7px;">
						<div style="float:left;"><img alt="" src="media/other/time.png" border="0" width="16" class="icon" />' . formatDate($row['time']) . '</div>';
					$topic_v = $topic_v.'<div style="float:right;">'._FORUM_MESSAGE.' <a href="' . $link . '#entry' . $row['id'] . '" title="'._FORUM_MOVE_TO_MESSAGE.'">№' . ($i+$cut) . '</a></div>';
					if(($i+$cut) == 1)
					{
						//echo '<div style="float:right; padding-right:10px;">' . draw_rating($id, 'board', $name['score'], $name['votes']) . '</div>';
					}
					
					$topic_v = $topic_v.'</th>
				  </tr>
				  <tr class="thNOanim">
				    <td class="thNOanim" valign="top">
						<img alt="" src="' . avatar($row['uid']) . '" border="0" hspace="3" /><br />						
						<sup>'._FORUM_GROUP.': <font color="' . $row['gcolor'] . '">' . $row['gname'] . '</font><br />
						'._FORUM_USER_N.': ' . $row['uidd'] . '<br />
						'._FORUM_MESSAGES.': ' . $row['messages'] . '<br />
						'._FORUM_REPUTATION.': <a href="javascript:void(0)" onclick="carmaHistory(\'' . $row['uidd'] . '\')"><span id="pcarma' . $row['uidd'] . '">' . ($row['carma'] > 0 ? '+' . $row['carma'] : $row['carma']) . '</span></a><br />
						' . ($row['place'] ? _FORUM_SFROM.': ' . $row['place'] . '<br />' : '') . '						
						<a href="javascript:void(0)" onclick="javascript:insertCode(\'b\', \'\', \'' . $row['username'] . '\'); gid(\'qr\').style.display = \'block\';">'._FORUM_INSERT_NICK.'</a>
						</sup>
					</td>
					
				    <td class="thNOanim" valign="top">
						<div class="forumMessage" id="fastEdit-' . $row['id'] . '">' . attachForum($core->bbDecode($row['message']), $row['files']) . '</div>
						' . ($row['signature'] ? '<br /><br />-------------------- <br /><noindex>' . $core->bbDecode($row['signature']).'</noindex>' : '') . '
					</td>
				  </tr>
				  <tr class="thNOanim" style="padding:5px;">
				    <td class="thNOanim" style="padding:5px;"><a href="javascript:void(0)" title="'._FORUM_KARMA_PLUS.'" onclick="javascript:modal_box(\'carma' . $row['uidd'] . '\')" style="color:white"><img alt="" src="media/edit/plus.png" border="0" class="icon" /></a> <a href="javascript:void(0)" title="'._FORUM_KARMA_MINUS.'" onclick="javascript:modal_box(\'carma' . $row['uidd'] . '\')" style="color:white"><img alt="" src="media/edit/minus.png" border="0" class="icon" /></a></td>
					
				    <td class="thNOanim" style="padding:5px;">
					
					<div style="float:left"><a href="javascript:void(0)" onclick="javascript:QuickQuote(\'s\', \'qickMessage\'); gid(\'qr\').style.display = \'block\';" >Цитировать</a></div>';
					if(($allowEdit && $row['uid'] == $core->auth->user_id) OR $core->auth->isAdmin)
					{
						$topic_v = $topic_v.'<div style="float:right">';
						if(($i+$cut) != 1)	{
							$topic_v = $topic_v.'<a href="javascript:void(0)" onclick="javascript:forumPostDelete(\'' . $row['id'] . '\', \'fastEdit-' . $row['id'] . '\')" title="'._DELETE.'"><img alt="" src="media/board/icons/del.png" border="0" class="icon" /></a>';
						}
						$topic_v = $topic_v.'<a href="javascript:void(0)" onclick="javascript:forumPostEdit(\'' . $row['id'] . '\', \'fastEdit-' . $row['id'] . '\')" title="'._FORUM_FAST_EDIT.'"><img alt="" src="media/board/icons/edit_add.png" border="0" class="icon" /></a>';
						$topic_v = $topic_v.'<a href="board/editPost/' . $row['id'] . '/' . md5($core->auth->user_info['tail'].'-'.date('d')) . '/' . $page . '" title="'._FORUM_FULL_EDIT.'"><img alt="" src="media/board/icons/fullEdit.png" border="0" class="icon" /></a></div>';
					}
					$topic_v = $topic_v.'</td>
				  </tr>		  
				 ';
			}
			if($i == $db->numRows($result))
			{
				$topic_v = $topic_v.'
				<tr class="thNOanim">
				    <td class="thNOanim" colspan="2">
					<div style="float:left">
						<form name="search_form" method="post" action="board/search">
							<input type="hidden" name="fid" value="' . $name['forum'] . '"/>
							<input type="text" name="query" value="" />
							<input type="submit" value="'._FORUM_SEARCH_BY_FORUMS.'" />
						</form>
					</div>
					
					<div style="float:right">
						<a href="board/topic-' . $name['id'] . '/old">'._FORUM_PRED.'</a> | <a href="board/topic-' . $name['id'] . '"><strong>' . $name['title'] . '</strong></a> | <a href="board/topic-' . $name['id'] . '/new">'._FORUM_SLED.'</a>
					</div>
					</td>
				</tr>		
				';
			}
		}
		else
		{
			$topic_v = $topic_v.'<tr><td class="row4" align="center">'._FORUM_GROUP_USERS.' <strong>' . $core->auth->user_info['gname'] . '</strong> '._FORUM_READ_DENIED.'</td></tr>';
		}
		
		
		
			if ((permission($name['forum'], 'Moder'))&&(permission($name['forum'], 'Reply'))) 
			{
					$topic_v = $topic_v.'<tr class="thNOanim">
							<td class="thNOanim" colspan="2">';
			}
			if($name['closed'] == 0)
		{
			if(permission($name['forum'], 'Moder'))
			{
				$topic_v = $topic_v.'<div style="float:left"><form id="tablesForm" method="post" action="board/do">
						<input type="hidden" name="ttid" value="' . $name['id'] . '"/>
						<input type="hidden" name="fid" value="' . $id . '"/>
						<select name="deiv">
							<option value="important">'._FORUM_IMPORTANT.'</option>
							<option value="noimportant">'._FORUM_USUAL.'</option>
							<option value="close">'._FORUM_CLOSE.'</option>
							<option value="open">'._FORUM_OPEN.'</option>
							<option value="delete">'._FORUM_DELETE.'</option>
						</select>
						<input type="submit" value="'._FORUM_SUBMIT_GOGO.'" />
					</form></div>';
			}			
			if(permission($name['forum'], 'Reply'))
			{
				$topic_v = $topic_v.'<div align="right" style="float:right"><a href="#" onclick="showhide(\'qr\'); return false;" ><input type="submit" value="Написать ответ" /></div>';
			}
			
		}
else
		{
			$topic_v = $topic_v.'<div align="right" style="float:right"><img alt="" src="media/board/buttons/t_closed.gif" border="0" /></div>';
		}
		if ((permission($name['forum'], 'Moder'))&&(permission($name['forum'], 'Reply'))) 
			{
				$topic_v = $topic_v.'</td>
				    </tr>';
			}

	
			if($name['closed'] == 0)
		{		
					if($core->auth->isUser)
				{
					if(isset($_GET['err']) OR isset($showErr))
					{
						$showErr = true;
						$core->tpl->info(_FORUM_MESSAGE_TEXT_NULL, 'warning');
					}
					$uniqCode = gencode(10);					
				}		
				
		}	
			$ta =bb_area('qickMessage', '', 5, 'textarea', false, true);
			$core->tpl->open('topic.view');
			$core->tpl->loadFile('board/topic.view');
			$core->tpl->setVar('TITLE', $name['title']);
			$core->tpl->setVar('ACTION', ((permission($name['forum'], 'Edit') && $name['poster'] == $core->auth->user_id OR $core->auth->isAdmin) ? ' [ <a href="board/user/editThread/' . $name['id'] . '/' . md5($core->auth->user_info['tail'].'-'.date('d')) . '" >'._EDIT.'</a> - ' . ($name['closed'] == 1 ? '<a href="board/user/open/' . $name['id'] . '/' . md5($core->auth->user_info['tail'].'-'.date('d')) . '">'._FORUM_OPEN_THEME.'</a>' : '<a href="board/user/close/' . $name['id'] . '/' . md5($core->auth->user_info['tail'].'-'.date('d')) . '">'._FORUM_CLOSE_THEMEADMIN.'</a>') . ' - <a href="board/user/delete/' . $name['id'] . '/' . md5($core->auth->user_info['tail'].'-'.date('d')) . '">'._FORUM_DELETE_THEME.'</a> ]' : ''));
			
			$core->tpl->setVar('TOPIC', $topic_v);	
			$core->tpl->setVar('SHOW_EDIT', (!isset($showErr) ? 'display:none' : ''));	
			$core->tpl->setVar('UPLOAD', (($board_conf['loadFiles'] == 1) ? '<div align="left" style="padding-top:10px;"><a href="javascript:void(0)" onclick="showhide(\'fileUpload\');">'._FILE_EDITOR.'</a><br /><div id="fileUpload" style="display:none;">' . forumUpload($uniqCode) . '</div></div>' : ''));	
			$array_replace["#\\[close\\](.*?)\\[/close\\]#is"] = (($name['closed'] == 0) ? '\\1' : '');				
			$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);		
			$core->tpl->setVar('TEXTAREA', $ta);			
			$core->tpl->setVar('ID', $id);				
			$core->tpl->end();
			$core->tpl->close();				
					
		
		
		
		$core->tpl->close();

		$core->tpl->pages($page, $num, $all, 'board/topic-' . $id . '/{page}');
		
		$core->tpl->open('Do');
		//echo '<div align="right"><select name="url" id="ulrGo">' . stremyanka($name['forum'], 'list') . '</select> <input type="button" value="'._FORUM_MOVE_TO_SECTION.'" onclick="window.location = \'/\' + gid(\'ulrGo\').value;" /></div>';
		$core->tpl->close();
	}
	else
	{
		location('/board');
	}
}

function boardCarmaInit($uid, $uname)
{
global $core;
	require_once(ROOT . 'usr/plugins/modal_box/init.php');
	if($core->auth->isUser == false)
	{
		$content = '<div class="mbmest">'._FORUM_CHANGE_KARMA_REGISTERED_ONLY.'</div>';
	}
	elseif($uid == $core->auth->user_info['id'])
	{
		$content = '<div class="mbmest">'._FORUM_KARMA_YOURSELF_DENIED.'</div>';
	}
	elseif(isset($_COOKIE['carma-' . $uid]))
	{
		$content = '<div class="mbmest">'._FORUM_KARMA_USER_DONT_AGAIN.'</div>';
	}
	else
	{
		$content = '<table border="0" cellspacing="3" cellpadding="3" style="width:100%;"><tr><td style="width:30%;" valign="top">'._FORUM_ACTION.':</td><td><select id="carmaDo"><option value="p">'._FORUM_REPUTATION_PLUS.'</option><option value="m">'._FORUM_REPUTATION_MINUS.'</option><option value="n">'._FORUM_NEUTRAL.'</option></select></td></tr><tr><td valign="top">'._FORUM_MESSAGE_TO_USER.': </td><td><textarea name="textarea" style="width:80%;" rows="4" id="carmaText"></textarea><br /><sup>'._FORUM_MAX_SYMBOLS.'</sup></td></tr><tr><td>&nbsp;</td><td><input type="button" value="'._SEND.'" class="inputsubmit" onclick="addCarma(\'' . $uid . '\', \'carma' . $uid . '\')" /></td></tr></table>';
	}
	
	modal_box(_FORUM_USER_KARMA_CHANGING, _FORUM_KARMA_SELECT_ACTION.' "<b>'.$uname.'</b>"', $content, 'carma'.$uid);
	$core->tpl->headerIncludes['carmaHistoryBoard'] = '<script>function carmaHistory(uid) { modal_box(\'carmaHistory\'+uid); AJAXEngine.showedLoadBar = \'\'; AJAXEngine.sendRequest(\'ajax.php?do=carmaHistory&uid=\'+uid, \'carmaHistory\'+uid); }</script>';
	modal_box(_FORUM_USER_KARMA_HISTORY, _FORUM_KARMA_HISTORY.'"<b>'.$uname.'</b>"', '<div id="carmaHistory' . $uid . '"><div class="mbmest">'._FORUM_KARMA_LOADING.'</div></div>', 'carmaHistory'.$uid);
}
//поиск
function searchForum()
{
global $db, $core, $board_conf, $url;
	$orderType = isset($_POST['order']) ? filter($_POST['order'], 'a') : '';
	$fidSearch = isset($_POST['fid']) ? intval($_POST['fid']) : '';
	$query = isset($_POST['query']) ? filter($_POST['query'], 'a') : '';
	
	if($fidSearch)
	{
		$where = "WHERE title LIKE '%" . $db->safesql($query) . "%' AND forum = '" . $fidSearch . "'";
	}
	else
	{
		$where = "WHERE title LIKE '%" . $db->safesql($query) . "%'";
	}
	
	switch($orderType)
	{
		default:
			$orderBy = 'lastTime';
			break;			
			
		case 'subject':
			$orderBy = 'title';
			break;			
			
		case 'lastpost':
			$orderBy = 'lastTime';
			break;			
			
		case 'starter':
			$orderBy = 'poster';
			break;		
			
		case 'started':
			$orderBy = 'startTime';
			break;			
			
		case 'replies':
			$orderBy = 'replies';
			break;			
			
		case 'views':
			$orderBy = 'views';
			break;
	}

	if(isset($_POST['sort']) && $_POST['sort'] == 'ASC')
	{
		$orderHow = 'ASC';
	}
	else
	{
		$orderHow = 'DESC';
	}
	
	set_title(array(_FORUM_TITLE, $query));
	
	menu();
	
	//$core->tpl->open();
	//	echo '<a href="board">'._FORUM_TITLE.'</a> > '._SEARCH;
	//$core->tpl->close();
	
	$num = $board_conf['threads_num'];
	$page = init_page();
	$cut = ($page-1)*$num;		
	list($all) = $db->fetchRow($db->query("SELECT COUNT(id) FROM " . DB_PREFIX . "_board_threads " . $where . ""));

		
		$result = $db->query("SELECT t.*, u.nick FROM `" . DB_PREFIX . "_board_threads` as t LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on(t.poster = u.id) $where ORDER BY " . $orderBy . " " . $orderHow . " LIMIT " . $cut . "," . $num . "");
		if($db->numRows($result) > 0) 
			{
				while($row = $db->getRow($result))
				{
					$topics[$row['important']][$row['id']] = $row;
				}
				
				if(isset($topics[1]))
				{
					$warn = 0;
					$topic_important = '';
					foreach($topics[1] as $id => $row)
					{
						$warn++;
						$topic_important = $topic_important.'<tr>
					       <td style="text-align:center" ><img alt="" src="media/board/icons/yellow.png" border="0"/></td>
					       <td style="text-align:center" >' . ($row['icon'] ? '<img alt="" src="media/board/theme_icon/' . $row['icon'] . '" border="0" />' : '') . '</td>
					       <td><b><a href="board/topic-' . $row['id'] . '" title="' . _FORUM_IN_SECTION . ' ' . $row['title'] . '">' .$row['title'] . '</a></b></td>
					        <td style="text-align:center" >' . $row['replies'] . '</td>
					        <td style="text-align:center" ><a href="profile/' . $row['nick'] . '" title="' . _FORUM_AUTHOR_THEME . ' ' . $row['nick'] . '">' . $row['nick'] . '</a></td>
					        <td style="text-align:center" >' . $row['views'] . '</td>
					        <td  nowrap="nowrap">' . formatDate($row['lastTime']) . ' <br />
					        <b>' . _FORUM_LAST . ':</b> <a href="profile/' . $row['lastPoster'] . '" title="' . _FORUM_LAST_OTVETEVSHY . '">' . $row['lastPoster'] . '</a></td>';
						if(permission($tid, 'Moder')) $topic_important = $topic_important.'<td style="text-align:center"><input type="checkbox" name="checks[]" value="' . $id . '" /></td>';
						$topic_important = $topic_important.'</tr>';
					}
					
				}				
				$i = 0;
				if(isset($topics[0]))
				{
					$topic_last = '';
					foreach($topics[0] as $id => $row)
					{
						$i++;
						if($row['closed'] == 1)
						{
							$icon = 'closed.png';
						}
						elseif($row['replies'] > 20 && $row['views'] > 400)
						{
							if($row['lastTime'] > time()-86400)
							{
								$icon = 'pop_new.png';
							}
							else
							{
								$icon = 'pop.png';
							}
						}
						elseif(($row['lastTime'] > time()-86400) && $row['replies'] < 20 && $row['views'] < 400)
						{
							$icon = 'mess_new.png';
						} 
						elseif($row['isUserPost'] > 0)
						{
							$icon = 'your_mess.png';
						}
						else
						{
							$icon = 'no_mess.png';
						}
						
						$topic_last = $topic_last.'<tr>
					       <td style="text-align:center" ><img alt="" src="media/board/icons/yellow.png" border="0"/></td>
					       <td style="text-align:center" >' . ($row['icon'] ? '<img alt="" src="media/board/theme_icon/' . $row['icon'] . '" border="0" />' : '') . '</td>
					       <td><b><a href="board/topic-' . $row['id'] . '" title="' . _FORUM_IN_SECTION . ' ' . $row['title'] . '">' .$row['title'] . '</a></b></td>
					        <td style="text-align:center" >' . $row['replies'] . '</td>
					        <td style="text-align:center" ><a href="profile/' . $row['nick'] . '" title="' . _FORUM_AUTHOR_THEME . ' ' . $row['nick'] . '">' . $row['nick'] . '</a></td>
					        <td style="text-align:center" >' . $row['views'] . '</td>
					        <td  nowrap="nowrap">' . formatDate($row['lastTime']) . ' <br />
					        <b>' . _FORUM_LAST . ':</b> <a href="profile/' . $row['lastPoster'] . '" title="' . _FORUM_LAST_OTVETEVSHY . '">' . $row['lastPoster'] . '</a></td>';
						if(permission($tid, 'Moder')) $topic_last = $topic_last.'<td style="text-align:center"><input type="checkbox" name="checks[]" value="' . $id . '" /></td>';
						$topic_last = $topic_last.'</tr>';
					}
				}
			}
		
			$core->tpl->open('topic.list');
			$core->tpl->loadFile('board/topic.list');
			$core->tpl->setVar('TITLE', 'Поиск по форуму');
			$core->tpl->setVar('NEW_TOPIC', ((permission($tid, 'Reply') && $name['open'] == 0) ? '<a href="board/newTopic/' . $tid . '" >Новая тема</a>': ''));	
			$array_replace["#\\[moder\\](.*?)\\[/moder\\]#is"] = ((permission($tid, 'Moder')) ? '\\1' : '');	
			$array_replace["#\\[nomoder\\](.*?)\\[/nomoder\\]#is"] = ((!permission($tid, 'Moder')) ? '\\1' : '');			
			$array_replace["#\\[important\\](.*?)\\[/important\\]#is"] = ((isset($topics[1])) ? '\\1' : '');							
			$array_replace["#\\[last\\](.*?)\\[/last\\]#is"] = ((isset($topics[0])) ? '\\1' : '');	
			$array_replace["#\\[admin\\](.*?)\\[/admin\\]#is"] = (($core->auth->isAdmin) ? '\\1' : '');	
			$array_replace["#\\[search\\](.*?)\\[/search\\]#is"] = (!isset($_POST['query']) ? '\\1' : '');	
			$array_replace["#\\[noempty\\](.*?)\\[/noempty\\]#is"] = ((isset($topics[0]) && count($topics[0]) == $i OR isset($topics[1]) && count($topics[1]) == $warn) ? '\\1' : '');	
			$array_replace["#\\[empty\\](.*?)\\[/empty\\]#is"] = ((!(isset($topics[0]) && count($topics[0]) == $i OR isset($topics[1]) && count($topics[1]) == $warn)) ? '\\1' : '');
			$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);		
			$core->tpl->setVar('TOPIC_IMPORTANT', $topic_important);	
			$core->tpl->setVar('TOPIC_LAST', $topic_last);	
			$core->tpl->setVar('T_ID', $tid);	
			$core->tpl->setVar('ID', $id);	
			$core->tpl->setVar('QUERY', $query);	
			$core->tpl->setVar('MASSAGE', (($query) ? _FORUM_NO_FOUND_TRY_AGAIN : _FORUM_ENTER_TEXT_FOR_SEARCH));				
			$core->tpl->end();
			$core->tpl->close();	
	
		
					$cookie = isset($_COOKIE['Block_sort']) ? true : false;
					$core->tpl->open('forum.short');
					$core->tpl->loadFile('board/forum.short');					
					$core->tpl->setVar('COOKIE_IMG',  ($cookie ? 'close' : 'open'));
					$core->tpl->setVar('COOKIE_DIS',  ($cookie ? 'none' : 'block'));			
					$short = '
						<form method="post" name="forumSearch" action="board/search">
							<select name="order">
								<option value="subject" ' . (isset($_POST['order']) && $_POST['order'] == 'subject' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_THEME.'</option>
								<option value="lastpost" ' . ((!isset($_POST['order']) OR $_POST['order'] == 'lastpost') ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_LAST_POST.'</option>
								<option value="starter" ' . (isset($_POST['order']) && $_POST['order'] == 'starter' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_AUTHOR.'</option>
								<option value="started" ' . (isset($_POST['order']) && $_POST['order'] == 'started' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_CREATE.'</option>
								<option value="replies" ' . (isset($_POST['order']) && $_POST['order'] == 'replies' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_ANSWERS.'</option>
								<option value="views" ' . (isset($_POST['order']) && $_POST['order'] == 'views' ? 'selected="selected"' : '') . '>'._FORUM_SORT_BY_VIEWS.'</option>
							</select>
							<select name="sort">
								<option value="ASC" ' . (isset($_POST['sort']) && $_POST['sort'] == 'ASC' ? 'selected="selected"' : '') . '>'._FORUM_VOZRASTANIE.'</option>
								<option value="DESC" ' . ((!isset($_POST['sort']) OR $_POST['sort'] == 'DESC') ? 'selected="selected"' : '') . '>'._FORUM_UBYVANIE.'</option>
							</select>
							<input type="submit" value="'._FORUM_SORT.'" />
						</form>';
					$core->tpl->setVar('SHORT',  $short);	
					$core->tpl->end();
					$core->tpl->close();		
		
		
}

function forumUpload($uniqCode, $start = 1)
{
global $board_conf;
	$content = '<script type="text/javascript">var i = ' . $start . '; function addUploadInput() { i++; gid(\'addUploadInput\').innerHTML += \'<div style="margin-top:3px;"><input name="files[\' + i + \']" type="file" size="35" /> [file=\'+i+\']</div>\'; }</script>';
	$content .= _FORUM_ALOWS_EXPANSIONS.' <b>' . $board_conf['formats'] . '</b><br>'._FORUM_MAX_SIZE.' <b>' . formatfilesize($board_conf['maxSize']) . '</b><br>'._FORUM_PICTURE_MAX_SIZE.' <b>2 000 x 2 000 px</b>';
	$content .= '<br /><br /><input name="files[' . $start . ']" type="file" size="35" /> [file=' . $start . '] [ <a href="javascript:void(0)" onclick="addUploadInput()">+</a> ]<div id="addUploadInput"></div><sup><br />'._FORUM_UPLOAD_INFO.'</sup>';
	return $content;

}

switch(isset($url[1]) ? $url[1] : null) 
{
	default:
		if(isset($url[1]) && eregStrt('forum-', $url[1]))
		{
			showForum(intval(str_replace('forum-', '', $url[1])));
		}
		elseif(isset($url[1]) && eregStrt('topic-', $url[1]))
		{
			showTopic(intval(str_replace('topic-', '', $url[1])));
		}
		else
		{
			main();
		}
		break;
		
	case 'search':
		searchForum();
		break;
		
	case 'postMessage':
		if($core->auth->isUser)
		{
			$type = filter($_POST['type'], 'a');
			$tid = intval($_POST['tid']);
			
			$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_threads` WHERE `id` = '" . $tid . "'");
			$name = $db->getRow($query);
			
			if(permission($name['forum'], 'Reply'))
			{
				if($name['closed'] == 0)
				{
					switch($type)
					{
						case 'quick':
							$mess = filter(utf_decode($_POST['qickMessage']));
							break;
					}
					
					if($mess != '')
					{
						list($pid, $submessage, $uid, $time, $files) = $db->fetchRow($db->query("SELECT id, message, uid, time, files FROM " . DB_PREFIX . "_board_posts WHERE tid='" . $tid . "' ORDER BY time DESC LIMIT 1"));
						
						if($uid == $core->auth->user_id && $time >= time() - 120 && empty($_FILES['files']['name'][1]))
						{
							$m = $submessage . parseBB(processText("\n\n" . $mess));
							$db->query("UPDATE `" . DB_PREFIX . "_board_posts` SET message='" . $db->safesql($m) . "', `time` = '" . time() . "' WHERE `id` =" . $pid . " LIMIT 1 ;");
							//boardUpload(0, $tid, $m, $pid, unserialize($files));
						}
						else
						{
							$pidTime = time();
							$db->query("INSERT INTO `" . DB_PREFIX . "_board_posts` ( `id` , `tid` , `message` , `uid` , `username` , `ip` , `time` , `visible` , `editUser` , `editReason` ) VALUES (NULL, '" . $tid . "', '" . $db->safesql(parseBB(processText($mess))) . "', '" . $core->auth->user_id . "', '" . $core->auth->user_info['nick'] . "', '" . getenv('REMOTE_ADDR') . "', '" . $pidTime . "', '1', '', '');");
							$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET replies = replies+1, lastPoster = '" . $core->auth->user_info['nick'] . "', lastTime = '" . $pidTime . "' WHERE `id` =" . $tid . " LIMIT 1 ");
							$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET posts = posts+1, lastPost = '" . $pidTime . "', lastPoster = '" . $core->auth->user_info['nick'] . "', lastTid = '" . $tid . "', lastSubject = '" . $name['title'] . "' WHERE `id` =" . $name['forum'] . " LIMIT 1 ");
							$db->query("UPDATE `" . DB_PREFIX . "_board_users` SET messages = messages+1 WHERE `uid` =" . $core->auth->user_id . " LIMIT 1 ");
							boardUpload($pidTime, $tid, $mess);
						}
						
						location('board/topic-' . $tid . '/getlastpost');
					}
					else
					{
						location('board/topic-' . $tid . '/errorPost');
					}
				}
				else
				{
					location();
				}
			}
			else
			{
				location();
			}
		}
		else
		{
			location();
		}
		break;
		
	case 'newTopic':
		$fid = intval($url[2]);
		
		if(permission($fid, 'Reply'))
		{
			menu();
			$uniqCode = gencode(10);
			//$core->tpl->open();
			//echo stremyanka($fid, 'linked') . '&nbsp;&gt;&nbsp;'._FORUM_ADD_THEME;
			//$core->tpl->close();
			$textArea = bb_area('topicMessage', '', 5, 'textarea', false, true);
			$icon = '';
			foreach(glob(ROOT . 'media/board/theme_icon/*.gif') as $file)
					{
						$icon = $icon.'<label><input type="radio" name="icon" value="' . basename($file) . '" /> <img alt="" src="media/board/theme_icon/' . basename($file) . '" border="0" class="icon _pointer" /></label>';
					}		
		
			$forum_upload = forumUpload($uniqCode);
			$core->tpl->open('topic.add');
			$core->tpl->loadFile('board/topic.add');		
			$core->tpl->setVar('ICON', $icon);
			$core->tpl->setVar('TEXTAREA', $textArea);
			$core->tpl->setVar('FORUM_UPLOAD',  $forum_upload);
			$core->tpl->setVar('ID',  $fid);
			$core->tpl->setVar('UNIQCODE',  $uniqCode);			
			$array_replace["#\\[upload\\](.*?)\\[/upload\\]#is"] = (($board_conf['loadFiles'] == 1) ? '\\1' : '');	
			$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);			
			$core->tpl->end();
			$core->tpl->close();			
			

		}
		else
		{
			location('/board');
		}
		break;
		
	case 'saveTopic':
		$fid = intval($_POST['forum']);
		$title = filter($_POST['title']);
		$message = filter(utf_decode($_POST['topicMessage']));
		$icon = filter($_POST['icon'], 'a');

		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_forums` WHERE `id` = '" . $fid . "'");
		$name = $db->getRow($query);
		
		if($name && $name['open'] == 0)
		{
			if(permission($fid, 'Create'))
			{
				if($title && $message)
				{
					$time = time();
					$db->query("INSERT INTO `" . DB_PREFIX . "_board_threads` ( `id` , `forum` , `title` , `poster` , `startTime` , `lastTime` , `lastPoster` , `views` , `replies` , `important` , `closed` , `score` , `votes` , `icon` , `closetime` ) VALUES ('', '" . $fid . "', '" . $db->safesql(processText($title)) . "', '" . $core->auth->user_id . "', '" . $time . "', '" . $time . "', '" . $core->auth->user_info['nick'] . "', '0', '0', '0', '0', '', '', '" . $icon . "', '');");
					list($tid) = $db->fetchRow($db->query("SELECT id FROM " . DB_PREFIX . "_board_threads WHERE title='" . $db->safesql(processText($title)) . "' AND startTime='" . $time . "'"));
					
					$timepid = time();
					
					$db->query("INSERT INTO `" . DB_PREFIX . "_board_posts` ( `id` , `tid` , `message` , `uid` , `username` , `ip` , `time` , `visible` , `editUser` , `editReason` ) VALUES (NULL, '" . $tid . "', '" . $db->safesql(parseBB(processText($message))) . "', '" . $core->auth->user_id . "', '" . $core->auth->user_info['nick'] . "', '" . getenv('REMOTE_ADDR') . "', '" . $timepid . "', '1', '', '');");
					$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET threads = threads+1, posts = posts+1, lastPost = '" . time() . "', lastPoster = '" . $core->auth->user_info['nick'] . "', lastTid = '" . $tid . "', lastSubject = '" . $title . "' WHERE `id` =" . $fid . " LIMIT 1 ");
					$db->query("UPDATE `" . DB_PREFIX . "_board_users` SET messages = messages+1 WHERE `uid` =" . $core->auth->user_id . " LIMIT 1 ");					
					boardUpload($timepid, $tid, $message);
					location('/board/topic-' . $tid);
				}
				else
				{
					$core->tpl->info(_FORUM_TEXT_FIELD_NULL.' <a href="javascript:void(0)" onclick="javascript:history.go(-1);">'._BACK.'</a>');
				}
			}
		}
		break;
		
	case 'do':
		prt($_POST);
		$type = filter($_POST['deiv'], 'a');
		$fid = intval($_POST['fid']);
		$ttid = isset($url[2]) ? intval($url[2]) : false;
		
		if(!$ttid && isset($_POST['ttid']))
		{
			$ttid = intval($_POST['ttid']);
		}			
		
		switch($type)
		{
			case 'important':
				if($ttid)
				{
					$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `important` = '1' WHERE `id` =" . $ttid . " LIMIT 1 ;");
				}
				else
				{
					foreach($_POST['checks'] as $tid)
					{
						$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `important` = '1' WHERE `id` =" . $tid . " LIMIT 1 ;");
					}
				}
				break;
				
			case 'noimportant':
				if($ttid)
				{
					$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `important` = '0' WHERE `id` =" . $ttid . " LIMIT 1 ;");
				}
				else
				{
					foreach($_POST['checks'] as $tid)
					{
						$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `important` = '0' WHERE `id` =" . $tid . " LIMIT 1 ;");
					}
				}
				break;
				
			case 'close':
				if($ttid)
				{
					$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `closed` = '1' WHERE `id` =" . $ttid . " LIMIT 1 ;");
				}
				else
				{
					foreach($_POST['checks'] as $tid)
					{
						$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `closed` = '1' WHERE `id` =" . $tid . " LIMIT 1 ;");
					}
				}
				break;			
			
			case 'open':
				if($ttid)
				{
					$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `closed` = '0' WHERE `id` =" . $ttid . " LIMIT 1 ;");
				}
				else
				{
					foreach($_POST['checks'] as $tid)
					{
						$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `closed` = '0' WHERE `id` =" . $tid . " LIMIT 1 ;");
					}
				}
				break;			
			
			case 'delete':
				if($ttid)
				{
					deleteTopic($ttid, $fid);
				}
				else
				{
					foreach($_POST['checks'] as $tid)
					{
						deleteTopic($tid, $fid);
					}
				}
				break;			
			
		}
		
		if($ttid)
		{
			location('/board/topic-' . $ttid . '/getlastpost');
		}
		else
		{
			location('/board/forum-' . $fid);
		}
		break;
		
	case 'admin':
		$fid = intval($_POST['tid']);
		$type = filter($_POST['deiv']);
		
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_forums` WHERE `id` = '" . $fid . "'");
		$name = $db->getRow($query);
		
		if($core->auth->isAdmin && $name)
		{
			switch($type)
			{
				case 'close_forum':
					$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET `open` = '1' WHERE `id` =" . $fid . " LIMIT 1 ;");
					break;			
					
				case 'open_forum':
					$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET `open` = '0' WHERE `id` =" . $fid . " LIMIT 1 ;");
					break;
			}
		}

		location('/board/forum-' . $fid);
		break;
	
	case 'editPost':
		$pid = intval($url[2]);
		$hash = $url[3];
		$page = intval($url[4]);
		
		$query = $db->query("SELECT p.*, t.forum, t.title as ttitle FROM `" . DB_PREFIX . "_board_posts` as p LEFT JOIN `" . DB_PREFIX . "_board_threads` as t ON(p.tid = t.id) WHERE p.id = '" . $pid . "'");
		$name = $db->getRow($query);
		if(permission($name['forum'], 'Attach') && $name && $name['uid'] == $core->auth->user_id OR $core->auth->isAdmin)
		{
			set_title(array(_FORUM_TITLE, _FORUM_EDITING_POST));
			menu();
			$uniqCode = gencode(10);
			$textArea = bb_area('text', html2bb($name['message']), 10, 'textarea', false, true);
			if(!empty($name['files']))
			{	
				$file = '';
				foreach(unserialize($name['files']) as $ssid => $cont)
				{
					$file = $file . '<input type="checkbox" name="delete[]" value="' . $ssid . '" /> ' ._DELETE .' '. $cont['name'] . '? <br />';
					$start = $ssid;
				}				
			}
			else $start = 0;
			$forum_upload = forumUpload($uniqCode, ($start+1));
			
			$core->tpl->open('topic.edit.full');
			$core->tpl->loadFile('board/topic.edit.full');		
			$core->tpl->setVar('TEXTAREA', $textArea);			
			$core->tpl->setVar('FORUM_UPLOAD',  $forum_upload);
			$core->tpl->setVar('UNIQCODE',  $uniqCode);	
			$core->tpl->setVar('FILE',  $file);	
			$core->tpl->setVar('ID',  $name['id']);	
			$core->tpl->setVar('PAGE',  $page);		
			$core->tpl->setVar('TID',  $name['tid']);		
			$array_replace["#\\[upload\\](.*?)\\[/upload\\]#is"] = (($board_conf['loadFiles'] == 1) ? '\\1' : '');	
			$array_replace["#\\[file\\](.*?)\\[/file\\]#is"] = ((!empty($name['files'])) ? '\\1' : '');	
			$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);			
			$core->tpl->end();
			$core->tpl->close();
		}
	break;
		
	case 'user':
		$type = $url[2];
		$tid = intval($url[3]);
		$hash = $url[4];
		
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_threads` WHERE `id` = '" . $tid . "'");
		$name = $db->getRow($query);
		
		if(permission($name['forum'], 'Edit') && $name && $name['poster'] == $core->auth->user_id OR $core->auth->isAdmin)
		{
			if(md5($core->auth->user_info['tail'] . '-' . date('d')) == $hash)
			{
				menu();
				switch($type)
				{
					case 'editThread':						
						$icon = '';
						$path = ROOT . 'media/board/theme_icon/';
						$dh = opendir($path);
						$c=0;						
						while ($file = readdir($dh)) 
						{
							if(eregStrt('.gif', $file)) 
							{
								$check = $name['icon'] == $file ? 'checked' : '';
								$icon=$icon.'<label><input type="radio" name="icon" value="' . $file . '" ' . $check. ' /> <img alt="" src="media/board/theme_icon/' . $file . '" border="0" class="icon" /></label> ';
							}
						}
						closedir($dh);		
						$core->tpl->open('topic.edit');
						$core->tpl->loadFile('board/topic.edit');		
						$core->tpl->setVar('ICON', $icon);
						$core->tpl->setVar('HASH', $hash);
						$core->tpl->setVar('FORUM_NAME',  $name['forum']);
						$core->tpl->setVar('ID',  $tid);
						$core->tpl->setVar('NAME',  prepareTitle($name['title']));									
						$core->tpl->end();
						$core->tpl->close();												
					break;
						
					case 'delete':
						deleteTopic($tid, $name['forum']);
						$core->tpl->info(_FORUM_THEME_DELETED_OK.' <a href="board/forum-' . $name['forum'] . '">'._BACK.'</a>');
						break;
						
					case 'close':
						$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `closed` = '1' WHERE `id` =" . $tid . " LIMIT 1 ;");
						location('/board/topic-' . $tid . '/getlastpost');
						break;
						
					case 'open':
						$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `closed` = '0' WHERE `id` =" . $tid . " LIMIT 1 ;");
						location('/board/topic-' . $tid . '/getlastpost');
						break;
				}
			}		
		}
		break;
		
	case 'userSave':
		$hash = $url[2];
		$fid = intval($_POST['forum']);
		$tid = intval($_POST['tid']);
		$title = filter($_POST['title']);
		$type = filter($_POST['type'], 'a');
		$mess = isset($_POST['mess']) ? filter($_POST['mess']) : '';
		$icon = filter($_POST['icon'], 'a');
		
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_threads` WHERE `id` = '" . $tid . "'");
		$name = $db->getRow($query);
		
		if(permission($name['forum'], 'Edit') && $name && $name['poster'] == $core->auth->user_id OR $core->auth->isAdmin)
		{
			if(md5($core->auth->user_info['tail'] . '-' . date('d')) == $hash)
			{
				menu();
				switch($type)
				{
					case 'topic':
						if($title && $fid && $tid)
						{
							if($db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `title` = '" . $title . "', `icon` = '" . $icon . "' WHERE `id` =" . $tid . " LIMIT 1 ;"))
							{
								location('/board/topic-' . $tid . '/getlastpost');
							}
							else
							{
								location();
							}
						}
						else
						{
							$core->tpl->info(_FORUM_TEXT_FIELD_NULL, 'warning');
						}
						break;
				
				}
			}
		}
		break;
		
		case 'ajax':
			$no_head = true;
			header('Content-type: text/plain; charset=utf-8');
			$switch = $url[2];
			$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : '';
			$blocked = isset($_REQUEST['blocked']) ? intval($_REQUEST['blocked']) : '';
			
			switch($switch)
			{
				case 'fastForm':
					if($id)
					{
						list($content, $files) = $db->fetchRow($db->query("SELECT message, files FROM " . DB_PREFIX . "_board_posts WHERE id='" . $id . "'"));
						
						if($blocked)
						{
							echo $content;
						}
						else
						{
							echo "<form action=\"javascript:forumSaveEdit('fastEdit-".$id."', addition);\" name=\"fast\" id=\"fast\">";
							bb_area('edit', html2bb($content), 5, 'textarea', '');
							if(!empty($files))
							{
								echo "<input type=\"hidden\" id=\"files\" value=\"1\"/>";
								foreach(unserialize($files) as $ssid => $cont)
									echo '<input type="checkbox" onclick="if(confirm(\''._FORUM_ARE_YOU_SURE_DELETE_FILE.' ' . $cont['name'] . '?\')) addition += \'&delete[]=' . $ssid . '\'; else return false;" /> '._DELETE.' ' . $cont['name'] . '? <br />';
							}
							else
							{
								echo "<input type=\"hidden\" id=\"files\" value=\"0\"/>";
							}
							echo "<input type=\"hidden\" id=\"id\" value=\"" . $id . "\"/>";
							echo "<div align=\"right\"> <br /><input type=\"submit\" name=\"button\" value=\""._APPLY."\" /> <input type=\"submit\" name=\"button\" value=\""._CANCEL."\" onclick=\"ajaxSimple('index.php?url=board/ajax/fastForm&blocked&id=" . $id . "', 'fastEdit-".$id."', true);\" /></div>";
							echo "</form>";
						}
					}
					break;
					
				case 'fastSave':
					$pid = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : '';
					$text = isset($_REQUEST['text']) ? filter(utf_decode($_REQUEST['text'])) : '';
					$f = isset($_REQUEST['files']) ? true : '';
					
					if($text)
					{
						if($f == true) 
						{
							list($files) = $db->fetchRow($db->query("SELECT `files` FROM `" . DB_PREFIX . "_board_posts` WHERE `id`='" . $pid . "'  LIMIT 1 ;"));
							$unfiles = unserialize($files);
							if(!empty($_REQUEST['delete']))
							{
								foreach($_REQUEST['delete'] as $id)
								{
									$inf = $unfiles[$id];
									@unlink(ROOT.$inf['file']);
									@unlink(ROOT.$inf['fileTh']);
									unset($unfiles[$id]);
									$text = str_replace('[file='.$id.']', '', $text);
								}
							}
							$fill = empty($unfiles) ? '' : serialize($unfiles);
							$db->query("UPDATE `" . DB_PREFIX . "_board_posts` SET `message` = '" . $db->safesql(parseBB(processText($text))) . "', `files` = '" . $fill . "' WHERE `id` =" . $pid . " LIMIT 1 ;");
							
							if(isset($_REQUEST['tid']))
							{
								boardUpload(0, $tid, $text, $pid, $unfiles);
								location('/board/topic-' . $_REQUEST['tid'] . '/page/' . $_REQUEST['page'] . '#entry' . $pid);
							}
							else
								echo attachForum($core->bbDecode(parseBB($text)), serialize($unfiles));
						}
						else
						{
							$db->query("UPDATE `" . DB_PREFIX . "_board_posts` SET `message` = '" . $db->safesql(parseBB(processText($text))) . "' WHERE `id` =" . $pid . " LIMIT 1 ;");
							if(isset($_REQUEST['tid']))
							{
								list($files) = $db->fetchRow($db->query("SELECT `files` FROM `" . DB_PREFIX . "_board_posts` WHERE `id`='" . $pid . "'  LIMIT 1 ;"));
								boardUpload(0, $tid, $text, $pid, unserialize($files));
								location('/board/topic-' . $_REQUEST['tid'] . '/page/' . $_REQUEST['page'] . '#entry' . $pid);
							}
							else
								echo $core->bbDecode(parseBB($text));
						}
					}
					else
					{
						echo _FORUM_ERROR;
					}
					break;
					
				case 'delete':
					list($tid, $uid, $forum) = $db->fetchRow($db->query("SELECT p.tid, p.uid, t.forum FROM " . DB_PREFIX . "_board_posts as p LEFT JOIN " . DB_PREFIX . "_board_threads as t on(p.tid=t.id) WHERE p.id='" . $id . "'"));
					if(permission($forum, 'Edit') && isset($uid) && $uid == $core->auth->user_id OR $core->auth->isAdmin)
					{
						if($db->query("DELETE FROM `" . DB_PREFIX . "_board_posts` WHERE `id` = " . $id . " LIMIT 1"))
						{
							foreach(glob(ROOT.'files/board/boardFile_*_' . $id . '_*.*') as $f) @unlink($f);
							foreach(glob(ROOT.'files/board/boardFile_*_' . $id . '_*_thumb.*') as $s) @unlink($s);
							$db->query("UPDATE `" . DB_PREFIX . "_board_threads` SET `replies` = `replies`-1 WHERE `id` =" . $tid . " LIMIT 1 ;");
							$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET `posts` = `posts`-1 WHERE `id` =" . $forum . " LIMIT 1 ");
							echo '<font color="green">'._FORUM_MESSAGE_DELETED.'</font>';
						}
						else
						{
							echo _FORUM_ERROR;
						}
					}
					break;
			}
			break;
}
