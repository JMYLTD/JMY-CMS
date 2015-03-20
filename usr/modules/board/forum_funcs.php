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



function deleteTopic($tid, $fid)
{
global $db;
	list($all) = $db->fetchRow($db->query("SELECT COUNT(id) FROM " . DB_PREFIX . "_board_posts WHERE tid='" . $tid . "'"));
	$db->query("DELETE FROM `" . DB_PREFIX . "_board_posts` WHERE `tid` = " . $tid . "");
	$db->query("DELETE FROM `" . DB_PREFIX . "_board_threads` WHERE `id` = " . $tid . " LIMIT 1");
	foreach(glob(ROOT.'files/board/boardFile_' . $tid . '_*_*.*') as $f) @unlink($f);
	foreach(glob(ROOT.'files/board/boardFile_' . $tid . '_*_*_thumb.*') as $s) @unlink($s);
	list($newTid, $newTitle, $lastTime, $lastPoster) = $db->fetchRow($db->query("SELECT id, title, lastTime, lastPoster FROM " . DB_PREFIX . "_board_threads WHERE forum='" . $fid . "' ORDER BY lastTime DESC LIMIT 1"));
	if(!empty($newTitle))
	{
		$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET posts = posts-" . $all . ", threads=threads-1, lastPost='" . $lastTime . "', lastPoster='" . $lastPoster . "', lastTid='" . $newTid . "', lastSubject='" . $newTitle . "' WHERE `id` =" . $fid . " LIMIT 1 ");
	}
	else
	{
		$db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET posts = posts-" . $all . ", threads=threads-1, lastPost='', lastPoster='', lastTid='0', lastSubject='' WHERE `id` =" . $fid . " LIMIT 1 ");
	}
}


function menu()
{
global $core;
	
	
	$query = isset($_POST['query']) ? filter($_POST['query'], 'a') : '';
	$cookie = isset($_COOKIE['Block_menu']) ? true : false;	
	$core->tpl->open('menu');
	echo '<script src="usr/plugins/js/forum.js" type="text/javascript"></script>';
	$core->tpl->loadFile('board/forum.menu');	
	$core->tpl->setVar('NAME', $core->auth->user_info['nick']);
	$core->tpl->setVar('QUERY', $query);	
	$core->tpl->setVar('COOKIE_IMG',  ($cookie ? 'close' : 'open'));
	$core->tpl->setVar('COOKIE_DIS',  ($cookie ? 'none' : 'block'));
	$core->tpl->end();
	$core->tpl->close();
	
}

