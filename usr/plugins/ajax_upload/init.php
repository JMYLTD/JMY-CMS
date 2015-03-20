<?php
if (!defined('ACCESS')) {
	header('Location: /');
	exit;
}

require ROOT . 'etc/files.config.php';


function file_upload($module, $id, $textarea = '') {
global $files_conf, $flist, $config, $core;
	$ajax_dir = 'usr/plugins/ajax_upload';
	$root = ROOT;
	$formats = $files_conf['imgFormats'].','.$files_conf['attachFormats'];
	$format = explode(',', $formats);
	$id = $id ? $id : 'temp';
	foreach($format as $ff) 
	{
		$flist .= '*'.$ff.';';
	}
	$uniCode = 'fe'.gencode(3);
	$max_size = formatfilesize($files_conf['max_size']);
	if($module == 'user')
	{
		if(!empty($textarea))
		{
			global $news_conf;
			$id = 'user_temp'.$core->auth->user_id;
			$module = $textarea;
			full_rmdir(ROOT.'files/'.$module.'/'.$id);
			mkdir(ROOT.'files/'.$module.'/'.$id, 0777);
			@chmod_R(ROOT.'files/'.$module.'/'.$id, 0777);
			$uploadScr = $config['url'].'/usr/modules/' . $module . '/ajax_upload.php';
			$files_conf['thumb_width'] = $news_conf['thumb_width'];
			$files_conf['max_size'] = $news_conf['max_size'];
		}
		else
		{
			$uploadScr = $config['url'].'/usr/plugins/ajax_upload/upload_user.php';
		}
		//echo '<script src="usr/js/engine.js" type="text/javascript"></script><script type="text/javascript">var textareaName = \'' . $textarea . '\'; var userMod = true;</script><script type="text/javascript" src="usr/js/bb_editor.js"></script>';
	}
	else
	{
		$uploadScr = $config['url'].'/usr/plugins/ajax_upload/upload.php';
	}
	$hash = $_COOKIE[COOKIE_AUTH];
$file_editor = <<<HTML
<link href="{$ajax_dir}/files/default.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$ajax_dir}/files/swfupload.js"></script>
<script type="text/javascript" src="{$ajax_dir}/files/swfupload.queue.js"></script>
<script type="text/javascript" src="{$ajax_dir}/files/fileprogress.js"></script>
<script type="text/javascript" src="{$ajax_dir}/files/handlers.js"></script>
<script type="text/javascript">
		var {$uniCode};
		var water = {$files_conf['watermark']};
		var prevSize = {$files_conf['thumb_width']};
		var id = '{$id}';
		var mod = '{$module}';
		var uniCode = '{$uniCode}';
		
		 
		 function watermarkStatus(id)
		 {
			if(water == 0) 
			{ 
				water = 1; 
				gid(id).innerHTML = '<font color="green">Включено</font>'; 
			} 
			else
			{ 
				water = 0; 
				gid(id).innerHTML = '<font color="red">Выключено</font>'; 
			}
			eval("{$uniCode}.addPostParam('watermark',"+(this.water == 1 ? 1 : 0)+");");
		 }
		 
		function previewSize(id)
		{
			var s = prompt('Размер файла в пикселах:', prevSize);
			if(s)
			{
				eval("{$uniCode}.addPostParam('thumbSize',"+s+");");
				gid(id).innerHTML = s;
			}
			else
			{
				alert('Размер не может быть равен 0!');
			}
		}
		

		function uploaderStart() 
		{
			{$uniCode} = new SWFUpload({
				upload_url: "{$uploadScr}",
				post_params: {'id':'{$id}','module':'{$module}','watermark':water,'thumbSize':prevSize,'hash':'{$hash}'},

				// File Upload Settings
				file_size_limit : "{$files_conf['max_size']}",	// 100MB
				file_types : "*.*",
				file_types_description : "All Files",
				file_upload_limit : "10",
				file_queue_limit : "0",

				// Event Handler Settings (all my handlers are in the Handler.js file)
				file_dialog_start_handler : fileDialogStart,
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,

				// Button Settings
				button_image_url : "/media/uploader/selectfiles.png",
				button_placeholder_id : "spanButtonPlaceholder1",
				button_width: 24,
				button_height: 24,
				
				// Flash Settings
				flash_url : "{$ajax_dir}/files/swfupload.swf",
				

				custom_settings : {
					progressTarget : "fsUploadProgress1",
					cancelButtonId : "btnCancel1"
				},
				

				// Debug Settings
				debug: false
			});

	
	     }
	</script>

	<br style="clear:both" />
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
				<td><div id="filelist">
					<script type="text/javascript">fileList('{$id}', '{$module}', 'ok', '');</script>
				</div>
				<br style="clear:both" /></td>
			</tr>
			<tr valign="top">
				<td>
					<div id="uploadForm_{$uniCode}">

						<div><div style="float:left">
							<span id="spanButtonPlaceholder1"></span>
						</div>
							<div id="buttonTwoStage" style="float:left; margin-left:4px;">
							<img src="media/uploader/upload.png" border="0" onclick="{$uniCode}.startUpload()" title="Загрузить" onmouseover="this.src = 'media/uploader/uploadhover.png'" onmouseout="this.src = 'media/uploader/upload.png'" style="cursor:pointer" /> 				
							<img src="media/uploader/cancel.png" border="0" onclick="{$uniCode}.cancelQueue();" title="Отменить загрузку" onmouseover="this.src = 'media/uploader/cancelhover.png'" onmouseout="this.src = 'media/uploader/cancel.png'" style="cursor:pointer"  /> 
							</div>
						
						<div style="float:right">
						<img src="media/uploader/size.png" border="0" title="Изменить размер превью" onmouseover="this.src = 'media/uploader/sizehover.png'" onmouseout="this.src = 'media/uploader/size.png'" style="cursor:pointer" onclick="previewSize('prevSize');" />
						<img src="media/uploader/watermark.png" border="0" title="Наложение ватермарка" onmouseover="this.src = 'media/uploader/watermarkhover.png'" onmouseout="this.src = 'media/uploader/watermark.png'" style="cursor:pointer" onclick="watermarkStatus('waterCont');" />
						</div>
						
						</div>
						<div style="clear:both; font-size:11px; text-align:right">
						Наложение ватермарка: <span id="waterCont"><script type="text/javascript">
if(water == 0) document.write('<font color="red">Выключено</font>'); else document.write('<font color="green">Включено</font>'); </script></span>
<br />
Размер первью: <b><span id="prevSize"><script type="text/javascript">document.write(prevSize)</script></span>px</b><br />
							<!--	Макс. размер файла: <font color="green">{$max_size}</font><br />
								Допустимые форматы: <br /><font color="green">{$formats}</font><br />
								Кол-во одновременных закачек: <font color="green">30</font><br />-->
</div>
<br style="clear:both;" />
						<div id="fsUploadProgress1">
						
						</div>
					</div></td>
					</tr>
		</table>
</div>
HTML;

return $file_editor;
}
