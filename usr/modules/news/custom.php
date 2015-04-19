<?php
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
global $core, $db, $news_conf;
$where = ' AND c.lang = \'' . $core->InitLang() . '\'';
if($category != 'all')
{
	$catsArr = array_map('trim', explode(',', $category));
	$i = 0;
	foreach($catsArr as $cat)
	{
		$i++;
		if($i == 1) $where .= " AND "; else $where .= " OR ";
		$where .= "cat like '%," . $cat . ",%'";
	}
}

if(!empty($notin))
{
	$notcatsArr = array_map('trim', explode(',', $notin));
	foreach($notcatsArr as $cat)
	{
		$where .= " AND cat NOT LIKE '%," . $cat . ",%'";
	}
}

	if(($order!='date')&($order!='views')&($order!='votes')&($order!='comments'))
	{
		$order='date';
	}	
	if(($short!='DESC')&($short!='ASC'))
	{
		$short='DESC';
	}
	


$core->loadModLang('news');
$core->tempModule = 'news';
$queryDB = $db->query("SELECT n.*, c.* FROM ".DB_PREFIX."_news as n LEFT JOIN ".DB_PREFIX."_langs as c on(c.postId=n.id and c.module='news') WHERE active='1' " . $where . " ORDER BY " . $order . " " . $short . " LIMIT 0, " . $limit . "");

$custom = '';

if($db->numRows($queryDB) > 0) 
{
	while($news = $db->getRow($queryDB)) 
	{	
		$catInfo = $news['cat'] !== ',0,' ? $core->catInfo('news', $news['cat']) : '';
		$cat = $news['cat'] !== ',0,' ? $core->getCat('news', $news['cat'], 'short', 3) : '';
		$news_link = $news['cat'] !== ',0,' ? 'news/' . $core->getCat('news', $news['cat'], 'development') . '/' : 'news/';
		$cat_one = $news['cat'] !== ',0,' ? $core->getCat('news', $news['cat'], 'altname', 1) : 'index';
		$short = $core->bbDecode($news['short'], $news['id'], true);
		$miniImg = _getCustomImg($short);		
		ob_start();
		$core->tpl->loadFile($template);
		$core->tpl->setVar('TITLE', $news['title']);
		$core->tpl->setVar('SHORT', '<div id="short-' . $news['id'] . '">' . $core->bbDecode($news['short'], $news['id'], true) . '</div>');		
		$core->tpl->sources = preg_replace("#\\[img:([0-9]*?)\\]#is", (!empty($miniImg[0]) ? '<img src="' . $miniImg[0] . '" border="0" width="\\1" />' : ''), $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\[mini_img\\](.*?)\\[/mini_img\\]#ies","if_set('" . (!empty($miniImg[0]) ? true : '') . "', '\\1')", $core->tpl->sources);
		$core->tpl->setVar('CATEGORY', $cat);
		$core->tpl->setVar('CAT_ONE', $cat_one);
		$core->tpl->setVar('ALTNAME', $news['altname']);
		$core->tpl->setVar('ICON', isset($catInfo['icon']) ? $core->getCatImg($news_link, $catInfo['icon'], $catInfo['title']) : '');
		$core->tpl->setVar('AUTHOR', '<a href="profile/' . $news['author'] . '" title="' . _PAGE . ': ' . $news['author'] . '">' . $news['author'] . '</a>');
		$core->tpl->setVar('VIEWS', $news['views']);
		$core->tpl->setVar('COMMENTS', $news['comments']);
		$core->tpl->setVar('FULL_LINK', $news_link . $news['altname'] . ".html");		
		$core->tpl->sources = preg_replace("#\\[tags\\](.*?)\\[/tags\\]#ies","if_set('" . $news['tags'] . "', '\\1')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\[more\\](.*?)\\[/more\\]#ies","format_link('\\1', '" . $news_link . $news['altname'] . ".html')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\[category\\](.*?)\\[/category\\]#ies","if_set('".$cat."', '\\1')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\{%MYDATE:(.*?)%\\}#ies","date('\\1', '" . $news['date'] . "')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\{%TITLE:(.*?)%\\}#ies", "short('\\1', '" . $news['title'] . "')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\{%SHORT:(.*?)%\\}#ies", "short('\\1', '" . processText($short) . "')", $core->tpl->sources);		
		$core->tpl->sources = preg_replace("#\\{%IMG:(.*?):(.*?)%\\}#is", (!empty($miniImg[(int)${1}]) ? $miniImg[(int)${1}] : "\${2}") , $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\{%IMG:(.*?)%\\}#is",  $miniImg[(int)${1}], $core->tpl->sources);
		$core->tpl->setVar('DATE', formatDate($news['date']));
		$core->tpl->setVar('ID', $news['id']);
		
		
		$core->tpl->setVar('RATING', $news['allow_rating'] ? draw_rating($news['id'], 'news', $news['score'], $news['votes']) : '');
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
		$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);
		$core->tpl->end();
		unset($tags);
		$custom .= ob_get_contents();
		ob_end_clean();

	}
}