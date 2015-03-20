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
global $core;
$core->module->rating->table = '_gallery_photos';
$core->module->rating->id = 'photo_id';
$core->module->rating->votes = 'ratings';