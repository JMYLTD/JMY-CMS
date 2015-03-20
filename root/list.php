<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision    24.02.2015
*/

if (!defined('ACCESS')) {
	header('Location: /');
	exit;
}

$component_array = array(
	'config' => array (
		'name' => _AP_CONF,
		'desc' => _AP_CONF_DESC,		
		'shown' => 1,
		'subAct' => array(
			_AP_CONF_CONF => '',
			_AP_CONF_COPY => 'backup',
		),
	),
	'blocks' => array (
		'name' => _AP_BLOCKS,
		'desc' => _AP_BLOCKS_DESC,		
		'subAct' => array(
			_AP_BLOCKS_C => '',
			_AP_BLOCKS_ADD => 'add',
			_AP_BLOCKS_TYPE => 'types',
			_AP_BLOCKS_ATYPE => 'typeAdd',
			_AP_BLOCKS_SORT => 'resort',
		),
		'shown' => 1
	),		
	'cats' => array (
		'name' => _AP_CATS,
		'desc' => _AP_CATS_DESC,		
		'subAct' => array(
			_AP_CATS_LIST => '',
			_AP_CATS_ADD => 'add',
			_AP_CATS_FADD => 'scan',
		),
		'shown' => 1
	),
	
	'comments' => array (
		'name' => _AP_COMMENTS,
		'desc' => _AP_COM_DESC,	
		'subAct' => array(
			_AP_COMMENTS_MAIN => '',
			_AP_COMMENTS_MODER => 'moder',
		),
		'shown' => 1
	),	
	'user' => array (
		'name' => _AP_USERS,
		'desc' => _AP_USERS_DESC,		
		'subAct' => array(
			_LIST => '',
			_GROUPS => '../groups',
		),
		'shown' => 1
	),
	'groups' => array(
		'name' => _AP_GROUPS,
		'desc' => _AP_GROUPS_DESC,
		'subAct' => array(
			_LIST => '',
			_AP_GROUPS_ADD => 'add',
			_AP_GROUPS_POINTS => 'points',
		),
		'shown' => 1
	),
	
	'xfields' => array (
		'name' => _AP_XFIELDS,
		'desc' => _AP_XFIELDS_DESC,		
		'subAct' => array(
		
			_LIST => '',
			_AP_XFIELDS_ADD => 'add',
		),
		'shown' => 1
	),		
	
	'modules' => array (
		'name' => _AP_MODS,
		'desc' => _AP_MODS_DESC,		
		'shown' => 1
	),		
	'publications' => array (
		'name' => _AP_PUB ,
		'desc' => _AP_PUB_DESC,		
		'shown' => 1
	),	
);

$services_array = array(	
	'db' => array (
		'name' => _AP_DB,
		'desc' => _AP_DB_DESC,		
		'subAct' => array(
			_AP_DB_TABLES => '',
			_AP_DB_OPTIMIZE => 'optimize',
			_AP_DB_REPAIR => 'repair',
			_AP_DB_BACKUP => 'backup',
			_AP_DB_FIX => 'fix',
		),
		'shown' => 1
	),		
	'smiles' => array (
		'name' => _AP_SMILES,
		'desc' => _AP_SMILES_DESC,			
		'shown' => 1
	),	
	'voting' => array (
		'name' => _AP_POLL,
		'desc' => _AP_POLL_DESC,		
		'subAct' => array(
			_AP_POLL_LIST => '',
			_AP_POLL_ADD => 'add'
		),
		'shown' => 1
	),	
	'statistic' => array (
		'name' => _AP_STATS,
		'desc' => _AP_STATS_DESC,		
		'shown' => 1
	),	
	'log' => array (
		'name' => _AP_LOGS,
		'desc' => _AP_LOGS_DESC,		
		'shown' => 1
	),
	'lang' => array (
		'name' => _AP_LANG,
		'desc' => _AP_LANG_DESC,	
		'subAct' => array(
			_AP_LANG_MAIN => '',
			_AP_LANG_PANEL => 'panel',
			_AP_LANG_MODULES => 'modules',
		),				
		'shown' => 1
	),
	'templates' => array (
		'name' => _AP_TPL,
		'desc' => _AP_TPL_DESC,	
		'subAct' => array(
			_AP_TPL_ALL => '',
			_AP_TPL_TPL => 'edit_tpl',
			_AP_TPL_CSS => 'edit_css',
		),		
		'shown' => 1
	)
);