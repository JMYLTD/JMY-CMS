<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/


if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
loadConfigBLOCK('online');
loadLang('blocks');
global $user, $core, $online_conf;

foreach($core->auth->groups_array as $gid => $groups)
{
	$color[$gid] = $groups['color'];
	$gname[$gid] = $groups['name'];
}
$on = $db->query("SELECT o.*, p.nick FROM " . DB_PREFIX . "_online AS o LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` AS p ON (o.uid=p.id)");
while($online = $db->getRow($on)) 
{
	$onn[] = array($online['nick'], $online['ip'], $online['url'], $online['uid'], $online['group']);
}
if(!empty($onn))
{
	$guests = 0;
	foreach($onn as $info)
	{
		$group = $info[4];
		if($group == $user['guestGroup'])
			$guests++;
		elseif($group != $user['botGroup'])
			$users[] = $info;
		elseif($group == $user['botGroup'])
			$bots[] = $info;
	}
	if ($online_conf['user']=="1") 
	{
		if(!empty($users))
		{
			$nUs = count($users);
			$perColU = ceil($nUs/2);
			echo '<b>'._BLOCK_ONLINE_USER_ON.':</b>';
			echo '<table width="100%" border="0" cellspacing="1" cellpadding="3"><tr><td valign="top">';
			$n = 0;
			foreach($users as $uInfo)
			{
				$n++;
				echo '<a href="profile/' . $uInfo[0] . '" title="' . $uInfo[0] . ' - ' . $gname[$uInfo[4]] . '"><font color="' . $color[$uInfo[4]] . '">'.$uInfo[0] . '</font></a>';
				
				if($n == $perColU)
				{
					echo '</td><td valign="top">';
				}
			}
			echo '</td></tr></table>';
		}
		else
		{
			echo '<b>'._BLOCK_ONLINE_USER_EMPTY.'</b><br>';
		}
	}
	
	
	if ($online_conf['bot']=="1") {
	if(!empty($bots))
	{
		$perColB = ceil(count($bots)/2);
		$b = 0;
		echo '<b>'._BLOCK_ONLINE_BOT.'</b>';
		echo '<table width="100%" border="0" cellspacing="1" cellpadding="3"><tr><td valign="top">';
		foreach($bots as $bInfo)
		{
			$b++;
			echo SpiderDetect(false, false, $info[3]);
			if($b == $perColB)
			{
				echo '</td><td valign="top">';
			}
		}
		echo '</td></tr></table>';
	}
	}
	
	if ($online_conf['guest']=="1") {
	echo '<b>'._BLOCK_ONLINE_GUEST_ON.':</b> '.$guests;
	}
}

if ($online_conf['top']=="1") 
{
	$usr = $db->query("SELECT `nick`, `group` FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE last_visit > " . (time()-86400) . " LIMIT ".$online_conf['top_numb']);
	if($db->numRows($usr) > 0) 
	{
		echo '<br /><br /><b>'.$online_conf['top_numb']._BLOCK_ONLINE_TOP_ON.':</b>';
		echo '<table width="100%" border="0" cellspacing="1" cellpadding="3"><tr><td valign="top">';
		$y = 0;
		$perColY = ceil($db->numRows($usr)/2);
		while($rows = $db->getRow($usr)) 
		{
			$y++;
			echo '<a href="profile/' . $rows['nick'] . '" title="' . $rows['nick'] . ' - ' . $gname[$rows['group']] . '"><font color="' . $color[$rows['group']] . '">'.$rows['nick'] . '</font></a><br />';
			if($y == $perColY)
			{
				echo '</td><td valign="top">';
			}
		}
		echo '</td></tr></table>';
	}
}
