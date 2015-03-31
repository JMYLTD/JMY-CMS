<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

class bb
{

	var $codeArr = array();
	
	function parse($text, $pubId, $html)
	{
	global $smileRepl, $smiles, $core, $config;

		if($core->html_editor == 1)
		{
			$replace = array(
				'../../..' => $config['url'],
			);
			
			$text = stripslashes(str_replace(array_keys($replace), array_values($replace), $text));

			return $text;
		}
		
		if($pubId === true) $html = true;
		
		foreach($smiles as $smile => $info)
		{
			$smileRepl .= $smile.'|';
		}
		
		if($html == true)
		{
			$in[] = '%\[html\](.+?)\[\/html\]%iues';
			$out[] = '$this->prepareHTML(\'\\1\')';
			
		}
		
		$in[] = '%\[code=(php|sql|html|javascript|css|text)\](.+?)\[\/code\]%iuse';
		$out[] = "\$this->prepareCode('\\2', '\\1')";	
				
		$in[] = '%\[b\](.+?)\[/b\]%ius';
		$out[] = '<strong>\\1</strong>';

		$in[] = '%\[i\](.+?)\[\/i\]%ius';
		$out[] = '<i>\\1</i>';

		$in[] = '%\[u\](.+?)\[\/u\]%ius';
		$out[] = '<u>\\1</u>';

		$in[] = '%\[s\](.*)\[\/s\]%ius';
		$out[] = '<s>\\1</s>';    
		
		$in[] = '%\[ul\](.*)\[\/ul\]%ius';
		$out[] = '<ul>\\1</ul>';	
		
		$in[] = '%\[ol\](.*)\[\/ol\]%ius';
		$out[] = '<ol>\\1</ol>';
		
		$in[] = '%\[thumb(=left|=right|=center)? alt=(.+?)\](.+?)\[\/thumb\]%iues';
		$out[] = '\$this->thumbnailParse(\'\\3\', \'\\1\', false, \'\\2\')';       
		
		$in[] = '%\[img(=left|=right|=center)? alt=(.+?)\](.+?)\[\/img\]%iues';
		$out[] = '\$this->imageParse(\'\\3\', \'\\1\', \'\\2\')';   

		$in[] = '%\[thumb(=left|=right|=center)?\](.+?)\[\/thumb\]%iues';
		$out[] = '\$this->thumbnailParse(\'\\2\', \'\\1\')';       
		
		$in[] = '%\[img(=left|=right|=center)?\](.+?)\[\/img\]%iues';
		$out[] = '\$this->imageParse(\'\\2\', \'\\1\')';   
		
		$in[] = '%\[color=(.+?)\](.+?)\[\/color\]%ius';
		$out[] = "<span style=\"color:\\1\">\\2</span>";	
		
		$in[] = '%\[size=([0-9])\](.+?)\[\/size\]%ius';
		$out[] = "<span style=\"font-size:1\\1pt\">\\2</span>";	
		
		$in[] = '%\[url=(.+?)\](.+?)\[\/url\]%iues';
		$out[] = "\$this->formatBBUrl('\\1', '\\2')";	
		
		$in[] = '%\[email=(.+?)\](.+?)\[\/email\]%iues';
		$out[] = "\$this->formatBBEmail('\\1', '\\2')";

		$in[] = '%\[hr\]%iu';
		$out[] = '<hr />';   

		$in[] = '%\[br\]%iu';
		$out[] = '<br />';    
		
		$in[] = '%\[left\](.+?)\[\/left\]%ius';
		$out[] = '<div align="left">\\1</div>';        
		
		$in[] = '%\[flash\](.+?)\[\/flash\]%ius';
		$out[] = '<!--flash--><object align="middle" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param value="sameDomain" name="allowScriptAccess"><param value="\\3" name="movie"><param value="high" name="quality"><param value="#ffffff" name="bgcolor"><param value="transparent" name="wmode"><embed width="\\1" height="\\2" align="middle" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" name="bubbles" wmode="transparent" bgcolor="#ffffff" quality="high" src="\\3"></object><!--flash:end-->';        
		
		$in[] = '%\[flash=([0-9]+?)x([0-9]+?)\](.+?)\[\/flash\]%ius';
		$out[] = '<!--flash:\\1x\\2--><object align="middle" width="\\1" height="\\2" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"><param value="sameDomain" name="allowScriptAccess"><param value="\\3" name="movie"><param value="high" name="quality"><param value="#ffffff" name="bgcolor"><param value="transparent" name="wmode"><embed align="middle" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowscriptaccess="sameDomain" name="bubbles" wmode="transparent" bgcolor="#ffffff" quality="high" src="\\3"></object><!--flash:end-->';        
		
		$in[] = '%\[justify\](.+?)\[\/justify\]%ius';
		$out[] = '<div align="justify">\\1</div>';    

		$in[] = '%\[center\](.+?)\[\/center\]%ius';
		$out[] = '<div align="center">\\1</div>';

		$in[] = '%\[right\](.+?)\[\/right\]%ius';
		$out[] = '<div align="right">\\1</div>';

		$in[] = '%\[video\](.+?)\[\/video\]%iues';
		$out[] = '$this->formatBBVideo(\'\\1\')';		

		$in[] = '%\[audio\](.+?)\[\/audio\]%iues';
		$out[] = '$this->formatBBVideo(\'\\1\')';	
		
		$in[] = '%\[spoiler\]%iues';
		$out[] = '$this->spoiler()';	
		
		$in[] = '%\[/spoiler\]%si';
		$out[] = '</div></div><!--spoiler:end-->';		
		
		$in[] = '%\[spoiler\=(.+?)\]%iues';
		$out[] = '$this->spoiler(\'\\1\')';		

		$in[] = '%(' . mb_substr($smileRepl, 0, -1, 'UTF-8') . ')%iuse';
		$out[] = "\$this->formatSmile('\\1')";
		
		$result = nl2br(preg_replace($in, $out, $text));

		$replace = array(
			'[quote]' => '<!--quote--><div class="quote"><strong>' . _QUOTE . ':</strong><br />',
			'[/quote]' => '</div><!--quote:end-->',
			'[*]' => '<li>',
             '%\[blockquote\](.*?)\[/blockquote\]%si' => "<blockquote>\\1</blockquote>",
             '%\[sub\](.*?)\[/sub\]%si' => "<sub>\\1</sub>",
             '%\[sup\](.*?)\[/sup\]%si' => "<sup>\\1</sup>",
             '%\[li\](.*?)\[\/li\]%si' => "<li>\\1</li>",
             '%\[h1\](.+?)\[/h1\]%si' => "<h1>\\1</h1>",
             '%\[h2\](.+?)\[/h2\]%si' => "<h2>\\1</h2>",
             '%\[h3\](.+?)\[/h3\]%si' => "<h3>\\1</h3>",
             '%\[h4\](.+?)\[/h4\]%si' => "<h4>\\1</h4>",
             '%\[h5\](.+?)\[/h5\]%si' => "<h5>\\1</h5>",
             '%\[h6\](.+?)\[/h6\]%si' => "<h6>\\1</h6>",

		);
		
		$result = str_replace(array_keys($replace), array_values($replace), $result);
		
		if($html == true)
			$result = preg_replace('#<<html::([0-9])::html>>#es', '$this->DoHtml(\'\\1\')', $result);
			$result = preg_replace('#<<code::(.*?)::(.*?)::code>>#es', '$this->highlight_code(\'\\1\', \'\\2\')', $result);

	
		$this->htmlArr = array();
		
		return stripslashes($result);
	}
	
