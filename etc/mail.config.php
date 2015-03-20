<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $mail_conf;
$mail_conf = array();
$mail_conf['register'] = 1;
$mail_conf['subscribeComments'] = 'Вы подписались на комментарии!';