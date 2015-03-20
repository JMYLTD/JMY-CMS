<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $security;
$security = array();
$security['xNums'] = "12345";
$security['stopNick'] = "%laed*";
$security['stopMails'] = "";
$security['stopWords'] = "хуй,пизда,шлюха";
$security['stopReplace'] = "*Цензор*";
$security['allowHTML'] = "b,i,s,u,font";
$security['banIp'] = "192.15.*.%
127.1.*.*
23.2332.2323.3";
$security['banIpMessage'] = "Вас забанили!";
$security['captcha_width'] = "120";
$security['captcha_height'] = "60";
$security['captcha_lenght'] = "6";
$security['lang'] = "ru";