	function bbSite($text, $pubId)
	{
	global $core, $config;
		$in[] = '%\[hide\](.+?)\[\/hide\]%iues';
		$out[] = '$this->hide(\'\\1\')';
		
		if(strpos($text, "[attach=") !== false && $pubId)
		{
			$text = $this->parseAttach($text, $pubId);
		}
		
		if($core->html_editor == 1)
		{
			$in[] = '%\[video\](.+?)\[\/video\]%iues';
			$out[] = '$this->formatBBVideo(\'\\1\')';		

			$in[] = '%\[audio\](.+?)\[\/audio\]%iues';
			$out[] = '$this->formatBBVideo(\'\\1\')';	

		}
		
		if(eregStrt("--ThumbNail--", $text))
		{
			if(empty($core->tpl->headerIncludes['thumbNail']))
			{
				require(ROOT . 'media/imageEffects/' . $config['imageEffect'] . '/init.php');
				$core->tpl->headerIncludes['thumbNail'] = $js;
			}
		}		
		
		if(eregStrt("!--code:", $text))
		{
			if(empty($core->tpl->headerIncludes['hightlightCode']))
			{
				$core->tpl->endJs = '
					<script type="text/javascript" src="usr/plugins/highlight_code/scripts/shCore.js"></script>
					<script type="text/javascript" src="usr/plugins/highlight_code/scripts/shBrushCss.js"></script>
					<script type="text/javascript" src="usr/plugins/highlight_code/scripts/shBrushJScript.js"></script>
					<script type="text/javascript" src="usr/plugins/highlight_code/scripts/shBrushPhp.js"></script>
					<script type="text/javascript" src="usr/plugins/highlight_code/scripts/shBrushSql.js"></script>
					<script type="text/javascript" src="usr/plugins/highlight_code/scripts/shBrushPlain.js"></script>
					<link type="text/css" rel="stylesheet" href="usr/plugins/highlight_code/styles/shCoreDefault.css"/>
					<script type="text/javascript">SyntaxHighlighter.all();</script>';
			}
		}
		if(eregStrt("!--audio:", $text))
		{
		$core->tpl->endJs = "
							 <script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js\"></script>
							 <script src=\"http://progressionstudios.com/player/build/mediaelement-and-player.min.js\"></script>
							 <script src=\"http://progressionstudios.com/player/build/mep-feature-playlist.js\"></script>
							 <link rel=\"stylesheet\" href=\"http://progressionstudios.com/player/css/progression-player.css\" />
							 <link href=\"http://progressionstudios.com/player/font-awesome/css/font-awesome.min.css\" rel=\"stylesheet\" type=\"text/css\" />
							 <link rel=\"stylesheet\" href=\"http://progressionstudios.com/player/css/skin-minimal-light.css\" />				
							 <script>
							 $('.progression-single').mediaelementplayer({
								audioWidth: 400, 
								audioHeight:40,
								startVolume: 0.5, 
								features: ['playpause','current','progress','duration','tracks','volume','fullscreen']
								});
							 </script>";
		}
		return preg_replace($in, $out, $text);
	}
	
