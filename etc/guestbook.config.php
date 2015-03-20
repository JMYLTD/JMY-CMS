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


global $guestbook_conf;
$guestbook_conf = array();
$guestbook_conf['comments_num'] = "2";
$guestbook_conf['reply_mail'] = "1";
$guestbook_conf['keywords'] = "гостевая книга, guest book, гости, отзывы";
$guestbook_conf['description'] = "Гостевая книга это место где каждый сможет оставить отзыв о работе сайта!";

