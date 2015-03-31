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
 
		$this->setVar('META', $meta);
		$this->setVar('MODULE', $content);		
		
		$this->setVarBlock('BLOCKS:FILE:(.*?)', "\$this->blockParse('\\1', 'file')");
		$this->setVarBlock('BLOCKS:TYPE:(.*?)', "\$this->blockParse('\\1', 'type')");
		$this->setVarBlock('BLOCKS:ID:([0-9]*)', "\$this->blockParse('\\1', 'id')");
		
		$this->setVarTPL('TPL:(.*?)', "\$this->loadTPL('\\1')");

		$this->setVar('FULL_AJAX:start', '<div id="fullAjax">');
		$this->setVar('FULL_AJAX:end', '</div>');
		$this->setVar('GENERATE', mb_substr(microtime(1) - TIMER, 0, 5));
		$this->setVar('GZIP', $config['gzip'] ? 'GZIP Включён' : 'GZIP Выключён');
		$this->setVar('SITE_NAME', $config['name']);
		$this->setVar('SITE_SLOGAN', $config['slogan']);
		$this->setVar('TIME_ZONE', mb_substr($db->timeQueries, 0, 5));
		$this->setVar('MOD_NAME', $url[0]);
		$this->setVar('THEME', 'usr/tpl/'.$this->tplDir);
		$this->setVar('URL', $config['url']);
		$this->setVar('FULL_LNK', $full_lnk);
		$this->setVar('LICENSE', 'Powered by <a target="_blank" href="http://cms.jmy.su/">JMY CMS</a>');
		$this->setVar('D_YEAR', date("Y"));
		$this->setVar('D_MOTH', date("M"));
		$this->setVar('D_DAY',  date("d"));
		$this->setVar('ADMINLOG', $core->auth->isAdmin ? ' <a href="' . ADMIN . '">[Панель управления]</a>' : '');
		$this->setVar('USER_AVATAR', avatar($core->auth->user_id));
		$this->setVar('QUERIES', $db->numQueries);
		
		$this->setVar('URL_LOGIN', 'profile/login');
		$this->setVar('URL_REG', 'profile/register');
		$this->setVar('URL_FORGOT', 'profile/forgot_pass');
		$this->setVar('URL_LOGOUT', 'profile/logout');
		$this->setVar('URL_PROFIL', 'profile');
		$this->setVar('URL_PM', 'pm');
		$this->setVar('URL_BLOG', 'blog');
		$this->setVar('URL_FORUM', 'board');
		$this->setVar('URL_NEWS', 'news');
		$this->setVar('URL_GUEST', 'guestbook');
		$this->setVar('URL_GALLERY', 'gallery');
		$this->setVar('URL_SITEMAP', 'sitemap');
		$this->setVar('URL_FEEDBACK', 'feedback');	
		$this->setVar('SEARCH', 'search');	