	function imageParse($img, $align = '', $alt = '')
	{
	global $config;
		$align = str_replace('=', '', $align);
		require ROOT . 'etc/files.config.php';
		$linked = eregStrt('http://', $img)||eregStrt('https://', $img) ? true : false;
		
		if($linked == false) 
		{
			list($width, $height, $type, $attr) = @getimagesize($img);
		}
		else
		{
			$width = '';
			$height = '';
			$type = '';
		}
		
		if(($width && $height && $type) OR $linked == true)
		{
			if(isset($width) && $width > $files_conf['thumb_width'])
			{
				return stripslashes('<!--IMG--><img src="' . $img . '" width="' . $files_conf['thumb_width']. '" border="0" alt="' . (empty($alt) ? '' : ' title="'.stripslashes($alt).'"') . (empty($alt) ? '' : ' title="'.stripslashes($alt)).'"' . ' ' . (!empty($align) ? 'align="' . $align . '"' : '') . ' hspace="10" /><!--IMG:end-->');
			}
			
			return stripslashes('<!--IMG--><img src="' . $img . '" border="0" alt="' . (empty($alt) ? '' : stripslashes($alt)) . '"' . (empty($alt) ? '' : ' title="'.stripslashes($alt).'"') . ' style="max-width:' . $files_conf['thumb_width']. 'px;" ' . (!empty($align) ? 'align="' . $align . '"' : '') . ' hspace="10" /><!--IMG:end-->');
		}
	}	

