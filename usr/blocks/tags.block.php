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

global $db, $tagContent, $cache;

$tagContent = $cache->do_get('tags_block_'.$core->lang);

if(empty($tagContent))
{
	$tag_query = $db->query("SELECT tag FROM " . DB_PREFIX . "_tags WHERE module = 'news'");
	if($db->numRows($tag_query) > 0)
	{
		while($tag = $db->getRow($tag_query)) 
		{
			$tags[] = $tag['tag'];
		}

		$tag_cloud = new TagsCloud;
		$tags_list = $tag_cloud->get_cloud($tags);  

		$tag_count = 0;

		foreach ($tags_list as $tag) 
		{
			$tag_count++;
			if($tag_count < 20)
			{
				$tagContent .= $tag.' ';
			}
		}

		if($tag_count >= 20)
		{
			$tagContent .= '<div align="right"><a href="tags" title="все теги">[ все ]</a></div>"';
		}
	}
	else
	{
		$tagContent = 'Тегов нет.';
	}
	
	$cache->do_put('tags_block_'.$core->lang, $tagContent, 3600);
}

echo $tagContent;
