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
global $core, $db, $news_conf;
$where = 'l.lang = \'' . $core->InitLang() . '\'';
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

$core->loadModLang('content');
$core->tempModule = 'content';

$custom = '';

			$query = $db->query("SELECT c.*, l.* FROM " . DB_PREFIX . "_content as c LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=c.id and l.module='content') WHERE " . $where . " ORDER BY c.date DESC LIMIT 0, " . $limit . "");
			
			if($db->numRows($query) > 0) 
			{
				while($static = $db->getRow($query))
				{
					$cat = $static['cat'] !== ',0,' ? $core->getCat('content', $static['cat'], 'short', 3) : '';
					$link = $static['cat'] !== ',0,' ? 'content/' . $core->getCat('content', $static['cat'], 'development') . '/' : 'content/';
					//$cat_one = $static['cat'] !== ',0,' ? $core->getCat('content', $static['cat'], 'altname', 1) : 'index';
					$short = $core->bbDecode(str($static['short'], 500), $static['id'], true);
					$miniImg = _getCustomImg($short);
		ob_start();
					$core->tpl->loadFile($template);
					$core->tpl->setVar('TITLE', $static['title']);
					$core->tpl->setVar('SHORT', '<div id="short-' . $static['id'] . '">' . $short . '</div>');
					$core->tpl->setVar('DATE', formatDate($static['date']));
		$core->tpl->sources = preg_replace("#\\[short:([0-9]*?)\\]#ies","str(strip_tags(\$core->bbDecode(str(\$static['short'], 500), \$static['id'], true))), '\\1')", $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\[img:([0-9]*?)\\]#is", (!empty($miniImg[0]) ? '<img src="' . $miniImg[0] . '" border="0" width="\\1" />' : ''), $core->tpl->sources);
		$core->tpl->sources = preg_replace("#\\[mini_img\\](.*?)\\[/mini_img\\]#ies","if_set('" . (!empty($miniImg[0]) ? true : '') . "', '\\1')", $core->tpl->sources);
					$core->tpl->setVar('CATEGORY', $cat);
				//	$core->tpl->setVar('CAT_ONE', $cat_one);
					$core->tpl->setVar('ALTNAME', $static['translate']);
					$core->tpl->sources = preg_replace("#\\[more\\](.*?)\\[/more\\]#ies","format_link('\\1', '" . $link . $static['translate'] . ".html')", $core->tpl->sources);
					$core->tpl->sources = preg_replace("#\\[category\\](.*?)\\[/category\\]#ies","if_set('".$cat."', '\\1')", $core->tpl->sources);
					$core->tpl->setVar('ID', $static['id']);
					$core->tpl->end();
		$custom .= ob_get_contents();
		ob_end_clean();
				}
			}