	function thumbnailParse($img, $align = '', $req = false, $alt = '')
	{
	global $core, $config;
	static $js, $picture;
		$align = str_replace('=', '', $align);
		
		if(($img && $config['imageEffect'] && file_exists(ROOT . $img)) || $req == true)
		{
			if($req)
			{
				require ROOT . 'etc/files.config.php';
			}
			
			$full = str_replace('thumb/thumb-', '', $img);
			if(file_exists(ROOT . $full) || $req == true)
			{
				if(empty($js) && empty($picture))
					require(ROOT . 'media/imageEffects/' . $config['imageEffect'] . '/init.php');

				$repl = array(
					'{full}' => $full,
					'{thumb}' => $img,
					'{img}' => 'alt="' . (empty($alt) ? '' : stripslashes($alt)) . '"' . (empty($alt) ? '' : ' title="'.stripslashes($alt).'"') . ($req ? ' width="' . $files_conf['thumb_width']. '"' : '') . (!empty($align) ? ' align="' . $align . '"' : ''),
					'{href}' => ''
				);
				
				return stripslashes('<!--ThumbNail-->'.img_preview(str_replace(array_keys($repl), array_values($repl), $picture), 'box').'<!--ThumbNail:end-->');
			}
		}
	}
	function LOADTPL($file)
	{
		global $core, $config;
		$loadDefault = 'usr/tpl/default/' . $file . $core->tpl->ext;
		$loadTheme = 'usr/tpl/'.$config['tpl'].'/' . $file . $core->tpl->ext;
		if (isset($loadTheme)) 
		{
			$text = file_get_contents(ROOT . $loadDefault);		
		}
		else
		{
			$text = file_get_contents(ROOT . $loadTheme);		
		}
		return $text;
	}

	function parseAttach($text, $pubId)
	{
	global $core, $db;
		$module = $core->getMod(true);
		$pubId = intval($pubId);
		$q = $db->query("SELECT * FROM `" . DB_PREFIX . "_attach` WHERE `pub_id`='" . $pubId . "' AND `mod`='" . $module . "'");		
		if($db->numRows($q) > 0) 
		{
			$first = $this->LOADTPL('attach');
			$stat = $first;
			$position=strpos($stat,'[static]');
			$stat=substr($stat,$position);
			$position=strpos($stat,'[/static]');
			$stat=substr($stat,0,$position);
			$stat = preg_replace( "#\\[static]#ies", '', $stat);				
			$first = preg_replace( "#\\[static](.*?)\\[/static]#ies", '', $first);			
			echo $stat;			
			while($rows = $db->getRow($q))
			{				
				if($core->auth->group_info['showAttach'] == 1)
				{	
					$replace = $first;
					$replace = str_replace('{%NUMB%}', $rows['downloads'], $replace);
					$replace = str_replace('{%SIZE%}', formatfilesize(@filesize($rows['url']), true), $replace);
					$replace = str_replace('{%ID%}', $rows['id'], $replace);
					$replace = str_replace('{%NAME%}', $rows['name'], $replace);						
				}
				else
				{
					$replace = _ACCESS_ATTACH;
				}				
				$text = str_replace('[attach=' . $rows['id'] . ']', $replace, $text);
			}
		}		
		return stripslashes($text);
	}


	function spoiler($title = '')
	{
		$code = gencode(5);
		return '<!--spoiler--><div class="spoiler"><a href="javascript:void(0)" onclick="showhide(\'sp' . $code . '\')">' . (!empty($title) ? '<span class="_spoilertitle">'.stripslashes($title).'</span>' : _SPOILE_EXPAND) . '</a><div id="sp' . $code . '" style="display:none;"><br />';	
	}
	
