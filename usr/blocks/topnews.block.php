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

$num = 5;


echo '';

$query = $db->query("SELECT c.*, l.* FROM " . DB_PREFIX . "_news as c LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=c.id and l.module='news') WHERE l.lang = 'ru'  ORDER BY views DESC LIMIT " . $num . "");

$i = 0;
if($db->numRows($query) > 0) 
{
	while($row = $db->getRow($query))
	{
		echo '<li><a href="/news/' . $row['altname'] . '.html">' . $row['title']  .'</a></li> ';
	  
	}
}

echo '';