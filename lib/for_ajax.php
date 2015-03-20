<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('AJAX')) 
{
    header('Location: /');
    exit;
}


function vote() 
{
    global $core, $db;
    if($core->auth->group_info['allowRating'] == 0) return;
	$id = intval($_REQUEST['id']);
	$module = filter($_REQUEST['module'], 'module');
	$num = intval($_REQUEST['num']);
	
	list($check) = $db->fetchRow($db->query("SELECT time FROM " . DB_PREFIX . "_ratings WHERE (uid='" . $core->auth->user_id . "' OR ip='" . getRealIpAddr() . "') AND id='" . $id . "'"));

    if(!empty($module) && !$check && $num <= 10 && $id > 0) 
    {
		$uid = 0;

        $db->query("UPDATE " . DB_PREFIX . "_" . $module . " SET score = score+" . $num . ", votes = votes+1 WHERE id= '" . $id . "'");
        $data = $db->getRow($db->query("SELECT score, votes FROM " . DB_PREFIX . "_" . $module . " WHERE id='" . $id . "'"));

        if($core->auth->isUser) 
        {
            $uid = $core->auth->user_id;
        }

        $db->query("INSERT INTO " . DB_PREFIX . "_ratings VALUES (NULL, '" . $id . "', '" . $uid . "', '" . $module . "', '" . time() . "', '" . getRealIpAddr() . "')");
        echo draw_rating($id, $module, $data['score'], $data['votes'], 1);
    } 
    else 
    {
        echo draw_rating($id, $module, 0, 0, 0);
    }
}

function carma_vote()
{
   global $core, $db;
    if($core->auth->group_info['allowRating'] == 0) return;
	$id = intval($_REQUEST['id']);
	$type = intval($_REQUEST['type']);
	
	list($check) = $db->fetchRow($db->query("SELECT time FROM `" . DB_PREFIX . "_ratings` WHERE (`uid`='" . $core->auth->user_id . "' OR `ip`='" . getRealIpAddr() . "') AND `mod`='news' AND `id`='" . $id . "'"));
	
	if(!empty($type) && !$check && !empty($id))
	{
		switch($type)
		{
			case 1:
				$db->query("UPDATE " . DB_PREFIX . "_news SET score = score+1 WHERE id= '" . $id . "'");
				break;
				
			case 2:
				$db->query("UPDATE " . DB_PREFIX . "_news SET votes = votes+1 WHERE id= '" . $id . "'");
				break;
		}
		
		$data = $db->getRow($db->query("SELECT score, votes FROM " . DB_PREFIX . "_news WHERE id='" . $id . "'"));
		
		$db->query("INSERT INTO " . DB_PREFIX . "_ratings VALUES (NULL, '" . $id . "', '" . $core->auth->user_id . "', 'news', '" . time() . "', '" . getRealIpAddr() . "')");
		
		echo draw_rating($id, 'news', $data['score'], $data['votes']);
	}
	else
	{
		echo _ALREADY_VOTED;
	}
}

function fast_edit() 
{
global $core, $db;
    $id = intval($_REQUEST['id']);
    $module = filter($_REQUEST['module'], 'module');
    $type = explode('/', $_REQUEST['type']);
    $type = filter($type[0], 'a');

    switch($module) 
    {
        default:
            return;
            break;

        case "news":
            $data = $db->getRow($db->query("SELECT " . $type . " FROM " . DB_PREFIX . "_langs WHERE postId='" . $id . "' AND module='news'"));
            break;
    }

    if($core->auth->isAdmin && !isset($_REQUEST['blocked'])) 
    {
        echo "<form action=\"javascript:fast_post('" . $type . '-' . $id . "');\" name=\"fast\" id=\"fast\">";
        bb_area('edit', html2bb($data[$type]), 10, 'textarea', '');
        echo "<input type=\"hidden\" id=\"id\" value=\"$id\"/>";
        echo "<input type=\"hidden\" id=\"module\" value=\"$module\"/>";
        echo "<input type=\"hidden\" id=\"type\" value=\"$type\"/>";
        echo "<div align=\"right\"> <br /><input type=\"submit\" name=\"button\" value=\"" . _APPLY . "\" class=\"b\"/> <input type=\"submit\" name=\"button\" value=\"" . _CANCEL . "\" onclick=\"fastCancel(" . $id . ", '$module', '$type', '".$type."-".$id."');\" class=\"b\"/></div>";
        echo "</form>";
    } 
    else
    {
        echo $core->bbDecode($data[$type], $id);
    }
}


