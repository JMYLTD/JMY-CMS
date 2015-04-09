<?php
if (!defined('ACCESS')) 
{
    header('Location: /');
    exit;
}


global $news_conf;
$news_conf = array();
$news_conf['num'] = "5";
$news_conf['comments_num'] = "10";
$news_conf['fullLink'] = "1";
$news_conf['noModer'] = "1";
$news_conf['preModer'] = "5";
$news_conf['related_news'] = "5";
$news_conf['addNews'] = "1";
$news_conf['showCat'] = "1";
$news_conf['subLoad'] = "1";
$news_conf['catCols'] = "3";
$news_conf['showBreadcumb'] = "1";
$news_conf['tags'] = "1";
$news_conf['tags_num'] = "3";
$news_conf['tagIll'] = "0";
$news_conf['illFormat'] = "<b>{tag}</b>";
$news_conf['limitStar'] = "5";
$news_conf['starStyle'] = "";
$news_conf['carma_rate'] = "1";
$news_conf['carma_summ'] = "1";
$news_conf['fileEditor'] = "1";
$news_conf['imgFormats'] = "jpg,gif,png";
$news_conf['attachFormats'] = "zip,rar,mp3,avi,mp4,flv,3gp,torrent";
$news_conf['max_size'] = "1024000000000000000";
$news_conf['thumb_width'] = "600";

