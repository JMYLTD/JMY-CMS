<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $poll_conf;
$poll_conf = array();
$poll_conf['poll_id'] = "2";
$poll_conf['poll_rand'] = "1";

