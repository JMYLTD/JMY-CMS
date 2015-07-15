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
		$this->setVar('LICENSE', 'Powered by <a target="_blank" href="http://jmy.su/">JMY CMS</a>');
		$this->setVar('D_YEAR', date("Y"));
		$this->setVar('D_MOTH', date("M"));
		$this->setVar('D_DAY',  date("d"));
		$this->setVar('ADMINLOG', $core->auth->isAdmin ? ' <a href="' . ADMIN . '">[Панель управления]</a>' : '');
		$this->setVar('USER_AVATAR', avatar($core->auth->user_id));
		$this->setVar('USER_NAME',  $core->auth->user_info['nick']);
		$this->setVar('QUERIES', $db->numQueries);
		$this->setVar('NEW_PM', $core->auth->newPmsNumb);
		$this->setVar('TITLE_NOW', $title_now);
		//ссылки модулей
		$this->setVar('URL_LOGIN', 'profile/login');
		$this->setVar('URL_ADD', '/news/addPost');
		$this->setVar('URL_ADMIN', ADMIN);
		$this->setVar('URL_REG', 'profile/register');
		$this->setVar('URL_FORGOT', 'profile/forgot_pass');
		$this->setVar('URL_LOGOUT', 'profile/logout');
		$this->setVar('URL_PDA', 'index.php?phone_change');
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
		//auth
		$this->setVar('AUTH_VK', $config['url'].'/auth.php?url=vk');
		$this->setVar('AUTH_OK', $config['url'].'/auth.php?url=odnoklassniki');
		$this->setVar('AUTH_FB', $config['url'].'/auth.php?url=facebook');
		$this->setVar('AUTH_GP', $config['url'].'/auth.php?url=google');
		$this->setVar('AUTH_YA', $config['url'].'/auth.php?url=yandex');
		$this->setVar('AUTH_MM', $config['url'].'/auth.php?url=mailru');
		//share
		$this->setVar('SHARE_VK', 'https://vk.com/share.php?url='.$full_lnk.'&title='.$title_now);
		$this->setVar('SHARE_FB', 'http://www.facebook.com/share.php?u='.$full_lnk);
		$this->setVar('SHARE_TW', 'http://twitter.com/timeline/home?status='.$full_lnk.'%20'.$title_now);
		$this->setVar('SHARE_GP', 'https://plus.google.com/share?url='.$full_lnk);
		$this->setVar('SHARE_LJ', 'http://www.livejournal.com/update.bml?mode=full&subject=test&event='.$full_lnk);
		$this->setVar('SHARE_BZ', 'http://www.google.com/buzz/post?message='.$title_now.'&url='.$full_lnk.'&hl=ru');
		$this->setVar('SHARE_TB', 'http://www.tumblr.com/share/link?url='.$full_lnk.'&amp;title='.$title_now);
		$this->setVar('SHARE_LI', 'http://www.linkedin.com/shareArticle?mini=true&amp;url='.$full_lnk);
		$this->setVar('SHARE_PP', 'https://plus.google.com/share?url='.$full_lnk);
		$this->setVar('SHARE_SB', 'https://plus.google.com/share?url='.$full_lnk);
		$this->setVar('SHARE_YA', 'https://plus.google.com/share?url='.$full_lnk);
		$this->setVar('SHARE_MM', 'http://connect.mail.ru/share?url='.$full_lnk.'&title='.$title_now.'&description=&imageurl=');
		$this->setVar('SHARE_PR', 'javascript:window.print();');
		$this->setVar('SHARE_MA', 'mailto:?Subject='.$title_now.'&amp;Body=Share%20from%20'.$config['name'].'%20'.$full_lnk);
		$this->setVar('SHARE_PT', 'javascript:void((function()%7Bvar%20e=document.createElement(\'script\');e.setAttribute(\'type\',\'text/javascript\');e.setAttribute(\'charset\',\'UTF-8\');e.setAttribute(\'src\',\'http://assets.pinterest.com/js/pinmarklet.js?r=\'+Math.random()*99999999);document.body.appendChild(e)%7D)());');
		
		

		