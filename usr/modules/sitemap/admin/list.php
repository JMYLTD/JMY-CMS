<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ACCESS')) {
	header('Location: /');
	exit;
}

$module_array['sitemap'] = array(
		'name' => _SM_SITEMAP,
		'desc' => _SM_SITEMAP_DESC,
		'subAct' => array(
			_SM_SITEMAP => '',
			_SM_SITEMAP_GEN => 'create',
			_SM_SITEMAP_UPDATE => 'update',						
			_CONFIG => 'config',
		)
);

$toconfig['sitemap'] = array
(
	'name' => _SM_SITEMAP,
	'link' => 'module/sitemap/config',
	'param' => 'sitemap_config'
);