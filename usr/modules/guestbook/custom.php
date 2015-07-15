<?php
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
global $core, $db, $guestbook_conf;

	if ($order == 'date_reply')
	{
		$order='reply DESC, date';	
	}
	else
	{
		$order='id';
	}
	
	if(($short!='DESC')&($short!='ASC'))
	{
		$short='DESC';
	}
$core->loadModLang('guestbook');
$core->tempModule = 'guestbook';
$queryDB = $db->query("SELECT * FROM ".DB_PREFIX."_guestbook ORDER BY " . $order . " " . $short . " LIMIT 0, " . $limit . "");
$custom = '';

if($db->numRows($queryDB) > 0) 
{
	while($guestbook = $db->getRow($queryDB)) 
	{				
		ob_start();
		$core->tpl->loadFile($template);
		$core->tpl->setVar('AVATAR', 'media/avatar/'.(($guestbook['gender']==1) ? 'male.jpg' : 'female.jpg'));
		$core->tpl->setVar('NAME', $guestbook['name'] );
		$core->tpl->setVar('EMAIL', $guestbook['email'] );
		$core->tpl->setVar('ID', $guestbook['id'] );
		$core->tpl->setVar('DATE', formatDate($guestbook['date']) );
		$core->tpl->setVar('WEBSITE', (!empty($guestbook['website']) ? _G_WEBSITE.': '.$guestbook['website'] : _G_WEBSITE_0));
		$core->tpl->setVar('REPLY', $guestbook['reply']);
		$array_replace["#\\[reply\\](.*?)\\[/reply\\]#is"] = (!empty($guestbook['reply']) ? '\\1' : '');						
		$core->tpl->setVar('REPLY_FLAG', (!empty($guestbook['reply']) ? _G_REPLY_1 : _G_REPLY_0));					
		$core->tpl->setVar('COMMENT', $guestbook['comment']);
		$core->tpl->sources = preg_replace(array_keys($array_replace), array_values($array_replace), $core->tpl->sources);
		$core->tpl->end();		
		$custom .= ob_get_contents();
		ob_end_clean();

	}
}