	function hide($content)
	{
	global $core;
		if($core->auth->group_info['showHide'] == 1)
		{
			return stripslashes($content);
		}
		else
		{
			return '<div class="spoiler"><strong>' . str_replace('[group]', $core->auth->group_info['gname'], _GR_DENIDE) . '</strong></div>';
		}
	}
	
	function highlight_code($count, $lang = 'plain')
	{
	global $user;
			if(isset($this->codeArr[$count]))
			{
				
				$mainCodeName = $lang;
				$code = htmlspecialchars_decode($this->codeArr[$count]);
				return '<!--code:' . $lang . '--><div class="codeBox"><div class="codeTitle">' . _CODE . ' - ' . strtoupper($mainCodeName) . '</div><div class="codeContent" style="overflow-x:auto;"><pre class="brush: ' . ($lang == 'html' || $lang == 'text' ? 'plain' : $lang) . ';">' . wordwrap(str_replace('&amp;#123;', '&#123;', htmlspecialchars($code)), 110, "\n", true) . '</pre></div></div><!--code:end-->';
			}
	}
	
	private function prepareHTML($content)
	{
		if(empty($this->htmlArr))
		{
			$count = -1;
		}
		else
		{
			$count = (count($this->htmlArr)-1);
		}
		
		$count++;
		$this->htmlArr[] = stripslashes($content);
		
		return '<<html::' . $count . '::html>>';
	}	
	
	
	private function prepareCode($content, $php)
	{
		if(empty($this->codeArr))
		{
			$count = -1;
		}
		else
		{
			$count = (count($this->codeArr)-1);
		}
		
		$count++;
		$this->codeArr[] = $content;
		
		return '<<code::' . $count . '::' . $php . '::code>>';
	}
	
	
	private function doHtml($count, $fromParse = false)
	{
		if(isset($this->htmlArr[$count]) && $fromParse == false)
		{
			return '<!--html_text-->'.$this->htmlArr[$count].'<!--html_text:end-->';
		}
		elseif(isset($this->htmlArr[$count]) && $fromParse)
		{
			return '[html]'.$this->htmlArr[$count].'[/html]';
		}
	}	
	
	private function doCode($count, $php)
	{
		return '[code=' . $php . ']'.stripslashes(str_replace("\n", '', $this->codeArr[$count])).'[/code]';
	}
	
	private function covertNl2Br($content)
	{
		return nl2br(stripslashes($content));
	}

	private function formatBBUrl($url, $content)
	{
		if(!empty($url) && !empty($content))
		{
			if(eregStrt('://', $url))
			{
				$arr = explode('://', $url);
				return '<!--url--><a href="go.php?url=' . base64_encode($url) . '" title="ссылка" target="_blank" onclick="javascript:this.href=\'' . $arr[0] . '://' . htmlspecialchars($arr[1], ENT_QUOTES) . '\'" onmouseover="javascript:this.href=\'' . $arr[0] . '://' . htmlspecialchars($arr[1], ENT_QUOTES) . '\'">' . stripslashes($content) . '</a><!--url:end-->';
			}
			else
			{
				return '<a href="' . $url . '" title="ссылка">' . stripslashes($content) . '</a>';
			}
		}
	}
	
	function smileDecode($url)
	{
	global $smiles;
			

		foreach($smiles as $smile => $info)
		{
			$decode[$info['url']] = $smile;
		}
		
		return !empty($decode[$url]) ? $decode[$url] : '';
	}
	
	function imgDecode($url, $alt, $align = '', $type = 'img')
	{
	global $config;
		return stripslashes('[' . $type . '' . (!empty($align) ? '='.$align : '') . (!empty($alt) ? ' alt='.$alt : '') . ']' . str_replace($config['url'].'/', '', $url) . '[/' . $type . ']');
	}
		

