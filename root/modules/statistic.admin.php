<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

$adminTpl->admin_head('Статистика системы');

    $result = mysql_query( "SHOW TABLE STATUS" );
    $dbsize = 0;
	
    while( $row = mysql_fetch_array( $result ) ) {  
	
        $dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];
		
    }
	
list($news) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_news WHERE active=1"));
list($newsModer) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_news WHERE active=0"));
list($users) = $db->fetchRow($db->query("SELECT COUNT(*) FROM  `" . USER_DB . "`.`" . USER_PREFIX . "_users`"));
list($cats) = $db->fetchRow($db->query("SELECT COUNT(*) FROM  ".DB_PREFIX."_categories"));
list($contents) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_content"));
list($comments) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_comments"));
list($online) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_online"));
list($blogs) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_blogs"));
list($bposts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_blog_posts"));
list($forums) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_board_forums"));
list($fposts) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_board_posts"));
list($albums) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_gallery_albums"));
list($photos) = $db->fetchRow($db->query("SELECT COUNT(*) FROM ".DB_PREFIX."_gallery_photos"));
$cacheSize = formatfilesize(dirsize(ROOT.'tmp/mysql')+dirsize(ROOT.'tmp/cache'));
$dbSize = formatfilesize($dbsize);

$adminTpl->open();
echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">								
						<b>Статистика системы:</b>
					</div>
					<div class="panel-body">
					
				';

echo <<<HTML
	<div style="float:left; width:60%; padding-top:20px;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
		    <td width="35%"><b>Новостей:</b></td>
		    <td><font color="green">[{$news}] из них  [{$newsModer}] на модерации- <a href="administration/mod/news">все новости</a>, <a href="administration/publications/mod/news">на модерации</a></font></td>
		  </tr>				  
		  <tr>
		    <td width="35%"><b>Пользователи:</b></td>
		    <td><font color="green">[{$users}] - <a href="administration/user">все пользователи</a></font></td>
		  </tr>		  
		    <td width="30%"><b>Категорий:</b></td>
		    <td><font color="green">[{$cats}] - <a href="administration/cats">все категории</a></font></td>
		  </tr>
          <tr>
		    <td width="30%"><b>Статических страниц:</b></td>
		    <td><font color="green">[{$contents}]</font></td>
		  </tr>
          		  <tr>
		    <td width="35%"><b>Всего комментариев:</b></td>
		    <td><font color="green">[{$comments}]</font></td>
		  </tr>	
          		  <tr>
		    <td width="35%"><b>Пользователей онлайн:</b></td>
		    <td><font color="green">[{$online}]</font></td>
		  </tr>	
          <td width="35%"><b>Тиц:</b></td>
		    <td><font color="green"><div id="tic">[ <a href="javascript:void(0)" onclick="ajaxSimple('index.php?url={ADMIN}/do/tic', 'tic')">получить</a> ]</div></font></td>
		  </tr>   
          <td width="35%"><b>PR:</b></td>
		    <td><font color="green"><div id="pr">[ <a href="javascript:void(0)" onclick="ajaxSimple('index.php?url={ADMIN}/do/pr', 'pr')">получить</a> ]</div></font></td>
		  </tr>   
      
		</table>
	</div>
	<div style="float:left; width:40%;">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
			  	  
		  <tr>
		    <td width="35%"><b>Создано блогов:</b></td>
		    <td><font color="green">[{$blogs}]</font></td>
		  </tr>	

         <tr>
		    <td width="35%"><b> Сообщений в блогах:</b></td>
		    <td><font color="green"> [{$bposts}] </font></td>
		  </tr>
		<tr>
		    <td width="35%"><b>Категорий форума:</b></td>
		    <td><font color="green">[{$forums}]</font></td>
		  </tr>   
          
                       <tr>
		    <td width="35%"><b>Сообщений на форуме:</b></td>
		    <td><font color="green">[{$fposts}]</font></td>
		  </tr>   
      
                             <tr>
		    <td width="35%"><b>Альбомов в галереи:</b></td>
		    <td><font color="green">[{$albums}]</font></td>
		  </tr>   
          
          		    <td width="35%"><b>Фото в галереи:</b></td>
		    <td><font color="green">[{$photos}]</font></td>
		  </tr>   
                		    <td width="35%"><b>Кэш:</b></td>
		    <td><font color="green">{$cacheSize}</font></td>
		  </tr>   
      
      
             <tr>
		    <td width="35%"><b>Размер БД:</b></td>
		    <td><font color="green">{$dbSize}</font></td>
		  </tr>       
   
		</table>  </div> 

<hr>
<strong style="color:#0099FF">Информация о сервере:</strong><br>
HTML;


$dfs = @disk_free_space( "." );
$freespace = formatfilesize( $dfs );
$php = phpversion() < '5.1.0' ? '#FF0000' : '';
$MySQLVersion = mysql_fetch_array(mysql_query('SELECT VERSION()'));
$mysql = $MySQLVersion[0] < '4.4.4' ? '#FF0000' : '#009900';
$post_size = SubStr(ini_get('post_max_size'), 0, -1) < '8' ? '#FF0000' : '#009900';
$execution = ini_get('max_execution_time') < '30' ? '#FF0000' : '#009900';
$register = ini_get('register_globals') == true ? '#FF0000' : '#009900';
$safe_mode = ini_get('safe_mode') == false ? '#FF0000' : '#009900';
$magic_quotes = ini_get('magic_quotes_gpc') == false ? '#FF0000' : '#009900';


echo 'Операционная система: <span style="color:#00CC33">'.@php_uname( "s" ) . " " . @php_uname( "r" ).'</span><br />';
echo 'Версия PHP: <span style="color:#00CC33">'.phpversion().'</span> <a href="#" title="Версия php должна быть не мение 5.2">[ ? ]</a><br />';
echo 'Версия MySQL: <span style="color: '.$mysql.'">'.$MySQLVersion[0].'</span> <a href="#" title="Версия MySQL должна быть не мение 5">[ ? ]</a><br />';
echo 'Post size: <span style="color: '.$post_size.'">'.ini_get('post_max_size').'</span> <a href="#" title="Рекомендуем минимум 50М">[ ? ]</a><br />';
echo 'Максимальное время выполнения скрипта: <span style="color: '.$execution.'">'.ini_get('max_execution_time').'</span> <a href="#" title="Если значение 0 значит ограничений нет">[ ? ]</a><br />';
echo 'Register globals: <span style="color: '.$register.'">'.(ini_get('register_globals') ? 'Включен' : 'Выключен').'</span> <a href="#" title="Должен быть включен!">[ ? ]</a><br />';
echo 'Safe mode: <span style="color: '.$safe_mode.'">'.(ini_get('safe_mode') ? 'Включен' : 'Выключен').'</span> <a href="#" title="Эта функция должна быть выключена!">[ ? ]</a><br />';
echo 'Magic quotes gpc: <span style="color: '.$magic_quotes.'">'.(ini_get('magic_quotes_gpc') ? 'Включен' : 'Выключен').'</span> <a href="#" title="Эта функция должна быть выключена!">[ ? ]</a><br />';
echo 'Размер свободного места на диске: <span style="color:#00CC33">'.formatfilesize( $dfs ).'</span><br />';

$ip = getRealIpAddr();
$br = strtok($_SERVER['HTTP_USER_AGENT'], ' ');
echo <<<HTML
<hr><strong style="color:#0099FF">Информация о вас:</strong><br>
<b>Ваш IP:</b>
{$ip}<br> 
<b>Браузер:</b>
{$br}<br>
<script language="JavaScript">
<!--
var height=0; var width=0;
if (self.screen) {
width = screen.width
height = screen.height
}
else if (self.java) {
var jkit = java.awt.Toolkit.getDefaultToolkit();
var scrsize = jkit.getScreenSize();
width = scrsize.width;
height = scrsize.height;
}
//-->
</script> 
<script language="JavaScript">
<!--
if (width > 0 && height > 0) {
document.writeln('<b>Разрешение экрана:</b> ',width,'x',height)
} else {
document.writeln('<b>неизвестно</b>')
} 
//-->
</script>
HTML;
echo '	</div>
				</section>
			</div>
		</div>';
$adminTpl->close();
$adminTpl->admin_foot();
?>