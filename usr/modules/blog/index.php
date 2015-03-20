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


if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}

loadConfig('blog');

function menu($path = '')
{
global $core;

		$core->tpl->open('blog_menu');
		$core->tpl->loadFile('blog/blog.menu');
		$core->tpl->setVar('B_ALL', ($path == 1 ? '<strong>'._BLOG_ALL.'</strong>' : _BLOG_ALL));
		$core->tpl->setVar('B_THEMES', ($path == 2 ? '<strong>'._BLOG_THEME_BLOG.'</strong>' : _BLOG_THEME_BLOG));
		$core->tpl->setVar('B_PERS', ($path == 3 ? '<strong>'._BLOG_PERSONAL_BLOG.'</strong>' : _BLOG_PERSONAL_BLOG));
		$core->tpl->setVar('B_LIST', ($path == 4 ? '<strong>'._BLOG_LIST.'</strong>' : _BLOG_LIST));
		$core->tpl->setVar('B_WRITE', ($path == 5 ? '<strong>'._BLOG_WRITE2.'</strong>' : _BLOG_WRITE2));
		$core->tpl->setVar('B_CREATE', ($path == 6 ? '<strong>'._BLOG_CREATE2.'</strong>' : _BLOG_CREATE2));
		$core->tpl->setVar('B_MY', ($path == 7 ? '<strong>'._BLOG_MY.'</strong>' : _BLOG_MY));
		$core->tpl->setVar('B_MY_URL', 'blog/user/' . $core->auth->user_id);	
		$core->tpl->end();
		$core->tpl->close();
}