function fast_save() 
{
    global $db, $core;
    $text = filter(utf_decode($_REQUEST['text']), 'html');
    $id = intval($_REQUEST['id']);
    $module = filter($_REQUEST['module'], 'module');
    $type = filter($_REQUEST['type'], 'a');
	
    if($text && $id && $module && $type) 
    {
        $db->query("UPDATE " . DB_PREFIX . "_langs SET " . $type . " = '" . $db->safesql(parseBB(processText($text), $id, true)) . "' WHERE postId='" . $id . "' AND module='news'");
		
        echo $core->bbDecode(parseBB($text, $id, true), $id, true);
    } 
    else 
    {
        echo _IMPOSSIBLE_EMPTY . ' <a href="javascript:void(0);" onclick="fast_edit(\'' . $id . '\', \'' . $type . '\', \'news\')">' . _BACK . '</a>';
    }
}

function commentDeleteAjax()
{
    global $core, $db, $config;
    $id = intval($_REQUEST['id']);
    $mod = filter($_REQUEST['mod'], 'module');
    $nid = intval($_REQUEST['nid']);

    $data = $db->getRow($db->query("SELECT uid, text FROM " . DB_PREFIX . "_comments WHERE id='" . $id . "' AND module='" . $db->safesql($mod) . "' AND post_id='" . $nid . "'"));

    if($data && ($core->auth->isAdmin OR $core->auth->isUser && $core->auth->user_id == $data['uid']) && !isset($_REQUEST['blocked'])) 
    {
        $db->query("DELETE FROM `" . DB_PREFIX . "_comments` WHERE `id` = " . $id . " LIMIT 1");
		add_point($mod, $nid, '-');
        echo '<center><span style="color:green"><strong>' . _MESSAGE_DELETED . '</strong></span></center>';
    }
    else
    {
        echo '<span style="color:red">' . _MESSAGE_NOTDELETED . '</span>';
    }
}

function commentSubscribe()
{
    global $core, $user, $db;
    if($core->auth->isUser && $user['commentSubscribe'] == 1)
    {
        $do = intval($_REQUEST['do']);
        $mod = filter($_REQUEST['mod'], 'module');
        $id = intval($_REQUEST['nid']);

        if($do == 1)
        {
            $db->query("INSERT INTO `" . DB_PREFIX . "_com_subscribe` ( `id` , `module` , `uid` ) VALUES ('" . $id . "', '" . $mod . "', '" . $core->auth->user_id . "');");
            echo _COMMSUBSCRIBE;
        }
        else
        {
            $db->query("DELETE FROM `" . DB_PREFIX . "_com_subscribe` WHERE `id` = " . $id . " AND `module` = '" . $mod . "' AND `uid` = " . $core->auth->user_id . "");
            echo _COMMUNSUBSCRIBE;
        }

    }
}

function commentPage()
{
    $mod = isset($_REQUEST['mod']) ? filter($_REQUEST['mod'], 'module') : '';
    $pid = isset($_REQUEST['pid']) ? intval($_REQUEST['pid']) : '';
    $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : '';
    $commNum = isset($_REQUEST['commNum']) ? intval($_REQUEST['commNum']) : '';

    if($mod && $pid && $page && $commNum)
    {
        show_comments($mod, $pid, $commNum, true, $page);
    }
    else
    {
        echo 'error';
    }
}

function captcha()
{
    if(isset($_REQUEST[session_name()]))
    {
        session_start();
    }

    $captcha = new KCAPTCHA;

    if($_REQUEST[session_name()])
    {
        $_SESSION['securityCode'] = $captcha->getKeyString();
    }
}

function comment_savea() 
{
    global $db, $core, $user, $config, $mail_conf;
    if($config['comments'] == 0 || $core->auth->group_info['addComment'] == 0) return;
    $id = intval($_REQUEST['id']);
    $reply_to = intval($_REQUEST['reply_to']);
    $text = filter($_REQUEST['text']);
    $gauthor = filter($_REQUEST['author'], 'a');
    $email = $core->auth->isUser ? '' : filter($_REQUEST['email'], 'mail');
    $mod = filter($_REQUEST['mod'], 'module');
    $commNum = intval($_REQUEST['commNum']);
    $time = time();
    $uid = $core->auth->isUser ? $core->auth->user_id : 0;
    $author = $core->auth->isUser ? '' : 'Гость - ' . $gauthor;
    $guest = $core->auth->isUser ? false : true;
	
    if(!$core->auth->isUser)
    {
        if(!captcha_check('securityCode')) 
        {
            $warning[] = _CAPTCHA_NOTVALID;
        }
    }

    if($guest) 
    {
        if(empty($gauthor) || empty($email)) 
        {
			if(empty($email))
			{
				$warning[] = _EMAIL_BADFORMAT;
			}
			else
			{
				$warning[] = _NOT_FILLED;
			}
        } 
        else 
        {
			setcookie('commentator', $gauthor, time() + 86400, '/');
			setcookie('email', $email, time() + 86400, '/');
        }
    }

    if($user['commentSubscribe'] == 1)
    {
        $subscribe = intval($_REQUEST['subscribe']);
    }

    session_start();
    if(isset($_SESSION['lastComment']) && (time()-$_SESSION['lastComment'] < $user['commentOften']))
    {
        $warning[] = str_replace('{time}', $user['commentOften'], _OFTEN_QUERY);
    }

    if(!isset($warning))
    {
        if($text && eregStrt($_SERVER['HTTP_HOST'], $_SERVER['HTTP_REFERER'])) 
        {
			$parent = ($core->auth->isUser && $reply_to > 0 && $user['commentTree'] == 1 ? $reply_to : 0);
            $query = $db->query("INSERT INTO `" . DB_PREFIX . "_comments` ( `id` , `uid` , `post_id` , `module` , `text` , `date` , `gemail` , `gname` , `gurl` , `parent` , `status` ) VALUES (NULL, '" . $uid . "', '" . $id . "', '" . $mod . "', '" . $db->safesql(parseBB(processText($text))) . "', '" . $time . "', '" . $email . "', '" . $author . "', '', '" . $parent . "', '1');");
			delcache('userInfo_'.$uid);

           /* if($core->auth->isUser && isset($subscribe) && $subscribe == 1 && $uid != $core->auth->user_id)
            {
				sendMail($core->auth->user_info['email'], 'Вы подписались под комментарии на сайте ' . $config['name'], $text);
            }*/
			
			if($core->auth->isUser && $mod != 'profile')
			{
				user_points($uid, 'add_comment');
			}
			
			if($mod != 'profile')
			{
				add_point($mod, $id);
			}

        } 
        else 
        {
            $core->tpl->info(_EMPTY_TEXT, 'warning');
            exit;
        }
    }

    if(isset($warning))
    {
        show_comments($mod, $id, $commNum, true, false, $warning);
    }
    else
    {
        $_SESSION['lastComment'] = time();	
        show_comments($mod, $id, $commNum, true, false, true);
    }
}