	function htmltobb($text)
	{
	global $smileRepl, $smileRepl2, $smiles, $core;

		if($core->html_editor == 1)
		{
			return $text;
		}
		
		foreach($smiles as $smile => $info)
		{
			$smileRepl .= $info['url'].'|';
		}
		
        $array_html = array(
            '%<!--html_text-->(.*?)<!--html_text:end-->%iues',
            '%<!--url-->.*?onmouseover="javascript:this.href=\'(.*?)\'">(.*?)</a><!--url:end-->%ius',
            '%<!--code:(.*?)-->.*?class="brush: .*?">(.*?)</pre></div></div><!--code:end-->%ieus',
            '%<!--quote--><div class="quote"><strong>Цитата:</strong>(.*?)</div><!--quote:end-->%ius',
            '%<!--IMG--><img src="(.*?)".*?alt="(.*?)".*?align="(.*?)".*?<!--IMG:end-->%iues',
            '%<!--IMG--><img src="(.*?)".*?alt="(.*?)".*?<!--IMG:end-->%iues',
            '%<!--ThumbNail-->.*?src="(.*?)".*?alt="(.*?)".*?align="(.*?)".*?<!--ThumbNail:end-->%iues',
            '%<!--ThumbNail-->.*?src="(.*?)".*?alt="(.*?)".*?<!--ThumbNail:end-->%iues',
            '%<!--flash-->.*?src="(.*?)".*?<!--flash:end-->%ius',
            '%<!--flash:([0-9]*)x([0-9]*)-->.*?src="(.*?)".*?<!--flash:end-->%ius',
            '%<!-- video:(.*?):(.*?) -->.*?value="(.*?)".*?<!-- video:(.*?):end -->%ius',
			'%<!-- video:youtube:(.*?) -->.*?src="(.*?)".*?<!-- video:youtube:end -->%ius',
			'%<!-- video:rutube:(.*?) -->.*?src="(.*?)".*?<!-- video:rutube:end -->%ius',
			'%<!-- video:twitch:(.*?) -->.*?src="(.*?)".*?<!-- video:twitch:end -->%ius',
            '%<!--video:flv-->.*?&amp;file=(.*?)".*?<!--video:end-->%ius',
            '%<!--audio-->.*?&amp;file=(.*?)".*?<!--audio:end-->%ius',
            '%<!--spoiler--><div class="spoiler">.*?<span class="_spoilertitle">(.*?)</span>.*?style="display:none;">%ius',
			'%<!--spoiler--><div class="spoiler">.*?style="display:none;">%ius',
            '%</div></div><!--spoiler:end-->%ius',
            '%<img src="(' . $smileRepl . ')".*?alt="" border="0" style="vertical-align:middle" />%iues',
            '%&reg;%ius',
            '%&copy;%ius',
            '%&\#153;%ius',
            '%<hr />%ius',
            '%<h6>(.*?)</h6>%ius',
            '%<span style="font-size:1(.*?)pt">(.*?)</span>%ius',
            '%<span style="color:(.*?)">(.*?)</span>%ius',
            '%<h5>(.*?)</h5>%ius',
            '%<h4>(.*?)</h4>%ius',
            '%<h3>(.*?)</h3>%ius',
            '%<h2>(.*?)</h2>%ius',
            '%<h1>(.*?)</h1>%ius',
            '%<s>(.*?)</s>%ius',
            '%<u>(.*?)</u>%ius',
            '%<i>(.*?)</i>%ius',
            '%<b>(.*?)</b>%ius',
            '%<strong>(.*?)</strong>%ius',
            '%<li>(.*?)</li>%ius',
            '%<sup>(.*?)</sup>%ius',
            '%<sub>(.*?)</sub>%ius',
            '%<div align="(.*?)">(.*?)</div>%ius',
		);
		
    
        $array_bb = array(
			"\$this->prepareHTML('\\1')",
			"[url=\\1]\\2[/url]",
			"\$this->prepareCode('\\2', '\\1')",
			"[quote]\\1[/quote]",
			"\$this->imgDecode('\\1', '\\2', '\\3')",
			"\$this->imgDecode('\\1', '\\2')",
			"\$this->imgDecode('\\1', '\\2', '\\3', 'thumb')",
			"\$this->imgDecode('\\1', '\\2', '', 'thumb')",
			"[flash]\\1[/flash]",
			"[flash=\\1x\\2]\\3[/flash]",
			"[video]\\3[/video]",			
			"[video]\\2[/video]",
			"[video]http://rutube.ru/video/\\1[/video]",
			"[video]http://www.twitch.tv/\\1[/video]",
			"[video]\\1[/video]",
			"[audio]\\1[/audio]",
			"[spoiler=\\1]",
			"[spoiler]",
			"[/spoiler]",
			"\$this->smileDecode('\\1')",
            "(r)",
            "(c)",
            "(tm)",
            "[hr]",
            "[h6]\\1[/h6]",
            "[size=\\1]\\2[/size]",
            "[color=\\1]\\2[/color]",
            "[h5]\\1[/h5]",
            "[h4]\\1[/h4]",
            "[h3]\\1[/h3]",
            "[h2]\\1[/h2]",
            "[h1]\\1[/h1]",
            "[s]\\1[/s]",
            "[u]\\1[/u]",
            "[i]\\1[/i]",
            "[b]\\1[/b]",
            "[b]\\1[/b]",
            "[li]\\1[/li]",
            "[sup]\\1[/sup]",
            "[sub]\\1[/sub]",
            "[\\1]\\2[/\\1]",
        );
	
		$text = preg_replace($array_html, $array_bb, $text);
		
		$text = stripslashes($text);
		
		$text = str_replace("<br />", "", $text);
		
		$text = preg_replace('#<<html::([0-9])::html>>#es', '$this->DoHtml(\'\\1\', true)', $text);
		$text = preg_replace('#<<code::([0-9])::(.*?)::code>>#es', '$this->doCode(\'\\1\', \'\\2\')', $text);
		
		return $text;
	}
	
