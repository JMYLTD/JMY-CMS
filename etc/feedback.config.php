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


global $feedback_conf;
$feedback_conf = array();
$feedback_conf['allow_attach'] = "1";
$feedback_conf['formats'] = "jpg,zip,rar";
$feedback_conf['file_size'] = "10000000";
$feedback_conf['keywords'] = "обратная связь, контакты, feedback, связь с администрацией";
$feedback_conf['description'] = "Обратная связь, место где вы можете связаться с администрацией сайта!";

