<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $allowCahce;
$allowCahce = array();
$allowCahce['tplFiles'] = "1";
$allowCahce['plugins'] = "1";
$allowCahce['categories'] = "1";
$allowCahce['userInfo'] = "1";

