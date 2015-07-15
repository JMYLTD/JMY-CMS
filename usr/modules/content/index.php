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
require (ROOT.'etc/content.config.php');

function view($name)
{
global $db, $config, $core, $url, $headTag, $content_conf;
	if($name)
	{
		$name = str_replace(array('.html', '.htm'), '', $name);
		
		$query = $db->query("SELECT c.*, l.* FROM " . DB_PREFIX . "_content as c LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=c.id and l.module='content') WHERE translate='" . $db->safesql($name) . "'  AND l.lang = '" . $core->InitLang() . "'");
		$static = $db->getRow($query);

		if($static)
		{
			$core->tpl->uniqTag[] = 'view';
			$core->tpl->uniqTag[] = 'view-'.$static['id'];
			$core->tpl->uniqTag[] = $static['translate'];
			set_title(array($static['title']));
			
			if(!empty($static['keywords']))
			{
				$core->tpl->keywords = $static['keywords'];
			}
			
			if(!empty($static['theme']))
			{
				$theme = $static['theme'].'/';
			}
			else
			{
				$theme = '';
			}
			
			if(!empty($static['img']))
			{
				$img_content = $static['img'];
			}
			else
			{
				$img_content = 'default.png';
			}
			
			
			$core->tpl->loadFile('content/'.$theme.'content-view');
			$core->tpl->setVar('TITLE', $static['title']);
			$core->tpl->setVar('TEXT', $core->bbDecode($static['short'], $static['id'], true));
			$core->tpl->setVar('TRANSLATE', $static['translate']);
			$core->tpl->setVar('KEYWORDS', $static['keywords']);
			$core->tpl->setVar('DATE', $static['date']);
			$core->tpl->end();
			
			if($content_conf['allowComm'] == 1)
			{
				show_comments('content', $static['id'], $content_conf['comments_num']);
			}
		}
		else
		{
				$core->tpl->info('Страница не найдена!');
		}
	}
	else
	{
		include(ROOT . 'usr/tpl/404.tpl');
	}
}
 
switch(isset($url[1]) ? $url[1] : null) 
{
	default:
		if(eregStrt('.htm', mjsEnd($url)))
		{
			view(mjsEnd($url));
		}
		else
		{
			$nn = $content_conf['num'];
			$page = init_page();
			$cut = ($page-1)*$nn;
			set_title(array('Статические страницы'));
			$where = '';
			$file = 'index';
			$link = '';
			$core->tpl->title('Статьи');
			$core->tpl->uniqTag = 'main';
			
			
			if(isset($url[1]) && $url[1] != 'page')
			{
				$cat = mjsEnd($url);
				
				$altname = filter($cat, 'a');
				$cat_query = $db->query("SELECT id as cid, name FROM ".DB_PREFIX."_categories WHERE altname='" . $altname . "'");
				
				if($db->numRows($cat_query) == 0)
				{
					location();
				}
				
				$cat_info = $db->getRow($cat_query);
				$pLink = '/' . $core->getCat('content', $cat_info['cid'], 'development');
				$where = "AND cat like '%," . $cat_info['cid'] . ",%'";
				
			}
			else
			{
				$pLink = '';
				$where = '';
			}
			
			if(!INDEX)
			{
				$core->getCat('content', isset($cat_info['cid']) ? $cat_info['cid'] : '', 'breadcrumb', 1);
				$core->getCatList(isset($cat_info['cid']) ? $cat_info['cid'] : '', 'content', 3);
			}
			
			$query = $db->query("SELECT c.*, l.* FROM " . DB_PREFIX . "_content as c LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=c.id and l.module='content') WHERE l.lang = '" . $core->InitLang() . "' " . $where . " ORDER BY c.date DESC LIMIT " . $cut . ", " . $nn . "");
			
			if($db->numRows($query) > 0) 
			{
				while($static = $db->getRow($query))
				{
					$cat = $static['cat'] !== ',0,' ? $core->getCat('content', $static['cat'], 'short', 3) : '';
					$link = $static['cat'] !== ',0,' ? 'content/' . $core->getCat('content', $static['cat'], 'development') . '/' : 'content/';				
					$core->tpl->loadFile('content/content-main');
					$core->tpl->setVar('TITLE', $static['title']);
					$core->tpl->setVar('SHORT', '<div id="short-' . $static['id'] . '">' . $core->bbDecode(str($static['short'], 500), $static['id'], true) . '</div>');
					$core->tpl->setVar('DATE', formatDate($static['date']));
					$core->tpl->setVar('CATEGORY', $cat);		
					$core->tpl->setVar('ALTNAME', $static['translate']);
					$core->tpl->sources = preg_replace("#\\[more\\](.*?)\\[/more\\]#ies","format_link('\\1', '" . $link . $static['translate'] . ".html')", $core->tpl->sources);
					$core->tpl->sources = preg_replace("#\\[category\\](.*?)\\[/category\\]#ies","if_set('".$cat."', '\\1')", $core->tpl->sources);
					$core->tpl->setVar('ID', $static['id']);
					$core->tpl->end();
				}
				
				list($all) = $db->fetchRow($db->query("SELECT count(c.id) FROM " . DB_PREFIX . "_content as c LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=c.id and l.module='content') WHERE l.lang = '" . $core->InitLang() . "' " . $where));

				
				$core->tpl->pages($page, $nn, $all, 'content' . $pLink . '/{page}');
			}
			else
			{
				$core->tpl->info('Статей нет');
			}
		}
		break;
		

}