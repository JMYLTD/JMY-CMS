<?php
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
loadConfig('news');
$core->loadModLang('news');
$core->tempModule = 'news';
$where = " AND title LIKE '%" . $db->safesql($query) . "%' OR short LIKE '%" . $db->safesql($query) . "%'  OR short LIKE '%" . $db->safesql($query) . "%'";
$where .= ' AND c.lang = \'' . $core->InitLang() . '\'';
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
					$tags .= '<a href="news/tags/' . $tag . '" title="' . $tag . '">' . ($headTag == $tag ? '<strong>' . $tag . '</strong>' : $tag) . '</a>, ';
				}
			}
			$catInfo = $news['cat'] !== ',0,' ? $core->catInfo('news', $news['cat']) : '';
			$cat = $news['cat'] !== ',0,' ? $core->getCat('news', $news['cat'], 'short', 3) : '';
			$news_link = $news['cat'] !== ',0,' ? 'news/' . $core->getCat('news', $news['cat'], 'development') . '/' : 'news/';
			$cat_one = $news['cat'] !== ',0,' ? $core->getCat('news', $news['cat'], 'altname', 1) : 'index';
			$short = $core->bbDecode($news['short'], $news['id'], true);
			$core->tpl->loadFile('news/news-'.(is_array($core->tpl->uniqTag) ? $core->tpl->uniqTag[0] : 'main'));
			$core->tpl->setVar('TITLE', $news['title']);
			$core->tpl->setVar('SHORT', $short);
			$core->tpl->setVar('FULL', '<div id="full-' . $news['id'] . '">' . $core->bbDecode($news['full'], $news['id'], true) . '</div>');
			$core->tpl->setVar('CATEGORY', $cat);
			$core->tpl->setVar('CAT_ONE', $cat_one);
			$core->tpl->setVar('ALTNAME', $news['altname']);
			$core->tpl->setVar('ICON', isset($catInfo['icon']) ? $core->getCatImg($news_link, $catInfo['icon'], $catInfo['title']) : '');
			$core->tpl->setVar('AUTHOR', '<a href="profile/' . $news['author'] . '" title="' . _PAGE . ': ' . $news['author'] . '">' . $news['author'] . '</a>');
			$core->tpl->setVar('VIEWS', $news['views']);
			$core->tpl->setVar('COMMENTS', $news['comments']);
			$core->tpl->setVar('TAGS', mb_substr($tags, 0, -2));
			$core->tpl->setVar('FULL_LINK', $news_link . $news['altname'] . ".html");
			$miniImg = _getCustomImg($short);
			$array_replace = array(
				"#\\[tags\\](.*?)\\[/tags\\]#ies" => "if_set('" . $news['tags'] . "', '\\1')",
				"#\\[more\\](.*?)\\[/more\\]#ies" => "format_link('\\1', '" . $news_link . $news['altname'] . ".html')",
				"#\\[category\\](.*?)\\[/category\\]#ies" => "if_set('".$cat."', '\\1')",
				"#\\[edit\\](.*?)\\[/edit\\]#is" => (($core->auth->isModer||$core->auth->isAdmin)  ? "\${1}" : ''),
				"#\\{%MYDATE:(.*?)%\\}#ies" => "date('\\1', '" . $news['date'] . "')",
				"#\\{%TITLE:(.*?)%\\}#ies" => "short('\\1', '" . $news['title'] . "')",
				"#\\{%SHORT:(.*?)%\\}#ies" => "short('\\1', '" . $short . "')",
				"#\\[img:([0-9]*?)\\]#is" => (!empty($miniImg[0]) ? '<img src="' . $miniImg[0] . '" border="0" width="\\1" />' : ''),
				"#\\[mini_img\\](.*?)\\[/mini_img\\]#ies" => "if_set('" . (!empty($miniImg[0]) ? true : '') . "', '\\1')",				
			);
			if(!empty($news['fields']) && $news['fields'] != 'N;')
			{
				$fields = unserialize($news['fields']);
				foreach($fields as $xId => $xData)
				{
					if(!empty($xData[1]))
					{
						$array_replace["#\\[xfield_value:" . $xId . "\\]#is"] = $xData[1];
					}
				}
			}
			$array_replace["#\\[xfield:([0-9]*?)\\](.*?)\\[/xfield:([0-9]*?)\\]#ies"] = "ifFields('" . $news['fields'] . "', '\\1', '\\2')";
			if($news_conf['showBreadcumb'] == '1')
			{
				$catId = explode(',', $news['cat']);
				$core->tpl->setVar('BREADCUMB', $core->getCat('news', ($catId[1] != 0) ? $catId[1] : '', 'breadcrumb', 1));
			}			
			$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);
			$core->tpl->sources = preg_replace("#\\{%IMG:(.*?):(.*?)%\\}#is", (!empty($miniImg[(int)${1}]) ? $miniImg[(int)${1}] : "\${2}") , $core->tpl->sources);
			$core->tpl->sources = preg_replace("#\\{%IMG:(.*?)%\\}#is",  $miniImg[(int)${1}], $core->tpl->sources);
			$core->tpl->setVar('DATE', formatDate($news['date']));
			$core->tpl->setVar('ID', $news['id']);
			$core->tpl->setVar('RATING', $news['allow_rating'] ? draw_rating($news['id'], 'news', $news['score'], $news['votes']) : '');
			$core->tpl->setVar('EDIT', ($core->auth->isModer||$core->auth->isAdmin)  ? '<a href="news/edit/'.$news['id'].'">'._EDIT.'</a>' : '');
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