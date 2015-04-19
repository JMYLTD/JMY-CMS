<?php
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}

$module_name = $url[0];
loadConfig('gallery');


//Меню - статика
function menu()
{
global $core, $db;
	list($all) = $db->fetchRow($db->query("SELECT Count(photo_id) FROM ".DB_PREFIX."_gallery_photos WHERE status='1'"));
	list($albs) = $db->fetchRow($db->query("SELECT Count(album_id) FROM ".DB_PREFIX."_gallery_albums"));
	$core->tpl->open('menu');
	$core->tpl->loadFile('gallery/menu');
	$core->tpl->setVar('all', $all);
	$core->tpl->setVar('albs', $albs);
	$core->tpl->end();
	$core->tpl->close();
}

function formatSize($size)
{
    if ($size > 1048576)
    {
        $result = round($size / 1048576 * 100) / 100 . ' '._MB;
    }
    elseif ($size > 1024)
    {
        $result = round($size / 1024 * 100) / 100 . ' '._KB;
    }
    else
    {
        $result = $size . ' '._B;
    }
    return $result;
}


function get_album($aid="") 
{
	global  $db;
		$content = '';
		$result = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_albums ORDER BY title");
		if ($db->numRows($result) > 0) 
		{
			while ($row = $db->getRow($result)) 
			{
				$sel = ($aid == $row['album_id']) ? "selected" : "";
				$content .= "<option value=\"".$row['album_id']."\" $sel>" . $row['title'] . "</option>";
			}
		}
		return $content;
}


