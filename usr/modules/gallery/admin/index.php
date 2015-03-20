<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ADMIN_SWITCH')) {
    header('Location: /');
    exit;
}

$query = isset($_POST['query']) ? filter($_POST['query'], 'a') : '';

$admin_conf['num'] = 12;
require ROOT . 'etc/gallery.config.php'; 

function newU()
{
global $adminTpl, $core, $db;
	$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE status='2'");
	if($db->numRows($query) > 0) 
	{
		$adminTpl->info(_NEW_PHOTOS.' <a href="'.ADMIN.'/module/gallery/new">'._MOVE_TO.'</a>');
	}
}

switch(isset($url[3]) ? $url[3] : null) 
{
	default:
		$adminTpl->admin_head(_MODULES.' | '._ALBUM);
		$page = init_page();
		$cut = ($page-1)*$admin_conf['num'];	
		if(isset($url[3]) && $url[3] == 'delOk')
		{
			$adminTpl->info(_ALBUM_DELETE_OK);
		}
		newU();
		echo '
						<style>
							.tooltip1 span{
									border-radius: 5px 5px 5px 5px;
									visibility: hidden;
									position: absolute;
									left: 200px;
									background: #fff;
									box-shadow: -2px 2px 10px -1px #333; 
									border-radius: 5px;	
								}
								 
								.tooltip1:hover span{
								visibility: visible;
								}
							</style>
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' ._ALBUM_LIST. '</b>						
					</div>';			
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_albums ORDER BY last_update DESC LIMIT ".$cut.", ".$admin_conf['num']."");
		if($db->numRows($query) > 0) {			
			echo '<div class="panel-body no-padding">					
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-7">' . _TITLE . '</th>
									<th class="col-md-3">'. _DESCRIPTION. '</th>									
									<th class="col-md-3">' . _ACTIONS . '</th>																	
								</tr>
							</thead>
							<tbody>';				
			while($album = $db->getRow($query)) {		
				
				$title = str($album['title'], 20);
				$img = $album['last_image'] ? '<a class="tooltip1" href="/gallery/album/' . $album['trans']  . '">'.$title.' <span><img src="' . $album['last_image'] . '"/></span></a>' : '<a href="/gallery/album/' . $album['trans']  . '">'.$title.'</a>';
				echo '
				<tr>
					<td><span class="pd-l-sm"></span>'.$album['album_id'].'</td>
					<td>'.$img.'</td>
					<td>'.($album['description'] ? $album['description'] : 'Нет описания').'</td>
					<td>	
							<a href="{MOD_LINK}/albumEdit/' . $album['album_id']. '">
							<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
							</a>
							<a href="{MOD_LINK}/albumDelete/' . $album['album_id'] . '" onClick="return getConfirm(\'Удалить альбом - ' . $title . '?\')" title="' . _DELETE . '" class="delete">
							<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
							</a>
					</td>					
				</tr>';				
			
			}
			echo '<tr><td></td><td></td><td></td><td></td></tbody></table>';
		echo "</div>";	
		}
		else
		{
			echo '<div class="panel-heading">'._NO_NEW_PHOTOS.'</div>';					
		}
		echo'</section></div></div>';			
		$all_query = $db->query("SELECT * FROM " . DB_PREFIX . "_gallery_albums");
		$all = $db->numRows($all_query);
		$adminTpl->pages($page, $admin_conf['num'], $all, ADMIN.'/module/gallery/{page}');
		$adminTpl->admin_foot();
		break;
		
	case 'photos':
		$adminTpl->admin_head(_MODULES.' | '._ALBUM.' | '._PHOTO);
		$page = init_page();
		$cut = ($page-1)*$admin_conf['num'];
		if(isset($url[3]) && $url[3] == 'delOk')
		{
			$adminTpl->info(_PHOTO_DELETE_OK);
		}
		newU();		
		echo '
						<style>
							.tooltip1 span{
									border-radius: 5px 5px 5px 5px;
									visibility: hidden;
									position: absolute;
									left: 200px;
									background: #fff;
									box-shadow: -2px 2px 10px -1px #333; 
									border-radius: 5px;	
								}
								 
								.tooltip1:hover span{
								visibility: visible;
								}
							</style>
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _PHOTO_LIST . '</b>						
					</div>';
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE status!='2' ORDER BY add_date DESC LIMIT ".$cut.", ".$admin_conf['num']."");
		if($db->numRows($query) > 0) 
		{
				echo '<div class="panel-body no-padding">					
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-7">' . _TITLE . '</th>
									<th class="col-md-3">'._AUTHOR.'</th>									
									<th class="col-md-3">' . _ACTIONS . '</th>																	
								</tr>
							</thead>
							<tbody>';	
			while($photo = $db->getRow($query)) {		
				$images = unserialize($photo['photos']);
				$thumb = $images['mini'];
				$title = str($photo['title'], 20);
				echo '
				<tr>
					<td><span class="pd-l-sm"></span>'.$photo['photo_id'].'</td>
					<td><a class="tooltip1" href="gallery/photo/' . $photo['photo_id'] . '">'.$title.' [' . ($photo['status'] == 0 ? '<font color="red">'._G_OFF.'</font>' : '<font color="green">'._G_ON.'</font>') . ']<span><img src="' . $thumb . '"/></span></a></td>
					<td>'.$photo['author'].'</td>
					<td>	
							<a href="{MOD_LINK}/photoEdit/' . $photo['photo_id'] . '">
							<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
							</a>
							<a href="{MOD_LINK}/photoDelete/' . $photo['photo_id'] . '" onClick="return getConfirm(\'Удалить фотографию - ' . $photo['title'] . '?\')" title="' . _DELETE . '" class="delete">
							<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
							</a>
					</td>					
				</tr>';	
			}
			echo '<tr><td></td><td></td><td></td><td></td></tbody></table>';
		echo "</div>";	
		}
		else
		{
			echo '<div class="panel-heading">'._NO_NEW_PHOTOS.'</div>';					
		}
		echo'</section></div></div>';		
		$all_query = $db->query("SELECT * FROM " . DB_PREFIX . "_gallery_photos WHERE status!='0'");
		$all = $db->numRows($all_query);
		$adminTpl->pages($page, $admin_conf['num'], $all, ADMIN.'/module/gallery/photos/{page}');
		$adminTpl->admin_foot();
		break;
		
	case 'new':
		$adminTpl->admin_head(_MODULES.' | '._ALBUM.' | '._NEW_PHOTOS);
		echo '
						<style>
							.tooltip1 span{
									border-radius: 5px 5px 5px 5px;
									visibility: hidden;
									position: absolute;
									left: 200px;
									background: #fff;
									box-shadow: -2px 2px 10px -1px #333; 
									border-radius: 5px;	
								}
								 
								.tooltip1:hover span{
								visibility: visible;
								}
							</style>
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _NEW_PHOTOS . '</b>						
					</div>';	
	$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE status='2' ORDER BY add_date DESC");	
	if($db->numRows($query) > 0) 
	{
		echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{MOD_LINK}/activate">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-7">' . _TITLE . '</th>
									<th class="col-md-3">'._AUTHOR.'</th>									
									<th class="col-md-3">' . _ACTIONS . '</th>	
									<th class="col-md-1"> 
										<input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;" />
									</th>								
								</tr>
							</thead>
							<tbody>';	
		while($photo = $db->getRow($query)) 
		{
			$images = unserialize($photo['photos']);
			$thumb = $images['mini'];
			echo '
			<tr>
				<td><span class="pd-l-sm"></span>'.$photo['photo_id'].'</td>
				<td><a class="tooltip1" href="{MOD_LINK}/photoEdit/' . $photo['photo_id'] . '">'.$photo['title'].'<span><img src="' . $thumb . '"/></span></a></td>
				<td>'.$photo['author'].'</td>
				<td>
						<a href="{MOD_LINK}/activate/' . $photo['photo_id'] . '" onClick="return getConfirm(\''._ACTIVATE_PHOTO.'\')" title="' . _ACTIVATE . '">
						<button type="button" class="btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _ACTIVATE .'">A</button>
						</a>
						<a href="{MOD_LINK}/photoEdit/' . $photo['photo_id'] . '">
						<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
						</a>
						<a href="{MOD_LINK}/photoDelete/' . $photo['photo_id'] . '" onClick="return getConfirm(\'Удалить фотографию - ' . $photo['title'] . '?\')" title="' . _DELETE . '" class="delete">
						<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
						</a>
				</td>
				<td><input type="checkbox" name="checks[]" value="'.$photo['photo_id'].'" /></td>
			</tr>';	
		}
		echo '<tr><td></td><td></td><td></td><td></td><td></td></tbody></table>
		<div align="right">
			<table>
			<tr>		
			<td valign="top">
			<input name="submit" type="submit" class="btn btn-success" id="sub" value="'._ACCEPT.'" /><span class="pd-l-sm"></span>
			</td>
			</tr>
			</table>
			<br />	
			</div>';
		echo "</form></div>";	
		}
		else
		{
			echo '<div class="panel-heading">'._NO_NEW_PHOTOS.'</div>';					
		}
		echo'</section></div></div>';
		$adminTpl->admin_foot();
		break;
		
	case 'albumEdit':
		$aid = isset($url[4]) ? intval($url[4]) : '';
	case 'addAlbum':
		$adminTpl->admin_head(_MODULES.' | '._ALBUM.' | '._ADD_ALBUM);
			
		if(isset($aid))
		{
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_albums WHERE album_id = '" . $aid . "'");
			$alb = $db->getRow($query);
			$array = unserialize($alb['sizes']);
			$title = prepareTitle($alb['title']);
			$altname = $alb['trans'];
			$description = $alb['description'];
			$preview = $array['mini'];
			$big = $array['long'];
			$watermark = $alb['watermark'];
			$tit = _EDIT_ALBUM;
		}
		else
		{
			$title = '';
			$altname = '';
			$description = '';
			$preview = $gallery_config['size-mini'];
			$big = $gallery_config['size-long'];
			$watermark = true;			
			$tit = _ADD_ALBUM;
		}
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . $tit . '</b>						
					</div>
					<div class="panel-body">
						<form class="form-horizontal parsley-form" role="form" action="{MOD_LINK}/albumSaved" method="post">
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _TITLE . '</label>
													<div class="col-sm-4">
														<input value="' . $title . '" id="title" type="text" name="title" class="form-control" data-parsley-required="true" data-parsley-trigger="change" onchange="getTranslit(gid(\title\).value, \'altname\')"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _ADRESS . ' (URI)</label>
													<div class="col-sm-4">
														<input value="'. $altname . '" id="altname" type="text" name="altname" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' ._MINI_PREVIEW_WIDTH. '</label>
													<div class="col-sm-4">
														<input value="'. $preview . '" id="preview" type="text" name="preview" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' ._PIC_WIDTH. '</label>
													<div class="col-sm-4">
														<input value="'. $big . '" id="big" type="text" name="big" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' ._WATERMARK. '</label>
													<div class="col-sm-4">
														'.checkbox('watermark', $watermark).'
													</div>
												</div>
											</div>
											</section></div></div>';
											if(isset($aid)) 
		{
			echo '<input type="hidden" name="aid" value="' . $aid . '">';
		}										
		echo '<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<div class="panel-heading no-border"><b>Текст</b></div>
							<div class="panel-body">
								<div class="switcher-content">
								'.adminArea('description', html2bb($description), 5, 'textarea', '', true).'
									<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'.(isset($aid) ? _REFRESH : _ADD).' '._SALBUM.'">
									</form>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>';										
												
												
												
		
		$adminTpl->admin_foot();
		break;
		
	case 'albumSaved':
		$adminTpl->admin_head(_MODULES.' | '._ALBUM.' | '._ALBUM_ADDING);
		$title = isset($_POST['title']) ? filter($_POST['title'], 'title') : '';
		$altname = isset($_POST['altname']) ? filter($_POST['altname'], 'a') : '';
		$description = isset($_POST['description']) ? filter($_POST['description']) : '';
		$watermark = (isset($_POST['watermark']) && $_POST['watermark'] == 'on') ? 1 : 0;
		$preview = isset($_POST['preview']) ? intval($_POST['preview']) : $gallery_config['size-mini'];
		$big = isset($_POST['big']) ? intval($_POST['big']) : $gallery_config['size-long'];
		$aid = isset($_POST['aid']) ? true : false;
		
		if($title && $altname && !isset($stop))
		{
			$array['mini'] = $preview;
			$array['long'] = $big;
			$size = serialize($array);
			
			if($aid == false)
			{
				$dir = $gallery_config['save'] . $altname;
				if(!is_dir($dir))
				{
					mkdir($dir, 0777);
					@chmod_R($dir, 0777);
					mkdir($dir . '/thumb', 0777);
					@chmod_R($dir . '/thumb', 0777);
				}
				else
				{
					$r = gencode(3);
					mkdir($dir . $r, 0777);
					@chmod_R($dir . $r, 0777);
					mkdir($dir . $r . '/thumb', 0777);
					@chmod_R($dir . $r . '/thumb', 0777);
				}

				$db->query("INSERT INTO `" . DB_PREFIX . "_gallery_albums` (`title` , `trans` , `description` , `views` , `nums` , `watermark` , `sizes` , `dir` ) VALUES ('" . $db->safesql(processText($title)) . "', '" . $altname . "', '" . $db->safesql(parseBB(processText($description))) . "', '0', '0', '" . $watermark . "', '" . $size . "', '" . $dir . "');");
				$adminTpl->info(_ALBUM_OK.' <a href="{MOD_LINK}/addAlbum">'._BACK_TO_ADD_ALBUM.'</a> '._OR.' <a href="{MOD_LINK}">'._WATCH_ALBUM_LIST.'</a>');
			}
			else
			{
				$db->query("UPDATE `" . DB_PREFIX . "_gallery_albums` SET `title` = '" . $db->safesql(processText($title)) . "', `trans` = '" . $altname . "', `description` = '" . $db->safesql(parseBB(processText($description))) . "', `watermark` = '" . $watermark . "', `sizes` = '" . $size . "' WHERE `album_id` =" . $_POST['aid'] . " LIMIT 1 ;");
				$adminTpl->info(_ALBUM_REFRESH.' <a href="{MOD_LINK}/addAlbum">'._BACK_TO_ADD_ALBUM.'</a> '._OR.' <a href="{MOD_LINK}">'._WATCH_ALBUM_LIST.'</a>');
			}
		}
		else
		{
			$adminTpl->info(_NOT_FILLED, 'error');
		}
		$adminTpl->admin_foot();
		break;
		
	case 'albumDelete':
		$aid = isset($url[4]) ? intval($url[4]) : '';
		
		if(!empty($aid))
		{
			$cat = $db->getRow($db->query("DELETE FROM ".DB_PREFIX."_gallery_albums WHERE album_id='" . $aid . "'"));
			$photo_del = $db->query("SELECT photo_id, cat, photos FROM ".DB_PREFIX."_gallery_photos WHERE cat='$aid'");
			while ($row = $db->getRow($photo_del)) 
			{
				$ppid = $row['photo_id'];
				$cat = $row['cat'];
				$ph = $row['ph'];
				$i = unserialize($ph);
				if (file_exists($i['photo'])) @unlink($i['photo']);
				if (file_exists($i['original'])) @unlink($i['original']);
				if (file_exists($i['mini'])) @unlink($i['mini']);
				deleteComments('gallery', $ppid);
				$db->query("DELETE FROM ".DB_PREFIX."_gallery_photos WHERE photo_id='$ppid'");
			}
			
			full_rmdir($cat['dir']);
		}
		
		location(ADMIN . '/module/gallery/delOk');
		break;	
		
	case 'photoDelete':
		$pid = isset($url[4]) ? intval($url[4]) : '';
		
		if(!empty($pid))
		{
			list($cat, $ph, $sts) = $db->fetchRow($db->query("SELECT cat, photos, status FROM ".DB_PREFIX."_gallery_photos WHERE photo_id='$pid'"));
			$i = unserialize($ph);
			@unlink($i['photo']);
			@unlink($i['original']);
			@unlink($i['mini']);
			deleteComments('gallery', $pid);
			$db->query("DELETE FROM ".DB_PREFIX."_gallery_photos WHERE photo_id='$pid'");
			list($author, $add_date, $photos) = $db->fetchRow($db->query("SELECT author, add_date, photos FROM ".DB_PREFIX."_gallery_photos WHERE cat='$cat' ORDER BY add_date DESC LIMIT 1"));
			if($sts != 2)
			{
				if($author)
				{
					$j = unserialize($photos);
					$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET nums=nums-1, last_update='" . $add_date . "', last_author='" . $db->safesql($author) . "', last_image='" . $j['mini'] . "' WHERE album_id='$cat'");
				}
				else
				{
					$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET nums=nums-1, last_update='', last_author='', last_image='' WHERE album_id='" . $cat . "'");
				}
			}
		}
		
		location(ADMIN . '/module/gallery/photos/delOk');
		break;
	
	
	case 'photoEdit':
		$aid = intval($url[4]);
	case 'addPhoto':
		$adminTpl->admin_head(_MODULES.' | '._ALBUM.' | '._ADD_PHOTO);
			
		if(isset($aid))
		{
			$bb = new bb;
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE photo_id = '" . $aid . "'");
			$photo = $db->getRow($query);
			$photoss = unserialize($photo['photos']);
			$title = prepareTitle($photo['title']);
			$album = $photo['cat'];
			$author = $photo['author'];
			$model = $photo['tech'];
			$photos = $photoss['original'];
			$date = $photo['photo_date'];
			$description = $bb->htmltobb($photo['description']);
			$status = $photo['status'] == 2 ? 0 : $photo['status'];
			$type = 'text';
			$tit = _EDIT_PHOTO;
		}
		else
		{
			$title = '';
			$album = '';
			$author = $core->auth->user_info['nick'];
			$model = '';
			$date = '';
			$photos = '';
			$description = '';
			$status = true;
			$watermark = true;
			$type = 'file';
			$tit = _ADD_PHOTO;
		}
		
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . $tit . '</b>						
					</div>
					<div class="panel-body">
						<form class="form-horizontal parsley-form" action="{MOD_LINK}/photoSave" method="post" enctype="multipart/form-data" name="news">
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _TITLE . '</label>
													<div class="col-sm-4">
														<input value="' . $title . '" id="title" type="text" name="title" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _ALBUM . '</label>
													<div class="col-sm-4">
														<select name="album" class="form-control">';
														$result = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_albums ORDER BY title");
														if ($db->numRows($result) > 0) 
														{
															while ($row = $db->getRow($result)) 
															{
																$sel = ($album == $row['album_id']) ? "selected" : "";
																echo "<option value=\"" . $row['album_id'] . "\" " . $sel . ">" . $row['title'] . "</option>";
															}
														}
												echo '</select>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' . _AUTHOR . '</label>
													<div class="col-sm-4">
														<input value="' . $author . '" id="author" type="text" name="author" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' .  _FOTO_DATE . '</label>
													<div class="col-sm-4">
														<input value="' . $date . '" id="date" type="text" name="date" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' .  _FOTO_MODEL . '</label>
													<div class="col-sm-4">
														<input value="' . $model . '" id="model" type="text" name="model" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>												
												<div class="form-group">
													<label class="col-sm-3 control-label">' .  _UPLOAD_FORM . '</label>
													<div class="col-sm-4">';
												echo "<input type=\"" . $type . "\" size=\"20\" name=\"urlLocal\" class=\"textinput\" value=\"" . $photos . "\" id=\"title\" style=\"width:300px;\" />";
												echo'	</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' .  _UPLOAD_SITE . '</label>
													<div class="col-sm-4">
														<input type="text" name="urlFrom" class="form-control" data-parsley-required="true" data-parsley-trigger="change"\>
													</div>
												</div>
												<div class="form-group">
													<label class="col-sm-3 control-label">' .  _ACTIVATE . '</label>
													<div class="col-sm-4">
														'.checkbox('status', $status).'
													</div>
												</div>';
												if(isset($watermark))
														{
											echo' <div class="form-group">
													<label class="col-sm-3 control-label">' .  _WATERMARK_AL . '</label>
													<div class="col-sm-4">	
															'.checkbox('watermark', $watermark).'
													</div>
												</div>';
														}
		
										echo '</div>
											</section></div></div>';
											if(isset($aid)) 
											{
												echo '<input type="hidden" name="aid" value="' . $aid . '">';
											}
		echo '<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<div class="panel-heading no-border"><b>'._DESCRIPTION.'</b></div>
							<div class="panel-body">
								<div class="switcher-content">
								'.adminArea('description', $description, 5, 'textarea', '', true).'
									<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'.(isset($aid) ? _REFRESH : _ADD).' '._SPHOTO.'">
									</form>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>';										
												
		
		$adminTpl->admin_foot();
		break;
		
	case 'photoSave':
		$bb = new bb;
		$aid = isset($_POST['aid']) ? intval($_POST['aid']) : '';
		$title = isset($_POST['title']) ? filter($_POST['title'], 'title') : '';
		$album = isset($_POST['album']) ? intval($_POST['album']) : '';
		$author = isset($_POST['author']) ? filter($_POST['author'], 'nick') : '';
		$date = isset($_POST['date']) ? filter($_POST['date']) : '';
		$model = isset($_POST['model']) ? filter($_POST['model'], 'a') : '';
		$description = isset($_POST['description']) ? $bb->parse(processText(filter($_POST['description'])), false, false) : '';
		$urlFrom = isset($_POST['urlFrom']) ? filter($_POST['urlFrom']) : '';
		$photoStatus = ($_POST['status'] == 'on') ? 1 : 0;
		$watermark = isset($_POST['watermark']) && ($_POST['watermark'] == 'on') ? 1 : 0;
		$adminTpl->admin_head(_MODULES.' | '._ALBUM.' | '._PHOTO_ADDING);
		
		if((isset($_FILES['urlLocal']) && is_uploaded_file($_FILES['urlLocal']['tmp_name']) OR $urlFrom != '') OR isset($aid)) 
		{
			$fileIs = true;
			if($urlFrom != '') $loadFrom = true;
		}
		else
		{
			$stop[] = _GNOTIFY1;
		}
		
		if($title && $album)
		{
			if(!empty($aid))
			{
				$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE photo_id = '" . $aid . "'");
				$photo = $db->getRow($query);
				$photos = unserialize($photo['photos']);
				
				if($album != $photo['cat'])
				{
					$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE cat = '" . $photo['cat'] . "' ORDER BY add_date DESC LIMIT 1");
					$photoNew = $db->getRow($query);
					$photosNew = unserialize($photoNew['photos']);
					prt($photoNew);
					
					$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET nums=nums+1, last_update='" . time() . "', last_author='" . $author . "', last_image='" . $photos['mini'] . "' WHERE album_id='" . $album . "'");
					$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET nums=nums-1, last_update='" . $photoNew['add_date'] . "', last_author='" . $photoNew['author'] . "', last_image='" . $photosNew['mini'] . "'  WHERE album_id='" . $photo['cat'] . "'");
				}

				if(($photo['status'] == 0 OR $photo['status'] == 2) && $photoStatus == 1)
				{
					$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET nums=nums+1, last_update='" . time() . "', last_author='" . $author . "', last_image='" . $photos['mini'] . "' WHERE album_id='" . $album . "'");
				}
				
				if($photoStatus == 0 && $photo['status'] > 0)
				{
					$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE cat = '" . $photo['cat'] . "' ORDER BY add_date DESC LIMIT 1");
					$photoNew = $db->getRow($query);
					$photosNew = unserialize($photoNew['photos']);
					
					$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET nums=nums-1, last_update='" . $photoNew['add_date'] . "', last_author='" . $photoNew['author'] . "', last_image='" . $photosNew['mini'] . "'  WHERE album_id='" . $photo['cat'] . "'");
				}
				
				$photos['original'] = $_POST['urlLocal'];
				$sz = serialize($photos);
				$db->query("UPDATE `".DB_PREFIX."_gallery_photos` SET `title` = '" . $title . "', `cat` = '" . $album . "', `description` = '" . $description . "', `author` = '" . $author . "', `add_date` = '" . time() . "', `photo_date` = '" . $date . "', `photos` =  '" . $sz . "', `tech` = '" . $model . "', `status` = '" . $photoStatus . "' WHERE `photo_id` = " . $aid . " LIMIT 1");
				$info[] = _PHOTO_N_ALBUM_REFRESH." ";
			}
			else
			{
				if(isset($fileIs))
				{
					
					
					$result = $db->query("SELECT * FROM " . DB_PREFIX . "_gallery_albums WHERE album_id='" . $album . "'");
					$alb = $db->getRow($result);
					$sizes = unserialize($alb['sizes']);
					$miniSize = $sizes['mini'];
					$bigSize = $sizes['long'];

					
					$dir = $alb['dir'] ? $alb['dir'] . '/' : $gallery_config['save'];
					$dirThumb = $alb['dir'] ? $alb['dir'] . '/thumb/' : $gallery_config['save-thumb'];
					
					$parseFile = $urlFrom != '' ? $urlFrom : $_FILES['urlLocal']['tmp_name'];
					$fileName = $urlFrom != '' ? basename($urlFrom) : gencode(10).'.'.getExt($_FILES['urlLocal']['name']);
					
					$bigImage = $dir . $fileName;
					$middleImage = $dirThumb . 'big-' . $fileName;
					$thumbImage = $dirThumb . 'mini-' . $fileName;
					
					$fileInfo = getimagesize($parseFile);
					
					if(!isset($loadFrom))
					{
						$bigGen = new Thumbnail($parseFile);
						$bigGen->quality = $gallery_config['quality'];
						if($watermark) 
						{
							$bigGen->img_watermark = $gallery_config['waterdir'];
						}
						$bigGen->process();
						$status = $bigGen->save($bigImage);
					}
					else
					{
						$bigImage = $parseFile;
					}
					
					if($fileInfo[0] > $bigSize) 
					{
						$middleGen = new Thumbnail($parseFile);
						$middleGen->size_width($bigSize);
						$middleGen->quality = $gallery_config['quality'];
						if($watermark) 
						{
							$middleGen->img_watermark = $gallery_config['waterdir'];
						}
						$middleGen->process();
						$middleGen->save($middleImage);
					}
					else 
					{
						$middleImage = $bigImage;
					}
					
					$thumbGen = new Thumbnail($parseFile);
					$thumbGen->size_auto($miniSize);
					$thumbGen->quality = $gallery_config['quality'];
					$thumbGen->process();
					$status = $thumbGen->save($thumbImage);

					$i['mini'] = $thumbImage;
					$i['photo'] = $middleImage;
					$i['original'] = $bigImage;
					$i['size'] = $fileInfo[0].'x'.$fileInfo[1];
					$i['mb'] = isset($loadFrom) ? filesize($parseFile) : $_FILES['urlLocal']['size'];
					$convert = serialize($i);
					
					$nowTime = time();
					
					$db->query("INSERT INTO `".DB_PREFIX."_gallery_photos` ( `photo_id` , `cat` , `title` , `description` , `author` , `add_date` , `photo_date` , `photos` , `tech` , `views` , `gets` , `comments` , `score` , `ratings` , `status` , `groups_allow` ) VALUES (NULL, '" . $album . "', '" . $db->safesql($title) . "', '" . $db->safesql($description) . "', '" . $author . "', '" . $nowTime . "', '" . $date . "', '" . $convert . "', '" . $model . "', '', '', '', '', '', '" . $photoStatus . "', '')");
					$db->query("UPDATE " . DB_PREFIX . "_gallery_albums SET nums=nums+1, `last_update` = '" . $nowTime . "', `last_author` = '" . $author . "', `last_image` = '" . $thumbImage . "' WHERE album_id='" . $album . "'");
					
					$info[] = _PHOTO_N_ALBUM_REFRESH." ";
				}
			}
		}
		else
		{
			$stop[] = _GNOTIFY2;
		}
		
		if(isset($stop))
		{
			$stopText = '';
			foreach($stop as $text)
			{
				$stopText .= $text . '<br/>';
			}
			
			$adminTpl->info($stopText . ' <a href="{MOD_LINK}/addPhoto">'._BACK_TO_ADD_ALBUM.'</a> '._OR.' <a href="{MOD_LINK}/photos">'._WATCH_PHOTO_LIST.'</a>', 'error');
		}
		else
		{
			$infoText = '';
			foreach($info as $text)
			{
				$infoText .= $text . '<br/>';
			}
			
			$adminTpl->info($infoText . ' <a href="{MOD_LINK}/addPhoto">'._BACK_TO_ADD_ALBUM.'</a> '._OR.' <a href="{MOD_LINK}/photos">'._WATCH_PHOTO_LIST.'</a>');
			
		}
		$adminTpl->admin_foot();
		break;
		
	case 'activate':
		$pid = isset($url[4]) ? intval($url[4]) : '';
		$checks = isset($_POST['checks']) ? $_POST['checks'] : '';
		if(!empty($checks))
		{
			foreach($checks as $pid)
			{
				$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE photo_id = '" . $pid . "'");
				$photo = $db->getRow($query);
				$db->query("UPDATE `".DB_PREFIX."_gallery_photos` SET `status` = '1' WHERE `photo_id` = " . $pid . " LIMIT 1");
				$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET nums=nums+1 WHERE album_id='" . $photo['cat'] . "'");
			}
		}
		elseif(!empty($pid))
		{
			$query = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE photo_id = '" . $pid . "'");
			$photo = $db->getRow($query);
			$db->query("UPDATE `".DB_PREFIX."_gallery_photos` SET `status` = '1' WHERE `photo_id` = " . $pid . " LIMIT 1");
			$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET nums=nums+1 WHERE album_id='" . $photo['cat'] . "'");
		}
		
		location(ADMIN.'/module/gallery');
		
		break;
		
	case 'config':
		require (ROOT.'etc/gallery.config.php');
		
		$configBox = array(
			'gallery' => array(
				'varName' => 'gallery_config',
				'title' => _GALLERY_SETTINGS,
				'groups' => array(
					'main' => array(
						'title' => _BASIC_SETTINGS,
						'vars' => array(
							'size-mini' => array(
								'title' => _SIZE_MINI,
								'description' => _SIZE_MINI_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),						
							'size-long' => array(
								'title' => _SIZE_LONG,
								'description' => _SIZE_LONG_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'alb-col' => array(
								'title' => _ALB_COL,
								'description' => _ALB_COL_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'photos-col' => array(
								'title' => _PHOTOS_COL,
								'description' => _PHOTOS_COL_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'photos-num' => array(
								'title' => _PHOTOS_NUM,
								'description' => _PHOTOS_NUM_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'search-col' => array(
								'title' => _SEARCH_COL,
								'description' => _SEARCH_COL_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'search-num' => array(
								'title' => _SEARCH_NUM,
								'description' => _SEARCH_NUM_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'comment' => array(
								'title' => _COMMENT,
								'description' => _COMMENT_DESC,
								'content' => radio("comment", $gallery_config['comment']),
							),									
							'add' => array(
								'title' => _PHOTO_ADD,
								'description' => _PHOTO_ADD_DESC,
								'content' => radio("add", $gallery_config['add']),
							),							
						)
					),
					'files_formats' => array(
						'title' => _FILES_FORMATS,
						'vars' => array(
							'save' => array(
								'title' => _PHOTOS_PATH,
								'description' => _PHOTOS_PATH_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),						
							'save-thumb' => array(
								'title' => _PREWIEV_PATH,
								'description' => _PREWIEV_PATH_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'waterdir' => array(
								'title' => _WATERMARK_PATH,
								'description' => _WATERMARK_PATH_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'quality' => array(
								'title' => _QUALITY,
								'description' => _QUALITY_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'max_size' => array(
								'title' => _PHOTOS_MAX_SIZE,
								'description' => _PHOTOS_MAX_SIZE_DESC,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'typefile' => array(
								'title' => _TYPE_FILES,
								'description' => _TYPE_FILES_DESC,
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
		
		generateConfig($configBox, 'gallery', '{MOD_LINK}/config', $ok);
		break;
}