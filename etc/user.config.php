<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


$user = array();
$user['guestGroup'] = "3";
$user['botGroup'] = "4";
$user['banGroup'] = "5";
$user['count_points'] = "1";
$user['noAvatar'] = "files/avatars/no_avatar.png";
$user['avatar_load'] = "1";
$user['avatar_width'] = "100";
$user['avatar_height'] = "100";
$user['avatar_size'] = "204800";
$user['with_activate'] = "1";
$user['activeFlash'] = "1";
$user['activeVideo'] = "1";
$user['activeAudio'] = "1";
$user['activeAttach'] = "0";
$user['editor'] = "bb";
$user['bbViz'] = "1";
$user['highlightCode'] = "1";
$user['commentOften'] = "10";
$user['commentEditText'] = "[color=green]Сообщение отредактировано {user}, {date}[/color]";
$user['commentSignature'] = "<br />-------------------- <br /><noindex>[sig]</noindex>";
$user['commentSubscribe'] = "1";
$user['commentModeration'] = "1";
$user['commentTree'] = "1";
$user['pmShown'] = "1";
$user['isBan'] = "1";
$user['userWall'] = "1";
$user['userWallNum'] = "10";
$user['userFriends'] = "1";
$user['userGuests'] = "1";
$user['readBlog'] = "1";
$user['lang'] = "ru";

