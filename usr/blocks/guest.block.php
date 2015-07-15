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

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "_guestbook`" . (!empty($noShow) ? 'WHERE name NOT IN (' . $noShow . ')' : '') . " ORDER BY id DESC LIMIT " . $num . "");

$i = 0;
if($db->numRows($query) > 0) 
{
	while($row = $db->getRow($query))
	{
		echo '<li>
		<img src="' .'media/avatar/'. (($row['gender']==0) ? 'male.jpg' : 'female.jpg') . '">
		<a href="/guestbook/"><strong>' . $row['name']  .' Пишет:</strong> ' . $row['comment']  .'</a>
        <div class="date-like-comment">
		<span class="date"><time datetime="2014-02-17">' . formatDate($row['date']) . '</time></span>
		<a class="comments" href="/guestbook/"><i class="fa fa-check"></i> ' . (!empty($row['reply']) ? _G_REPLY_1 : _G_REPLY_0) . '</a>
		<a class="like" href="mailto:' . $row['email'] . '"><i class="fa fa-envelope"></i> Email автора</a>
		<a class="like" href="' . $row['website'] . '" target="blank"><i class="fa fa-globe"></i> Сайт автора</a>
		</div>
		</li>';
	  
	}
}

echo '';