function commentEditAjax() 
{
    global $core, $db;
    $id = intval($_REQUEST['id']);
    $data = $db->getRow($db->query("SELECT uid, text FROM " . DB_PREFIX . "_comments WHERE id='" . $id . "'"));

    if($data && ($core->auth->isAdmin OR $core->auth->isUser && $core->auth->user_id == $data['uid']) && !isset($_REQUEST['blocked'])) 
    {
        $text = $data['text'];
        echo "<form action=\"javascript:commentEditSave('comment_" . $id . "');\" name=\"fast\" id=\"fast\">";
        $text = preg_replace("#\n---\n<\!--edit-->(.*)<\!--edit-end-->#i", '', $text);
        bb_area('edit', html2bb($text), 6, 'textarea', '');
        echo "<input type=\"hidden\" id=\"id\" value=\"$id\"/>";
        echo "<div align=\"right\"> <br /><input type=\"submit\" name=\"button\" value=\"" . _APPLY . "\" class=\"b\"/> <input type=\"button\" name=\"button\" value=" . _CANCEL . " onclick=\"commentCancel(" . $id . ");\" class=\"b\"/></div>";
        echo "</form>";
    } 
    else
    {
        echo $core->bbDecode($data['text']);
    }
}

function commentEditAjaxSave()
{
    global $db, $core, $user;
    $text = filter(utf_decode($_REQUEST['text']));
    $id = intval($_REQUEST['id']);

    $data = $db->getRow($db->query("SELECT uid, text FROM " . DB_PREFIX . "_comments WHERE id='" . $id . "'"));

    if($text && $id && $data && ($core->auth->isAdmin OR $core->auth->isUser && $core->auth->user_id == $data['uid'])) 
    {
        if(!empty($user['commentEditText']))
        {
            $arrayReplace = array('{user}' => $core->auth->user_info['nick'], '{date}' => formatDate(time()));
            $text .= "\n---\n<!--edit-->" . str_replace(array_keys($arrayReplace), array_values($arrayReplace), $user['commentEditText'])."<!--edit-end-->";
        }

        $db->query("UPDATE " . DB_PREFIX . "_comments SET text = '" . $db->safesql(parseBB(processText($text))) . "' WHERE id = '" . $id . "'");
        echo stripslashes($core->bbDecode(parseBB($text)));
    } 
    else 
    {
        echo _IMPOSSIBLE_EMPTY . ' <a href="javascript:void(0);" onclick="commentEdit(\'' . $id . '\', \'comment_' . $id . '\')">' . _BACK . '</a>';
    }
}



function check_login() 
{
    global $db;
    $uname = filter(utf_decode(urldecode($_REQUEST['uname'])), 'nick');
    list($check) = $db->fetchRow($db->query("SELECT Count(id) FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick='" . $db->safesql($uname) . "'"));
    if($check > 0) 
    {
        echo '<font color="red">' . _USER_EXISTS . '</font>';
    } 
    else 
    {
        echo '<font color="green">' . str_replace('{uname}', $uname, _USER_FREE) . '</font>';
    }
}

function cal($op) 
{
    require ROOT . 'usr/plugins/calendar.plugin.php';
    calendar($op);
}