switch(isset($url[1]) ? $url[1] : null) 
{
		
	case 'write':
		set_title(array(_BLOGS, _BLOG_WRITE));		
		menu(5);
		if($core->auth->isUser)
		{
			$textArea = bb_area('postText', '', 5, 'textarea', false, true);
			$query = $db->query("SELECT id, title FROM `" . DB_PREFIX . "_blogs`");
			$blogList = '<option value="0">'._BLOG_PERSONAL.'</option><option disabled>---------</option>';
			while($blogs = $db->getRow($query)) 
			{
				$blogList .= '<option value="' . $blogs['id'] . '" ' . (isset($url[2]) && $url[2] == $blogs['id'] ? 'selected' : '') . '>' . $blogs['title'] . '</option>';
			}
			$core->tpl->open('blog_add');
			$core->tpl->loadFile('blog/add.post');
			$core->tpl->setVar('BLOGS', $blogList);
			$core->tpl->setVar('TITLE', '');
			$core->tpl->setVar('TAGS', '');
			$core->tpl->setVar('NOTE', '');
			$core->tpl->setVar('ACTIONS', '');
			$core->tpl->setVar('BLOGCHOOSE', '');
			$core->tpl->setVar('TEXTAREA', $textArea);
			$core->tpl->end();
			$core->tpl->close();
		}
		else
		{
			$core->tpl->info(_BLOG_NOTIFY1);
		}
		break;
		
	case 'savePost':
		if($core->auth->isUser)
		{
			$blog = isset($_POST['blog']) ? intval($_POST['blog']) : '';
			$title = isset($_POST['title']) ? filter($_POST['title'], 'title') : '';
			$postText = isset($_POST['postText']) ? filter($_POST['postText']) : '';
			$tags = isset($_POST['tags']) ? filter($_POST['tags'], 'a') : '';
			$note = !empty($_POST['note']) ? 1 : 0;
			$pid = !empty($_POST['pid']) ? intval($_POST['pid']) : '';						
			$blogCheck = $db->query("SELECT altname FROM `" . DB_PREFIX . "_blogs` WHERE id = '" . $blog . "' LIMIT 1");
			if(!empty($title) && !empty($postText) && ($db->numRows($blogCheck) == 1 || $blog == 0))
			{
				if($blog != 0) $blogInfo = $db->getRow($blogCheck);
				
				if($pid != 0)
				{
					list($puid) = $db->fetchRow($db->query("SELECT uid FROM ".DB_PREFIX."_blog_posts WHERE id = '" . $pid . "' LIMIT 1"));
					if(empty($puid)) location('blog');
					$isAdmin = (($core->auth->isUser && $puid == $core->auth->user_info['id']) || ($core->auth->isUser && eregStrt(','.$core->auth->user_info['id'].',', $blogInfo['admins'])) || $core->auth->isAdmin) ? true : false;
				}

				if(empty($pid) || $pid == 0)
				{
					if($blog != 0 && $blog_conf['preModer'] == 1 && $note == 0)
					{
					
					}
					else
					{						
						menu(5);						
						$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_blog_posts` WHERE title = '" . $title . "' AND bid = '" . $blog . "'");
						if($db->numRows($query) == 0)
						{
							$t = time();
							$db->query("INSERT INTO `" . DB_PREFIX . "_blog_posts` (`bid` ,`title` ,`text` ,`date` ,`tags` ,`uid` ,`status` ) VALUES ('" . $blog . "', '" . $db->safesql(processText($title)) . "', '" . $db->safesql(parseBB(processText($postText))) . "', '" . $t . "', '" . $tags . "', '" . $core->auth->user_info['id'] . "', '" . ($note == 1 ? 2 : 1) . "');");
							$core->tpl->info(_BLOG_POST_ADD_OK.' <a href="' . ($blog == 0 ? 'blog/user/' . $core->auth->user_info['id'] : 'blog/view/'.$blogInfo['altname']) . '" title="'._BLOG_MOVE_TO_BLOG.'">'._BLOG_SSELECTED_BLOG.'</a>. <a href="blog/write" title="'._BLOG_WRITE.'">'._BLOG_WANT_ADD_MORE.'</a>');
							if($blog != 0)
							{
								$db->query("UPDATE `" . DB_PREFIX . "_blogs` SET `posts` = `posts`+1,`lastUpdate` = '" . $t . "' WHERE `id` =" . $blog . ";");
							}
						}
						else
						{
							$core->tpl->info(_BLOG_NOTIFY3, 'warning');
						}
					}
				}
				elseif(isset($_POST['delete']) && $isAdmin == true)
				{
					$db->query("DELETE FROM `" . DB_PREFIX . "_blog_posts` WHERE `id` = ".$pid);
					if($blog != 0)
					{
						$db->query("UPDATE `" . DB_PREFIX . "_blogs` SET `posts` = `posts`-1 WHERE `id` =" . $blog . ";");
					}
					menu();
					$core->tpl->info(_BLOG_POST_DELETE_OK.' <a href="blog/" title="'._BLOG_TO_HOME.'">'._BLOG_TO_HOME.'</a>');					
				}
				elseif($isAdmin == true)
				{
					if($blog != 0 && $blog_conf['preModer'] == 1 && $note == 0)
					{
					
					}
					else
					{						
						menu();
						$db->query("UPDATE `" . DB_PREFIX . "_blog_posts` SET `title` = '" . $db->safesql(processText($title)) . "', `text` = '" . $db->safesql(parseBB(processText($postText))) . "', `tags` = '" . $db->safesql(processText($tags)) . "', `status` = '" . ($note == 1 ? 2 : 1) . "' WHERE `id` =" . $pid . ";");
						$core->tpl->info(_BLOG_POST_REFRESH_OK.' <a href="blog/read/' . $pid . '" title="'._BLOG_VIEW_POST2.'">'._BLOG_VIEW_POST2.'</a>');

					}
				}
				else
				{
					location('blog');
				}
			}
			else
			{				
				menu(5);
				$core->tpl->info(_BLOG_NOTIFY2, 'warning');
			}
		}
		else
		{
			location();
		}
		break;
		
	case 'user':
		$uid = intval($url[2]);
		$num = $blog_conf['postsPerPage'];
		$page = init_page();
		$cut = ($page-1)*$num;
		
		if($core->auth->isUser && $core->auth->user_info['id'] == $uid)
		{
			$admin = 1;
			$userInfo = $core->auth->user_info;
			$where = '';
		}
		else
		{
			$admin = 0;
			$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_users` WHERE id = '" . $uid . "' LIMIT 1");
			$where = "AND status='1'";
			if($db->numRows($query) == 1)
			{
				$userInfo = $db->getRow($query);
			}
			else
			{
				location('blog');
			}
		}		
		menu($admin == 1 ? 7 : 0);
		set_title(array(_BLOGS, _BLOG_FROM.' '.$userInfo['nick']));
		$core->tpl->title(_BLOG_FROM.' '.$userInfo['nick']);
		
		$queryP = $db->query("SELECT * FROM `" . DB_PREFIX . "_blog_posts` WHERE bid = '0' AND uid= '" . $userInfo['id'] . "' " . $where . " ORDER BY date DESC LIMIT " . $cut . ", ".$num);
		if($db->numRows($queryP) > 0)
		{
			while($posts = $db->getRow($queryP)) 
			{
				$isAdmin = (($core->auth->isUser && $core->auth->user_info['id'] == $posts['uid']) || $core->auth->isAdmin) ? true : false;
				$tag_list = explode(',', $posts['tags']);
				foreach($tag_list as $tag) 
				{
					$tag = trim($tag);
					$tags[] = '<a href="blog/tags/'.urlencode($tag).'" title="' . $tag . '">'.$tag.'</a>';
				}
				
				$core->tpl->loadFile('blog/view.post');
				$core->tpl->setVar('BLOG_NAME', '<a href="blog/user/' . $userInfo['id']. '" title="'._BLOG_MOVE_TO_BLOG.'">'._BLOG_FROM.' '. $userInfo['nick']. '</a>');
				$core->tpl->setVar('POST_TITLE', '<a href="blog/read/' . $posts['id'] . '" title="'._BLOG_READ_POST.'">'.$posts['title'].'</a>');
				$core->tpl->setVar('TEXT', $core->bbDecode($posts['text']));
				$core->tpl->setVar('STATUS', ($posts['status'] == 0 ? '[ <font color="red">'._BLOG_ON_SMODERATE.'</font> ] ' : ($posts['status'] == 2 ? '[ <font color="blue">'._BLOG_SDRAFT.'</font> ] ' : '')));
				$core->tpl->setVar('TAGS', implode(', ', $tags));
				$core->tpl->setVar('ADMIN', $isAdmin ? '<a href="blog/editPost/' . $posts['id'] . '" title="'._BLOG_EDIT_POST.'">Редактировать</a>' : '');
				$core->tpl->setVar('RATING', '<a href="javascript:void(0)" title="'._BLOG_POST_VOTE.'" onclick="' . ($core->auth->isUser ? ($core->auth->user_info['id'] != $posts['uid'] ? (!eregStrt(','.$core->auth->user_info['id'].',', $posts['ratingUsers']) ? 'blogRating(\'' . $posts['id'] . '\', \'rate_' . $posts['id'] . '\')' : 'alert(\''._BLOG_ALREADY_VOTED.'\')') : 'alert(\''._BLOG_YOURSELF_VOTED.'\');') : 'alert(\''._BLOG_AUTHORIZED_VOTING_ONLY.'\');') . '">Рэйтинг</a> <span id="rate_' . $posts['id'] . '" class="blog_postRating">' . ($posts['rating'] > 0 ? '+' : '') . $posts['rating'] . '</span>');
				$core->tpl->setVar('USER', '<a href="profile/'.$userInfo['nick'].'" title="'._BLOG_VIEW_PROFILE.'">'.$userInfo['nick'].'</a>');
				$core->tpl->setVar('COMMENTS', '<a href="blog/read/' . $posts['id'] . '#comm" title="'._BLOG_VIEW_COMMENTS.'">'.$posts['comments'].'</a>');
				$core->tpl->setVar('DATE', formatDate($posts['date']));
				$core->tpl->end();
				unset($tags, $tag_list);
			}
			list($numPosts) = $db->fetchRow($db->query("SELECT Count(id) FROM ".DB_PREFIX."_blog_posts WHERE bid = '0' AND uid= '" . $userInfo['id'] . "' " . $where));
			$core->tpl->pages($page, $num, $numPosts, 'blog/user/' . $userInfo['id'] . '/{page}');

		}
		else
		{
			$core->tpl->info(_BLOG_EMPTY);
		}
		break;
		
	case 'create':
		set_title(array(_BLOGS, _BLOG_CREATE));		
		menu(6);		
		if($core->auth->isUser)
		{
			$core->tpl->open('blog_create');
			$core->tpl->loadFile('blog/add.blog');
			$core->tpl->setVar('TITLE', '');
			$core->tpl->setVar('ALTNAME', '');
			$core->tpl->setVar('DESCRIPTION', '');
			$core->tpl->setVar('AVATAR_REPLACE', '');
			$core->tpl->end();
			$core->tpl->close();
		}
		else
		{
			$core->tpl->info(_BLOG_NOTIFY4);
		}
		break;
		
	case 'saveBlog':
		if($core->auth->isUser)
		{
			$bid = isset($_POST['bid']) ? intval($_POST['bid']) : '';
			$title = filter($_POST['title'], 'title');
			$description = filter($_POST['description']);
			$altname = !empty($_POST['altname']) ? translit(filter($_POST['altname'], 'a')) : translit($title);			
			if(!empty($title) && !empty($description) && !empty($altname))
			{
				if(empty($bid))
				{
						
					menu(6);						
					$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_blogs` WHERE title = '" . $title . "' OR altname = '" . $db->safesql($altname) . "'");
					if($db->numRows($query) == 0)
					{
						$avatar = '';
						if(!empty($_FILES['blogAvatar']['name']))
						{
							if($foo = new Upload($_FILES['blogAvatar']))
							{
								$foo->file_new_name_body = 'blogAvatar_'.$altname;
								$foo->image_resize = true;
								$foo->image_x = 50;
								$foo->image_ratio_y = true;
								$foo->Process(ROOT.'files/blog');
								
								if ($foo->processed) 
								{
									$avatar = 'files/blog/blogAvatar_'.$altname.'.'.$foo->file_dst_name_ext;
									$foo->Clean();
								}
							}
						}
						$db->query("INSERT INTO `" . DB_PREFIX . "_blogs` (`title` ,`altname` ,`description` ,`avatar` ,`date` ,`admins`) VALUES ('" . $db->safesql(processText($title)) . "', '" . $altname . "', '" . $db->safesql(processText($description)) . "', '" . $avatar . "', '" . time() . "', '," . $core->auth->user_info['id'] . ",');");
						$core->tpl->info(_BLOG_BLOG_CREATE_OK.' <a href="blog/view/' . $altname . '" title="'._BLOG_VIEW_BLOG.'">'._BLOG_VIEW_BLOG2.'</a> '._BLOG_S_OR_NOW.' <a href="blog/write" title="'._BLOG_WRITE.'">'._BLOG_SLEAVE_POST.'</a> '._BLOG_IN_THIS);
					}
					else
					{
						$core->tpl->info(_BLOG_NOTIFY5, 'warning');
					}
				}
				else
				{
					
					$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_blogs` WHERE id = '" . $bid . "' LIMIT 1");
					if($db->numRows($query) == 1)
					{
						$blog = $db->getRow($query);
						$isAdmin = ($core->auth->isAdmin || eregStrt(','.$core->auth->user_info['id'].',', $blog['admins'])) ? true : false;
						if($isAdmin == true)
						{
							if(isset($_POST['deleteAvatar']))
							{
								@unlink($blog['avatar']);
								$avatar = '';
							}
							
							if(!empty($_FILES['blogAvatar']['name']))
							{
								@unlink($blog['avatar']);
								
								if($foo = new Upload($_FILES['blogAvatar']))
								{
									$foo->file_new_name_body = 'blogAvatar_'.$altname;
									$foo->image_resize = true;
									$foo->image_x = 50;
									$foo->image_ratio_y = true;
									$foo->Process(ROOT.'files/blog');
									
									if ($foo->processed) 
									{
										$avatar = 'files/blog/blogAvatar_'.$altname.'.'.$foo->file_dst_name_ext;
										$foo->Clean();
									}
								}
							}			
							
							menu();
							$db->query("UPDATE `" . DB_PREFIX . "_blogs` SET `title` = '" . $db->safesql(processText($title)) . "', `altname` = '" . $db->safesql(processText($altname)) . "', `description` = '" . $db->safesql(processText($description)) . "'" . (isset($avatar) ? ", `avatar` = '" . $avatar . "'" : '') . " WHERE `id` =" . $blog['id'] . ";");
							$core->tpl->info(_BLOG_BLOG_SAVE_OK.' <a href="blog/view/' . $altname . '" title="'._BLOG_VIEW_BLOG.'">'._BLOG_VIEW_BLOG.'</a>.');
						}
						else
						{
							location('blog');
						}
					}
					else
					{
						location('blog');
					}
				}
			}
			else
			{				
				menu(6);
				$core->tpl->info(_BLOG_NOTIFY6, 'warning');
			}
		}
		else
		{
			location();
		}
		break;
		
	case 'list':
		$num = $blog_conf['blogsPerPage'];
		$page = init_page();
		$cut = ($page-1)*$num;		
		set_title(array(_BLOGS, _BLOG_LIST));		
		menu(4);
		$core->tpl->open('blogList');
		
		$query = $db->query("SELECT id as blogId, title, altname, description, avatar, posts, readersNum, date, lastUpdate, admins, readers, (SELECT sum(rating) FROM ".DB_PREFIX."_blog_posts WHERE bid = blogId) as sumRate FROM `" . DB_PREFIX . "_blogs` ORDER BY posts DESC LIMIT " . $cut . ", ".$num."");
		while($blog = $db->getRow($query)) 
		{
			$sumRate = empty($blog['sumRate']) ? 0 : $blog['sumRate'];
			$core->tpl->loadFile('blog/list.blog');
			$core->tpl->setVar('AVATAR', ($blog['avatar'] ? $blog['avatar'] : 'usr/tpl/' . $config['tpl'] . '/assest/images/engine/default-blog-avatar.png'));
			$core->tpl->setVar('BLOG_NAME', '<a href="blog/view/' . $blog['altname'] . '" title="'._BLOG_MOVE_TO_BLOG_VIEW.'">'.$blog['title'].'</a>');
			$core->tpl->setVar('READERS', $blog['readersNum']);
			$core->tpl->setVar('POSTS', $blog['posts']);
			$core->tpl->setVar('RATING', $blog['posts'] == 0 ? 0 : round($sumRate/$blog['posts'], 2));
			$core->tpl->setVar('LAST_UPDATE', $blog['lastUpdate'] ? formatDate($blog['lastUpdate']) : _BLOG_SNEVER);
			$core->tpl->setVar('ADMINISTRATION', $blog['posts']);
			$core->tpl->end();
		}
		$core->tpl->close();
		list($numBlogs) = $db->fetchRow($db->query("SELECT Count(id) FROM ".DB_PREFIX."_blogs"));
		$core->tpl->pages($page, $num, $numBlogs, 'blog/list/{page}');
		break;

	case 'view':
		$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_blogs` WHERE altname = '" . $url[2] . "'");
		if($db->numRows($query) == 1)
		{
			$blog = $db->getRow($query);
			set_title(array(_BLOGS, $blog['title']));
			menu();
			$qAdm = $db->query("SELECT nick FROM `" . DB_PREFIX . "_users` WHERE id IN (" . mb_substr($blog['admins'], 1, -1) . ")");
			$admins = '';
			$adminBlog = false;
			while($zAdm = $db->getRow($qAdm))
			{
				$admins .= '<span class="_userfriends"><a href="profile/'.$zAdm['nick'].'" title="'._BLOG_TO_ADMIN_PAGE.'">'.$zAdm['nick'].'</a></span>';
			}
			
			if($core->auth->isUser && eregStrt(','.$core->auth->user_info['id'].',', $blog['admins'])) $adminBlog = true;
			
			if(!empty($blog['readers']))
			{
				$readers = '';
				foreach(explode(',', $blog['readers']) as $reader)
				{
					if(!empty($reader))
					{
						$readers .= '<span class="_userfriends"><a href="profile/'.$reader.'" title="'._BLOG_TO_READER_PAGE.'">'.$reader.'</a></span>';
					}
				}
			}
			
			list($sumRate) = $db->fetchRow($db->query("SELECT sum(rating) FROM ".DB_PREFIX."_blog_posts WHERE bid = '" . $blog['id'] . "'"));
			$sumRate = empty($sumRate) ? 0 : $sumRate;
			$rating = $blog['posts'] > 0 ? round($sumRate/$blog['posts'], 2) : 0;
			$core->tpl->open('blogView');
			$core->tpl->loadFile('blog/view.blog');
			$core->tpl->setVar('AVATAR', ($blog['avatar'] ? $blog['avatar'] : 'usr/tpl/' . $config['tpl'] . '/assest/images/engine/default-blog-avatar.png'));
			$core->tpl->setVar('BLOG_NAME', '<a href="blog/view/' . $blog['altname'] . '" title="'._BLOG_MOVE_TO_BLOG_VIEW.'">'.$blog['title'].'</a>');
			$core->tpl->setVar('DESCRIPTION', $blog['description'] ? $blog['description'] : _BLOG_NO_DESCRIPTION);
			$core->tpl->setVar('READERS', $blog['readers'] ? $readers : _BLOG_NO_READERS);
			if($core->auth->isUser) $core->tpl->setVar('BECOME_READER', !eregStrt(',' . $core->auth->user_info['nick'] . ',', $blog['readers']) ? _BLOG_TO_BE_READER : _BLOG_UNSUBSCRIBE);			
			$core->tpl->setVar('ADMINS', $admins);
			$core->tpl->setVar('RATING', $rating);
			$core->tpl->setVar('EDIT', $adminBlog ? '[ <a href="blog/blogEdit/' . $blog['id'] . '">'._BLOG_SEDIT.'</a> - '._BLOG_SDELETE.' ]' : '');
			$core->tpl->setVar('ID', $blog['id']);
			$core->tpl->setVar('POSTS', $blog['posts']);
			$core->tpl->setVar('LAST_UPDATE', $blog['lastUpdate'] ? formatDate($blog['lastUpdate']) : _BLOG_SNEVER);
			$core->tpl->end();
			$core->tpl->close();
			
			
			$num = $blog_conf['postsPerPage'];
			$page = init_page();
			$cut = ($page-1)*$num;
			
			$queryP = $db->query("SELECT u.nick, b.* FROM `" . DB_PREFIX . "_blog_posts` as b LEFT JOIN `" . DB_PREFIX . "_users` as u ON (u.id = b.uid) WHERE b.bid = '" . $blog['id'] . "' ORDER BY b.date DESC LIMIT " . $cut . ", ".$num);

			if($db->numRows($queryP) > 0)
			{

				while($posts = $db->getRow($queryP)) 
				{
					$isAdmin = (($core->auth->isUser && $posts['uid'] == $core->auth->user_info['id']) || ($core->auth->isUser && eregStrt(','.$core->auth->user_info['id'].',', $blog['admins'])) || $core->auth->isAdmin || $adminBlog == true) ? true : false;
					if($posts['status'] == 1 || $isAdmin)
					{
						$tag_list = explode(',', $posts['tags']);
						foreach($tag_list as $tag) 
						{
							$tag = trim($tag);
							$tags[] = '<a href="blog/tags/'.urlencode($tag).'" title="' . $tag . '">'.$tag.'</a>';
						}
						
						$status = ($isAdmin ? ($posts['status'] == 0 ? '[ <font color="red">'._BLOG_ON_SMODERATE.'</font> ] ' : ($posts['status'] == 2 ? '[ <font color="blue">'._BLOG_SDRAFT.'</font> ] ' : '')) : '');
						
						$core->tpl->loadFile('blog/view.post');
						$core->tpl->setVar('BLOG_NAME', '<a href="blog/view/' . $blog['altname']. '" title="'._BLOG_MOVE_TO_BLOG.'">'. $blog['title']. '</a>');
						$core->tpl->setVar('POST_TITLE', '<a href="blog/read/' . $posts['id'] . '" title="'._BLOG_READ_POST.'">'.$posts['title'].'</a>');
						$core->tpl->setVar('TEXT', $core->bbDecode($posts['text']));
						$core->tpl->setVar('STATUS', $status);
						$core->tpl->setVar('RATING', '<a href="javascript:void(0)" title="'._BLOG_POST_VOTE.'" onclick="' . ($core->auth->isUser ? ($core->auth->user_info['id'] != $posts['uid'] ? (!eregStrt(','.$core->auth->user_info['id'].',', $posts['ratingUsers']) ? 'blogRating(\'' . $posts['id'] . '\', \'rate_' . $posts['id'] . '\')' : 'alert(\''._BLOG_ALREADY_VOTED.'\')') : 'alert(\''._BLOG_YOURSELF_VOTED.'\');') : 'alert(\''._BLOG_AUTHORIZED_VOTING_ONLY.'\');') . '">Рэйтинг</a> <span id="rate_' . $posts['id'] . '" class="blog_postRating">' . ($posts['rating'] > 0 ? '+' : '') . $posts['rating'] . '</span>');
						$core->tpl->setVar('TAGS', implode(', ', $tags));
						$core->tpl->setVar('ADMIN', $isAdmin ? '<a href="blog/editPost/' . $posts['id'] . '" title="'._BLOG_EDIT_POST.'">Редактировать</a>' : '');
						$core->tpl->setVar('USER', '<a href="profile/'.$posts['nick'].'" title="'._BLOG_VIEW_PROFILE.'">'.$posts['nick'].'</a>');
						$core->tpl->setVar('COMMENTS', '<a href="blog/read/' . $posts['id'] . '#comm" title="'._BLOG_VIEW_COMMENTS.'">'.$posts['comments'].'</a>');
						$core->tpl->setVar('DATE', formatDate($posts['date']));
						$core->tpl->end();
						unset($tags, $tag_list);
					}
				}
				
				list($numPosts) = $db->fetchRow($db->query("SELECT Count(id) FROM ".DB_PREFIX."_blog_posts  WHERE bid = '" . $blog['id'] . "'"));
				$core->tpl->pages($page, $num, $numPosts, 'blog/view/' . $blog['altname'] . '/{page}');
			}
			else
			{
				$core->tpl->info('Блог пуст. <a href="blog/write/' . $blog['id'] . '" title="'._BLOG_ADD_MYSELF_POST.'">'._BLOG_ADD_POST.'</a>?');
			}
		}
		else
		{
			location('blog');
		}
		break;
			
			
	default:
	case 'show':
		$num = $blog_conf['postsPerPage'];
		$page = init_page();
		$cut = ($page-1)*$num;
		
		switch(isset($url[2]) ? $url[2] : '')
		{
			default:
				$info = _BLOG_NO_POSTS_IN_BLOGS;
				$title = _BLOG_LAST_POSTS;
				$menu = '';
				$where = '';
				$query = $db->query("SELECT id, title, altname FROM `" . DB_PREFIX . "_blogs`");
				while($blog = $db->getRow($query)) $blogName[$blog['id']] = array($blog['title'], $blog['altname']);
				break;
				
			case 'all':
				$info = _BLOG_NO_POSTS_IN_BLOGS;
				$title = _BLOG_ALL_POSTS;
				$menu = 1;
				$where = '';
				$query = $db->query("SELECT id, title, altname FROM `" . DB_PREFIX . "_blogs`");
				while($blog = $db->getRow($query)) $blogName[$blog['id']] = array($blog['title'], $blog['altname']);
				break;
				
			case "thematic":
				$info = _BLOG_NO_POSTS_IN_THEME_BLOGS;
				$title = _BLOG_THEME_POSTS;
				$menu = 2;
				$where = "WHERE bid != '0'";
				$query = $db->query("SELECT id, title, altname FROM `" . DB_PREFIX . "_blogs`");
				while($blog = $db->getRow($query)) $blogName[$blog['id']] = array($blog['title'], $blog['altname']);
				break;
				
			case "personal":
				$info = _BLOG_NO_POSTS_IN_PERSONAL_BLOGS;
				$title = _BLOG_POSTS_FROM_PERSONAL_BLOGS;
				$menu = 3;
				$where = "WHERE bid = '0'";
				break;
		}
		
		set_title(array(_BLOGS, $title));	
		menu($menu);
		$queryP = $db->query("SELECT u.nick, b.* FROM `" . DB_PREFIX . "_blog_posts` as b LEFT JOIN `" . DB_PREFIX . "_users` as u ON (u.id = b.uid) " . $where . " ORDER BY b.date DESC LIMIT " . $cut . ", ".$num."");
		if($db->numRows($queryP) > 0)
		{
			while($posts = $db->getRow($queryP)) 
			{
				$blogTitle = $posts['bid'] == 0 ? '<a href="blog/user/' . $posts['uid'] . '" title="'._BLOG_VIEW_BLOG.'">'._BLOG_FROM.' '.$posts['nick'].'</a>' : '<a href="blog/view/' . $blogName[$posts['bid']][1] . '" title="'._BLOG_VIEW_BLOG.'">' . $blogName[$posts['bid']][0] . '</a>';
				$tag_list = explode(',', $posts['tags']);
				foreach($tag_list as $tag) 
				{
					$tag = trim($tag);
					$tags[] = '<a href="blog/tags/'.urlencode($tag).'" title="' . $tag . '">'.$tag.'</a>';
				}

				$isAdmin = (($core->auth->isUser && $posts['uid'] == $core->auth->user_info['id']) || $core->auth->isAdmin) ? true : false;
				
				$status = ($core->auth->isUser && $posts['uid'] == $core->auth->user_info['id'] ? ($posts['status'] == 0 ? '[ <font color="red">'._BLOG_ON_SMODERATE.'</font> ] ' : ($posts['status'] == 2 ? '[ <font color="blue">'._BLOG_SDRAFT.'</font> ] ' : '')) : '');
				
				$core->tpl->loadFile('blog/view.post');
				$core->tpl->setVar('BLOG_NAME', $blogTitle);
				$core->tpl->setVar('POST_TITLE', '<a href="blog/read/' . $posts['id'] . '" title="'._BLOG_READ_POST.'">'.$posts['title'].'</a>');
				$core->tpl->setVar('TEXT', $core->bbDecode($posts['text']));
				$core->tpl->setVar('STATUS', $status);
				$core->tpl->setVar('RATING', '<a href="javascript:void(0)" title="'._BLOG_POST_VOTE.'" onclick="' . ($core->auth->isUser ? ($core->auth->user_info['id'] != $posts['uid'] ? (!eregStrt(','.$core->auth->user_info['id'].',', $posts['ratingUsers']) ? 'blogRating(\'' . $posts['id'] . '\', \'rate_' . $posts['id'] . '\')' : 'alert(\''._BLOG_ALREADY_VOTED.'\')') : 'alert(\''._BLOG_YOURSELF_VOTED.'\');') : 'alert(\''._BLOG_AUTHORIZED_VOTING_ONLY.'\');') . '">Рэйтинг</a> <span id="rate_' . $posts['id'] . '" class="blog_postRating">' . ($posts['rating'] > 0 ? '+' : '') . $posts['rating'] . '</span>');
				$core->tpl->setVar('TAGS', implode(', ', $tags));
				$core->tpl->setVar('ADMIN', $isAdmin ? '<a href="blog/editPost/' . $posts['id'] . '" title="'._BLOG_EDIT_POST.'">Редактировать</a>' : '');
				$core->tpl->setVar('USER', '<a href="profile/'.$posts['nick'].'" title="._BLOG_VIEW_PROFILE.">'.$posts['nick'].'</a>');
				$core->tpl->setVar('COMMENTS', '<a href="blog/read/' . $posts['id'] . '#comm" title="'._BLOG_VIEW_COMMENTS.'">'.$posts['comments'].'</a>');
				$core->tpl->setVar('DATE', formatDate($posts['date']));
				$core->tpl->end();
				unset($tags, $tag_list);
			}
			
			list($numPosts) = $db->fetchRow($db->query("SELECT Count(id) FROM ".DB_PREFIX."_blog_posts " . $where));
			$core->tpl->pages($page, $num, $numPosts, 'blog/show/' . (isset($url[2]) ? $url[2] : '') . '/{page}');
		}
		else
		{
			$core->tpl->info($info);
		}
		break;
		
		
	case 'read':
		$postId = intval($url[2]);
		$queryP = $db->query("SELECT bp.*, b.id as blogid, b.title as btitle, b.altname as balt FROM `" . DB_PREFIX . "_blog_posts` as bp LEFT JOIN `" . DB_PREFIX . "_blogs` as b on (bp.bid = b.id) WHERE bp.id = '" . $postId . "' LIMIT 1");
		if($db->numRows($queryP) > 0)
		{
			$posts = $db->getRow($queryP);
			list($nick) = $db->fetchRow($db->query("SELECT nick FROM `" . DB_PREFIX . "_users` WHERE id = " . $posts['uid'] . " LIMIT 1"));

			set_title(array(_BLOGS, _BLOG_VIEW_POST, $posts['title']));
			menu();
			
			$blogTitle = $posts['bid'] == 0 ? '<a href="blog/user/' . $posts['uid'] . '" title="'._BLOG_VIEW_BLOG.'">'._BLOG_FROM.' '.$nick.'</a>' : '<a href="blog/view/' . $posts['balt'] . '" title="'._BLOG_VIEW_BLOG.'">' . $posts['btitle'] . '</a>';
			$tag_list = explode(',', $posts['tags']);
			foreach($tag_list as $tag) 
			{
				$tag = trim($tag);
				$tags[] = '<a href="blog/tags/'.urlencode($tag).'" title="' . $tag . '">'.$tag.'</a>';
			}

			$isAdmin = (($core->auth->isUser && $posts['uid'] == $core->auth->user_info['id']) || $core->auth->isAdmin) ? true : false;
			
			$status = ($core->auth->isUser && $posts['uid'] == $core->auth->user_info['id'] ? ($posts['status'] == 0 ? '[ <font color="red">'._BLOG_ON_SMODERATE.'</font> ] ' : ($posts['status'] == 2 ? '[ <font color="blue">'._BLOG_SDRAFT.'</font> ] ' : '')) : '');
				
			$core->tpl->loadFile('blog/view.post');
			$core->tpl->setVar('BLOG_NAME', $blogTitle);
			$core->tpl->setVar('POST_TITLE', '<a href="blog/read/' . $posts['id'] . '" title="'._BLOG_READ_POST.'">'.$posts['title'].'</a>');
			$core->tpl->setVar('TEXT', $core->bbDecode($posts['text']));
			$core->tpl->setVar('STATUS', $status);
			$core->tpl->setVar('RATING', '<a href="javascript:void(0)" title="'._BLOG_POST_VOTE.'" onclick="' . ($core->auth->isUser ? ($core->auth->user_info['id'] != $posts['uid'] ? (!eregStrt(','.$core->auth->user_info['id'].',', $posts['ratingUsers']) ? 'blogRating(\'' . $posts['id'] . '\', \'rate_' . $posts['id'] . '\')' : 'alert(\''._BLOG_ALREADY_VOTED.'\')') : 'alert(\''._BLOG_YOURSELF_VOTED.'\');') : 'alert(\''._BLOG_AUTHORIZED_VOTING_ONLY.'\');') . '">Рэйтинг</a> <span id="rate_' . $posts['id'] . '" class="blog_postRating">' . ($posts['rating'] > 0 ? '+' : '') . $posts['rating'] . '</span>');
			$core->tpl->setVar('TAGS', implode(', ', $tags));
			$core->tpl->setVar('ADMIN', $isAdmin ? '<a href="blog/editPost/' . $posts['id'] . '" title="'._BLOG_EDIT_POST.'">Редактировать</a>' : '');
			$core->tpl->setVar('USER', '<a href="profile/'.$nick.'" title="._BLOG_VIEW_PROFILE.">'.$nick.'</a>');
			$core->tpl->setVar('COMMENTS', '<a href="blog/read/' . $posts['id'] . '#comm" title="'._BLOG_VIEW_COMMENTS.'">'.$posts['comments'].'</a>');
			$core->tpl->setVar('DATE', formatDate($posts['date']));
			$core->tpl->end();
			
			if($blog_conf['comments'] == 1)
			{
				show_comments('blog', $posts['id'], $blog_conf['comperpage']);
			}
		}
		break;
		
	case 'editPost':
		$postId = intval($url[2]);
		
		$queryP = $db->query("SELECT * FROM `" . DB_PREFIX . "_blog_posts` WHERE id = '" . $postId . "' LIMIT 1");
		if($db->numRows($queryP) > 0 && $core->auth->isUser)
		{
			set_title(array(_BLOGS, _BLOG_EDIT_POST));
			
			menu();
			
			$posts = $db->getRow($queryP);
			$isAdmin = (($core->auth->isUser && $posts['uid'] == $core->auth->user_info['id']) || ($core->auth->isUser && eregStrt(','.$core->auth->user_info['id'].',', $blog['admins'])) || $core->auth->isAdmin) ? true : false;
			if($isAdmin == true)
			{
				$textArea = bb_area('postText', html2bb($posts['text']), 10, 'textarea', false, true);
				$query = $db->query("SELECT id, title FROM `" . DB_PREFIX . "_blogs`");
				$blogList = '<option value="0">'._BLOG_PERSONAL.'</option><option disabled>---------</option>';
				while($blogs = $db->getRow($query)) 
				{
					$blogList .= '<option value="' . $blogs['id'] . '" ' . ($posts['bid'] == $blogs['id'] ? 'selected' : '') . '>' . $blogs['title'] . '</option>';
				}
				
				$core->tpl->open('blog_add');
				$core->tpl->loadFile('blog/add.post');
				$core->tpl->setVar('BLOGS', $blogList);
				$core->tpl->setVar('TEXTAREA', $textArea);
				$core->tpl->setVar('TITLE', prepareTitle($posts['title']));
				$core->tpl->setVar('BLOGCHOOSE', 'disabled');
				$core->tpl->setVar('TAGS', $posts['tags']);
				$core->tpl->setVar('NOTE',  $posts['status'] == 2 ? 'checked' : '');
				$core->tpl->setVar('ACTIONS', '<input name="delete" type="submit" value="'._BLOG_DELETE_POST.'"  /><input type="hidden" name="pid" value="' . $postId . '" /><hr />'._BLOG_POST_STATUS.': <strong>'.($posts['status'] == 1 ? '<font color="green">'._BLOG_ACTIVE.'</font>' : ($posts['status'] == 0 ? '<font color="red">'._BLOG_ON_MODERATE.'</font>' : '<font color="blue">'._BLOG_DRAFT.'</font>')).'</strong>' . ($blog_conf['preModer'] == 1 ? _BLOG_NOTIFY7 : ''));
				$core->tpl->end();
				$core->tpl->close();
			}
			else
			{
				location('blog');
			}
		}
		else
		{
			location('blog');
		}
		break;
		
	case 'becomeReader':
		$blogId = intval($url[2]);
		if($core->auth->isUser)
		{
			$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_blogs` WHERE id = '" . $blogId . "'");
			if($db->numRows($query) == 1)
			{
				$blog = $db->getRow($query);
				set_title(array(_BLOGS, _BLOG_SUBSCRIBE));				
				menu();
				if(eregStrt(',' . $core->auth->user_info['nick'] . ',', $blog['readers']))
				{
					$db->query("UPDATE `" . DB_PREFIX . "_blogs` SET `readers` = '" . str_replace(',' . $core->auth->user_info['nick'] . ',', '', $blog['readers']) . "' WHERE `id` =" . $blogId . ";");
					$core->tpl->info(_BLOG_UNSUBSCRIBE_OK.' "<a href="blog/view/' . $blog['altname'] . '">' . $blog['title'] . '</a>".');
				}
				elseif(eregStrt(','.$core->auth->user_info['id'].',', $blog['admins']))
				{
					$core->tpl->info(_BLOG_NOTIFY8, 'warning');
				}
				else
				{
					$add = empty($blog['readers']) ? ',' . $core->auth->user_info['nick'] . ',' : $core->auth->user_info['nick'] . ',';
					$db->query("UPDATE `" . DB_PREFIX . "_blogs` SET `readers` = '" . $add . "' WHERE `id` =" . $blogId . ";");
					$core->tpl->info(_BLOG_SUBSCRIBE_OK.' "<a href="blog/view/' . $blog['altname'] . '">' . $blog['title'] . '</a>".');
				}
			}
			else
			{
				location('blog');
			}
		}		
		break;
		
	case 'blogEdit':
		$blogId = intval($url[2]);
		
		$queryB = $db->query("SELECT * FROM `" . DB_PREFIX . "_blogs` WHERE id = '" . $blogId . "' LIMIT 1");
		if($db->numRows($queryB) == 1 && $core->auth->isUser)
		{
			$blog = $db->getRow($queryB);
			$isAdmin = ($core->auth->isAdmin || eregStrt(','.$core->auth->user_info['id'].',', $blog['admins'])) ? true : false;
			if($isAdmin == true)
			{
				set_title(array(_BLOGS, _BLOG_EDIT));
				
				menu();
				$core->tpl->open('blog_create');
				$core->tpl->loadFile('blog/add.blog');
				$core->tpl->setVar('TITLE', prepareTitle($blog['title']));
				$core->tpl->setVar('ALTNAME', $blog['altname']);
				$core->tpl->setVar('DESCRIPTION', $blog['description']);
				$core->tpl->setVar('AVATAR_REPLACE', ($blog['avatar'] ? '<div style="width:120px; text-align:center"><img src="' . $blog['avatar'] . '" border="0" class="blogAvatar" /><br /><input name="deleteAvatar" type="checkbox" /> '._BLOG_DELETE_AVATAR.'</div>' : '<strong>'._BLOG_NO_AVATAR.'</strong>') . '<hr/><input type="hidden" name="bid" value="' . $blogId . '" />');
				$core->tpl->end();
				$core->tpl->close();
			}
			else
			{
				location('blog');
			}	
		}
		else
		{
			location('blog');
		}

		break;
		
	case 'ajaxRating':
		$no_head = true;
		$pid = intval($_POST['pid']);
		ajaxInit();
		if($core->auth->isUser)
		{
			list($postUser, $rating, $rus) = $db->fetchRow($db->query("SELECT uid, rating, ratingUsers FROM ".DB_PREFIX."_blog_posts WHERE id = '" . $pid . "'"));
			if(!empty($postUser) && $core->auth->user_info['id'] != $postUser && !eregStrt(','.$core->auth->user_info['id'].',', $rus))
			{
				$rU = empty($rus) ? ','.$core->auth->user_info['id'].',' : $rus.$core->auth->user_info['id'].',';
				$db->query("UPDATE `".DB_PREFIX."_blog_posts` SET `rating` = `rating`+1, `ratingUsers` = '" . $rU . "' WHERE `id` =" . $pid . ";");
				echo '+'.$rating+1;
			}
			elseif(!empty($postUser))
			{
				echo ($rating > 0 ? '+' : '').$rating;
			}
		}
		break;
}