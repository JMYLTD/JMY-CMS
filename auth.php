<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ACCESS', true);
define('VERSION_ID', '1.7'); 
define('TIMER', microtime(1));
define('ROOT', dirname(__FILE__) . '/');
define('PLUGINS', dirname(__FILE__) . '/usr/plugins/');
define('COOKIE_AUTH', 'auth_jmy');
define('COOKIE_PAUSE', 'pause_jmy');
define('AJAX', true);
define('PAUSE_TIME', 120);
define('COOKIE_TIME', 2592000);
define('ADMIN', 'administration');
define('HACK_SQL', '/SELECT|INSERT|ALTER|DROP|UNION|OUTFILE|WHERE/i');
define('DENIED_HTML', '/<.*?(script|meta|body|object|iframe|frame|applet|style|form|img|onmouseover).*?>/i');

define('INDEX', isset($_GET['url']) ? false : true);

session_start();

require ROOT . 'boot/sub_classes/socialauther/autoload.php';
require ROOT . 'etc/social.config.php';	


$adapterConfigs = array(
    'vk' => array(
        'client_id'     => $social['vk_client_id'],
        'client_secret' => $social['vk_client_secret'],
        'redirect_uri'  => 'http://jmy.com/auth.php?provider=vk'
    ),
    'odnoklassniki' => array(
        'client_id'     => '168635560',
        'client_secret' => 'C342554C028C0A76605C7C0F',
        'redirect_uri'  => 'http://localhost/auth?provider=odnoklassniki',
        'public_key'    => 'CBADCBMKABABABABA'
    ),
    'mailru' => array(
        'client_id'     => '770076',
        'client_secret' => '5b8f8906167229feccd2a7320dd6e140',
        'redirect_uri'  => 'http://jmy.com/auth.php?provider=mailru'
    ),
    'yandex' => array(
        'client_id'     => '8a9cf6f8b9f24f5eba493cdac7a60097',
        'client_secret' => 'c79c0b3f98ac45b99d9a67216b251d4b',
        'redirect_uri'  => 'http://jmy.com/auth.php?provider=yandex'
    ),
    'google' => array(
        'client_id'     => '333193735318.apps.googleusercontent.com',
        'client_secret' => 'lZB3aW8gDjIEUG8I6WVcidt5',
        'redirect_uri'  => 'http://jmy.com/auth.php?provider=google'
    ),
    'facebook' => array(
        'client_id'     => '613418539539988',
        'client_secret' => '2deab137cc1d254d167720095ac0b386',
        'redirect_uri'  => 'http://jmy.com/auth.php?provider=facebook'
    )
);

function redicret($message, $url = 'news', $text = 'Спасибо') 
			{
				$full_url = $url;
				include(ROOT . 'usr/tpl/redirect.tpl');	
			}	
			
$adapters = array();
foreach ($adapterConfigs as $adapter => $settings) {
    $class = 'SocialAuther\Adapter\\' . ucfirst($adapter);
    $adapters[$adapter] = new $class($settings);
}
if (isset($_GET['provider']) && array_key_exists($_GET['provider'], $adapters) && !isset($_SESSION['user_auth'])) 
{
    $auther = new SocialAuther\SocialAuther($adapters[$_GET['provider']]);
    if ($auther->authenticate()) 
	{      
            $values = array(
                $auther->getProvider(),
                $auther->getSocialId(),
                $auther->getName(),
                $auther->getEmail(),
                $auther->getSocialPage(),
                $auther->getSex(),
                date('Y-m-d', strtotime($auther->getBirthday())),
                $auther->getAvatar()
            );
			
			$user = new stdClass();
			$user->provider   = $auther->getProvider();
			$user->socialId   = $auther->getSocialId();
			$user->name       = $auther->getName();
			$user->email      = $auther->getEmail();
			$user->sex        = $auther->getSex();
			$user->birthday   = $auther->getBirthday(); 
			$user->avatar     = $auther->getAvatar();
			
			$_SESSION['user_auth'] = $user;
			
			redicret('Вы успешно вышли сейчас вас переместит обратно!', '/profile/social_auth');
			
    }
	else
	{
			redicret('Вы успешно вышли сейчас вас переместит обратно!', '');
	}  
}
else if (isset($_GET['url']) && array_key_exists($_GET['url'], $adapters) && !isset($_SESSION['user_auvth'])) 
{	
	$url=ucfirst($_GET['url']);	
	foreach ($adapters as $title => $adapter) 
	{
		if (ucfirst($title)==$url)
		{
			header('Location: '.$adapter->getAuthUrl());			
		}
    }
	
}
else
{
	if (isset($_SESSION['user_auth']))
	{
		header('Location: /profile/social_auth');
	}
	else
	{
		header('Location: /index.php');
	}   
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title></title>
</head>
<body>


</body>
</html>