function searchList()
{
    global $db;
    $query = filter(utf_decode($_REQUEST['query']), 'a');

    $news = $db->query("SELECT n.id, l.title, n.date, n.altname FROM " . DB_PREFIX . "_news as n LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=n.id and l.module='news') WHERE l.title LIKE '%" . $db->safesql($query) . "%'");

    if($db->numRows($news) > 0 && $query)
    {
        echo '<ul>';

        while($rows = $db->getRow($news)) 
        {
            echo '<li><a href="/news/view/'. $rows['altname'] . '">' . $rows['title'] . '</a> - ' . formatDate($rows['date']);
        }

        echo '</ul>';
    }
    else
    {
        echo '<ul>';
        echo '<li>' . _NOTHING_FOUND;
        echo '</ul>';
    }
}

function carmaHistory()
{
    global $db;
    $uid = intval($_REQUEST['uid']);

    $q = $db->query("SELECT c.*, d.nick as fromnick FROM `" . USER_DB . "`.`" . USER_PREFIX . "_user_carma` as c LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as d on(c.`from`=d.`id`) WHERE c.`to`='" . $uid . "' ORDER BY c.`time` DESC");
    if($db->numRows($q) > 0)
    {
        echo '<div style="max-height:250px; overflow:auto;"><hr />';
        while($rows = $db->getRow($q))
        {
            switch($rows['do'])
            {
                case 'p':
                    $show = '<img src="media/edit/plus.png" border="0" style="vertical-align:middle;" />';
                    break;
                case 'm':
                    $show = '<img src="media/edit/minus.png" border="0" style="vertical-align:middle;" />';
                    break;
                case 'n':
                    $show = '<img src="media/edit/ok.png" border="0" style="vertical-align:middle;" />';
                    break;
            }
            echo $show . ' <i>от: <a href="profile/' . $rows['fromnick'] . '">' . $rows['fromnick'] .'</a> [' . formatDate($rows['time']) . ']</i><br /><b>'.$rows['text'].'</b><hr />';
        }
        echo '</div>';
    }
    else
    {
        echo '<div class="mbmest">' . _CARMANOCHANGES . '</div>';
    }
}

function addCarma()
{
    global $core, $db;
    $uid = intval($_REQUEST['uid']);
    $does = filter($_REQUEST['does'], 'a');
    $text = utf_decode(filter($_REQUEST['text']));

    switch($does)
    {

        case 'p':
            $do = '+1';
            break;

        case 'm':
            $do = '-1';
            break;

        case 'n':
            $do = 0;
            break;
    }

    if(!isset($_COOKIE['carma-' . $uid]) && $core->auth->isUser && $core->auth->user_info['id'] != $uid)
    {
        if($do != 0) 
        {
            $db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET carma = carma " . $do . " WHERE `id` = '" . $uid . "' LIMIT 1 ;");
        }

        setcookie('carma-' . $uid, time(), time() + 86400, '/');

        $db->query("INSERT INTO `" . USER_DB . "`.`" . USER_PREFIX . "_user_carma` ( `_id` , `from` , `to` , `text` , `do` , `time` ) VALUES (NULL, '" . intval($core->auth->user_info['id']) ."', '" . intval($uid) . "', '" . $text . "', '" . $does . "', '" . time() . "');");
		
		if($does == 'p') user_points($uid, 'carma');
		
        list($points) = $db->fetchRow($db->query("SELECT carma FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE `id` = '" . $uid . "' LIMIT 1"));

        echo ($points > 0) ? '+' . $points : $points;
    }
    else
    {
        echo '0';
    }
}


