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

class TagsCloud 
{
	var $font_size_min = 14;
	var $font_size_step = 5;
	var $tags = array();
	
	 function get_tag_count($tag_name, $tags) 
	 {
		$count = 0;
		foreach ($tags as $tag) 
		{
			if ($tag == $tag_name) 
			{
				$count++;
			}
		}
		return $count;
	}

	 function tags_cloud($tags) 
	 {
		$tags_list = array();
		foreach ($tags as $tag) 
		{
			$tags_list[$tag] = $this->get_tag_count($tag, $tags);
		}

		return $tags_list;		

	}

	 function get_min_count($tags_list) 
	 {
		$min = $tags_list[$this->tags[0]];
		foreach ($tags_list as $tag_count) {
			if ($tag_count < $min) $min = $tag_count;
		}
		return $min;

	}

	function get_cloud($tags) 
	{
		$this->tags = $tags;
		$cloud = Array();
		$tags_list = $this->tags_cloud($this->tags);
		$min_count = $this->get_min_count($tags_list);

		foreach ($tags_list as $tag=>$count) 
		{
			$font_steps = $count - $min_count;
			$font_size = $this->font_size_min + $this->font_size_step * $font_steps;
			//style=\"font-size:".$font_size."px\"
			$cloud[] = "<a href=\"news/tags/" . $tag . "\" title=\"" . $tag . "\" class=\"tag" . $font_steps . "\" >" . $tag . "</a>";
		}

		return $cloud;
	}
}