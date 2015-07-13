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
				if (!empty($guestbook['uid']))
				{
					$query_user = $db->query("SELECT * FROM `" . DB_PREFIX . "_users` WHERE id = '" . $guestbook['uid'] . "' LIMIT 1");
					$user_info = $db->getRow($query_user);
				}				
				$core->tpl->loadFile('guestbook/guestbook_view');
				$core->tpl->setVar('AVATAR', (empty($guestbook['uid']) ? 'media/avatar/'.(($guestbook['gender']==1) ? 'male.jpg' : 'female.jpg') : avatar($user_info['id'])));
				$core->tpl->setVar('NAME', 	 (empty($guestbook['uid']) ? $guestbook['name'] : $user_info['nick']));
				$core->tpl->setVar('EMAIL',  (empty($guestbook['uid']) ? $guestbook['email'] : $user_info['email']));
				$core->tpl->setVar('ID', $guestbook['id'] );
				$core->tpl->setVar('DATE', formatDate($guestbook['date']) );
				$core->tpl->setVar('WEBSITE', (!empty($guestbook['website']) ? _G_WEBSITE.': '.$guestbook['website'] : _G_WEBSITE_0));
				$core->tpl->setVar('REPLY', $guestbook['reply']);
				$array_replace["#\\[reply\\](.*?)\\[/reply\\]#is"] = (!empty($guestbook['reply']) ? '\\1' : '');						
				$core->tpl->setVar('REPLY_FLAG', (!empty($guestbook['reply']) ? _G_REPLY_1 : _G_REPLY_0));					
				$core->tpl->setVar('COMMENT', $guestbook['comment']);
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
			$core->tpl->setVar('UID',($core->auth->isUser ? $core->auth->user_info['id'] : ''));
			$core->tpl->setVar('UNAME', ($core->auth->isUser ? $core->auth->user_info['nick'] : ''));
			$core->tpl->setVar('CAPTCHA', captcha_image());
			$core->tpl->end();
			$core->tpl->close();			
			
		break;
		
	case "send":
		set_title(array(_G_GUESTBOOK, _SENDINGMESS));
		$uid = isset($_POST['uid']) ? intval($_POST['uid']) : '';
		$message = isset($_POST['message']) ? parseBB(processText(filter($_POST['message'], 'html'))) : '';
		if(captcha_check('securityCode')) 
		{
			if (empty($uid)) 
			{
				$site = isset($_POST['site']) ? filter($_POST['site']) : '';
				$gender = isset($_POST['gender']) ? intval($_POST['gender']) : '1';
				$email = isset($_POST['email']) ? filter($_POST['email']) : '';
				$name = isset($_POST['name']) ? filter($_POST['name']) : '';				
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
				if ($uid != $core->auth->user_info['id']) 
				{							
					$name =  $core->auth->user_info['nick'];
					$email =  $core->auth->user_info['email'];
					if(!empty($name) && !empty($message)) 
					{					
						$db->query("INSERT INTO `" . DB_PREFIX . "_guestbook` (`uid` ,`date` ,`name` ,`email` ,`comment`) VALUES (" . $uid . ", " . time() . ", '".$name."', '".$email."', '".$message."');");		
						$core->tpl->info(_SENDOK);
					} 
					else 
					{
						$core->tpl->info(_SENDFALSE, 'warning');
					}
				}
				else
				{
					$core->tpl->info(_SENDFALSE_0, 'error');
				}
		} 
		else 
		{
			$core->tpl->info(_CAPTCHAFALSE, 'warning');
		}
		break;

}