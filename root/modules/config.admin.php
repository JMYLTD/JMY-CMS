<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   31.03.2015
*/

if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}
require ROOT . 'etc/configs.config.php';

$core->loadLangFile('root/langs/{lang}.config.php');

foreach($configs as $file => $arr)
{
	require_once(ROOT.'etc/' . $file . '.config.php');
}
$configBox = array(
	'global' => array(
		'varName' => 'config',
		'title' => _GLOBAL,
		'groups' => array(
			'metaTags' => array(
				'title' => _GLOBAL_METATAGS,
				'vars' => array(
					'url' => array(
						'title' => _GLOBAL_METATAGS_URLT,
						'description' => _GLOBAL_METATAGS_URLD,
						'content' => '<input class="form-control" type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
					'name' => array(
						'title' => _GLOBAL_METATAGS_NAMET,
						'description' => _GLOBAL_METATAGS_NAMED,
						'content' => '<input class="form-control" type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
						'multilang' => true
					),
					'description' => array(
						'title' => _GLOBAL_METATAGS_DESCRIPTIONT,
						'description' => _GLOBAL_METATAGS_DESCRIPTIOND,
						'content' => '<input class="form-control" type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
						'multilang' => true
					),						
					'slogan' => array(
						'title' => _GLOBAL_METATAGS_SLOGANT,
						'description' => _GLOBAL_METATAGS_SLOGAND,
						'content' => '<input class="form-control" type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
						'multilang' => true
					),
					'keywords' => array(
						'title' => _GLOBAL_METATAGS_KEYWORDST,
						'description' => _GLOBAL_METATAGS_KEYWORDSD,
						'content' => '<textarea class="form-control" cols="30" rows="3" name="{varName}" class="form-control" id="keywords">{var}</textarea>',
						'multilang' => true
					),						
					'divider' => array(
						'title' => _GLOBAL_METATAGS_DIVIDERT,
						'description' => _GLOBAL_METATAGS_DIVIDERD,
						'content' => '<input class="form-control" type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
						'multilang' => true
					),						
					'charset' => array(
						'title' => _GLOBAL_METATAGS_CHARSETT,
						'description' => _GLOBAL_METATAGS_CHARSETD,
						'content' => '<input class="form-control" type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),						
					'mainModule' => array(
						'title' => _GLOBAL_METATAGS_MAINMODULET,
						'description' => _GLOBAL_METATAGS_MAINMODULED,
						'content' => changeModule(),
						
					),
					'lang' => array(
						'title' => _GLOBAL_METATAGS_LANGT,
						'description' => _GLOBAL_METATAGS_LANGD,
						'content' => changeLang(),
					)		
					
				)
			),
			'other' => array(
				'title' => _GLOBAL_OTHER,
				'vars' => array(
					'uniqKey' => array(
						'title' => _GLOBAL_OTHER_UNIQKEY,
						'description' => _GLOBAL_OTHER_UNIQKEYD,
						'content' => '<p class="form-control-static"><font color="red">' . $config['uniqKey'] . '</font></p><input type="hidden" size="20" name="' . $config['lang'] . '[uniqKey]"  value="' . $config['uniqKey'] . '" id="name"  maxlength="100" maxsize="100" />',
					),	
					'timezone' => array(
						'title' => _GLOBAL_OTHER_TIMEZONET,
						'description' => _GLOBAL_OTHER_TIMEZONED,
						'content' => timeZone(),
					),						
					'tpl' => array(
						'title' => _GLOBAL_OTHER_TPLT,
						'description' => _GLOBAL_OTHER_TPLD,
						'content' => changeTpl(),						
					),
					'tpl' => array(
						'title' => _GLOBAL_OTHER_TPL_MOBI,
						'description' => _GLOBAL_OTHER_TPL_MOBI_DESC,
						'content' => conf_radio("smartphone", $config['smartphone']),
					),
					'dbType' => array(
						'title' => _GLOBAL_OTHER_DBTYPET,
						'description' => _GLOBAL_OTHER_DBTYPED,
						'content' => dbType(),
					),					
					'imageEffect' => array(
						'title' => _GLOBAL_OTHER_IMAGEEFFECTT,
						'description' => _GLOBAL_OTHER_IMAGEEFFECTD,
						'content' => imageEffect(),
					),						
					'support_mail' => array(
						'title' => _GLOBAL_OTHER_SUPPORT_MAILT,
						'description' => _GLOBAL_OTHER_SUPPORT_MAILD,
						'content' => '<input class="form-control" type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
				)
			),
			'globalFunc' => array(
				'title' => _GLOBAL_GLOBALFUNC,
				'vars' => array(
					'gzip' => array(
						'title' => _GLOBAL_GLOBALFUNC_GZIPT,
						'description' => _GLOBAL_GLOBALFUNC_GZIPD,
						'content' => conf_radio("gzip", $config['gzip']),
					),					
					'off' => array(
						'title' => _GLOBAL_GLOBALFUNC_OFFT,
						'description' => _GLOBAL_GLOBALFUNC_OFFD,
						'content' => conf_radio("off", $config['off']),
					),
					'off_text' => array(
						'title' => _GLOBAL_GLOBALFUNC_OFF_TEXTT,
						'description' => _GLOBAL_GLOBALFUNC_OFF_TEXTD,
						'content' => "<textarea cols=\"30\" rows=\"5\" name=\"{varName}\" class=\"form-control\" id=\"off_text\">{var}</textarea>",
						'multilang' => true
					),								
					'cache' => array(
						'title' => _GLOBAL_GLOBALFUNC_CACHET,
						'description' => _GLOBAL_GLOBALFUNC_CACHED,
						'content' => conf_radio("cache", $config['cache']),
					),					
					'dbCache' => array(
						'title' => _GLOBAL_GLOBALFUNC_DBCACHET,
						'description' => _GLOBAL_GLOBALFUNC_DBCACHED,
						'content' => conf_radio("dbCache", $config['dbCache']),
					),					
					'mod_rewrite' => array(
						'title' => _GLOBAL_GLOBALFUNC_MOD_REWRITET,
						'description' => _GLOBAL_GLOBALFUNC_MOD_REWRITED,
						'content' => conf_radio("mod_rewrite", $config['mod_rewrite']),
					),
					'comments' => array(
						'title' => _GLOBAL_GLOBALFUNC_COMMENTST,
						'description' => _GLOBAL_GLOBALFUNC_COMMENTSD,
						'content' => conf_radio("comments", $config['comments']),
					),
					'plugin' => array(
						'title' => _GLOBAL_GLOBALFUNC_PLUGINT,
						'description' => _GLOBAL_GLOBALFUNC_PLUGIND,
						'content' => conf_radio("plugin", $config['plugin']),
					),
				)
			)
		),
	),
	'security' => array(
		'varName' => 'security',
		'title' => _SECURITY,
		'info' => _SECURITY_INFORMATION,
		'groups' => array(
			'filter' => array(
				'title' => _SECURITY_FILTER,
				'vars' => array(
					'xNums' => array(
						'title' => _SECURITY_FILTER_XNUMST,
						'description' => _SECURITY_FILTER_XNUMSD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),								
					'stopNick' => array(
						'title' => _SECURITY_FILTER_STOPNICKT,
						'description' => _SECURITY_FILTER_STOPNICKD,
						'content' => '<textarea cols="30" rows="3" name="{varName}" class="form-control">{var}</textarea>',
					),					
					'stopMails' => array(
						'title' => _SECURITY_FILTER_STOPMAILST,
						'description' => _SECURITY_FILTER_STOPMAILSD,
						'content' => '<textarea cols="30" rows="3" name="{varName}" class="form-control">{var}</textarea>',
					),					
					'stopWords' => array(
						'title' => _SECURITY_FILTER_STOPWORDST,
						'description' => _SECURITY_FILTER_STOPWORDSD,
						'content' => '<textarea cols="30" rows="3" name="{varName}" class="form-control">{var}</textarea>',
					),
					'stopReplace' => array(
						'title' => _SECURITY_FILTER_STOPREPLACET,
						'description' => _SECURITY_FILTER_STOPREPLACED,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),						
					'allowHTML' => array(
						'title' => _SECURITY_FILTER_ALLOWHTMLT,
						'description' => _SECURITY_FILTER_ALLOWHTMLD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),					
				)
			),			
			'ips' => array(
				'title' => _SECURITY_IPS,
				'vars' => array(
					'banIp' => array(
						'title' => _SECURITY_IPS_BANIPT,
						'description' => _SECURITY_IPS_BANIPD,
						'content' => '<textarea cols="30" rows="3" name="{varName}" class="form-control">{var}</textarea>',
					),					
					'banIpMessage' => array(
						'title' => _SECURITY_IPS_BANIPMESSAGET,
						'description' => _SECURITY_IPS_BANIPMESSAGED,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
						'multilang' => true
					),
				)
			),			
			'captcha' => array(
				'title' => _SECURITY_CAPTCHA,
				'vars' => array(
					'recaptcha' => array(
						'title' => _SECURITY_RECAPTHA,
						'description' => _SECURITY_RECAPTHA_DESC,
						'content' => conf_radio("recaptcha", $security['recaptcha']),
					),	
					'recaptcha_public' => array(
						'title' => _SECURITY_RECAPTHA_PUBLIC,
						'description' => _SECURITY_RECAPTHA_PUBLIC_DESC,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),		
					'recaptcha_private' => array(
						'title' => _SECURITY_RECAPTHA_PRIVATE,
						'description' => _SECURITY_RECAPTHA_PRIVATE_DESC,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),	
					'captcha_width' => array(
						'title' => _SECURITY_CAPTCHA_CAPTCHA_WIDTHT,
						'description' => _SECURITY_CAPTCHA_CAPTCHA_WIDTHD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
					'captcha_height' => array(
						'title' => _SECURITY_CAPTCHA_CAPTCHA_HEIGHTT,
						'description' => _SECURITY_CAPTCHA_CAPTCHA_HEIGHTD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
					'captcha_lenght' => array(
						'title' => _SECURITY_CAPTCHA_CAPTCHA_LENGHTT,
						'description' => _SECURITY_CAPTCHA_CAPTCHA_LENGHTD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
		
				)
			),
			
		)
	),
	'files' => array(
		'varName' => 'files_conf',
		'title' => _FILES,
		'groups' => array(
			'file' => array(
				'title' => _FILES_FILE,
				'vars' => array(
					'imgFormats' => array(
						'title' => _FILES_FILE_IMGFORMATST,
						'description' => _FILES_FILE_IMGFORMATSD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
					'attachFormats' => array(
						'title' => _FILES_FILE_ATTACHFORMATST,
						'description' => _FILES_FILE_ATTACHFORMATSD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
					'max_size' => array(
						'title' => _FILES_FILE_MAX_SIZET,
						'description' => _FILES_FILE_MAX_SIZED,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),					
				)
			),					
			'thumb' => array(
				'title' => _FILES_THUMB,
				'vars' => array(
					'thumb_width' => array(
						'title' => _FILES_THUMB_THUMB_WIDTHT,
						'description' => _FILES_THUMB_THUMB_WIDTHD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
					'quality' => array(
						'title' => _FILES_THUMB_QUALITYT,
						'description' => _FILES_THUMB_QUALITYD,
						'content' => waterMarkQuality(),
					),
				)
			),
			'watermark' => array(
				'title' => _FILES_WATERMARK,
				'vars' => array(
					'watermark' => array(
						'title' => _FILES_WATERMARK_WATERMARKT,
						'description' => _FILES_WATERMARK_WATERMARKD,
						'content' => yesNo('files', 'files_conf', 'watermark'),
					),
					'watermark_text' => array(
						'title' => _FILES_WATERMARK_WATERMARK_TEXTT,
						'description' => _FILES_WATERMARK_WATERMARK_TEXTD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),					
					'watermark_image' => array(
						'title' => _FILES_WATERMARK_WATERMARK_IMAGET,
						'description' => _FILES_WATERMARK_WATERMARK_IMAGED,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),					
					'watermark_valign' => array(
						'title' => _FILES_WATERMARK_WATERMARK_VALIGNT,
						'description' => _FILES_WATERMARK_WATERMARK_VALIGND,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),					
					'watermark_halign' => array(
						'title' => _FILES_WATERMARK_WATERMARK_HALIGNT,
						'description' => _FILES_WATERMARK_WATERMARK_HALIGND,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
				)
			),			
		)
	),		
	'cache' => array(
		'varName' => 'allowCahce',
		'title' => _CACHE_CONFIG,
		'groups' => array(
			'file' => array(
				'title' => _CACHE_CONFIG,
				'vars' => array(
					'tplFiles' => array(
						'title' => _CACHE_TPL,
						'description' => _CACHE_TPL_DESC,
						'content' => conf_radio("tplFiles", $allowCahce['tplFiles']),
					),
					'plugins' => array(
						'title' => _CACHE_BLOCK,
						'description' => _CACHE_BLOCK_DESC,
						'content' => conf_radio("plugins", $allowCahce['plugins']),
					),
					'categories' => array(
						'title' => _CACHE_CAT,
						'description' => _CACHE_CAT_DESC,
						'content' => conf_radio("categories", $allowCahce['categories']),
					),					
					'userInfo' => array(
						'title' => _CACHE_USER,
						'description' => _CACHE_USER_DESC,
						'content' => conf_radio("userInfo", $allowCahce['userInfo']),
					),					
				)
			),					
		)
	),	
	'admin' => array(
		'varName' => 'admin_conf',
		'title' => _ADMIN,
		'groups' => array(
			'main' => array(
				'title' => _FILES_FILE,
				'vars' => array(
					'num' => array(
						'title' => _ADMIN_MAIN_NUMT,
						'description' => _ADMIN_MAIN_NUMD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),					
					'ipaccess' => array(
						'title' => _ADMIN_MAIN_IPACCESST,
						'description' => _ADMIN_MAIN_IPACCESSD,
						'content' => '<textarea cols="30" rows="3" name="{varName}" class="form-control">{var}</textarea>'._ADMIN_MAIN_IPACCESS_HELP,
					),				
					'sessions' => array(
						'title' => _ADMIN_MAIN_SESSIONST,
						'description' => _ADMIN_MAIN_SESSIONSD,
						'content' => yesNo('admin', 'admin_conf', 'sessions'),
					),					
					'bar' => array(
						'title' => _ADMIN_MAIN_BART,
						'description' => _ADMIN_MAIN_BARD,
						'content' => yesNo('admin', 'admin_conf', 'bar'),
					),					
					'htmlEditor' => array(
						'title' => _ADMIN_HTMLEDITORT,
						'description' => _ADMIN_HTMLEDITORD,
						'content' => yesNo('admin', 'admin_conf', 'htmlEditor'),
					),
				)
			),					
		)
	),	
	'user' => array(
		'varName' => 'user',
		'title' => _USERC,
		'groups' => array(
			'main' => array(
				'title' => _USER_MAIN,
				'vars' => array(
					'guestGroup' => array(
						'title' => _USER_MAIN_GUESTGROUPT,
						'description' => _USER_MAIN_GUESTGROUPD,
						'content' => changeuGroup('guestGroup'),
					),							
					'botGroup' => array(
						'title' => _USER_MAIN_BOTGROUPT,
						'description' => _USER_MAIN_BOTGROUPD,
						'content' => changeuGroup('botGroup'),
					),						
					'banGroup' => array(
						'title' => _USER_MAIN_BANGROUPT,
						'description' => _USER_MAIN_BANGROUPD,
						'content' => changeuGroup('banGroup'),
					),						
					'count_points' => array(
						'title' => _USER_COUNT_POINTST,
						'description' => _USER_COUNT_POINTSD,
						'content' => yesNo('user', 'user', 'count_points'),
					),					
				)
			),					
			'avatar' => array(
				'title' => _USER_AVATAR,
				'vars' => array(
					'noAvatar' => array(
						'title' => _USER_AVATAR_NOAVATART,
						'description' => _USER_AVATAR_NOAVATARD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),						
					'avatar_load' => array(
						'title' => _USER_AVATAR_AVATAR_LOADT,
						'description' => _USER_AVATAR_AVATAR_LOADD,
						'content' => yesNo('user', 'user', 'avatar_load'),
					),							
					'avatar_width' => array(
						'title' => _USER_AVATAR_AVATAR_WIDTHT,
						'description' => _USER_AVATAR_AVATAR_WIDTHD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),
					'avatar_height' => array(
						'title' => _USER_AVATAR_AVATAR_HEIGHTT,
						'description' => _USER_AVATAR_AVATAR_HEIGHTD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),							
					'avatar_size' => array(
						'title' => _USER_AVATAR_AVATAR_SIZET,
						'description' => _USER_AVATAR_AVATAR_SIZED,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),						
	
				)
			),					
			'register' => array(
				'title' => _USER_REGISTER,
				'vars' => array(
					'with_activate' => array(
						'title' => _USER_REGISTER_WITH_ACTIVATET,
						'description' => _USER_REGISTER_WITH_ACTIVATED,
						'content' => yesNo('user', 'user', 'with_activate'),
					),							
				)
			),			
			'bbEditor' => array(
				'title' => _USER_BBEDITOR,
				'vars' => array(
					'activeFlash' => array(
						'title' => _USER_BBEDITOR_ACTIVEFLASHT,
						'description' => _USER_BBEDITOR_ACTIVEFLASHD,
						'content' => yesNo('user', 'user', 'activeFlash'),
					),					
					'activeVideo' => array(
						'title' => _USER_BBEDITOR_ACTIVEVIDEOT,
						'description' => _USER_BBEDITOR_ACTIVEVIDEOD,
						'content' => yesNo('user', 'user', 'activeVideo'),
					),						
					'activeAudio' => array(
						'title' => _USER_BBEDITOR_ACTIVEAUDIOT,
						'description' => _USER_BBEDITOR_ACTIVEAUDIOD,
						'content' => yesNo('user', 'user', 'activeAudio'),
					),								
					'activeAttach' => array(
						'title' => _USER_BBEDITOR_ACTIVEATTACHT,
						'description' => _USER_BBEDITOR_ACTIVEATTACHD,
						'content' => yesNo('user', 'user', 'activeAttach'),
					),					
					'editor' => array(
						'title' => _USER_BBEDITOR_EDITORT,
						'description' => _USER_BBEDITOR_EDITORD,
						'content' => '<select name="{varName}"><option value="bb">BB редактор</option></select>',
					),							
					'bbViz' => array(
						'title' => _USER_BBEDITOR_BBVIZT,
						'description' => _USER_BBEDITOR_BBVIZD,
						'content' => yesNo('user', 'user', 'bbViz'),
					),						
					'highlightCode' => array(
						'title' => _USER_BBEDITOR_HIGHLGIHTT,
						'description' => _USER_BBEDITOR_HIGHLGIHTD,
						'content' => yesNo('user', 'user', 'highlightCode'),
					),	
				)
			),
			'comments' => array(
				'title' => _USER_COMMENTS,
				'vars' => array(
					'commentOften' => array(
						'title' => _USER_COMMENTS_COMMENTOFTENT,
						'description' => _USER_COMMENTS_COMMENTOFTEND,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),							
					'commentEditText' => array(
						'title' => _USER_COMMENTS_COMMEDTEXT,
						'description' => _USER_COMMENTS_COMMEDTEXTD,
						'content' => '<textarea cols="30" rows="3" name="{varName}" class="form-control">{var}</textarea>',
						'html' => true
					),						
					'commentSignature' => array(
						'title' => _USER_COMMENTS_SIGNATURET,
						'description' => _USER_COMMENTS_SIGNATURED,
						'content' => '<textarea cols="30" rows="3" name="{varName}" class="form-control">{var}</textarea>',
						'html' => true
					),							
					'commentSubscribe' => array(
						'title' => _USER_COMMENTS_SUBSCRIBET,
						'description' => _USER_COMMENTS_SUBSCRIBED,
						'content' => yesNo('user', 'user', 'commentSubscribe'),
					),					
					'commentModeration' => array(
						'title' => _USER_COMMENTS_COMMMODERATE,
						'description' => _USER_COMMENTS_COMMMODERATED,
						'content' => yesNo('user', 'user', 'commentModeration'),
					),					
					'commentTree' => array(
						'title' => _USER_COMMENTS_COMMTREET,
						'description' => _USER_COMMENTS_COMMTREED,
						'content' => yesNo('user', 'user', 'commentTree'),
					),							
				)
			),			
			'other' => array(
				'title' => _USER_OTHER,
				'vars' => array(
					'pmShown' => array(
						'title' => _USER_OTHER_PMSHOWNT,
						'description' => _USER_OTHER_PMSHOWND,
						'content' => yesNo('user', 'user', 'pmShown'),
					),							
					'isBan' => array(
						'title' => _USER_OTHER_ISBANT,
						'description' => _USER_OTHER_ISBAND,
						'content' => yesNo('user', 'user', 'isBan'),
					),						
					'userWall' => array(
						'title' => _USER_OTHER_USERWALLT,
						'description' => _USER_OTHER_USERWALLD,
						'content' => yesNo('user', 'user', 'userWall'),
					),							
					'userWallNum' => array(
						'title' => _USER_OTHER_USERWALLNUMT,
						'description' => _USER_OTHER_USERWALLNUMD,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),			
					'userFriends' => array(
						'title' => _USER_OTHER_USERFRIENDST,
						'description' => _USER_OTHER_USERFRIENDSD,
						'content' => yesNo('user', 'user', 'userWall'),
					),	
					'userGuests' => array(
						'title' => _USER_OTHER_USERGUESTST,
						'description' => _USER_OTHER_USERGUESTSD,
						'content' => yesNo('user', 'user', 'userWall'),
					),											
					'readBlog' => array(
						'title' => _USER_OTHER_READBLOGT,
						'description' => _USER_OTHER_READBLOGD,
						'content' => yesNo('user', 'user', 'readBlog'),
					),						
				)
			),
		)
	),	
	'log' => array(
		'varName' => 'log_conf',
		'title' => _LOG,
		'groups' => array(
			'main' => array(
				'title' => _LOG_MAIN,
				'vars' => array(
					'phpError' => array(
						'title' => _LOG_MAIN_PHPERRORT,
						'description' => _LOG_MAIN_PHPERRORD,
						'content' => conf_radio("phpError", $log_conf['phpError']),
					),
					'queryError' => array(
						'title' => _LOG_MAIN_QUERYERRORT,
						'description' => _LOG_MAIN_QUERYERRORD,
						'content' => conf_radio("queryError", $log_conf['queryError']),
					),	
					'dbError' => array(
						'title' => _LOG_MAIN_DBERRORT,
						'description' => _LOG_MAIN_DBERRORD,
						'content' => conf_radio("dbError", $log_conf['dbError']),
					),					
					'accesError' => array(
						'title' => _LOG_MAIN_ACCESERRORT,
						'description' => _LOG_MAIN_ACCESERRORD,
						'content' => conf_radio("accesError", $log_conf['accesError']),
					),
					'compressSize' => array(
						'title' => _LOG_MAIN_COMPRESSSIZET,
						'description' => _LOG_MAIN_COMPRESSSIZED,
						'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
					),							
				)
			),					
		)
	),
);

function conf_radio($name, $val) 
{
global $config;	
	
	$but_1 = ($val) ? "checked" : "";
	$but_2 = (!$val) ? "checked" : "";
	return '
	<table>
	<tr>
	<td valign="top">
				<label class="radio radio-custom ' . $but_1 . '"><input type="radio" ' . $but_1 . ' value="1" name="{varName}" id="ch{varName}"><i class="radio ' . $but_1 . '"></i>'._YES.'</label>
			</td>
			<td>&nbsp&nbsp</td>
			<td valign="top">
				<label class="radio radio-custom ' . $but_2 . '"><input type="radio" ' . $but_2 . ' value="0" name="{varName}" id="ch{varName}"><i class="radio ' . $but_2 . '"></i>'._NO.'</label>
				</td>
				
	</tr>
	</table>';

	
	
	
}

function changeuGroup($var)
{
global $adminTpl, $db, $user;
    $content = '<select name="{varName}">';
	$query2 = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups`");
	while($rows2 = $db->getRow($query2)) 
	{
		$sel = ($user[$var] == $rows2['id']) ? 'selected' : '';
		$content .= '<option value="' . $rows2['id'] . '" ' . $sel . '>' . $rows2['name'] . '</option>';
	}
	$content .= '</select>';
	return $content;
}

function changeModule()
{
global $config, $core;
	$exceMods = array('feed', 'pm', 'search', 'poll');
    $content = '<select name="{varName}">';
	foreach ($core->getModList() as $module) 
	{
		if(!in_array($module, $exceMods) && !empty($core->tpl->modules[$module]))
		{
			$selected = ($module == $config['mainModule']) ? "selected" : "";
			$content .= '<option value="' . $module . '" ' . $selected . '>' . _mName($module) . '</option>';
		}
	}
	$content .= '</select>';
	return $content;
}

function yesNo($file, $global, $var)
{
global $adminTpl, $$global;
	$conf = $$global;
	$but_1 = ($conf[$var] == 1 ) ? "checked" : "";
	$but_2 = ($conf[$var] == 0) ? "checked" : "";	
	return '
	<table>
	<tr>
	<td valign="top">
				<label class="radio radio-custom ' . $but_1 . '"><input type="radio" ' . $but_1 . ' value="1" name="{varName}" id="ch{varName}"><i class="radio ' . $but_1 . '"></i>'._YES.'</label>
			</td>
			<td>&nbsp&nbsp</td>
			<td valign="top">
				<label class="radio radio-custom ' . $but_2 . '"><input type="radio" ' . $but_2 . ' value="0" name="{varName}" id="ch{varName}"><i class="radio ' . $but_2 . '"></i>'._NO.'</label>
				</td>
		
				
	</tr>
	</table>';

}

function waterMarkQuality()
{
global $adminTpl, $files_conf, $select;
	$content = '<select name="{varName}">';
	foreach (range(10, 100, 10) as $number) 
	{
		if($files_conf['quality'] == $number) $select = ' selected';
		$content .= '<option value="' . $number . '"' . $select . '>' . $number . '%</option>';
	}
	$content .= '</select>';
	
	return $content;
}

function changeLang()
{
global $adminTpl, $config, $core;
	$content = "<select name=\"" . $config['lang'] . "[lang]\" id=\"lang\" class=\"textinput\" >";
	foreach($core->getLangList(true) as $_ => $massa)
	{
		$sel = ($config['lang'] == $massa[0]) ? 'selected' : '';
		$content .= "<option value=\"" . $massa[0] . "\" " . $sel . ">" . $massa[1] . "</option>";
	}
	$content .= "</select>";
	return $content;
}


function timeZone()
{
global $adminTpl, $config;
	return '<select name="' . $config['lang'] . '[timezone]">
      <option value="">Стандартный</option>
      <option value="Pacific/Kwajalein" ' .($config['timezone'] == "Pacific/Kwajalein" ? "selected" : ""). '>(GMT -12:00) Eniwetok, Kwajalein</option>
      <option value="Pacific/Samoa" ' .($config['timezone'] == "Pacific/Samoa" ? "selected" : ""). '>(GMT -11:00) Midway Island, Samoa</option>
      <option value="US/Hawaii" ' .($config['timezone'] == "US/Hawaii" ? "selected" : ""). '>(GMT -10:00) Hawaii</option>
      <option value="US/Alaska" ' .($config['timezone'] == "US/Alaska" ? "selected" : ""). '>(GMT -9:00) Alaska</option>
      <option value="Canada/Pacific" ' .($config['timezone'] == "Canada/Pacific" ? "selected" : ""). '>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
      <option value="Canada/Mountain" ' .($config['timezone'] == "Canada/Mountain" ? "selected" : ""). '>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
      <option value="Canada/Central" ' .($config['timezone'] == "Canada/Central" ? "selected" : ""). '>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
      <option value="Canada/Eastern" ' .($config['timezone'] == "Canada/Eastern" ? "selected" : ""). '>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
      <option value="Canada/Atlantic" ' .($config['timezone'] == "Canada/Atlantic" ? "selected" : ""). '>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
      <option value="Canada/Newfoundland" ' .($config['timezone'] == "Canada/Newfoundland" ? "selected" : ""). '>(GMT -3:30) Newfoundland</option>
      <option value="Brazil/East" ' .($config['timezone'] == "Brazil/East" ? "selected" : ""). '>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
      <option value="Atlantic/Bermuda" ' .($config['timezone'] == "Atlantic/Bermuda" ? "selected" : ""). '>(GMT -2:00) Mid-Atlantic</option>
      <option value="Atlantic/Azores" ' .($config['timezone'] == "Atlantic/Azores" ? "selected" : ""). '>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
      <option value="Europe/London" ' .($config['timezone'] == "Europe/London" ? "selected" : ""). '>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
      <option value="Europe/Paris" ' .($config['timezone'] == "Europe/Paris" ? "selected" : ""). '>(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris</option>
      <option value="Europe/Kaliningrad" ' .($config['timezone'] == "Europe/Kaliningrad" ? "selected" : ""). '>(GMT +2:00) Kaliningrad, South Africa</option>
      <option value="Europe/Moscow" ' .($config['timezone'] == "Europe/Moscow" ? "selected" : ""). '>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
      <option value="Asia/Tehran" ' .($config['timezone'] == "Asia/Tehran" ? "selected" : ""). '>(GMT +3:30) Tehran</option>
      <option value="Asia/Baku" ' .($config['timezone'] == "Asia/Baku" ? "selected" : ""). '>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
      <option value="Asia/Kabul" ' .($config['timezone'] == "Asia/Kabul" ? "selected" : ""). '>(GMT +4:30) Kabul</option>
      <option value="Asia/Karachi" ' .($config['timezone'] == "Asia/Karachi" ? "selected" : ""). '>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
      <option value="Asia/Calcutta" ' .($config['timezone'] == "Asia/Calcutta" ? "selected" : ""). '>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
      <option value="Asia/Almaty" ' .($config['timezone'] == "Asia/Almaty" ? "selected" : ""). '>(GMT +6:00) Almaty, Dhaka, Colombo</option>
      <option value="Asia/Bangkok" ' .($config['timezone'] == "Asia/Bangkok" ? "selected" : ""). '>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
      <option value="Asia/Hong_Kong" ' .($config['timezone'] == "Asia/Yakutsk" ? "selected" : ""). '>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
      <option value="Asia/Yakutsk" ' .($config['timezone'] == "Asia/Yakutsk" ? "selected" : ""). '>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
      <option value="Australia/Darwin" ' .($config['timezone'] == "Australia/Darwin" ? "selected" : ""). '>(GMT +9:30) Adelaide, Darwin</option>
      <option value="Asia/Vladivostok" ' .($config['timezone'] == "Asia/Vladivostok" ? "selected" : ""). '>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
      <option value="Asia/Magadan" ' .($config['timezone'] == "Asia/Magadan" ? "selected" : ""). '>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
      <option value="Asia/Kamchatka" ' .($config['timezone'] == "Asia/Kamchatka" ? "selected" : ""). '>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
	</select>';
}

function changeTpl()
{
global $adminTpl, $config;
	$content = "<select class=\"other\" name=\"{varName}\">";
	$path = ROOT.'usr/tpl/';
	$dh = opendir($path);
	$c=0;
	while ($file = readdir($dh)) 
	{
		if(is_dir($path.$file) && $file != '.' && $file != '..' && $file != 'admin' && $file != 'default'&& $file != 'smartphone') 
		{
			$select = ($file == $config['tpl']) ? ' selected' : '';
			$content .= "<option value=\"$file\"$select>$file</option>";
		}
	}
	closedir($dh);
	$content .= "</select>";
	return $content;
}
	
function imageEffect()
{
global $adminTpl, $config;
	$content = "<select class=\"other\" name=\"" . $config['lang'] . "[imageEffect]\">";
	$path = ROOT . 'media/imageEffects/';
	$dh = opendir($path);
	$c=0;
	while ($file = readdir($dh)) 
	{
		if($file != '.' && $file != '..') 
		{
			if(file_exists($path.$file.'/init.php'))
			{
				require $path.$file.'/init.php';
				if($descr && $js && $picture)
				{
					$select = ($file == $config['imageEffect']) ? ' selected' : '';
					$content .= "<option value=\"$file\"$select>" . $descr . "</option>";
				}
			}
		}
	}
	closedir($dh);
	$content .= "</select>";
	return $content;
}

function dbType()
{
global $adminTpl, $config;
	$content = "<select class=\"other\" name=\"" . $config['lang'] . "[dbType]\">";
	$path = ROOT . 'boot/db/';
	$dh = opendir($path);
	$c=0;
	while ($file = readdir($dh)) 
	{
		if(eregStrt('.db.php', $file))
		{
			$select = (str_replace('.db.php', '', $file) == $config['dbType']) ? ' selected' : '';
			$content .= "<option value=\"" . str_replace('.db.php', '', $file) . "\"$select>" . ucfirst(str_replace('.db.php', '', $file)) . "</option>";
		}
	}
	closedir($dh);
	$content .= "</select>";
	return $content;
}

switch(isset($url[2]) ? $url[2] : null) {
	default:
		if(isset($url[2]) && isset($configBox[$url[2]]))
		{
			foreach(glob(ROOT.'etc/*'.$url[2].'.config.php') as $file)
			{
				$flang = str_replace(array(ROOT.'etc/', '.'.$url[2].'.config.php'), array('', ''), $file);
				if($file != ROOT.'etc/'.$url[2].'.config.php')
				{
					if(isset($core->langsLang[$flang]))
					{
						$varName = $configBox[$url[2]]['varName'];
						unset($$varName);
						require $file;
						$langArr[$flang] = $$varName;
					}
				}
			}
			
			require(ROOT.'etc/'.$url[2].'.config.php');
			
			$parseConf = $configBox[$url[2]];
			$varName = $configBox[$url[2]]['varName'];
			$confArr = $$varName;
			
			$adminTpl->admin_head(_CONFIGURATION . ' | ' . $parseConf['title']);
		
			echo '<form action="{ADMIN}/config/save" method="post" role="form"  data-parsley-validate="" novalidate="">';
			if(isset($url[3]) && $url[3] == 'ok')
			{
				$adminTpl->info(_SUCCESS_SAVE);
				echo '<br />';
			}

			
			$adminTpl->open();
		
			foreach($parseConf['groups'] as $group)
			{
				echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading no-border">
						<b>' . $group['title'] . '</b>
					</div>
				<div class="panel-body">
				<div class="switcher-content">
				<div class="form-horizontal parsley-form">
				';
			  foreach($group['vars'] as $var => $varArr)
			  {
				echo '
				<div class="form-group">
					<label class="col-sm-3 control-label">' . $varArr['title'] . '</label>
					<div class="col-sm-4">';
					    
					
				
					echo (isset($confArr[$var]) ? str_replace(array('{varName}', '{var}'), array($config['lang'].'['.$var.']', $confArr[$var]), $varArr['content']) : $varArr['content']);
				
				echo '	<p class="help-block">' . $varArr['description'] . '</p>
					</div>
				</div>';
			  }
			  echo '			
	<div align="right" style="padding-bottom:5px;"><input type="submit" class="btn btn-success" value="'._SAVE.'"></div>
	</div>
</div>
				</section></div>
				</div>';
			}
			echo '</table>
			<input type="hidden" size="20" name="lang" value="ru"  />
				<input type="hidden" size="20" name="ru[lang]" value="ru"  />
			<input type="hidden" size="20" name="conf_file" class="form-control" value="' . $url[2] . '" maxlength="100" maxsize="100" />
			<input type="hidden" size="20" name="conf_arr_name" class="form-control" value="' . $varName . '" maxlength="100" maxsize="100" />
			
			</form>';
			$adminTpl->close();

			$adminTpl->admin_foot();
		}
		else
		{
			$adminTpl->admin_head(_CONFIGURATION);
			$num_configs = count($configs);
			$count_configs = 0;
			
			$subcount = 0;
			foreach($configs as $subname => $subrow) 
			{
				$subcount++;
				
				$arr[$subcount] = $subname;
			}
				$adminTpl->open();	
			echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading no-border">
						<b>'._CONFIGURATION.':</b>
					</div>
					<div class="panel-body">
				<div class="switcher-content">
					';

						
	
			
			foreach($configs as $name => $row) 
			{
				if(isset($row['file'])) require ROOT . 'etc/' . $row['file'] . '.config.php';
				$count_configs++;
				$val_name = $name;
				echo '<div style="cursor:pointer"  onclick="document.location.href = \'{ADMIN}/config/' . $val_name . '\';">
					<label style="cursor:pointer" class="control-label">'. $row['name'] .': (' . count($$row['param']) . ')</label><br>
					'.$row['description'].'
				<br>				
				</div><br>	
			';
			}
			echo '	</div></div>
				</section></div>
				</div>';
			$adminTpl->close();
			foreach(glob(ROOT.'usr/modules/*/admin/list.php') as $listed) 
			{
				$file = $listed;
				$file = str_replace(ROOT.'usr/modules/', '', $file);
				$file = str_replace('/admin/list.php', '', $file);				
				$core->loadLangFile('usr/modules/'.$file.'/admin/lang/{lang}.admin.php');
				include($listed);
			}
			
			$toconfig['_smiles'] = array('name' => _SMILES,'link' => 'smiles','param'=>'smiles');
			$toconfig['_blocks'] = array('name' => _BLOCK_STANDART,'link' => 'blocks/standard','param'=>'blocks/standard');
			if(!empty($toconfig))
			{
			$adminTpl->open();
				echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading no-border">
						<b>'._CONFIG_MODULES.'</b>
					</div>
					<div class="panel-body">
				<div class="switcher-content"><div class="_open_title"></div>';
				
				echo '<br style="clear:both" />';
				foreach($toconfig as $name => $row) 
				{
				echo '<div style="cursor:pointer"  onclick="document.location.href = \'{ADMIN}/' . $row['link'] . '\';">
					<label style="cursor:pointer" class="control-label">'. $row['name'] .':</label><br>
					Настройки раздела: '. $row['name'].'
				<br>				
				</div><br>	
			';
					
				}
				echo '	</div></div>
				</section></div>
				</div>';
				$adminTpl->close();
			}
			$adminTpl->admin_foot();
		}
	break;

	case 'doit':
		$name = $url[3];
		$prename = $url[4];
		$param = '';
		
		foreach($configs as $k => $v)
		{
			$param .= $k . ',';
		}
		
		$param = mb_substr($param, 0, -1);
		
		$newarr = explode(',', $param);
		$namekey = array_search($name, $newarr);
		$prenamekey = array_search($prename, $newarr);
		foreach($newarr as $key => $val)
		{
			if($key == $namekey)
			{
				$last_arr[$key] = $prename;
			}
			elseif($key == $prenamekey)
			{
				$last_arr[$key] = $name;
			}
			else
			{
				$last_arr[$key] = $val;
			}
		}
		
		$genarr = '';
		
		foreach($last_arr as $val)
		{
			$genarr .= "\$configs['$val'] = array\n(\n";
			$i = 0;
			foreach($configs[$val] as $kk => $vv)
			{
				$i++;
				if($i == count($configs[$val]))
				{
					$genarr .= "'$kk' => '$vv'\n";
				}
				else
				{
					$genarr .= "'$kk' => '$vv',\n";
				}
			}
			$genarr .= ");\n\n";
			
			unset($i);
		}
		
		$content = "global \$configs;\n";
		$content .= $genarr;

		save_conf('etc/configs.config.php', $content);
		
		location(ADMIN . '/config');
		break;
	
	case "save":
		$file = 'etc/{lang}'.$_POST['conf_file'].'.config.php';
		$conf_arr_name = $_POST['conf_arr_name'];
		foreach($_POST as $lang => $arr)
		{
			if(is_array($arr) && isset($core->langsLang[$lang]))
			{
				$content = '';
				if($config['lang'] == $lang) $content .= "\$$conf_arr_name = array();\n";
				$html = array('off_text', 'commentSignature', 'commentEditText');
				foreach($arr as $k => $val) 
				{
					if($k != 'conf_arr_name' && $k != 'conf_file') 
					{
						if(!is_array($val)) {
							if(!in_array($k, $html))
							{
								if(($config['lang'] != $lang && $root_conf[$k] != $val) || $config['lang'] == $lang) $content .= "\$".$conf_arr_name."['".$k."'] = \"".htmlspecialchars(str_replace('"', '\"', stripslashes($val)), ENT_QUOTES)."\";\n";
								if($config['lang'] == $lang) $root_conf[$k] = $val;
							}
							else
							{
								if(($config['lang'] != $lang && $root_conf[$k] != $val) || $config['lang'] == $lang) $content .= "\$".$conf_arr_name."['".$k."'] = \"".str_replace('"', '\"', stripslashes($val))."\";\n";
								if($config['lang'] == $lang) $root_conf[$k] = $val;
							}
						} else {
							foreach($val as $karr => $varr) {
								$content .= "\$".$conf_arr_name."['".$k."']['".$karr."'] = \"".htmlspecialchars(stripslashes($varr), ENT_QUOTES)."\";\n";
							}
						}
					}
				}
				if(!empty($content)) 
				{
					$result = "global $$conf_arr_name;\n".$content;
					save_conf(str_replace('{lang}', ($config['lang'] == $lang ? '' : $lang.'.'), $file), $content);
				}
				unset($content);
			}
		}
		location(ADMIN . '/config/'.$_POST['conf_file'].'/ok');
	break;

	case 'backup':
		$adminTpl->admin_head(_BACKUP_NAME);
		
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>'._BACKUP_ADD.'</b>						
					</div><div class="panel-heading">';
		
		echo '<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/config/action">
		<table cellspacing="0" cellpadding="0" class="cont" width="100%" style="clear:both">';
		foreach($configs as $name => $row) 
		{
			if(isset($row['file'])) require ROOT . 'etc/' . $row['file'] . '.config.php';
			$val_name = $name;
			echo "
			<tr>
				<td><b>" . $row['name'] . "</b> <font color=\"green\">[etc/" . $name . ".config.php]</font></td>
				<td> <input type=\"checkbox\" name=\"checks[" . $name . "]\" value=\"" . (isset($row['param']) ? $row['param'] : $name.'_conf') . "\"></td>
			</tr>";	
		}

		foreach(glob(ROOT.'usr/modules/*/admin/list.php') as $listed) 
		{
			include($listed);
		}
			
		if(!empty($toconfig))
		{
			foreach($toconfig as $name => $row) 
			{
				echo "
				<tr>
					<td><b>" . $row['name'] . "</b> <font color=\"green\">[etc/" . $name . ".config.php]</font></td>
					<td> <input type=\"checkbox\" name=\"checks[" . $name . "]\" value=\"" . (isset($row['param']) ? $row['param'] : $name.'_conf') . "\"></td>
				</tr>";	
			}
		}
		echo '</table>
		<br>
		<input name="submit" type="submit" class="btn btn btn-success btn-parsley" id="sub" value="'._BACKUP_MAKE_COPY.'">	
		</form>';
		echo '</div></section></div></div>';

		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>'._BACKUP_RESTORE_FILE.'</b>						
					</div><div class="panel-heading">';
		echo '<div class="_open_title">'._BACKUP_RESTORE_DESC.'</div><br />';
		$adminTpl->open();
		echo "<form  action=\"{ADMIN}/config/restore\" method=\"post\" enctype=\"multipart/form-data\">"
		."<label>"._UPLOAD_FILE."</label> "
		."<input type=\"file\" name=\"file\" class=\"textinput\" /><br /><br /><input name=\"submit\" type=\"submit\" class=\"btn btn btn-success btn-parsley\" value=\""._RESTORE."\" />"
		."</form>";
		echo '</div></section></div></div>';
		$adminTpl->close();
		$adminTpl->admin_foot();	
		break;
		
	case 'restore':
		$adminTpl->admin_head(_BACKUP_RESTORE_NAME);
		if(isset($_FILES['file']['tmp_name']) && preg_match('#.txt#i', $_FILES['file']['name']))
		{
			$fileContent = unserialize(file_get_contents($_FILES['file']['tmp_name']));
			if(!empty($fileContent))
			{
				echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>'._BACKUP_RESTORE_NAME.'</b>						
					</div><div class="panel-heading">';			
				foreach($fileContent as $fileName => $confs)
				{
					$file = ROOT.'etc/'.$fileName.'.config.php';
					if(file_exists($file) && !empty($confs))
					{
						echo _BACKUP_RESTORE_LOG_1.' '.$fileName.'.config.php - <font color="green">'._BACKUP_RESTORE_LOG_2.'</font><br />';
						require($file);
						foreach($confs[1] as $param => $content)
						{
							if(!is_array($content))
							{
								eval('$'.$confs[0].'[$param] = is_utf8($content) ? $content : iconv(\'windows-1251//IGNORE\', \'UTF-8\', $content);');
							}
							else
							{
								foreach($content as $p => $c)
								{
									eval('$'.$confs[0].'[$param][$p] = is_utf8($c) ? $c : iconv(\'windows-1251//IGNORE\', \'UTF-8\', $c);');
								}
							}
						}
						saveMyConf($fileName, $confs[0], $$confs[0]);
						unset($$confs[0]);
					}
				}
				echo '</div></section></div></div>';
			}
		}
		else
		{
				$adminTpl->info(_BACKUP_ERROR_1, 'error');
		}
		$adminTpl->admin_foot();	
		break;
		
	case 'action':
		if(!empty($_POST['checks']))
		{
			foreach($_POST['checks'] as $fileName => $paramName)
			{
				$file = ROOT.'etc/'.$fileName.'.config.php';
				if(file_exists($file) && !empty($paramName))
				{
					require($file);
					$confBackup[$fileName] = array($paramName, $$paramName);
				}
			}

			if(!empty($confBackup))
			{
				$backup = serialize($confBackup);
				header('content-disposition: attachment; filename=config_backup_'.date('d-m-y', time()).'.txt');
				header('last-modified: '.time());
				header('accept-ranges: bytes');
				header('content-length: '.mb_strlen($backup));
				header('content-type: text/plain');
				echo $backup;
			}
		}
		else
		{
			$adminTpl->admin_head(_BACKUP_NAME . ' | ' . _ERROR);
			$adminTpl->info(_BACKUP_ERROR_0, 'error');
			$adminTpl->admin_foot();
		}
		break;

}

function saveMyConf($file, $parName, $configs)
{
		$file = 'etc/'.$file.'.config.php';
		$conf_arr_name = $parName;
		$content = "global $$conf_arr_name;\n";
		$content .= "\$$conf_arr_name = array();\n";
		$html = array('off_text', 'commentSignature', 'commentEditText');
		foreach($configs as $k => $val) {
			if($k != 'conf_arr_name' && $k != 'conf_file') 
			{
				if(!is_array($val)) 
				{
					if(!in_array($k, $html))
					{
						$content .= "\$".$conf_arr_name."['".$k."'] = \"".htmlspecialchars(str_replace('"', '\"', stripslashes($val)), ENT_QUOTES)."\";\n";
					}
					else
					{
						$content .= "\$".$conf_arr_name."['".$k."'] = \"".str_replace('"', '\"', stripslashes($val))."\";\n";
					}
				} 
				else 
				{
					foreach($val as $karr => $varr) {
						$content .= "\$".$conf_arr_name."['".$k."']['".$karr."'] = \"".htmlspecialchars(stripslashes($varr), ENT_QUOTES)."\";\n";
					}
				}
			}
		}
		save_conf($file, $content);
}