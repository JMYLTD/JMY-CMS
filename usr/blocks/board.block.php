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
$noShow = '';

echo '<table width="100%" border="0" cellspacing="1" cellpadding="3" class="boardBlockTable">' . "\n";
echo '<tr><th colspan="2">Название темы</th><th width="3%">Отв.</th><th width="10%">Автор</th><th width="3%">Пр.</th><th width="140">Последнее</th></tr>' . "\n";
$result = $db->query("SELECT t.*, u.nick FROM `" . DB_PREFIX . "_board_threads` as t LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on(t.poster = u.id) " . (!empty($noShow) ? 'WHERE t.forum NOT IN (' . $noShow . ')' : '') . " ORDER BY lastTime DESC LIMIT 0," . $num . "");
if($db->numRows($result) > 0) 
{
	while($row = $db->getRow($result))
	{
		echo '<tr><td width="16">' . ($row['icon'] ? '<img src="media/board/theme_icon/' . $row['icon'] . '" border="0" alt="" />' : '<img src="media/board/noicon.png" border="0" alt="" />') . '</td><td><a href="board/topic-' . $row['id'] . '/getlastpost" title="Просмотр темы">' . ($row['important'] == 1 ? '<font color="red"><b>' . $row['title'] . '</b></font>' : $row['title']) . '</a></td><td align="center">' . $row['replies'] . '</td><td align="center"><a href="profile/' . $row['nick'] . '" title="Автор">' . $row['nick'] . '</a></td><td align="center">' . $row['views'] . '</td><td align="center"><a href="board/topic-' . $row['id'] . '/getlastpost" title="К последнему сообщению"><img src="media/board/up.png" border="0" alt="Последнее сообщение" style="vertical-align:middle" /></a>' . formatDate($row['lastTime'], true) . ' <br /><a href="profile/' . $row['lastPoster'] . '" title="Последний ответевший"><strong>' . $row['lastPoster'] . '</strong></a></td></tr>' . "\n";
	}
}
echo '</table>' . "\n";