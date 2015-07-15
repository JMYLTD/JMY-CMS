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


global $configs;
$configs['global'] = array
(
'name' => 'Основные конфигурации системы',
'description' => 'Глобальные настройки JMY CMS (meta-теги, сжатие, URLs и др.)',
'param' => 'config'
);

$configs['security'] = array
(
'name' => 'Защита системы',
'description' => 'Настройка системы защиты (каптча, цензор лист, отображение ошибок, кукисы).',
'param' => 'security',
'file' => 'security'
);

$configs['social'] = array
(
'name' => 'Настройки авторизации',
'description' => 'Настройки авторизации через социальные сети.',
'param' => 'social',
'file' => 'social'
);

$configs['files'] = array
(
'name' => 'Управление файлами',
'description' => 'Настройка вложений: размер, форматы, папки',
'param' => 'files_conf',
'file' => 'files'
);

$configs['cache'] = array
(
'name' => 'Настройки кэширования',
'description' => 'Выключение/включение модулей кэш-системы',
'param' => 'allowCahce',
'file' => 'cache'
);

$configs['user'] = array
(
'name' => 'Настройки пользовательской части',
'description' => 'Настройки пользователей, групп, ботов.',
'param' => 'user',
'file' => 'user'
);

$configs['admin'] = array
(
'name' => 'Настройка административной панели',
'description' => 'Настройка вложений: размер, форматы, папки',
'param' => 'admin_conf',
'file' => 'admin'
);

$configs['log'] = array
(
'name' => 'Настройка логов',
'description' => 'Настройка типов ошибок и функций за которыми будет вестись наблюдение.',
'param' => 'log_conf',
'file' => 'log'
);


