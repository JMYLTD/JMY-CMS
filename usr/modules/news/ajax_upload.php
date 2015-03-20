<?php
define('RFILE', '../../../');
define('ROOT', '../../../');
require RFILE . 'lib/require.php';
require RFILE . 'boot/sub_classes/upload.class.php';

ajaxInit();

$auth = new auth(filter($_POST['hash'], 'a'));
require RFILE . 'etc/news.config.php';

if($auth->user_info['user'] == 1 && $auth->user_info['loadAttach'] == 1)
{
	$parseStr = $news_conf['imgFormats'];
	$parseStr2 = $news_conf['attachFormats'];
	$imageType = explode(',', $parseStr);
	$attachType = explode(',', $parseStr2);


	function reNameBadFile($fileFormat)
	{
		$badArray = array(
		'php' => 'phps'
		);
		if(in_array($fileFormat, array_keys($badArray)))
		{
			return $badArray[$fileFormat];
		}
		else
		{
			return $fileFormat;
		}
	}


	if(is_uploaded_file($_FILES['Filedata']['tmp_name'])) 
	{
		if($news_conf['max_size'] >= $_FILES['Filedata']['size'])
		{
			$fileInfo = explode('.', $_FILES['Filedata']['name']);
			$fileFormat = mb_strtolower(end($fileInfo));
			$id = filter($_POST['id'], 'a');
			$hash = filter($_POST['hash'], 'a');
			$module = filter($_POST['module'], 'module');
			$modDir = RFILE . 'files/' . $module . '/';
			$watermarkStatus = isset($_POST['watermark']) && is_numeric($_POST['watermark']) ? intval($_POST['watermark']) : $files_conf['watermark'];
			$thumbSize = isset($_POST['thumbSize']) && is_numeric($_POST['thumbSize']) ? intval($_POST['thumbSize']) : $news_conf['thumb_width'];

			if(!is_dir($modDir)) 
			{
				mkdir($modDir, 0777);
				@chmod_R($modDir, 0777);
				mkdir($modDir.'temp', 0777);
				@chmod_R($modDir.'temp', 0777);
			}
			
			$dir = RFILE . 'files/' . $module . '/' . $id . '/';
			$dfir = 'files/' . $module . '/' . $id . '/';
			
			if(!is_dir($dir)) 
			{
				mkdir($dir, 0777);
				@chmod_R($dir, 0777);
				mkdir($dir.'thumb', 0777);
				@chmod_R($dir.'thumb', 0777);
			} 
			else 
			{
				if(!is_dir($dir.'thumb')) 
				{
					mkdir($dir.'thumb', 0777);
				}
			}
			
			if(in_array($fileFormat, $imageType))
			{
				$xName = translit(mb_substr(utf_decode($_FILES['Filedata']['name']), 0, 20), '.');
				$xName = str_replace('.'.$fileFormat, '', $xName);
				$thumbDir = 'thumb-'.$xName;
				if (file_exists($imgDir)) 
				{
					$unicCode = gencode(10);
					$imgDir = $unicCode . '-' . $xName;
					$thumbDir = 'thumb-' . $unicCode . '-' . $xName;
				}
				
				list($width, $height, $type, $attr) = getimagesize($_FILES['Filedata']['tmp_name']);
				
				if($width && $height && $type)
				{
					/*$water = explode('|', $files_conf['watermark_position']);
					$original=new Thumbnail($_FILES['Filedata']['tmp_name']);
					$original->quality = $files_conf['quality'];
					$original->jpeg_progressive = 1;
					if($watermarkStatus == 1) 
					{
						if(!empty($files_conf['watermark_image']))
						{
							$original->img_watermark = $files_conf['watermark_image'];
							$original->img_watermark_Valing = strtoupper($files_conf['watermark_valign']);
							$original->img_watermark_Haling = strtoupper($files_conf['watermark_halign']);
						}
						else
						{
							$original->txt_watermark = $files_conf['watermark_text'];	
							$original->txt_watermark_Valing = strtoupper($files_conf['watermark_valign']);
							$original->txt_watermark_Haling = strtoupper($files_conf['watermark_halign']);
							$original->txt_watermark_font = 3;
						}
					}
					$original->process();
					$original->save($imgDir);
					if($width > $news_conf['thumb_width'])
					{
						$thumb=new Thumbnail($_FILES['Filedata']['tmp_name']);
						$thumb->size_width($thumbSize);
						$thumb->quality = $files_conf['quality'];
						if($watermarkStatus == 1) 
						{
							if(!empty($files_conf['watermark_image']))
							{
								$thumb->img_watermark = $files_conf['watermark_image'];
								$thumb->img_watermark_Valing = strtoupper($files_conf['watermark_valign']);
								$thumb->img_watermark_Haling = strtoupper($files_conf['watermark_halign']);
							}
							else
							{
								$thumb->txt_watermark = $files_conf['watermark_text'];	
								$thumb->txt_watermark_Valing = strtoupper($files_conf['watermark_valign']);
								$thumb->txt_watermark_Haling = strtoupper($files_conf['watermark_halign']);
								$thumb->txt_watermark_font = 3;
							}
						}
						$thumb->process();
						$thumb->save($thumbDir);
					}*/
					if($foo = new Upload($_FILES['Filedata']))
					{
						$foo->file_new_name_body = $xName;
						$foo->Process($dir);
							
						if ($foo->processed) 
						{
							$imgDir = $dir.$xName.'.'.$foo->file_dst_name_ext;
						}	
						
						if($width > $news_conf['thumb_width'])
						{						
							$foo->file_new_name_body = $thumbDir;
							$foo->image_resize = true;
							$foo->image_x = $thumbSize;
							$foo->allowed = array("image/*");
							$foo->image_ratio_y = true;
							$foo->Process($dir.'thumb/');
								
							if ($foo->processed) 
							{
								$thumbDir = $dir.$thumbDir.'.'.$foo->file_dst_name_ext;
							}
						}
						
						$foo->Clean();
					}
				}
				else
				{
					HandleError('Р¤Р°Р№Р» РЅРµ СЏРІР»СЏРµС‚СЃСЏ РєР°СЂС‚РёРЅРєРѕР№!');
				}
			}
			elseif(in_array($fileFormat, $attachType))
			{
				if($id == 'temp')
				{
					$id = 0;
					$temp = true;
				}

				if($foo = new Upload($_FILES['Filedata']))
				{
					$foo->Process($dir);
						
					if ($foo->processed) 
					{
						$foo->Clean();
					}	
					
				}
				
				$db->query("INSERT INTO `" . DB_PREFIX . "_attach` ( `id` , `name` , `url` , `pub_id` , `mod` , `downloads` , `date` ) 
		VALUES (NULL, '" . $foo->file_dst_name . "', '" . $dfir.$foo->file_dst_name . "', '" . $id . "', '" . $module . "', '0', '" . time() . "');");
			}
			else
			{
				HandleError('Формат файла не поддерживается');
			}
			/*$fp = @fopen('log.html', 'w');
			fwrite($fp, serialize($_POST));
			fclose($fp);*/
			HandleError('Файл загружен.');
			
		}
		else
		{
			HandleError('Слишком большой размер файла.');
		}
	}
	else
	{
		HandleError('Файл не определён. Проверьте настройки PHP');
		exit(0);
	}
}
else
{
	HandleError('Fuck me later.');
}

function HandleError($message) 
{
	//header("HTTP/1.1 500 Internal Server Error");
	echo $message;
	exit(0);
}