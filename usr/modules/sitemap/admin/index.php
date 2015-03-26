<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   26.03.2015
*/
 
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
} 

loadConfig('sitemap');
switch(isset($url[3]) ? $url[3] : null) 
{
	default:
		$adminTpl->admin_head(_MODULES .' | '. _SM_SITEMAP);
		echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>'._SM_SITEMAP.'</b>						
					</div>';
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_sitemap ORDER BY id ASC");
		if($db->numRows($query) > 0) 
		{
			echo '<div class="panel-body no-padding">					
						<table class="table no-margin">
							<thead>
								<tr>									
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-3">'._TITLE.'</th>
									<th class="col-md-2">'._SM_UPDATE.'</th>
									<th class="col-md-2">'._SM_PR.'</th>
									<th class="col-md-5">URL</th>
								</tr>
							</thead>
							<tbody>';	
			while ($result = $db->getRow($query)) 
			{
				echo '<tr>				
				<td><span class="pd-l-sm"></span>' . $result['id'] . '</td>
				<td>' . $result['name'] . '</td>				
				<td>' . $sitemap_conf['change'] . '</td>
				<td>'. $sitemap_conf['priority'] . '</td>	
				<td>'. $result['url'] . '</td>				
				</tr>';
			}		
			echo '<tr><td></td><td></td><td></td><td></td><td></td></tr></tbody></table></div>';
		}
		else
		{
			echo '<div class="panel-heading">'._SM_EMPTY.'</div>';
		}
		echo'</section></div></div>';	
		$adminTpl->admin_foot();
		break;

case 'create':
		global $core, $config;
		$adminTpl->admin_head(_MODULES .' | '. _SM_SITEMAP.' | '. _SM_GEN);
		$db->query("TRUNCATE TABLE " . DB_PREFIX . "_sitemap");
		$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". _SM_MAIN. "', '".$config['url']."/');");
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_plugins WHERE service='modules' ORDER BY title ASC");
		$exceMods = array('feed', 'pm', 'profile', 'search', 'poll', 'mainpage');
		if($db->numRows($query) > 0) 
		{
			while($mod = $db->getRow($query)) 
			{
				if(!in_array($mod['title'], $exceMods))
				{				
					if ($mod['active']==1) 
					{
						$file = ROOT.'usr/modules/'.$mod['title'].'/sitemap.php';
						$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $mod['content']. "', '".$config['url']."/".$mod['title']."');");
						if (file_exists($file))
						{							
							include($file);	
						}
					}
				}				
			}
		}
		$sitemapXML='<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
		<!-- Last update of sitemap '.date("Y-m-d H:i:s+06:00").' -->';
		$sitemapTXT=NULL;
		$query_sm = $db->query("SELECT * FROM ".DB_PREFIX."_sitemap ORDER BY id ASC");	
		$ic=$db->numRows($query_sm);
		if($db->numRows($query_sm) > 0) 
		{	
			while($sm = $db->getRow($query_sm)) 
			{
					$sitemapXML.="\r\n<url><loc>{$sm['url']}</loc><changefreq>{$sitemap_conf['change']}</changefreq><priority>{$sitemap_conf['priority']}</priority></url>";
					$sitemapTXT.="\r\n".$sm['url'].' '.$sitemap_conf['change'].' '.$sitemap_conf['priority'];
			}
			$flag=true;
		}		
		$sitemapXML.="\r\n</urlset>";		
		$fp=fopen('files/sitemap.txt','w+');if(!fwrite($fp,$sitemapTXT)){$flag=false;}fclose($fp);
		$fp=fopen('files/sitemap.xml','w+');if(!fwrite($fp,$sitemapXML)){$flag=false;}fclose($fp);	
		if ($flag==true)
		{
			$adminTpl->info(str_replace('{numb}', $ic, _SM_GEN_OK));	
		}
		else
		{
			$adminTpl->info(_SM_ERROR_0, 'error');	
		}
		$adminTpl->admin_foot();		
		break;
		
	case 'update':
		global $core, $config;
		$adminTpl->admin_head(_MODULES .' | '. _SM_SITEMAP.' | '. _SM_SEARCH);		
		$scheme=$sitemap_conf['scheme']; 
		$host=substr($config['url'], strrpos($config['url'], '//')+2); 
		$url_map=$scheme.$host.'sitemap.xml';
			
		if (strpos ( send_url("http://google.com/webmasters/sitemaps/ping?sitemap=", $url_map), "successfully added" ) !== false) 
		{
			$content_map .='Google: '._SM_SEND_OK.'<br />';
		} 
		else
		{
			$content_map .='Google: <a href="http://google.com/webmasters/sitemaps/ping?sitemap='.urlencode($url_map).'">'._SM_SEND_ERROR.'</a><br />';
		}
		if (strpos ( send_url("http://ping.blogs.yandex.ru/ping?sitemap=", $url_map), "OK" ) !== false) 
		{
			$content_map .='Яндекс: '._SM_SEND_OK.'<br />';
		} 
		else
		{
			$content_map .='Яндекс: <a href="http://ping.blogs.yandex.ru/ping?sitemap='.urlencode($url_map).'">'._SM_SEND_ERROR.'</a><br />';
		}
		if (strpos ( send_url("http://rpc.weblogs.com/pingSiteForm?name=InfraBlog&url=", $url_map), "Thanks for the ping" ) !== false) 
		{
			$content_map .='Weblogs: '._SM_SEND_OK.'<br />';
		} 
		else
		{
			$content_map .='Weblogs: <a href="http://rpc.weblogs.com/pingSiteForm?name=InfraBlog&url='.urlencode($url_map).'">'._SM_SEND_ERROR.'</a><br />';
		}		
		if (strpos ( send_url("http://www.bing.com/webmaster/ping.aspx?siteMap=", $url_map), "http://www.bing.com/ping?sitemap=" ) == false) 
		{
			$content_map .='Bing: '._SM_SEND_OK.'<br />';
		} 
		else
		{
			$content_map .='Bing: <a href="http://rpc.weblogs.com/pingSiteForm?name=InfraBlog&url='.urlencode($url_map).'">'._SM_SEND_ERROR.'</a><br />';
		}
		$adminTpl->info($content_map.' <br /><a href="{MOD_LINK}">'._SM_BACK.'</a>');			
		$adminTpl->admin_foot();
		break;			
		
	case 'config':		
		$configBox = array(
			'sitemap' => array(
				'varName' => 'sitemap_conf',
				'title' => _SM_CONFIG,
				'groups' => array(
					'main' => array(
						'title' => _SM_CONFIG_MAIN,
						'vars' => array(											
							'priority' => array(
								'title' => _SM_CONFIG_PR,
								'description' => _SM_CONFIG_PR_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'change' => array(
								'title' => _SM_CONFIG_UPDATE,
								'description' => _SM_CONFIG_UPDATE_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),	
							'keywords' => array(
								'title' => _CONFIG_KEYWORDS,
								'description' => _CONFIG_SEO_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),		
							'description' => array(
								'title' => _CONFIG_DESC,
								'description' => _CONFIG_SEO_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),									
						)
					),
				),
			),
		);
		$ok = false;		
		if(isset($_POST['conf_file']))
		{
			$ok = true;
		}		
		generateConfig($configBox, 'sitemap', '{MOD_LINK}/config', $ok);
		break;
		
}

function send_url($url, $sitemap)
{		
			$data = false;
			$file = $url.urlencode($sitemap);		
			if(function_exists('curl_init'))
			{			
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $file );
				curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 6 );			
				$data = curl_exec( $ch );
				curl_close( $ch );
				return $data;
				
			} 
			else 
			{
				return @file_get_contents( $file );
			}	
}

?>