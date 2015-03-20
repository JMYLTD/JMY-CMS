<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

$db->query("UPDATE `" . DB_PREFIX . "_blog_posts` SET `comments` = `comments`" . $do . "1 WHERE `id` = $id");