switch(isset($url[1]) ? $url[1] : null) 
{

	default:
		set_title(array(_GALLERY));
		menu();
		$tdwidth = intval(100/$gallery_config['alb-col']);
		$q = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_albums ORDER BY last_update DESC");
		if ($db->numRows($q)>=1) 
		{
			$core->tpl->open('album_list');
			
			$core->tpl->open('inc.top');
			$core->tpl->loadFile('gallery/inc.top');			
			$core->tpl->end();
			$core->tpl->close();
			
			echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" ><tr>";
			$cont = 0;
			while($result = $db->getRow($q)) 
			{
			
				if($result['sizes'] !== '') 
				{
					$size = unserialize($result['sizes']);
				} 
				else
				{
					$size['mini'] = $gallery_config['size-mini'];
					$size['long'] = $gallery_config['size-long'];
				}
				
				if (!file_exists($result['last_image'])) 
				{
					$qphoto = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE status='1' AND cat='$result[album_id]' ORDER BY photo_id ASC");
					$resultphoto = $db->getRow($qphoto);
					$as = unserialize($resultphoto['photos']);
					
					if(file_exists($as['mini'])) 
					{
						$limg = $as['mini'];
					} 
					else
					{
						$limg = "{%THEME%}/gallery/images/no_photo.gif";
					}
				} 
				else 
				{
					$limg = $result['last_image'];
				}
				
					echo "<td width=\"".$tdwidth."%\" valign=\"top\">";
					$core->tpl->loadFile('gallery/AlbumBody');
					$core->tpl->setVar('thumb', $limg);
					$core->tpl->setVar('size-mini', $size['mini']);
					$core->tpl->setVar('TITLE', $result['title']);
					$core->tpl->setVar('trans', $result['trans']);
					$core->tpl->setVar('description', $result['description']);
					$core->tpl->setVar('views', $result['views']);
					$core->tpl->setVar('nums', $result['nums']);
					$core->tpl->setVar('date', $result['last_update'] ? date("m.d.y, H:m", $result['last_update']) : _NO);
					$core->tpl->setVar('last_author', $result['last_author'] ? $result['last_author'] : _NO);
					$core->tpl->end();
					echo '</td>';
					if ($cont == ($gallery_config['alb-col'] - 1)) 
					{
						echo "</tr><tr>";
						$cont = 0;
					} 
					else 
					{
						$cont++;
					}
			}
			echo "</tr></table>";
			$core->tpl->open('inc.down');
			$core->tpl->loadFile('gallery/inc.down');			
			$core->tpl->end();
			$core->tpl->close();
			$core->tpl->close();
		} 
		else 
		{
			$core->tpl->info(_NO_ALBUMS);
		}
		
		break;
		
		
		
	case "album":
		$alb_trans = $url[2];
		list($album_id, $album_title, $trans, $sizes) = $db->fetchRow($db->query("SELECT album_id, title, trans, sizes FROM ".DB_PREFIX."_gallery_albums WHERE trans='" . $db->safesql($alb_trans) . "'"));
		$db->query("UPDATE ".DB_PREFIX."_gallery_albums SET views=views+1 WHERE album_id='" . $album_id . "' LIMIT 1");
		$num = $gallery_config['photos-num'];
		$page = init_page();
		$cut = ($page-1)*$num;
		set_title(array(_GALLERY, $album_title));
		menu();
		
		if($sizes !== '') 
		{
			$size = unserialize($sizes);
		} 
		else 
		{
			$size['mini'] = $gallery_config['size-mini'];
			$size['long'] = $gallery_config['size-long'];
		}
		
		$tdwidth = intval(100/$gallery_config['photos-col']);
		$photo_q = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE status='1' AND cat='$album_id' ORDER BY photo_id DESC LIMIT " . $cut . ", ".$num);
		if ($db->numRows($photo_q)>=1) 
		{
			$core->tpl->open('photos_list');
			
			$core->tpl->open('inc.top');
			$core->tpl->loadFile('gallery/inc.top');			
			$core->tpl->end();
			$core->tpl->close();
			
			
			echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"10\" ><tr>";
			$limg = "{%THEME%}/gallery/images/no_photo.gif";
			$c = 0;
			$cont = 0;
			require(ROOT . 'media/imageEffects/' . $config['imageEffect'] . '/init.php');
			$core->tpl->headerIncludes['thumbNail'] = $js;

			while($photo = $db->getRow($photo_q)) 
			{
				$c++;
				$images = unserialize($photo['photos']);
				$fileImg = file_exists($images['mini']) ? $images['mini'] : $limg;
				$repl = array(
					'{full}' => $images['original'],
					'{thumb}' => $fileImg,
					'{img}' => 'alt="'._INCREASE.'"',
					'{href}' => ''
				);

				echo "<td width=\"".$tdwidth."%\" valign=\"top\">";
				if($photo['score']>=1) 	$rate = ceil($photo['ratings']/$photo['score']); else $rate = 0;
				$core->tpl->loadFile('gallery/PhotoBody');
				$core->tpl->setVar('size-mini', $size['mini']);
				$core->tpl->setVar('c', $c);
				$core->tpl->setVar('photo_id', $photo['photo_id']);
				$core->tpl->setVar('title', str($photo['title'], 15));
				$core->tpl->setVar('author', $photo['author'] ? $photo['author'] : _NO);
				$core->tpl->setVar('description', $photo['description']);
				$core->tpl->setVar('date', date("m.d.y, H:m", $photo['add_date']));
				$core->tpl->setVar('image', str_replace(array_keys($repl), array_values($repl), $picture));
				$core->tpl->setVar('original', $images['original']);
				$core->tpl->setVar('views', $photo['views']);
				$core->tpl->setVar('comments', $photo['comments']);
				$core->tpl->setVar('rating', $rate);
				$core->tpl->end();
				echo '</td>';
				if ($cont == ($gallery_config['photos-col'] - 1)) 
				{
				echo "</tr><tr>";
					$cont = 0;
				} 
				else 
				{
					$cont++;
				}
			}
			
			echo "</tr></table>";
			
			$core->tpl->open('inc.down');
			$core->tpl->loadFile('gallery/inc.down');			
			$core->tpl->end();
			$core->tpl->close();
			
			$core->tpl->close();
			list($numphotos) = $db->fetchRow($db->query("SELECT Count(photo_id) FROM ".DB_PREFIX."_gallery_photos WHERE status='1' AND cat='$album_id'"));
			$numpages = ceil($numphotos/$gallery_config['photos-num']);
			$core->tpl->pages($page, $num, $numphotos, 'gallery/album/' . $alb_trans . '/{page}');
		} 
		else
		{
			$core->tpl->info(_NO_PHOTO);
		}
		break;
		
	case "photo":
		$id = isset($url[2]) ? intval($url[2]) : 0;
		$photo_q = $db->query("SELECT p.*, a.title as cat_title, a.trans, a.sizes FROM ".DB_PREFIX."_gallery_photos AS p LEFT JOIN ".DB_PREFIX."_gallery_albums AS a ON (p.cat=a.album_id) WHERE p.photo_id='$id' LIMIT 1");
		$photo = $db->getRow($photo_q);

		set_title(array(_GALLERY, $photo['cat_title'], $photo['title']));
		menu();
		
		if ($db->numRows($photo_q)==1) 
		{
			$core->tpl->open('photo');
			
			$db->query("UPDATE ".DB_PREFIX."_gallery_photos SET views=views+1 WHERE photo_id=$id");
			$images = unserialize($photo['photos']);
			$normal = $images['photo'];
			$original = $images['original'];
			
			if($photo['sizes'] !== '') 
			{
				$size = unserialize($photo['sizes']);
			} 
			else 
			{
				$size['mini'] = $gallery_config['size-mini'];
				$size['long'] = $gallery_config['size-long'];
			}
			
			$photo_nav = $db->query("SELECT photo_id FROM ".DB_PREFIX."_gallery_photos WHERE status='1' AND cat='". $photo['cat'] ."' ORDER BY photo_id ASC");
			$all = $db->numRows($photo_nav);
			
			$pg = '';
			
			while(list($photo_id) = $db->fetchRow($photo_nav)) 
			{
				$pg[] .= $photo_id;
			}
			
			$ng = array_search($photo['photo_id'], $pg);
			
			if($all == 1) 
			{
				$page_next = '';
				$page_prev = '';
			} 
			elseif($all == 2 && $pg[0] == $photo['photo_id'])
			{
				$page_next = '<a href="'.$url[0].'/photo/'.$pg[$ng+1].'">'._FORWARD_TO.'</a>';
				$page_prev = '';
			} 
			elseif($all == 2 && $pg[1] == $photo['photo_id']) 
			{
				$page_prev = '<a href="'.$url[0].'/photo/'.$pg[$ng-1].'">'._BACK_TO.'</a>';
				$page_next = '';	
			} 
			elseif($ng == 0 && $all>=3) 
			{
				$page_next = '<a href="'.$url[0].'/photo/'.$pg[$ng+1].'">'._FORWARD_TO.'</a>';
				$page_prev = '<a href="'.$url[0].'/photo/'.$pg[$ng+2].'">'._BACK_TO.'</a>';
			} 
			elseif(!isset($pg[$ng+1]) && $all>=3) 
			{
				$page_next = '<a href="'.$url[0].'/photo/'.$pg[$ng-2].'">'._FORWARD_TO.'</a>';
				$page_prev = '<a href="'.$url[0].'/photo/'.$pg[$ng-1].'">'._BACK_TO.'</a>';
			} 
			else 
			{
				$page_next = '<a href="'.$url[0].'/photo/'.$pg[$ng+1].'">'._FORWARD_TO.'</a>';
				$page_prev = '<a href="'.$url[0].'/photo/'.$pg[$ng-1].'">'._BACK_TO.'</a>';
			}
			
			if($photo['score']>=1) {
				$rate = ceil($photo['score']/$photo['ratings']); 
			}
			else
			{ 
				$rate = 0;
			}
			
			if(!file_exists($normal)) {
				$normal = "{%THEME%}/gallery/images/no_photo.gif";
				$wangh[] = 150;
			}
			else
			{
				$wangh = getimagesize($normal);
			}
			
			$core->tpl->loadFile('gallery/PhotoView');
			$core->tpl->setVar('photo_id', $photo['photo_id']);
			$core->tpl->setVar('size-long', ($size['long'] > $wangh[0]) ? $wangh[0] : $size['long']);
			$core->tpl->setVar('title', $photo['title']);
			$core->tpl->setVar('trans', $photo['trans']);
			$core->tpl->setVar('ctitle', $photo['cat_title']);
			$core->tpl->setVar('description', $photo['description'] ? $photo['description'] : _NO_INFO);
			$core->tpl->setVar('add_date', date("m.d.y, H:m", $photo['add_date']));
			$core->tpl->setVar('photo_date', $photo['photo_date'] ? $photo['photo_date'] : _NO_INFO);
			$core->tpl->setVar('normal', $normal);
			$core->tpl->setVar('original', $original);
			$core->tpl->setVar('tech', $photo['tech'] ? $photo['tech'] : _NO_INFO);
			$core->tpl->setVar('views', $photo['views']);
			$core->tpl->setVar('gets', $photo['gets']);
			$core->tpl->setVar('author', $photo['author']);
			$core->tpl->setVar('comments', $photo['comments']);
			$core->tpl->setVar('rating', draw_rating($photo['photo_id'], 'gallery', $photo['score'], $photo['ratings']));
			$core->tpl->setVar('img_size', $images['size']);
			$core->tpl->setVar('img_mb', formatSize($images['mb']));
			$core->tpl->setVar('next', $page_next);
			$core->tpl->setVar('prev', $page_prev);
			$core->tpl->end();
			$core->tpl->close();
			
			if($gallery_config['comment']!=='0') 
			{
				show_comments('gallery', $photo['photo_id'], 10);
			}
		} 
		else
		{
			$core->tpl->info(_PHOTO_NO_FOUND);
		}
		
		break;
		
		
		
	case "get_photo":
		$no_head = true;
		$id = intval($url[2]);
		$photo_q = $db->query("SELECT * FROM ".DB_PREFIX."_gallery_photos WHERE photo_id='" . $id . "' LIMIT 1");
		if ($db->numRows($photo_q)==1) 
		{
			$photo = $db->getRow($photo_q);
			$db->query("UPDATE ".DB_PREFIX."_gallery_photos SET gets=gets+1 WHERE photo_id='" . $id . "'");
			$images = unserialize($photo['photos']);
			$normal = $images['photo'];
			$original = $images['original'];
			$img = $original ? $original : $normal;
			$core->tpl->loadFile('gallery/GetPhoto');
			$core->tpl->setVar('img', $img);
			$core->tpl->setVar('id', $id);
			$core->tpl->setVar('URL', $config['url']);
			$core->tpl->setVar('title', $photo['title']);
			$core->tpl->end();
		} 
		else
		{
			location('/' . $url[0]);
		}
		break;
		
		
	case 'top':
		$top = true;
	case "search":
		if(isset($url[2]))
		{
			$word = $url[2];
		}
		else
		{
			$word = isset($_POST['word']) ? filter(trim($_POST['word']), 'a') : '';
		}
		if(!isset($top))
		{
			set_title(array(_GALLERY, _SEARCH, $word));
		}
		else
		{
			set_title(array(_GALLERY, _SEARCH, _BEST_PHOTOS));
		}
		$num = $gallery_config['photos-num'];
		$page = init_page();
		$cut = ($page-1)*$num;
		menu();
		
		if(!isset($top))
		{
			$core->tpl->open('search-form');
			$core->tpl->loadFile('gallery/SearchForm');
			$core->tpl->setVar('word', $word);
			$core->tpl->end();
			$core->tpl->close();
		}
		
		$tdwidth = intval(100/$gallery_config['search-col']);
		
		if(!isset($top))
		{
			$photo_q = $db->query("SELECT p.*, a.title as cat_title, a.trans, a.sizes FROM ".DB_PREFIX."_gallery_photos AS p LEFT JOIN " . DB_PREFIX . "_gallery_albums AS a ON (p.cat=a.album_id) WHERE p.status='1' AND (p.title LIKE '%".$db->safesql($word)."%' OR p.description LIKE '%".$db->safesql($word)."%' OR p.author LIKE '%".$db->safesql($word)."%') ORDER BY p.add_date DESC LIMIT " . $cut . ", " . $num . "");
		}
		else
		{
			$photo_q = $db->query("SELECT p.*, a.title as cat_title, a.trans, a.sizes FROM ".DB_PREFIX."_gallery_photos AS p LEFT JOIN " . DB_PREFIX . "_gallery_albums AS a ON (p.cat=a.album_id) WHERE p.status=1 ORDER BY p.views DESC LIMIT " . $cut . ", " . $num . "");
		}
		
		if (($db->numRows($photo_q)>=1 && $word) OR isset($top) && $db->numRows($photo_q)>= 1) 
		{
			$c = 0;
			$cont = 0;
			$core->tpl->open('search_list');
				$core->tpl->open('inc.top');
			$core->tpl->loadFile('gallery/inc.top');			
			$core->tpl->end();
			$core->tpl->close();
			
			echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"10\"><tr>";
			while($photo = $db->getRow($photo_q)) 
			{
				if($photo['sizes'] !== '') 
				{
					$size = unserialize($photo['sizes']);
				} 
				else 
				{
					$size['mini'] = $gallery_config['size-mini'];
					$size['long'] = $gallery_config['size-long'];
				}
				
				$c++;
				
				echo "<td width=\"".$tdwidth."%\"  valign=\"top\">";
				$images = unserialize($photo['photos']);
				$limg = "{%THEME%}/gallery/images/no_photo.gif";
				$thumb = file_exists($images['mini']) ? $images['mini'] : $limg;
				
				if($photo['score']>=1)
				{
					$rate = ceil($photo['score']/$photo['ratings']);
				} 
				else
				{
					$rate = 0;
				}
				
				$core->tpl->loadFile('gallery/SearchBody');
				$core->tpl->setVar('size-mini', $size['mini']);
				$core->tpl->setVar('title', $photo['title']);
				$core->tpl->setVar('trans', $photo['trans']);
				$core->tpl->setVar('ctitle', $photo['cat_title']);
				$core->tpl->setVar('c', $c);
				$core->tpl->setVar('description', $photo['description']);
				$core->tpl->setVar('photo_id', $photo['photo_id']);
				$core->tpl->setVar('author', $photo['author']);
				$core->tpl->setVar('original', $images['original']);
				$core->tpl->setVar('date', date("m.d.y, H:m", $photo['add_date']));
				$core->tpl->setVar('thumb', $thumb);
				$core->tpl->setVar('views', $photo['views']);
				$core->tpl->setVar('comments', $photo['comments']);
				$core->tpl->setVar('rating', $rate);
				$core->tpl->end();
				
				if ($cont == ($gallery_config['search-col'] - 1)) 
				{
					echo "</tr><tr>";
					$cont = 0;
				} 
				else 
				{
					$cont++;
				}
			}
			
			echo "</tr></table>";
				$core->tpl->open('inc.down');
			$core->tpl->loadFile('gallery/inc.down');			
			$core->tpl->end();
			$core->tpl->close();
			$core->tpl->close();
			if(!isset($top))
			{
				list($numphotos) = $db->fetchRow($db->query("SELECT Count(photo_id) FROM ".DB_PREFIX."_gallery_photos WHERE status='1'  AND (title LIKE '%".$word."%' OR description LIKE '%".$word."%' OR author LIKE '%".$word."%')"));
				$numpages = ceil($numphotos/$gallery_config['photos-num']);
				$core->tpl->pages($page, $num, $numphotos, 'gallery/search/' . urlencode($word));
			}
			else
			{
				list($numphotos) = $db->fetchRow($db->query("SELECT Count(photo_id) FROM ".DB_PREFIX."_gallery_photos WHERE status='1' ORDER BY views DESC"));
				$numpages = ceil($numphotos/$gallery_config['photos-num']);
				$core->tpl->pages($page, $num, $numphotos, 'gallery/top/');
			}
		} 
		else
		{
			if(!isset($top))
			{
				echo $core->tpl->info(_NO_SEARCH);
			}
		}
		break;
		
		
	case "add_photo":
		set_title(array(_GALLERY, _ADD_PHOTO));
		if ($gallery_config['add'] == "1") 
		{
			menu();
			$bb = bb_area('description', '', 5, 'textarea', '', true);
			$core->tpl->open('add');
			$core->tpl->loadFile('gallery/add');
			$core->tpl->setVar('MAX_SIZE', formatSize($gallery_config['max_size']));
			$core->tpl->setVar('ALLOW_TYPE', $gallery_config['typefile']);
			$core->tpl->setVar('AUTHOR', $core->auth->user_info['nick']);
			$core->tpl->setVar('ALBUMS', get_album());
			$core->tpl->setVar('DESCRIPTION', $bb);
			$core->tpl->end();
			$core->tpl->close();
		
		} else {
			location();
		}
		break;

		
		
	case "save":
		if($core->auth->isUser == false) location();
		$bb = new bb;
		$title = isset($_POST['title']) ? filter($_POST['title']) : '';
		$album = isset($_POST['album']) ? intval($_POST['album']) : '';
		$author = $core->auth->user_info['nick'];
		$date = isset($_POST['date']) ? filter($_POST['date']) : '';
		$description = isset($_POST['description']) ? $bb->parse(processText(filter($_POST['description'])), false, false) : '';
		$urlFrom = isset($_POST['urlFrom']) ? filter($_POST['urlFrom']) : '';
		$photoStatus = 2;

		set_title(array(_GALLERY, _ADD_PHOTO));
		menu();
		if(isset($_FILES['urlLocal']) && is_uploaded_file($_FILES['urlLocal']['tmp_name'])) 
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
			if(isset($fileIs))
			{
				
				
				$result = $db->query("SELECT * FROM " . DB_PREFIX . "_gallery_albums WHERE album_id='".$album."'");
				$alb = $db->getRow($result);
				$sizes = unserialize($alb['sizes']);
				$miniSize = $sizes['mini'];
				$bigSize = $sizes['long'];
					
				$dir = $alb['dir'] ? $alb['dir'] . '/' : $gallery_config['save'];
				$dirThumb = $alb['dir'] ? $alb['dir'] . '/thumb/' : $gallery_config['save-thumb'];
				
				$parseFile = $urlFrom != '' ? $urlFrom : $_FILES['urlLocal']['tmp_name'];
				$fileName = $urlFrom != '' ? basename($urlFrom) : $_FILES['urlLocal']['name'];
				
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
					$middleGen->img_watermark = $gallery_config['waterdir'];
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
				
				$db->query("INSERT INTO `".DB_PREFIX."_gallery_photos` ( `photo_id` , `cat` , `title` , `description` , `author` , `add_date` , `photo_date` , `photos` , `tech` , `views` , `gets` , `comments` , `score` , `ratings` , `status` , `groups_allow` ) VALUES (NULL, '" . $album . "', '" . $db->safesql($title) . "', '" . $db->safesql($description) . "', '" . $author . "', '" . $nowTime . "', '" . $date . "', '" . $convert . "', '', '', '', '', '', '', '" . $photoStatus . "', '')");
				
				$info[] = _PHOTO_OK.($photoStatus == 2 ? " "._GNOTIFY2 : '');
			}
		}
		else
		{
			$stop[] = _GNOTIFY3;
		}
		
		if(isset($stop))
		{
			$stopText = '';
			foreach($stop as $text)
			{
				$stopText .= $text.'<br/>';
			}
			
			$core->tpl->info($stopText, 'warning');
		}
		else
		{
			$infoText = '';
			foreach($info as $text)
			{
				$infoText .= $text.'<br/>';
			}
			
			$core->tpl->info($infoText);
			
		}
		break;
}