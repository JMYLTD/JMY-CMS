<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

class Rss
{
	var $Count=0;
	var $Template;
	var $Items=array();
	var $CashPath="";
	var $content = "";
	var $CacheLoad = false;


	function rss($url)
	{
		global $Items;
		if(!$url) die("RSS: неверный url"); 
		$urlx=parse_url($url);
		
		if($this->CacheLoad)
		{
			$Filename=$this->CashPath.$urlx[host].".rss";
			
			$modifed=time()-@filemtime($Filename);

			if(!file_exists($Filename) || $modifed>CASHE_TIMEOUT)
			{
				if( !($this->content = file_get_contents($url)) ) die("RSS: ошибка доступа");
				$rss_tmp=fopen($Filename,"w");
				fputs($rss_tmp, $this->content);
					 fclose($rss_tmp);	 
			}
			else $this->content = @file_get_contents($Filename);
		}
		else
		{
			$this->content = @file_get_contents($url);
		}

		preg_match_all("/<item>(.+)<\/item>/Uis", $this->content, $Items1, PREG_SET_ORDER);

		foreach($Items1 as $indx=>$var)
		{
			$this->Items[$indx]=$var[1];
		}
	}
	
	function getRssDescription()
	{
		preg_match("#<description>(.+?)</description>#i", $this->content, $ParsedItem);
		return $ParsedItem[1];
	}
	
	function parseItems($type = 'tpl')
	{	
		if(isset($this->Items[$this->Count]))
		{
			$Item = $this->Items[$this->Count];
		}
		else
		{
			return false;
		}
	
		$this->Count++;
		preg_match_all("/<(title|link|guid|description)>(.+)<\/(\\1)>/is", $Item, $ParsedItem, PREG_SET_ORDER);
		$ParsedArray['title'] = htmlspecialchars($ParsedItem[0][2], ENT_QUOTES);
		$ParsedArray['link'] = urldecode($ParsedItem[1][2]);
		if($ParsedItem[2][1] == 'description')
		{
			$ParsedArray['description'] = htmlspecialchars($ParsedItem[2][2], ENT_QUOTES);
		}
		elseif($ParsedItem[3][1] == 'description')
		{
			$ParsedArray['link'] = urldecode($ParsedItem[2][2]);
			$ParsedArray['description'] = htmlspecialchars($ParsedItem[3][2], ENT_QUOTES);
		}
		
		if($type == 'tpl')
		{
			return __tpl($this->Template,$ParsedArray);
		}
		elseif($type == 'array')
		{
			return $ParsedArray;
		}
	}	
}