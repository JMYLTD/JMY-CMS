<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/
 
//Редакцтя от 10.01.2015
 
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
loadConfig('guestbook');

switch(isset($url[1]) ? $url[1] : null) 
{
	default:		
			$page = init_page();
			$cut = ($page-1)*$guestbook_conf['comments_num'];
			
			set_title(array(_G_GUESTBOOK));
			if(!empty($guestbook_conf['keywords']))
			{
				$core->tpl->keywords =$guestbook_conf['keywords'];
			}
			if(!empty($guestbook_conf['description']))
			{
				$core->tpl->description = $guestbook_conf['description'];
			}
			
			
			$where = '';
			$file = 'index';
			$link = '';			
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_guestbook ORDER BY id ASC LIMIT " . $cut . ", " . $guestbook_conf['comments_num'] . "");			
			
			if($db->numRows($query) > 0) 
			{
			$core->tpl->open('g_up');
			$core->tpl->loadFile('guestbook/g_up');	
			$core->tpl->end();
			$core->tpl->close();
				while($guestbook = $db->getRow($query))
				{
						$core->tpl->loadFile('guestbook/guestbook_view');
						$core->tpl->setVar('AVATAR', 'media/avatar/'.(($guestbook[gender]==1) ? 'male.jpg' : 'female.jpg'));
						$core->tpl->setVar('NAME', $guestbook[name] );
						$core->tpl->setVar('EMAIL', $guestbook[email] );
						$core->tpl->setVar('ID', $guestbook[id] );
						$core->tpl->setVar('DATE', formatDate($guestbook[date]) );
						$core->tpl->setVar('WEBSITE', (!empty($guestbook[website]) ? _G_WEBSITE.': '.$guestbook[website] : _G_WEBSITE_0));
						$core->tpl->setVar('REPLY', $guestbook[reply]);
						$array_replace["#\\[reply\\](.*?)\\[/reply\\]#is"] = (!empty($guestbook[reply]) ? '\\1' : '');						
						$core->tpl->setVar('REPLY_FLAG', (!empty($guestbook[reply]) ? _G_REPLY_1 : _G_REPLY_0));					
						$core->tpl->setVar('COMMENT', $guestbook[comment]);
						$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);
						$core->tpl->end();			
				}	
				
			
			
			$core->tpl->open('g_down');
			$core->tpl->loadFile('guestbook/g_down');	
			$core->tpl->end();			
			$core->tpl->close();
			
			list($all) = $db->fetchRow($db->query("SELECT Count(id) FROM ".DB_PREFIX."_guestbook"));
			$core->tpl->pages($page, $guestbook_conf['comments_num'], $all, 'guestbook/{page}');	
			
			}
			else {
			$core->tpl->info(_G_NULL);
			}
			$core->tpl->open('guestbook_entry');
			$core->tpl->loadFile('guestbook/guestbook_entry');	
			$core->tpl->setVar('CAPTCHA', captcha_image());
			$core->tpl->end();
			$core->tpl->close();			
			
		break;
		
	case "send":
	set_title(array(_G_GUESTBOOK, _SENDINGMESS));
		if(captcha_check('securityCode')) 
		{
			$site = isset($_POST['site']) ? filter($_POST['site']) : '';
			$gender = isset($_POST['gender']) ? intval($_POST['gender']) : '1';
			$email = isset($_POST['email']) ? filter($_POST['email']) : '';
			$name = isset($_POST['name']) ? filter($_POST['name']) : '';
			$message = isset($_POST['message']) ? parseBB(processText(filter($_POST['message'], 'html'))) : '';
	

			if(!empty($name) && !empty($message)) 
			{				
					
				$db->query("INSERT INTO `" . DB_PREFIX . "_guestbook` (`date` ,`name` ,`email` ,`website` ,`comment`, `gender`) VALUES (" . time() . ", '".$name."', '".$email."', '".$site."', '".$message."' , '".$gender."' );");		
				$core->tpl->info(_SENDOK);
			} 
			else 
			{
				$core->tpl->info(_SENDFALSE, 'warning');
			}
		} 
		else 
		{
			$core->tpl->info(_CAPTCHAFALSE, 'warning');
		}
		break;

}