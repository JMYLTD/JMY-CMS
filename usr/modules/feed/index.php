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
 
function main() {
	global $db, $config, $core;
	$core->tpl->title = ('Информационн');
	echo 'модуль';
	
}
 
switch(isset($url[1]) ? $url[1] : null) {
	default:
		main();
	break;
	
	case "rss":
		$type = isset($url[2]) ? $url[2] : '';
		$cid = isset($url[3]) ? intval($url[3]) : '';
		$where = '';
		switch($type) {
			case "cat":
				$where = " AND n.cat like '%," . $cid . ",%'";
				break;
		}
		$no_head = true;
		$where .= ' AND c.lang = \'' . $core->InitLang() . '\'';
		$query = $db->query("SELECT n.*, c.*, cat.name FROM ".DB_PREFIX."_news as n LEFT JOIN ".DB_PREFIX."_langs as c on(c.postId=n.id and c.module='news') LEFT JOIN ".DB_PREFIX."_categories as cat on(n.cat = cat.id) WHERE n.active!='0' " . $where . " ORDER BY n.date DESC");
		if($db->numRows($query) > 0) 
		{
			header('Content-Type: application/xml; charset=utf-8');
			echo '<?xml version="1.0" encoding="UTF-8" ?> ' . "\n";
			echo '<rss version="2.0">' . "\n";
			echo '<channel>' . "\n";
			echo '<title>' . $config['name'] . '</title>' . "\n";
			echo '<link>' . $config['url'] . '</link>' . "\n";
			echo '<language>' . $config['lang'] . '</language>' . "\n";
			echo '<description>' . $config['description'] . '</description>' . "\n";
			echo '<generator>JMY RRS GENERATOR V1</generator>' . "\n";
			echo '<copyright>JMY LTD</copyright>' . "\n";
			
			while($news = $db->getRow($query)) {				
				$description = $core->bbDecode($news['short'], $news['id'], true);
				$news_link = $news['cat'] !== ',0,' ? 'news/' . $core->getCat('news', $news['cat'], 'development') . '/' : 'news/';
				$link = $news_link . $news['altname'] . ".html";
			    echo '<item>' . "\n";
			    echo '<title>' . $news['title'] . '</title>' . "\n";
			    echo '<guid isPermaLink="true">' . $config['url'] . '/' . $link . '</guid>' . "\n";
			    echo '<link>' . $config['url'] . '/' . $link . '</link>' . "\n";
			    echo '<description><![CDATA[' . $description . ']]></description>' . "\n";
			    echo '<category><![CDATA[' . $news['name'] . ']]></category>' . "\n";
			    echo '<pubDate>' . date('D, j M Y H:i:s O', $news['date']) . '</pubDate>' . "\n";
			    echo '</item>' . "\n";
			}
		
			echo '</channel>' . "\n";
			echo '</rss>';
		
		}
		break;
	
	case "atom":
		$type = isset($url[2]) ? $url[2] : '';
		$cid = isset($url[3]) ? intval($url[3]) : '';
		$where = '';
		switch($type) {
			case "cat":
				$where = " AND n.cat like '%," . $cid . ",%'";
				break;
		}
		$no_head = true;
		$where .= ' AND c.lang = \'' . $core->InitLang() . '\'';
		$query = $db->query("SELECT n.*, c.*, cat.name FROM ".DB_PREFIX."_news as n LEFT JOIN ".DB_PREFIX."_langs as c on(c.postId=n.id and c.module='news') LEFT JOIN ".DB_PREFIX."_categories as cat on(n.cat = cat.id) WHERE n.active!='0' " . $where . " ORDER BY n.date DESC");
		echo '<?xml version="1.0" encoding="utf-8"?>
        <feed xmlns="http://www.w3.org/2005/Atom">
          <title>'.$config['name'].'</title> 
          <link href="'.$config['url'].'"/>
          <updated>2003-12-13T18:30:02Z</updated>
          <author> 
            <name>Your Name</name>
          </author> 
          <id>'.$config['url'].'</id>';
		
		if($db->numRows($query) > 0) 
		{
			header('Content-Type: application/xml; charset=utf-8');
			echo '<?xml version="1.0" encoding="UTF-8" ?> ' . "\n";
			echo '<rss version="2.0">' . "\n";
			echo '<channel>' . "\n";
			echo '<title>' . $config['name'] . '</title>' . "\n";
			echo '<link>' . $config['url'] . '</link>' . "\n";
			echo '<language>' . $config['lang'] . '</language>' . "\n";
			echo '<description>' . $config['description'] . '</description>' . "\n";
			echo '<generator>' . $config['engine']['version'] . '</generator>' . "\n";
			echo '<copyright>' . $config['engine']['creators'] . '</copyright>' . "\n";
			
			while($news = $db->getRow($query)) {				
				$description = $core->bbDecode($news['short'], $news['id'], true);
				$news_link = $news['cat'] !== ',0,' ? 'news/' . $core->getCat('news', $news['cat'], 'development') . '/' : 'news/';
				$link = $news_link . $news['altname'] . ".html";
			    echo '<item>' . "\n";
			    echo '<title>' . $news['title'] . '</title>' . "\n";
			    echo '<guid isPermaLink="true">' . $config['url'] . '/' . $link . '</guid>' . "\n";
			    echo '<link>' . $config['url'] . '/' . $link . '</link>' . "\n";
			    echo '<description><![CDATA[' . $description . ']]></description>' . "\n";
			    echo '<category><![CDATA[' . $news['name'] . ']]></category>' . "\n";
			    echo '<pubDate>' . date('D, j M Y H:i:s O', $news['date']) . '</pubDate>' . "\n";
			    echo '</item>' . "\n";
			}
		
			echo '</channel>' . "\n";
			echo '</rss>';
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
echo ' <?xml version="1.0" encoding="utf-8"?>
        <feed xmlns="http://www.w3.org/2005/Atom">
          <title> Feed Title </title> 
          <link href=" http://yourwebsite.com/"/>
          <updated>2003-12-13T18:30:02Z</updated>
          <author> 
            <name>Your Name</name>
          </author> 
          <id>urn:uuid:60a76c80-d399-11d9-b93C-0003939e0af6</id>

          <entry>
            <title>Article Title</title>
            <link href=" http://yourwebsite.com/articlelink.html "/>
            <id>urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344efa6a</id>
            <updated>2003-12-13T18:30:02Z</updated>
            <summary>Some text.</summary>
          </entry>
          <entry>
            <title>Sports</title>
            <link href=" http://yourwebsite.com/sportslink.html "/>
            <id>urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344e45ab90</id>
            <updated>2003-12-14T13:30:55Z</updated>
            <summary>Some text.</summary>
          </entry>

        </feed>';
		break;
	
	case "opensearch":
		$no_head = true;
header('Content-Type: application/xml');
echo <<<HTML
<?xml version="1.0" encoding="utf-8"?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
<ShortName>{$config['name']}</ShortName>
<Description>{$config['description']}</Description>
<Tags>{$config['keywords']}</Tags>
<Url type="application/rss+xml" template="{$config['url']}/feed/opensearch/{searchTerms}"/>
<Url type="application/atom+xml" template="{$config['url']}/feed/opensearch/{searchTerms}"/>
<Url type="text/html" template="{$config['url']}/search/{searchTerms}"/>
<Image height="16" width="16" type="image/vnd.microsoft.icon">{$config['url']}/media/favicon.ico</Image>
<Attribution>{$config['engine']['version']}</Attribution>
<Language>ru</Language>
</OpenSearchDescription>
HTML;
	break;
}