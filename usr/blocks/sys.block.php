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

/**
 * Установка предупреждений
 */
$php = phpversion() < '5.1.0' ? '#FF0000' : '';
$MySQLVersion = mysql_fetch_array(mysql_query('SELECT VERSION()'));
$mysql = $MySQLVersion[0] < '4.4.4' ? '#FF0000' : '#009900';
$post_size = SubStr(ini_get('post_max_size'), 0, -1) < '8' ? '#FF0000' : '#009900';
$execution = ini_get('max_execution_time') < '30' ? '#FF0000' : '#009900';
$register = ini_get('register_globals') == true ? '#FF0000' : '#009900';
$safe_mode = ini_get('safe_mode') == false ? '#FF0000' : '#009900';
$magic_quotes = ini_get('magic_quotes_gpc') == false ? '#FF0000' : '#009900';

/**
 * Вывод блока
 */
echo 'PHP version: <span style="color: '.$php.'">'.phpversion().'</span><br />';
echo 'MySQL version: <span style="color: '.$mysql.'">'.$MySQLVersion[0].'</span><br />';
echo 'Post size: <span style="color: '.$post_size.'">'.ini_get('post_max_size').'</span><br />';
echo 'Execution time: <span style="color: '.$execution.'">'.ini_get('max_execution_time').'</span><br />';
echo 'Register globals: <span style="color: '.$register.'">'.(ini_get('register_globals') ? 'on' : 'off').'</span><br />';
echo 'Safe mode: <span style="color: '.$safe_mode.'">'.(ini_get('safe_mode') ? 'on' : 'off').'</span><br />';
echo 'Magic quotes gpc: <span style="color: '.$magic_quotes.'">'.(ini_get('magic_quotes_gpc') ? 'on' : 'off').'</span><br />';
?>