function editTitle()
{
    global $db, $core, $config;
    if($core->auth->isAdmin)
    {
        $text = trim(filter(utf_decode($_REQUEST['text'])));
        $id = intval($_REQUEST['id']);
        $module = filter($_REQUEST['module'], 'module');

        switch($module)
        {
            case 'blocks':
                $db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `title` = '" . $db->safesql(processText($text)) . "' WHERE `id` =" . $id . " LIMIT 1 ;");
                break;				

            case 'news':
                $db->query("UPDATE `" . DB_PREFIX . "_langs` SET `title` = '" . $db->safesql(processText($text)) . "' WHERE `postId` ='" . $id . "' AND module='news' AND lang='" . $config['lang'] . "' LIMIT 1 ;");
                break;	

            case 'content':
                $db->query("UPDATE `" . DB_PREFIX . "_langs` SET `title` = '" . $db->safesql(processText($text)) . "' WHERE `postId` ='" . $id . "' AND module='content' AND lang='" . $config['lang'] . "' LIMIT 1 ;");
                break;							

            case 'forum':
                $db->query("UPDATE `" . DB_PREFIX . "_board_forums` SET `title` = '" . $db->safesql(processText($text)) . "' WHERE `id` =" . $id . " LIMIT 1 ;");
                break;					

            case 'modules':
                $db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `content` = '" . $db->safesql(processText($text)) . "' WHERE `id` =" . $id . " LIMIT 1 ;");
                break;					

            case 'blockTypes':
                $db->query("UPDATE `" . DB_PREFIX . "_blocks_types` SET `title` = '" . $db->safesql(processText($text)) . "' WHERE `type` ='" . $id . "' LIMIT 1 ;");
                break;				
            case 'poll':
                $db->query("UPDATE `" . DB_PREFIX . "_polls` SET `title` = '" . $db->safesql(processText($text)) . "' WHERE `id` =" . $id . " LIMIT 1 ;");
                break;
            case 'group':
                $db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_groups` SET `name` = '" . $db->safesql(processText($text)) . "' WHERE `id` =" . $id . " LIMIT 1 ;");
                break;
        }

        echo $text . ' - <span style="color:green">' . _TITLE_UPDATE . '</span>';
    }
}

function _fileFf($format)
{
    switch($format)
    {
        default: $icon = 'blank.png'; break;
        case 'doc': $icon = 'word.png'; break;
        case 'bmp': $icon = 'word.png'; break;		
        case 'jpg':
        case 'jpeg': $icon = 'jpg.png'; break;		
        case 'png': $icon = 'png.png'; break;		
        case 'gif': $icon = 'gif.png'; break;	
		case 'psd': $icon = 'photoshop.png'; break;	
        case 'mp3': case 'wav': case 'ogg': $icon = 'music.png'; break;			
		case 'avi': case 'flv': case 'wmv': $icon = 'music.png'; break;	
        case 'pdf': $icon = 'finerider.png'; break;
        case 'exe': $icon = 'exe.png'; break;	
        case 'txt': $icon = 'txt.png'; break;			
        case 'phps': $icon = 'php.png'; break;		
        case 'html': case 'htm': $icon = 'code.png'; break;
        case 'rar': case 'zip':  case '7z': $icon = 'rar.png'; break;
    }
	
	return $icon;
}

function fileList()
{
    global $db, $core, $admin_conf, $url;
    $id = filter($_REQUEST['id'], 'a');
    $module = filter($_REQUEST['module'], 'module');
    $delete =  isset($_REQUEST['delete']) ? filter($_REQUEST['delete'], 'url') : '';
    $truncate =  isset($_REQUEST['truncate']) ? true : false;
    $modDir = ROOT . 'files/' . $module . '/';
	$isHTML = ($id == 'temp' && $admin_conf['htmlEditor'] == 1) ? true : false;
	
    if($id && $module)
    {
        if($id == 'temp')
        {
            $pid = '0';
        }
        else
        {
            $pid = $id;
        }

        $q = $db->query("SELECT * FROM `" . DB_PREFIX . "_attach` WHERE pub_id='" . $pid . "'");

        while($rows = $db->getRow($q))
        {
            $dbArray[$rows['url']] = array($rows['id'], $rows['name']);
        }

        if(!is_dir($modDir)) 
        {
            mkdir($modDir, 0777);
			@chmod_R($modDir, 0777);
            mkdir($modDir.'temp', 0777);
			@chmod_R($modDir.'temp', 0777);
        }

        $dir = ROOT . 'files/' . $module . '/' . $id . '/';
        $fileDir = 'files/' . $module . '/' . $id . '/';

        if(!is_dir($dir)) 
        {
            mkdir($dir, 0777);
			@chmod_R($dir, 0777);
            mkdir($dir.'thumb', 0777);
			@chmod_R($dir.'thumb', 0777);
        } 
        else 
        {
            if(!is_dir($dir.'thumb')) 
            {
                mkdir($dir.'thumb', 0777);
				@chmod_R($dir.'thumb', 0777);
            }
        }

        if($delete && file_exists($dir.$delete))
        {
            unlink($dir.$delete);

            $db->query("DELETE FROM `" . DB_PREFIX . "_attach` WHERE `url` = '" . $fileDir.$delete . "' LIMIT 1");

            if(file_exists($dir.'thumb/thumb-'.$delete))
            {
                unlink($dir.'thumb/thumb-'.$delete);
            }
            echo '<font color="green">' . _FILE_DELETED . ' ['.$fileDir.$delete.']</font>';
        }
        elseif($truncate == true)
        {
            unlinkRecursive($dir);
            unlinkRecursive($dir.'thumb/');
        }

        echo
        '
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="upload">
        <tr>
        <th width="75%">' . _FILE . '</th>
        <th widht="10%">' . _SIZE . '</th>
        <th width="100" align="center">' . _ACTIONS . '</th>
        </tr>
        <tr>
        <th colspan="2" style="text-align:left"><img src="media/uploader/folder.png" border="0" class="icon" width="16" height="16" />' . $fileDir . '</th>
        <th style="text-align:right">
        <img src="media/uploader/trash.png" border="0" class="_pointer icon" alt="' . _CLEAR_PATH . '" title="' . _CLEAR_PATH . '" onclick="fileList(\'' . $id . '\', \'' . $module . '\', \'truncate\', \'\');" />
        <img src="media/uploader/reload.png" border="0" class="_pointer icon" alt="' . _UPDATEFILELIST . '" title="' . _UPDATEFILELIST . '" onclick="fileList(\'' . $id . '\', \'' . $module . '\', \'ok\', \'\');" />
        </th>
        </tr>
        ';
        $dh = opendir($dir);
        $c=0;
        $insertImages = '';
        $insertObject = '';
        while ($file = readdir($dh)) 
        {
            if(is_file($dir.$file) && $file != '.' && $file != '..' && $file != 'Thumbs.db') 
            {
                $yesFile = true;
                $fileInfo = explode('.', $file);
                $fileFormat = end($fileInfo);

                $deistv = '';

                $insertObjectS = isset($dbArray[$fileDir.$file]) ? '[attach=' . $dbArray[$fileDir.$file][0] . ']' : '';
				
				$icon = _fileFf($fileFormat);

                switch($fileFormat)
                {
					case 'bmp':
                    case 'jpg':
                    case 'jpeg':
                    case 'png':
                    case 'gif':
						if($isHTML)
						{
							$iI = htmlspecialchars(parseBB((file_exists($dir.'thumb/thumb-'.$file) ? '[thumb]' . $fileDir.'thumb/thumb-'.$file . '[/thumb]' : '[img]' . $fileDir.$file . '[/img]')), ENT_QUOTES);
							$insertObjectS = htmlspecialchars(parseBB((file_exists($dir.'thumb/thumb-'.$file) ? '[thumb]' . $fileDir.'thumb/thumb-'.$file . '[/thumb]' : '[img]' . $fileDir.$file . '[/img]')), ENT_QUOTES);
							$insertJS = 'tinyMCE.execCommand(\'mceInsertContent\', false, \'' . $iI . '\');return false;';
							$insertImages .= $iI;
						}
						else
						{
							$insertJS = 'insertCode(\'' . (file_exists($dir.'thumb/thumb-'.$file) ? 'thumb' : 'img') . '\', \'center\', \'' . (file_exists($dir.'thumb/thumb-'.$file) ? $fileDir.'thumb/thumb-'.$file : $fileDir.$file) . '\');';
							$insertImages .= file_exists($dir.'thumb/thumb-'.$file) ? '[thumb]' . $fileDir.'thumb/thumb-'.$file . '[/thumb]' : '[img]' . $fileDir.$file . '[/img]';
							$insertObjectS = file_exists($dir.'thumb/thumb-'.$file) ? '[thumb]' . $fileDir.'thumb/thumb-'.$file . '[/thumb]' : '[img]' . $fileDir.$file . '[/img]';
						}
							$deistv .= '<img src="media/uploader/view.png" border="0" class="_pointer icon" alt="' . _VIEW . '" title="' . _VIEW . '" onclick="CaricaFoto(\'' . $fileDir.$file . '\');" />';
							$deistv .= '<img src="media/uploader/paste.png" border="0" class="_pointer icon" alt="' . _IMAGE_PAST . '" title="' . _IMAGE_PAST . '" onclick="' . $insertJS . '" />';
                        break;	

                    case 'exe':
                    case 'rar':
                    case 'zip':
                    case '7z':
                        $deistv .= '<a href="' . $fileDir.$file . '"><img src="media/uploader/save.png" border="0" class="_pointer icon" alt="' . _DOWNLOAD_FILE . '" /></a>';
                        break;

                    case 'mp3':
                    case 'wav':
                    case 'ogg':
						$insertJS = $isHTML ? 'tinyMCE.execCommand(\'mceInsertContent\', false, \'[audio]' . $fileDir.$file . '[/audio]\');return false;' : 'insertCode(\'audio\', \'\', \'' . $fileDir.$file . '\');';
						$deistv .= '<img src="media/filetypes/music.png" border="0" class="_pointer icon" alt="Вставить аудиофайл" title="Вставить аудиофайл" onclick="' . $insertJS . '" />';
						$deistv .= '<a href="' . $fileDir.$file . '"><img src="media/uploader/save.png" border="0" class="_pointer icon" alt="' . _DOWNLOAD_FILE . '" /></a>';
                        break;			

                    case 'avi':
                    case 'flv':
                    case 'wmv':
						$insertJS = $isHTML ? 'tinyMCE.execCommand(\'mceInsertContent\', false, \'[video]' . $fileDir.$file . '[/video]\');return false;' : 'insertCode(\'video\', \'\', \'' . $fileDir.$file . '\');';
						$deistv .= '<img src="media/filetypes/video.png" border="0" class="_pointer icon" alt="Вставить видео" title="Вставить видео" onclick="' . $insertJS . '" />';
						$deistv .= '<a href="' . $fileDir.$file . '"><img src="media/uploader/save.png" border="0" class="_pointer icon" alt="' . _DOWNLOAD_FILE . '" /></a>';
                        break;	

                }

                $insertObject .= $insertObjectS;
				
				
				$insertJS = $isHTML ? 'tinyMCE.execCommand(\'mceInsertContent\', false, \'' . $insertObjectS . '\');return false;' : 'insertIN(\'' . $insertObjectS . '\', \'' . $module . '\')';
                $deistv .= '<img src="media/uploader/insertall.png" border="0" class="_pointer icon" alt="' . _ATTACH_PAST . '" title="' . _ATTACH_PAST . '" onclick="' . $insertJS . '" />';
                $deistv .= '<img src="/media/edit/cross.png" border="0" class="_pointer icon" alt="Удалить файл" title="' . _DELETE_FILE . '" onclick="fileList(\'' . $id . '\', \'' . $module . '\', \'delete\', \'' . $file . '\');" />';

                echo '
                <tr>
                <td><img src="/media/filetypes/' . $icon . '" border="0" class="icon" width="16" height="16" alt="' . $file . '" />' . $file . ' ' . (isset($dbArray[$fileDir.$file]) ? '<b><font color="green">[db]</font>' : '') . '</td>
                <td align="center">' . formatfilesize(filesize($dir.$file), true) . '</td>
                <td align="right">' . $deistv . '</td>
                </tr>
                ';		
            }
        }
        closedir($dh);

        if(!isset($yesFile))
        {
            echo '<tr><td colspan="3">' . _FILES_NOTFOUND . '</td></tr>';
        }
		
		
		$insertJS1 = $isHTML ? 'tinyMCE.execCommand(\'mceInsertContent\', false, \'' . $insertObject . '\');return false;' : 'insertIN(\'' . $insertObject . '\', \'' . $module . '\')';
		$insertJS2 = $isHTML ? 'tinyMCE.execCommand(\'mceInsertContent\', false, \'' . $insertImages . '\');return false;' : 'insertIN(\'' . $insertImages . '\', \'' . $module . '\')';
        echo '<tr>
        <th colspan="2" ><div align="left">' . _FAST_ACTIONS . '</div></th>
        <th>
		<div  align="right">
        <img src="media/uploader/insertall.png" border="0" class="_pointer icon" alt="' . _INSERTALL_ATTACH . '" title="' . _INSERTALL_ATTACH . '" onclick="' . $insertJS1 . '" />
        <img src="media/uploader/insertimages.png" border="0" class="_pointer icon" alt="' . _INSERTALL_IMAGE . '" title="' . _INSERTALL_IMAGE . '" onclick="' . $insertJS2 . '" />
        <img src="media/uploader/trash.png" border="0" class="_pointer icon" alt="' . _CLEAR_PATH . '" title="' . _CLEAR_PATH . '" onclick="fileList(\'' . $id . '\', \'' . $module . '\', \'truncate\', \'\');" />
        <img src="media/uploader/reload.png" border="0" class="_pointer icon" alt="' . _UPDATEFILELIST . '" title="' . _UPDATEFILELIST . '" onclick="fileList(\'' . $id . '\', \'' . $module . '\', \'ok\', \'\');" />
		</div>
        </th>
        </tr>
        </table>
        ';
    }
}

/*

					case 'doc':
                        break;

                    case 'psd':
                        break;	

                    case 'pdf':
                        break;


                    case 'txt':
                        break;			

                    case 'phps':
                        break;		

                    case 'html':
                    case 'htm':
                        break;

						*/
function loginList()
{
    global $db;
    $query = filter(utf_decode($_REQUEST['query']), 'nick');
    $input = filter($_REQUEST['input'], 'a');


    $user = $db->query("SELECT nick FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick LIKE '%" . $db->safesql($query) . "%'");

    if($db->numRows($user) > 0 && $query)
    {
        echo '<ul>';

        while($rows = $db->getRow($user)) 
        {
            echo '<li><a href="javascript:void(0)" onclick="gid(\'' . $input . '\').value = \'' . $rows['nick'] . '\'; showhide(\'' . $input . '\');">' . $rows['nick'] . '</a>';
        }

        echo '</ul>';
    }
	else
	{
        echo '<ul>';
        echo '<li>'._NO_RESULTS;
        echo '</ul>';
	}
}

function getTranslit()
{
    $query = ($_REQUEST['query']);
    echo translit($query);
}

function getCatByModule()
{
    global $core;
    $module = filter($_POST['mod'], 'module');
    $cats_arr = $core->aCatList($module);
    echo "<option value=\"\">Без категории</option>";
    foreach ($cats_arr as $cid => $name) 
    {
        echo '<option value="' . $cid . '">' . $name . '</option>';
    }
}

function blockStatus()
{
    global $core, $db;
    if($core->auth->isAdmin)
    {
        $id = intval($_REQUEST['id']);
        $to = intval($_REQUEST['to']);
        delcache('plugins');
        $db->query("UPDATE `" . DB_PREFIX . "_plugins` SET `active` = '" . $to . "' WHERE `id` =" . $id . " LIMIT 1 ;");
        delcache('plugins');
        echo 'ok';
    }
}

function userDeleteBlock()
{
    global $core, $db;
    if($core->auth->isAdmin)
    {
        $id = intval($_REQUEST['id']);
        $db->query("DELETE FROM `" . DB_PREFIX . "_plugins` WHERE `id` = " . $id . " LIMIT 1");
        delcache('plugins');
        echo 'ok';
    }
}

function friendsAjax()
{
    global $core, $db, $op;
    $uid = intval($_REQUEST['uid']);
    if($core->auth->isUser && $uid != 0 && $uid != $core->auth->user_id)
    {
        switch($op)
        {
            case 'add':
                $addQ = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_user_friends` WHERE (who_invite='" . $core->auth->user_id . "' and whom_invite='" . $uid . "') OR (whom_invite='" . $core->auth->user_id . "' and who_invite='" . $uid . "')");
                if($db->numRows($addQ) > 0)
                {
                    $addR = $db->getRow($addQ);
                    if($addR['who_invite'] == $core->auth->user_id)
                    {
                        echo _FR_ERR1;
                    }
                    else
                    {
                        echo _FR_ERR2;
                    }
                }
                else
                {
                    $db->query("INSERT INTO `" . USER_DB . "`.`" . USER_PREFIX . "_user_friends` ( `who_invite` , `whom_invite` , `confirmed` ) VALUES ('" . $core->auth->user_id . "', '" . $uid . "', '0');");
                    echo _FR_INF1;
                }
                break;

            case 'delete':
                $db->query("DELETE FROM `" . USER_DB . "`.`" . USER_PREFIX . "_user_friends` WHERE (who_invite='" . $core->auth->user_id . "' and whom_invite='" . $uid . "') OR (whom_invite='" . $core->auth->user_id . "' and who_invite='" . $uid . "') LIMIT 1 ");
                echo _FR_INF2;
                break;

            case 'accept':
                $db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_user_friends` SET `confirmed` = '1' WHERE `who_invite` =" . $uid . " AND `whom_invite` =" . $core->auth->user_id . " AND `confirmed` =0 LIMIT 1;");
				user_points($uid, 'add_friend');
				user_points($core->auth->user_id, 'add_friend');
                echo _FR_INF3;
                break;
        }
    }
}


function fullAjax()
{
    header('Content-type: application/x-javascript; charset=utf-8');
    require ROOT . 'etc/fullajax.config.php';
    $blockLinks = '';
    if(trim($fullajax['blockLinks']) != '')
    {
        $blockLinks = ", '".implode("', '", explode(',', $fullajax['blockLinks']))."'";
    }
    $full = "SRAX.Default.loader = '" . $fullajax['loader'] . "';\n";
    $full .= "SRAX.showLoading = function(show, obj){
    var s = obj ? obj.style : 0;
    if (s)
    {\n";
    if($fullajax['loaderStatic'] == 0) 
    {
        $full .= "		var scrollTop = document.documentElement.scrollTop;
        if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) 
        {
        clHeight = document.documentElement.clientHeight;
        }
        else if (document.body && (document.body.clientWidth || document.body.clientHeight)) 
        {
        clHeight = document.body.clientHeight;
        }

        topOffset = (scrollTop + (clHeight/3))+'px';
        s.top = topOffset;\n";
    }
    $full .= "
    if (show) 
    {
    if (s.visibility) s.visibility = 'visible'; else s.display = 'block';
    }
    else
    {
    if (s.visibility) s.visibility = 'hidden'; else s.display = 'none';
    }
    }
    };\n";
    $full .= "
    SRAX.Filter.on('beforewrap', function(ops){
    return !SRAX.isXss(ops.el.href || ops.el.action);
    });
    ";

    $full .= "
    SRAX.Filter.add ({id:'fullAjax', url:'/', method:'post', params:'fullajax=yes'}).add({url:['/profile/login', '/ajax', 'ajax.php', '/forum', '/profile/logout', '" . ADMIN . "', 'files'" . $blockLinks . "], type:'nowrap'});
    SRAX.linkEqual[':ax:fullAjax:'] = '" . $fullajax['replace'] . "'; 
    SRAX.linkEqual['anchor:fullAjax:'] = '@'; 
    SRAX.onReady(SRAX.directLink);
    SRAX.Default.ANCHOR.RECURSIVE = 1;
    SRAX.Default.SCRIPT_SRC_REPEAT_APPLY = 0;
    SRAX.Html.onall('beforerequest', function(ops) {
    var p = ops.options.params;
    ops.options.params = (p ? p + '&': '') + 'fullajax=yes';
    });

    ";

    if($fullajax['storage'] == 1)
    {
        $full .= "SRAX.Default.STORAGE_SWF = 'usr/js/Storage.swf';\n";
    }

    echo $full."\n".htmlspecialchars_decode($fullajax['freeCode'], ENT_QUOTES);
}
