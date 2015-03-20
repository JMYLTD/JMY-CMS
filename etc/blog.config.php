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
 
global $blog_conf;
$blog_conf = array();
$blog_conf['postsPerPage'] = 10;
$blog_conf['blogsPerPage'] = 10;
$blog_conf['preModer'] = 0;
$blog_conf['comments'] = 1;
$blog_conf['comperpage'] = 10;