function permission($fid, $type, $tuid = 0)
{
global $core, $db;
	if($core->auth->isUser)
	{
		$ugid = $core->auth->user_info['group'];
	}
	else
	{
		$ugid = 3;
	}
	
	static $groups;
	
	if(!isset($groups))
	{
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_permissions` WHERE gid = '" . $ugid . "'");
		while($row = $db->getRow($query))
		{
			$groups[$row['fid']] = $row;
		}
	}

	if(isset($groups[$fid]) && $groups[$fid]['allow' . $type] == 1)
	{
		return true;
	}
	else
	{
		return false;
	}
}

function stremyanka($fid, $type)
{
global $db, $core;
	static $forum;
	
	if(!isset($forum))
	{
		$query = $db->query("SELECT id, title, pid FROM `" . DB_PREFIX . "_board_forums`");
		while($row = $db->getRow($query))
		{
			$forum[$row['id']] = array($row['title'], $row['pid']);
		}
	}

	switch($type)
	{
		case 'linked':
			foreach ($forum as $id => $arr) 
			{
				if($id == $fid) 
				{
					$array[$id] = '<a href="board/forum-' . $id . '" title="' . $arr[0] . '">' . $arr[0] . '</a>';
					$sub = $arr[1];
					
					while ($sub != "0") 
					{
						$array[$id] = '<a href="board/forum-' . $sub . '" title="' . $forum[$sub][0] . '">' . $forum[$sub][0] . "</a>&nbsp;&gt;&nbsp;" . $array[$id];
						$sub = $forum[$sub][1];
						$array[$id] = $array[$id];
					}
				}
			}
			
			return '<a href="board" title="Форум">Форум</a>&nbsp;&gt;&nbsp;' . (isset($array) ? $array[$fid] : '');
			break;
			
		case 'list':
			foreach ($forum as $id => $arr) 
			{
				$array[$id] = '<option value="board/forum-' . $id . '" ' . ($id == $fid ? 'selected' : '') . '>' . $arr[0] . '</option>';
				
				$sub = $arr[1];
				while ($sub != "0") 
				{
					$array[$id] = '<option value="board/forum-' . $id . '" ' . ($sub == $fid ? 'selected' : '') . '>' . $forum[$sub][0] . " >> " . $forum[$id][0] . "</option>";
					$sub = $forum[$sub][1];
				}
			}
			
			$list = '';

			foreach($array as $option)
			{
				$list .= $option;
			}
			
			return $list;
			break;
	}
}

function get_forums($pid = 0, $level = 1)
{
global $db, $core, $forum_name;
	static $forum;
	
	if(!isset($forum))
	{
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_board_forums` ORDER BY position");
		while($row = $db->getRow($query))
		{
			if(permission($row['id'], 'View'))
			{
				$forum[$row['pid']][$row['id']] = $row;
				
				if(isset($pid) && $pid == $row['id'])
				{
					$title = $row['title'];
				}
			}
		}
	}
	
	$count = 0;
	
	if(isset($forum[$pid]))
	{
		if($level == 1) $core->tpl->open('mainForums');
		
		if($pid > 0 && $level == 1)
		{
		$cookie = isset($_COOKIE['Block_forum-' . $pid]) ? true : false;
		$closed = true;
		$core->tpl->open('forum.list.top');
					$core->tpl->loadFile('board/forum.list.top');
					$core->tpl->setVar('ID', $pid);
					$core->tpl->setVar('FORUM_NAME', '<a href="board/forum-' . $pid . '" title="Главный раздел: ' . htmlspecialchars($title) . '">' . $title . '</a> - Подфорумы');
					$core->tpl->setVar('COOKIE_IMG',  ($cookie ? 'close' : 'open'));
					$core->tpl->setVar('COOKIE_DIS',  ($cookie ? 'none' : 'block'));		
					$core->tpl->end();
					$core->tpl->close();	
			
			
		
			$level = 2;
		}
		
		foreach($forum[$pid] as $id => $info)
		{
		
			if($level == 1)
			{
				if(isset($forum[$id]))
				{
					$cookie = isset($_COOKIE['Block_forum-' . $id]) ? true : false;
					$count = 0;	
					$core->tpl->open('forum.list.top');
					$core->tpl->loadFile('board/forum.list.top');
					$core->tpl->setVar('ID', $id);
					$core->tpl->setVar('FORUM_NAME', '<a href="board/forum-' . $info['id'] . '" title="Главный раздел: ' . htmlspecialchars($info['title']) . '">' . $info['title'] . '</a>');
					$core->tpl->setVar('COOKIE_IMG',  ($cookie ? 'close' : 'open'));
					$core->tpl->setVar('COOKIE_DIS',  ($cookie ? 'none' : 'block'));		
					$core->tpl->end();
					$core->tpl->close();			
				}
			}
			elseif($level == 2)
			{
				$count++;
				if($info['open'] == 1)
				{	
					$icon = 'inactive.png';
					$alt = 'Форум закрыт';
					
				}
				elseif($info['lastPost'] == '')
				{
					$icon = 'no_info.png';
					$alt = 'Нет тем в форуме';
				}
				elseif($info['lastPost'] > time()-86400)
				{
					$icon = 'board_more.png';
					$alt = 'Есть темы за последние сутки';
				}
				else
				{
					$icon = 'board_nonew.png';
					$alt = 'Ничего нового';
				}
					
					$core->tpl->open('forum.list.body');
					$core->tpl->loadFile('board/forum.list.body');
					$core->tpl->setVar('ID', $id);
					$core->tpl->setVar('ICON', $icon);
					$core->tpl->setVar('ICON_ALT', $alt);	
					$core->tpl->setVar('FORUM_NAME', '<a href="board/forum-' . $info['id'] . '" title="В раздел: ' . $info['title'] . '">' . $info['title'] . '</a>');			
					$forum_name = '';
					if(isset($forum[$id]))
					{
						get_forums($id, $level+1);
					}
					if ($forum_name<>'')
					{
					$forum_name=substr($forum_name, 0, -2);
					$forum_name='Подфорумы: '.$forum_name;
					}
					$core->tpl->setVar('DOP_FORUM', $forum_name);	
					$core->tpl->setVar('DESC', $info['description']);					
					$core->tpl->setVar('THREADS',  $info['threads']);					
					$core->tpl->setVar('POSTS',  $info['posts']);	
					$core->tpl->setVar('LAST_POSTS', ($info['lastPost'] == '' ? 'Новых тем нет' : '<a href="board/topic-' . $info['lastTid'] . '/getlastpost" title="К последнему сообщению"><img src="media/board/up.png" border="0" alt="Последнее сообщение" style="vertical-align:middle" /></a> <span>' . formatDate($info['lastPost']) . ' <br />
			        <b>Тема:</b>&nbsp;<a href="board/topic-' . $info['lastTid'] . '/getlastpost" title="К первому непрочитанному сообщению: ' . htmlspecialchars($info['lastSubject']) . '">' . str($info['lastSubject'], 20) . '</a><br />
			        <b>От:</b> <a href="profile/' . $info['lastPoster'] . '" title="Последний">' . $info['lastPoster'] . '</a></span>'));	
					$core->tpl->end();
					$core->tpl->close();							
				
					
			}
			elseif($level == 3)
			{
			$forum_name = $forum_name.'<a href="board/forum-' . $info['id'] . '" title="В раздел: ' . $info['title'] . '">' . $info['title'] . '</a>, ';		
			}
			
			
			if($level < 2)
			{
				if(isset($forum[$id]))
				{
					get_forums($id, $level+1);
				}
			}
			
			if(isset($forum[$pid]) && $count == count($forum[$pid]))
			{
					$core->tpl->open('forum.list.down');
					$core->tpl->loadFile('board/forum.list.down');						
					$core->tpl->end();
					$core->tpl->close();
			}
				
		}
		
		if($level == 1 OR isset($closed)) $core->tpl->close();
		
	}
}

function attachForum($text, $files, $act = false)
{
	if($act == false)
	{
		if(strpos($text, "[file=") !== false)
		{
			$text = preg_replace('#\[file\=(.+?)\]#es', 'attachForum(\'\\1\', \'' . $files . '\', true)', $text);
		}
		
		return $text;
	}
	else
	{
	global $core;
		$f = unserialize($files);
		if($f[$text]['type'] == 'file')
		{
			if($core->auth->group_info['showAttach'] == 1)
			{
				return '» <a href="' . $f[$text]['file'] . '" ax:wrap="0">' . _DOWNLOAD . ' ' . $f[$text]['name'] . '</a> (' . formatfilesize($f[$text]['size'], true) . ')';
			}
			else
			{
				return _ACCESS_ATTACH;
			}
		}
		else
		{
		global $config;
			if(!empty($f[$text]['fileTh']))
			{
				require(ROOT . 'media/imageEffects/' . $config['imageEffect'] . '/init.php');
				$core->tpl->headerIncludes['thumbNail'] = $js;
				$repl = array(
					'{full}' => $config['url'] . '/' . $f[$text]['file'],
					'{thumb}' => $config['url'] . '/' . $f[$text]['fileTh'],
					'{img}' => 'alt="' . _INCREASE . '"',
					'{href}' => ''
				);
				
				return '<!--ThumbNail-->'.img_preview(str_replace(array_keys($repl), array_values($repl), $picture), 'box').'<!--ThumbNail:end-->';	
			}
			else
			{
				return ''.img_preview('<img src="' . $config['url'] . '/' . $f[$text]['file'] . '" border="0" alt="" />', 'box').'';
			}
		}
	}
}



function boardUpload($timepid, $tid, $message, $pid = false, $alreadyFiles = false, $fromStart = false)
{
global $board_conf, $db, $core;
	if(!empty($_FILES['files']['name'][1]) && $board_conf['loadFiles'] == 1)
	{
		if($pid == false)
			list($pid) = $db->fetchRow($db->query("SELECT id FROM " . DB_PREFIX . "_board_posts WHERE tid='" . $tid . "' AND time='" . $timepid . "' AND uid='" . $core->auth->user_id . "'"));
		
		$size = 0;
		$img = array('png', 'jpg', 'gif', 'jpeg');
		foreach($_FILES['files']['tmp_name'] as $num => $adress)
		{
			$realNum = $num;
			/*			
				if($fromStart != false)
				{
					$num = $num+count($alreadyFiles);
					str_replace('[file='.$realNum.']', '[file=' . $num . ']', $fromStart);
				}*/
					
			$size += $_FILES['files']['size'][$realNum];			
			if($size > $board_conf['maxSize']) break;
					
			$fileFormat = getExt($_FILES['files']['name'][$realNum]);
			if(in_array($fileFormat, explode(',', $board_conf['formats'])))
			{
				$file = ROOT.'files/board/boardFile_' . $tid . '_'.$pid.'_'.$num.'.'.$fileFormat;
				if(in_array($fileFormat, $img))
				{
					$fileTh = ROOT.'files/board/boardFile_' . $tid . '_'.$pid.'_'.$num.'_thumb.'.$fileFormat;
					$resp = createThumb($_FILES['files']['tmp_name'][$realNum], $fileTh, $board_conf['maxWH'], $board_conf['maxWH'], true);
					if($resp == 'orig') $fileTh = '';
					createThumb($_FILES['files']['tmp_name'][$realNum], $file, 1000, 1000);
					$files[$num] = array('type' => 'img', 'name' => $_FILES['files']['name'][$realNum], 'file' => str_replace(ROOT, '', $file), 'size' => $_FILES['files']['size'][$realNum], 'ext' => $fileFormat, 'fileTh' => str_replace(ROOT, '', $fileTh));
				}
				else
				{
					copy($_FILES['files']['tmp_name'][$realNum], $file);
					$files[$num] = array('type' => 'file', 'name' => $_FILES['files']['name'][$realNum], 'file' => str_replace(ROOT, '', $file), 'size' => $_FILES['files']['size'][$realNum], 'ext' => $fileFormat);
				}
			}
		}

		if(!empty($alreadyFiles) && !empty($files)) $files = array_merge($alreadyFiles, $files);

		if(!empty($files))
		{
			if(!empty($files))
				$toDB = serialize($files);
			else
				$toDB = '';
								
			preg_match_all('#\[file\=([0-9]+)\]#i', $message, $matches);
							
			foreach($matches[1] as $unset)
			{
				unset($files[$unset]);
			}
							
			if(!empty($files))
			{
				foreach($files as $id => $inf)
				{
					$message .= "\n[file=".$id."]";
				}
			}
							
			$db->query("UPDATE `" . DB_PREFIX . "_board_posts` SET `message` = '" . $db->safesql(parseBB(processText($message))) . "', `files` = '" . $toDB . "' WHERE `id` =" . $pid . " LIMIT 1 ");
		}
	}
}