<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $cats;
$cats = array();
$cats['module'] = "news";

