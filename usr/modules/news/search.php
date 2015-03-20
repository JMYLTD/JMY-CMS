<?php
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}

$where = " AND title LIKE '%" . $db->safesql($query) . "%' OR short LIKE '%" . $db->safesql($query) . "%'  OR short LIKE '%" . $db->safesql($query) . "%'";
$where .= ' AND c.lang = \'' . $core->InitLang() . '\'';
$core->loadModLang('news');
$core->tempModule = 'news';
$page = init_page();
$cut = ($page-1)*$news_conf['num'];
$queryDB = $db->query("SELECT n.*, c.* FROM ".DB_PREFIX."_news as n LEFT JOIN ".DB_PREFIX."_langs as c on(c.postId=n.id and c.module='news') WHERE active!='0' " . $where . " ORDER BY fixed DESC, date DESC LIMIT " . $cut . ", " . $news_conf['num'] . "");

if($core->auth->isAdmin)
{
	$meta = "<script type=\"text/javascript\">var textareaName = '';</script>" . "\n";
	$meta .= "<script type=\"text/javascript\" src=\"usr/js/bb_editor.js\"></script>" . "\n";
	$core->tpl->headerIncludes[] = $meta;
	$core->tpl->headerIncludes[] = "<script src=\"usr/js/drop_down_menu.js\" type=\"text/javascript\"></script>";
}

if($db->numRows($queryDB) > 0) 
{
	$core->tpl->title('Новости: '.$db->numRows($queryDB));
	
	while($news = $db->getRow($queryDB)) 
	{
		$tag_list = explode(', ', $news['tags']);
		$tag_count = 0;
		$tags = false;
		
		foreach($tag_list as $tag) 
		{
			$tag_count++;
			if($tag_count < ($news_conf['tags_num']+1)) 
			{
				$tags .= '<a href="news/tags/' . $tag . '" title="' . $tag . '">' . ($tag) . '</a>, ';
			}
		}
		$catInfo = $news['cat'] !== ',0,' ? $core->catInfo('news', $news['cat']) : '';
		$cat = $news['cat'] !== ',0,' ? $core->getCat('news', $news['cat'], 'short', 3) : '';
		$news_link = $news['cat'] !== ',0,' ? 'news/' . $core->getCat('news', $news['cat'], 'development') . '/' : 'news/';
		$cat_one = $news['cat'] !== ',0,' ? $core->getCat('news', $news['cat'], 'altname', 1) : 'index';
		$core->tpl->loadFile('news/news-'.(is_array($core->tpl->uniqTag) ? $core->tpl->uniqTag[0] : 'main'));
		$core->tpl->setVar('TITLE', $news['title']);
		$core->tpl->setVar('SHORT', '<div id="short-' . $news['id'] . '">' . highlightSearch($query, $core->bbDecode($news['short'], $news['id'], true)) . '</div>');
		$core->tpl->setVar('FULL', '<div id="full-' . $news['id'] . '">' . highlightSearch($query, $core->bbDecode($news['full'], $news['id'], true)) . '</div>');
		$core->tpl->setVar('CATEGORY', $cat);
		$core->tpl->setVar('CAT_ONE', $cat_one);
		$core->tpl->setVar('ALTNAME', $news['altname']);
		$core->tpl->setVar('ICON', isset($catInfo['icon']) ? $core->getCatImg($news_link, $catInfo['icon'], $catInfo['title']) : '');
		$core->tpl->setVar('AUTHOR', '<a href="profile/' . $news['author'] . '" title="' . _PAGE . ': ' . $news['author'] . '">' . $news['author'] . '</a>');
		$core->tpl->setVar('VIEWS', $news['views']);
		$core->tpl->setVar('COMMENTS', $news['comments']);
		$core->tpl->setVar('TAGS', mb_substr($tags, 0, -2));
		$core->tpl->setVar('FULL_LINK', $news_link . $news['altname'] . ".html");
		$core->tpl->sources = preg_replace("#\\[tags\\](.*?)\\[/tags\\]#ies","if_set('" . $news['tags'] . "', '\\1')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\[more\\](.*?)\\[/more\\]#ies","format_link('\\1', '" . $news_link . $news['altname'] . ".html')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\[category\\](.*?)\\[/category\\]#ies","if_set('".$cat."', '\\1')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\{%MYDATE:(.*?)%\\}#ies","date('\\1', '" . $news['date'] . "')", $core->tpl->sources);
		$core->tpl->setVar('DATE', formatDate($news['date']));
		$core->tpl->setVar('ID', $news['id']);
		$core->tpl->setVar('RATING', $news['allow_rating'] ? draw_rating($news['id'], 'news', $news['score'], $news['votes']) : '');
		$core->tpl->setVar('EDIT', $core->auth->isAdmin ? "<a onclick=\"return dropdownmenu(this, event, menu_news, '150px', '" . $news['id'] . "', 'short')\" onmouseout=\"delayhidemenu()\" href=\"javascript:void(0);\"><img src=\"media/edit/plus.png\" border=\"0\" class=\"icon\" alt=\"\" /></a>" : '');
		$core->tpl->end();
		unset($tags);
	}

	list($all) = $db->fetchRow($db->query("SELECT count(n.id) FROM ".DB_PREFIX."_news as n LEFT JOIN ".DB_PREFIX."_langs as c on(c.postId=n.id and c.module='news') WHERE active!='0' " . $where));
	$core->tpl->pages($page, $news_conf['num'], $all, 'search/' . $query . '/{page}');
}
else 
{
	$result[] = 'Ни одной новости не найдено.';
}