<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/
 
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
} 
require ROOT . 'etc/sitemap.config.php'; 
switch(isset($url[3]) ? $url[3] : null) 
{
	default:
		$adminTpl->admin_head('Модули | Карта сайта');
		echo '
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>Карта сайта</b>						
					</div>';
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_sitemap ORDER BY id ASC");
		if($db->numRows($query) > 0) 
		{
			echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/blocks/action">
						<table class="table no-margin">
							<thead>
								<tr>									
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-5">'._TITLE.'</th>
									<th class="col-md-2">Частота обновления</th>
									<th class="col-md-2">Приоритет</th>
									<th class="col-md-3">URL</th>
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
		
		echo '<tr><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>
		
	</form></div>';
	}
else
{
echo '<div class="panel-heading">Карта сайта пуста!</div>';
}
echo'</section></div></div>';	
		
	
		$adminTpl->admin_foot();
		break;

case 'create':
		global $core, $config;
		$adminTpl->admin_head('Модули | Карта сайта | Генерация карты сайта');	
		
			
		set_time_limit(0);
		$host=substr($config['url'], strrpos($config['url'], '//')+2); // Хост сайта
		$scheme=$sitemap_conf['scheme']; 
		$urls=array();
		$content=NULL; 

		// Здесь ссылки, которые не должны попасть в sitemap.xml
		$nofollow=array('/search/','/404/','/pm/','/profile/','javascript:reloadCaptcha();');

		// Первой ссылкой будет главная страница сайта, ставим ей 0, т.к. она ещё не проверена
		$urls[$scheme.$host]='0';
		// Разрешённые расширения файлов, чтобы не вносить в карту сайта ссылки на медиа файлы. Также разрешены страницы без разрешения.
		$extensions[]='php';$extensions[]='aspx';$extensions[]='htm';$extensions[]='html';$extensions[]='asp';$extensions[]='cgi';$extensions[]='pl';


		function sitemap_geturls($page,&$host,&$scheme,&$nofollow,&$extensions,&$urls)
		{			
			if($urls[$page]==1){continue;}			
			$content=file_get_contents($page);if(!$content){unset($urls[$page]);return false;}			
			$urls[$page]=1;			
			if(preg_match('/<[Mm][Ee][Tt][Aa].*[Nn][Aa][Mm][Ee]=.?("|\'|).*[Rr][Oo][Bb][Oo][Tt][Ss].*?("|\'|).*?[Cc][Oo][Nn][Tt][Ee][Nn][Tt]=.*?("|\'|).*([Nn][Oo][Ff][Oo][Ll][Ll][Oo][Ww]|[Nn][Oo][Ii][Nn][Dd][Ee][Xx]|[Nn][Oo][Nn][Ee]).*?("|\'|).*>/',$content)){$content=NULL;}
			//Собираем все ссылки со страницы во временный массив, с помощью регулярного выражения.
			preg_match_all("/<[Aa][\s]{1}[^>]*[Hh][Rr][Ee][Ff][^=]*=[ '\"\s]*([^ \"'>\s#]+)[^>]*>/",$content,$tmp);$content=NULL;
			//Добавляем в массив links все ссылки не имеющие аттрибут nofollow
			foreach($tmp[0] as $k => $v){if(!preg_match('/<.*[Rr][Ee][Ll]=.?("|\'|).*[Nn][Oo][Ff][Oo][Ll][Ll][Oo][Ww].*?("|\'|).*/',$v)){$links[$k]=$tmp[1][$k];}}
			unset($tmp);
			//Обрабатываем полученные ссылки, отбрасываем "плохие", а потом и с них собираем...
			for ($i = 0; $i < count($links); $i++)
			{
				echo $links[$i];
				//Если слишком много ссылок в массиве, то пора прекращать нашу деятельность (читай спецификацию)
				if(count($urls)>49900){return false;}
				//Если не установлена схема и хост ссылки, то подставляем наш хост
				if(!strstr($links[$i],$scheme.$host)){$links[$i]=$scheme.$host.'/'.$links[$i];}
				//Убираем якори у ссылок
				$links[$i]=preg_replace("/#.*/X", "",$links[$i]);
				//Узнаём информацию о ссылке
				$urlinfo=@parse_url($links[$i]);if(!isset($urlinfo['path'])){$urlinfo['path']=NULL;}
				//Если хост совсем не наш, ссылка на главную, на почту или мы её уже обрабатывали - то заканчиваем работу с этой ссылкой
				if((isset($urlinfo['host']) AND $urlinfo['host']!=$host) OR $urlinfo['path']=='/' OR isset($urls[$links[$i]]) OR strstr($links[$i],'@')){continue;}
				//Если ссылка в нашем запрещающем списке, то также прекращаем с ней работать
				$nofoll=0;if($nofollow!=NULL){foreach($nofollow as $of){if(strstr($links[$i],$of)){$nofoll=1;break;}}}if($nofoll==1){continue;}
				//Если задано расширение ссылки и оно не разрешёно, то ссылка не проходит
				$ext=end(explode('.',$urlinfo['path']));
				$noext=0;if($ext!='' AND strstr($urlinfo['path'],'.') AND count($extensions)!=0){$noext=1;foreach($extensions as $of){if($ext==$of){$noext=0;continue;}}}if($noext==1){continue;}
				//Заносим ссылку в массив и отмечаем непроверенной (с неё мы ещё не забирали другие ссылки)
				$urls[$links[$i]]=0;
				//Проверяем ссылки с этой страницы
				sitemap_geturls($links[$i],$host,$scheme,$nofollow,$extensions,$urls);
			}
			return true;
		}
		
		sitemap_geturls($scheme.$host,$host,$scheme,$nofollow,$extensions,$urls);	
		$sitemapXML='<?xml version="1.0" encoding="UTF-8"?>
		<urlset xmlns="http://www.google.com/schemas/sitemap/0.84"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.google.com/schemas/sitemap/0.84/sitemap.xsd">
		<!-- Last update of sitemap '.date("Y-m-d H:i:s+06:00").' -->';
		$sitemapTXT=NULL; 
		$db->query("TRUNCATE TABLE " . DB_PREFIX . "_sitemap"); 		
		$ic=0;
		foreach($urls as $k => $v)
		{
			if ($k<>$config['url']) 
			{ 
				$file=file_get_contents($k);
				$position=strpos($file,'<title>');
				$file=substr($file,$position);
				$position=strpos($file,'</title>');
				$file=substr($file,0,$position);
				$file=strip_tags($file);
				$file = str_replace(' - '.$config['name'], '', $file);
			}
			else
			{
				$file ='Главная';
			}
			$db->query("INSERT INTO `" . DB_PREFIX . "_sitemap` ( `name` , `url`) VALUES ('". $file. "', '" . $k . "');");
			$sitemapXML.="\r\n<url><loc>{$k}</loc><changefreq>{$sitemap_conf['change']}</changefreq><priority>{$sitemap_conf['priority']}</priority></url>";$sitemapTXT.="\r\n".$k.$sitemap_conf['change'].$sitemap_conf['priority'];
			$ic++;
			
		}		 
		$sitemapXML.="\r\n</urlset>";		
		$sitemapXML=trim(strtr($sitemapXML,array('%2F'=>'/','%3A'=>':','%3F'=>'?','%3D'=>'=','%26'=>'&','%27'=>"'",'%22'=>'"','%3E'=>'>','%3C'=>'<','%23'=>'#','&'=>'&')));
		$sitemapTXT=trim(strtr($sitemapTXT,array('%2F'=>'/','%3A'=>':','%3F'=>'?','%3D'=>'=','%26'=>'&','%27'=>"'",'%22'=>'"','%3E'=>'>','%3C'=>'<','%23'=>'#','&'=>'&')));
		$fp=fopen('files/sitemap.txt','w+');if(!fwrite($fp,$sitemapTXT)){echo 'Ошибка записи!';}fclose($fp);
		$fp=fopen('files/sitemap.xml','w+');if(!fwrite($fp,$sitemapXML)){echo 'Ошибка записи!';}fclose($fp);		
		$adminTpl->info('Карта сайта сгенерирована! Добавлено '.$ic.' страниц. <a href="{MOD_LINK}">К списку</a>');			
		$adminTpl->admin_foot();
		break;
		
	case 'update':
		global $core, $config;
		$adminTpl->admin_head('Модули | Карта сайта | Уведомление поисковых систем');
		
		$scheme=$sitemap_conf['scheme']; 
		$host=substr($config['url'], strrpos($config['url'], '//')+2); 
		$url_map=$scheme.$host.'sitemap.xml';
			
		if (strpos ( send_url("http://google.com/webmasters/sitemaps/ping?sitemap=", $url_map), "successfully added" ) !== false) 
		{
			$content_map .='Google: Карта сайта принята <br />';

		} 
		else
		{
			$content_map .='Google: <a href="http://google.com/webmasters/sitemaps/ping?sitemap='.urlencode($url_map).'">Ошибка отправки карты сайта </a><br />';
		}
		if (strpos ( send_url("http://ping.blogs.yandex.ru/ping?sitemap=", $url_map), "OK" ) !== false) 
		{
			$content_map .='Яндекс: Карта сайта принята <br />';

		} 
		else
		{
			$content_map .='Яндекс: <a href="http://ping.blogs.yandex.ru/ping?sitemap='.urlencode($url_map).'">Ошибка отправки карты сайта </a><br />';
		}
		if (strpos ( send_url("http://rpc.weblogs.com/pingSiteForm?name=InfraBlog&url=", $url_map), "Thanks for the ping" ) !== false) 
		{
			$content_map .='Weblogs: Карта сайта принята <br />';

		} 
		else
		{
			$content_map .='Weblogs: <a href="http://rpc.weblogs.com/pingSiteForm?name=InfraBlog&url='.urlencode($url_map).'">Ошибка отправки карты сайта </a><br />';
		}		
		if (strpos ( send_url("http://www.bing.com/webmaster/ping.aspx?siteMap=", $url_map), "http://www.bing.com/ping?sitemap=" ) == false) 
		{
			$content_map .='Bing: Карта сайта принята <br />';

		} 
		else
		{
			$content_map .='Bing: <a href="http://rpc.weblogs.com/pingSiteForm?name=InfraBlog&url='.urlencode($url_map).'">Ошибка отправки карты сайта </a><br />';
		}
		$adminTpl->info($content_map.' <br /><a href="{MOD_LINK}">Обратно к карте сайта</a>');			
		$adminTpl->admin_foot();
	break;
			
		
		case 'config':
		require (ROOT.'etc/sitemap.config.php');
		
		$configBox = array(
			'sitemap' => array(
				'varName' => 'sitemap_conf',
				'title' => 'Настройки модуля "Карта сайта"',
				'groups' => array(
					'main' => array(
						'title' => 'Основные настройки',
						'vars' => array(
							'scheme' => array(
								'title' => 'Протокол сайта',
								'description' => 'Укажите протокол вашего сайта http:// или https://',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),						
							'priority' => array(
								'title' => 'Приоретет страниц',
								'description' => 'Для генерации в xml карте сайта',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'change' => array(
								'title' => 'Частота обновлений',
								'description' => 'Для генерации в xml карте сайта',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),	
							'keywords' => array(
								'title' => 'Keywords модуля',
								'description' => 'Для SEO оптимизации сайта',
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),		
							'description' => array(
								'title' => 'Description модуля',
								'description' => 'Для SEO оптимизации сайта',
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

function send_url($url, $sitemap) {		
			$data = false;
			$file = $url.urlencode($sitemap);		
			if( function_exists( 'curl_init' ) ) {			
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, $file );
				curl_setopt( $ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] );
				curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1 );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 6 );			
				$data = curl_exec( $ch );
				curl_close( $ch );
				return $data;
				
			} else {
				return @file_get_contents( $file );
			}	
		}	


?>