	private function formatBBEmail($mail, $content)
	{
		if(!empty($mail) && !empty($content))
		{
			if(eregStrt('@', $mail))
			{
				$arr = explode('@', $mail);
				return '<a href="javascript:void(0)" title="ссылка" target="_blank" onclick="javascript:this.href=\'mailto:' . $arr[0] . '\'+\'@' . htmlspecialchars($arr[1], ENT_QUOTES) . '\'" onmouseover="javascript:this.href=\'mailto:' . $arr[0] . '\'+\'@' . htmlspecialchars($arr[1], ENT_QUOTES) . '\'">' . $content . '</a>';
			}
		}
	}

	private function formatBBVideo($url)
	{
	global $core, $config;
		$parseUrl = parse_url(htmlspecialchars_decode($url, ENT_QUOTES));
		$query = array();
		if(isset($parseUrl['query']))
		{
			parse_str($parseUrl['query'], $query);
		}
		$host = getHost($url);
		$type = getExt($url);
		if($host == 'youtube.com')
		{	
			if (eregStrt('/v/', $url))
			{
				$id = str_replace('http://www.youtube.com/v/', '', $url);
			}
			elseif (eregStrt('/embed/', $url))
			{
				$id = str_replace('http://www.youtube.com/embed/', '', $url);
			}			
			else
			{
				$id = $query['v'];
			}			
			if($id)
			{			
				return '<!-- video:youtube:' . $id . ' --><iframe width="640" height="385" src="http://www.youtube.com/embed/'.$id.'" frameborder="0" allowfullscreen></iframe><!-- video:youtube:end -->';
			}
		}
		
		elseif($host == 'rutube.ru')
		{			
			if (eregStrt('/video/', $url))
			{
				$id = str_replace('http://rutube.ru/video/', '', $url);
				$position = strpos($id,'/');
				if (!isset($position))
				{
					$id = substr($id,0,$position);
				}
			}
			elseif (eregStrt('/play/embed/', $url))
			{
				$id = str_replace('http://rutube.ru/play/embed/', '', $url);
				$position = strpos($id,'?');
				if (!isset($position))
				{
					$id = substr($id,0,$position);
				}
			}			
			else
			{
				$id = $query['v'];
			}
			if($id)
			{			
				return '<!-- video:rutube:' . $id . ' --><iframe width="640" height="385" src="http://rutube.ru/play/embed/'.$id.'?autoStart=false" frameborder="0" allowfullscreen></iframe><!-- video:rutube:end -->';
			}
			
		}
		elseif($host == 'twitch.tv')
		{			
			if (eregStrt('twitch.tv/', $url))
			{
				$id = str_replace('http://www.twitch.tv/', '', $url);				
				$position = strpos($id,'/');
				if (!isset($position))
				{
					$id = substr($id,0,$position);
				}
				
			}		
			else
			{
				$id = $query['v'];
			}
			if($id)
			{			
				return '<!-- video:twitch:' . $id . ' --><iframe width="640" height="385" src="http://www.twitch.tv/'.$id.'/embed?autoplay=false" frameborder="0" allowfullscreen></iframe><!-- video:twitch:end -->';
			}
		}	
		elseif($host == 'smotri.com')
		{
			$id = $query['id'];
			if($id)
			{
				return '<!-- video:smotri:' . $id . ' --><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="400" height="330"><param name="movie" value="http://pics.smotri.com/scrubber_custom8.swf?file=' . $id . '&amp;bufferTime=3&autoStart=false&str_lang=eng&amp;xmlsource=http%3A%2F%2Fpics.smotri.com%2Fcskins%2Fblue%2Fskin_color_lightaqua.xml&xmldatasource=http%3A%2F%2Fpics.smotri.com%2Fskin_ng.xml" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="true" /><param name="bgcolor" value="#ffffff" /><embed src="http://pics.smotri.com/scrubber_custom8.swf?file=' . $id . '&amp;bufferTime=3&amp;autoStart=false&str_lang=eng&amp;xmlsource=http%3A%2F%2Fpics.smotri.com%2Fcskins%2Fblue%2Fskin_color_lightaqua.xml&xmldatasource=http%3A%2F%2Fpics.smotri.com%2Fskin_ng.xml" quality="high" allowscriptaccess="always" allowfullscreen="true" wmode="window" width="400" height="330" type="application/x-shockwave-flash"></embed></object><!-- video:smotri:end -->';
			}
		}
		elseif($type == 'flv' || $type == 'mp4' || $type == '3gp')
		{
			$code = rand(1, 100000);
			
			return '<!--video:flv--><object id="videoplayer' . $code . '" type="application/x-shockwave-flash" data="usr/plugins/uppod.swf" width="500" height="375"><param name="wmode" value="transparent" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><param name="movie" value="usr/plugins/uppod.swf" /><param name="flashvars" value="comment=' . end(explode('/', $url)) . '&amp;m=video&amp;file=' . ($host == 'files' ? $config['url'].'/'.$url : $url) . '" /></object><!--video:end-->';
		}
		elseif($type == 'mp3')
		{
			$code = rand(1, 100000);
			$arr = explode('/', $url);
			return '<!--audio--><div class="progression-skin progression-minimal-light"><audio class="wp-audio-shortcode" id="audio-6-1" preload="none" style="width: 100%; visibility: hidden;" controls="controls"><source type="audio/mpeg" src="' . ($host == 'files' ? $config['url'].'/'.$url : $url) . '" /><a href="' . ($host == 'files' ? $config['url'].'/'.$url : $url) . '">' . ($host == 'files' ? $config['url'].'/'.$url : $url) . '</a></audio></div><!--audio:end-->';
		}
	}
	

	private function formatSmile($smile)
	{
	global $smiles;
		if(is_array($smiles[$smile]))
		{
			return '<img src="' . $smiles[$smile]['url'] . '" title="' . $smiles[$smile]['title'] . '" alt="" border="0" style="vertical-align:middle" />';
		}
	}
}