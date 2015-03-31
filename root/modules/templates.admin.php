<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   06.03.2015
*/

if (!defined('ADMIN_ACCESS')) 
{
    header('Location: /');
    exit;
}

switch(isset($url[2]) ? $url[2] : null) 
{
	default:
		$adminTpl->admin_head(_TPL_TPLS);
		if(isset($url[2]))
		{
			if($url[2] == 'del_ok')
			{
				$adminTpl->info(_TPL_INFO_0);
			}
			elseif($url[2] == 'del_error')
			{
				$adminTpl->info(_TPL_INFO_1);
			}			
			elseif($url[2] == 'choose_ok')
			{
				$adminTpl->info(_TPL_INFO_2);
			}			
			elseif($url[2] == 'upload_ok')
			{
				$adminTpl->info(_TPL_INFO_3);
			}			
			elseif($url[2] == 'upload_error')
			{
				$adminTpl->info(_TPL_INFO_4, 'error');
			}
		}		
		$adminTpl->open();
		echo '<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<div class="panel-heading no-border">
							<b>'._TPL_UPLOAD_TPL.'</b>
						</div>
						<div class="panel-body">
							<div class="switcher-content">
								'._TPL_UPLOAD_INF.'<br><br>
								<form align="center" method="post" enctype="multipart/form-data" action="' . ADMIN . '/templates/upload">
									<input type="file" name="tpl" class="textinput" style="width:40%;" value="" /> 
									<br>
									<div align="left">
										<input  name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'._UPLOAD.'" />
									</div>	
								</form>									
							</div>
						</div>
					</section>
				</div>
			</div>';	
		$adminTpl->close();		
		$ni=0;
		foreach(glob(ROOT.'usr/tpl/*') as $dir)
		{
			
			$_name = basename($dir);
			if(is_dir($dir) && $_name != 'admin' && $_name != 'default' && $_name != 'smartphone')
			{
				$ni++;				
				if (($ni % 5 == 0)||($ni==1))
				{
					echo '<div class="row">';
				}				
				if (($_name<>'JMY_yellow')and($_name<>'JMY_white')and($_name<>'JMY_blue')){
				$stand=_TPL_OUT;
				}
				else 
				{
				$stand=_TPL_IN;
				}
				$preview = file_exists('usr/tpl/'.$_name.'/preview.png') ? 'usr/tpl/'.$_name.'/preview.png' : 'usr/tpl/no_preview.png';
				echo '<div class="col-xs-12 col-sm-6 col-md-3">
						<div class="panel panel-'. ($config['tpl'] == $_name ? 'default' : 'success') .' pricing-table-2">
							<div class="panel-heading">' . $_name . '</div>
							<div class="panel-body">
								<div class="plan-price text-center">
									<img src="' . $preview . '"  /><br><br>
									<p>'.$stand.'</p>
									<small>JMY CMS</small>
								</div>
								<ul class="plan-features text-center">
									<li><a href="{ADMIN}/templates/download/' . $_name . '">'._TPL_LOAD.'</a></li>
									<li><a href="{ADMIN}/templates/delete/' . $_name . '">'._TPL_DEL.'</a></li>								
								</ul>
							</div>
							<div class="panel-footer text-center">
								<button '. ($config['tpl'] == $_name ? 'class="btn btn-default btn-block" >'._CHOOSED : 'class="btn btn-success btn-block" onclick="location.href=\'{ADMIN}/templates/choose/' . $_name . '\';">'._CHOOSE) .'</button>
							</div>
						</div>
					</div>';
				if (($ni % 4 == 0))
				{
					echo '</div>';
				}
				
			}
			
		}
		if (($ni % 4 != 0)&&($ni!=0))
		{
			echo '</div>';
		}
		$adminTpl->admin_foot();
		break;
		
	case 'upload':
		if(isset($_FILES['tpl']))
		{
			require_once(ROOT.'boot/sub_classes/pclzip.lib.php');
			$archive = new PclZip($_FILES['tpl']['tmp_name']);
			if(($v_result_list = $archive->extract(PCLZIP_OPT_PATH, ROOT.'usr/tpl')) != 0)
			{
				$dir_root = explode('/', $v_result_list[0]['stored_filename']);
				if($dir_root[0] != 'usr' && $dir_root[0] != 'tpl' && file_exists(ROOT.'usr/tpl/' . $dir_root[0] . '/index.tpl'))
				{
					location(ADMIN.'/templates/upload_ok');
				}
				else
				{
					full_rmdir(ROOT.'usr/tpl/' . $dir_root[0]);
					location(ADMIN.'/templates/upload_error');
				}
			}
			else
			{
				location(ADMIN.'/templates/upload_error');
			}
		}
		else
		{
			location(ADMIN.'/templates/upload_error');
		}
		break;
		
	case 'save':
		foreach($_POST as $path => $html)
		{
			if(eregStrt('usr/tpl/'.$config['tpl'], $path))
			{
				if(is_writable(str_replace('_tpl', '.tpl', $path)))
				{
					$fp = @fopen(str_replace('_tpl', '.tpl', $path), 'w');
					fwrite($fp, stripslashes($html));
					fclose($fp);
					$save_is = true;
				}
			}
		
		}
	case 'edit_tpl':
		$adminTpl->admin_head(_TPL_EDET);
		$adminTpl->open();	
		echo '<div class="row">
				<div class="col-lg-12">
					<section>
						<nav class="navbar navbar-inverse" role="navigation"> 
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only">'._TPL_EDET_CH.'</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand">TPL</a>
							</div>
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav">';
	
        foreach(glob(ROOT.'usr/tpl/'.$config['tpl'].'/*/*.tpl') as $inFile)
        {
            $name = explode('usr/tpl/'.$config['tpl'].'/', $inFile);
            $name = $name[1];
            $subDir = explode('/', $name);
            $inDirs[$subDir[0]][] = $inFile;
        }
        $zeroDirs = glob(ROOT.'usr/tpl/'.$config['tpl'].'/*.tpl');
		
		$_names['index.tpl'] = _TPL_NAME_INDEX;
		$_names['info.tpl'] = _TPL_NAME_INFO;
		$_names['pages.tpl'] = _TPL_NAME_PAGE;
		$_names['sitemap.tpl'] = _TPL_NAME_SITEMAP;
		$_names['table.tpl'] = _TPL_NAME_TABLE;
		$_names['title.tpl'] = _TPL_NAME_TITLE;
		$_names['warning.tpl'] = _TPL_NAME_WARNING;		
		$_names['poll.tpl'] = _TPL_NAME_POLL;		
		$_names['feedback.tpl'] = _TPL_NAME_FEEDBACK;		
		$_names['block.tpl'] = _TPL_NAME_BLOCK;		
		$_names['bb_area.tpl'] = _TPL_NAME_BB;		
		$_names['mainpage.tpl'] = _TPL_NAME_MAINPAGE;	
		$_names['news-customs.tpl'] = _TPL_NAME_CUSTOMS;			
		$_names['comments=comments.view.tpl'] = _TPL_NAME_COM;
		$_names['news=news-cat.tpl'] = _TPL_NAME_N_CAT;
		$_names['news=news-view.tpl'] = _TPL_NAME_N_VIEW;
		$_names['news=news-main.tpl'] = _TPL_NAME_N_MAIN;
		$_names['news=news-add.tpl'] = _TPL_NAME_N_ADD;
		
		
		$_fold['blog'] = _TPL_FOLD_BLOG;
		$_fold['board'] = _TPL_FOLD_BOARD;
		$_fold['comments'] = _TPL_FOLD_COMM;
		$_fold['content'] = _TPL_FOLD_CONTENT;
		$_fold['gallery'] = _TPL_FOLD_GALLERY;
		$_fold['guestbook'] = _TPL_FOLD_GUEST;
		$_fold['news'] = _TPL_FOLD_NEWS;
		$_fold['pm'] = _TPL_FOLD_PM;
		$_fold['profile'] = _TPL_FOLD_PROFILE;
		$_fold['search'] = _TPL_FOLD_SEARCH;		
		echo '<li class="active dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">'._TPL_FOLD_MAIN.' <b class="caret"></b></a>
				<ul class="dropdown-menu">';
        foreach($zeroDirs as $file) 
        {
            $name = explode('usr/tpl/'.$config['tpl'].'/', $file);
            $name = $name[1];
            echo '<li>
						<a href="{ADMIN}/templates/edit_tpl/' . $name . '">' . (isset($_names[$name]) ? $_names[$name] : $name) . '</a>
				</li>';
        }
		echo '</ul>
			</li>';
        foreach($inDirs as $catalog => $files)
        {
				echo '<li class="dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"> ' . (isset($_fold[$catalog]) ? $_fold[$catalog] : $catalog) . ' <b class="caret"></b></a>
				<ul class="dropdown-menu">';          
            foreach($files as $file)
            {
				$name = explode('/', $file);
				$name = end($name);
				$_a = explode('usr/tpl/'.$config['tpl'].'/', $file);
				$absolute = str_replace('/', '=', end($_a));
				echo '<li><a href="{ADMIN}/templates/edit_tpl/' . $absolute . '">' . (isset($_names[$absolute]) ? $_names[$absolute] : $name) . '</a></li>';
            }
			echo '</ul>
			</li>';
        }
		echo '</ul>
		</div>
	</nav>
</section>
</div>
</div>';	
if(isset($save_is)) $adminTpl->info(_TPL_INFO_5);
$file = (isset($url[3]) && file_exists('usr/tpl/' . $config['tpl'] . '/'.str_replace('=', '/', $url[3]))) ? str_replace('=', '/', $url[3]) : 'index.tpl';
		$text = htmlspecialchars(file_get_contents(ROOT . 'usr/tpl/' . $config['tpl'] . '/'.$file), ENT_QUOTES);
		$count_rows = count(explode("\n", $text))*20;	
echo '<div class="row">
				<div class="col-lg-12">
					<section class="panel">
						<div class="panel-heading no-border">
							<b>'._TPL_EDET.' ('.$file.')</b>
						</div>
						<div class="panel-body">
							<div class="switcher-content">';
		echo '<div class="_edit_right">';
			
		echo '<form action="{ADMIN}/templates/save/' .  str_replace('/', '=', $file) . '"  method="post" style="margin:0; padding:0;">
		<div class="_code">		
		<textarea name="usr/tpl/' . $config['tpl'] . '/'. $file . '" class="textarea" id="_code">' .$text . '</textarea>
		<br />
		<div class="_save_me">
		<input name="submit" type="submit" class="btn btn-success" value="'._SAVE.'" /> 
		</div>
		</div>		
		</form>';
		echo '</div><br style="clear:both" />';
		echo '<script src="' . $config['url'] . '/usr/plugins/highlight_code/codemirror.js" type="text/javascript"></script><script type="text/javascript">var editor = CodeMirror.fromTextArea(\'_code\', {height: "dynamic",parserfile: ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js"],stylesheet: ["' . $config['url'] . '/usr/plugins/highlight_code/xmlcolors.css", "' . $config['url'] . '/usr/plugins/highlight_code/jscolors.css", "' . $config['url'] . '/usr/plugins/highlight_code/csscolors.css"], path: "' . $config['url'] . '/usr/plugins/highlight_code/", lineNumbers: true});
</script>';
echo '										
							</div>
						</div>
					</section>
				</div>
			</div>';
		$adminTpl->close();
		$adminTpl->admin_foot();
		break;		
		
	case 'savecss':
		foreach($_POST as $path => $html)
		{
			if(eregStrt('usr/tpl/'.$config['tpl'], $path))
			{
				if(is_writable(str_replace('_css', '.css', $path)))
				{
					$fp = @fopen(str_replace('_css', '.css', $path), 'w');
					fwrite($fp, stripslashes($html));
					fclose($fp);
					$save_is = true;
				}
			}
		
		}
	case 'edit_css':
		$adminTpl->admin_head(_TPL_EDET_CSS);
		$adminTpl->open();	
		echo '<div class="row">
				<div class="col-lg-12">
					<section>
						<nav class="navbar navbar-inverse" role="navigation"> 
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
									<span class="sr-only">'._TPL_EDET_CSS_CH.'</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand">CSS</a>
							</div>
							<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
								<ul class="nav navbar-nav">';			
        foreach(glob(ROOT.'usr/tpl/'.$config['tpl'].'/*/*.css') as $inFile)
        {
            $name = explode('usr/tpl/'.$config['tpl'].'/', $inFile);
            $name = $name[1];
            $subDir = explode('/', $name);
            $inDirs[$subDir[0]][] = $inFile;
        }
        $zeroDirs = glob(ROOT.'usr/tpl/'.$config['tpl'].'/assest/css/*.css');		
		$_names['engine.css'] = _TPL_NAME_CSS_ENG;	
		$_names['theme.css'] = _TPL_NAME_CSS_THM;	
		if(!empty($zeroDirs))
		{
			echo '<li class="active dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">'._TPL_FOLD_MAIN.' <b class="caret"></b></a>
				<ul class="dropdown-menu">';
			foreach($zeroDirs as $file) 
			{
				$name = explode('usr/tpl/'.$config['tpl'].'/assest/css/', $file);
				$name = end($name);
				$_a = explode('usr/tpl/'.$config['tpl'].'/', $file);
				$absolute = str_replace(array('/', '.css'), array('=', '_css'), end($_a));
				echo '<li><a href="{ADMIN}/templates/edit_css/' . $absolute . '">' . (isset($_names[$name]) ? $_names[$name] : $name) . '</a></li>';
			}
			echo '</ul>
			</li>';
		}
		
        foreach($inDirs as $catalog => $files)
        {
			echo '<li class="dropdown">
				<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">'.$catalog.' <b class="caret"></b></a>
				<ul class="dropdown-menu">';
            foreach($files as $file)
            {
				$name = explode('/', $file);
				$name = end($name);
				$_a = explode('usr/tpl/'.$config['tpl'].'/', $file);
				$absolute = str_replace(array('/', '.css'), array('=', '_css'), end($_a));
				echo '<li><a href="{ADMIN}/templates/edit_css/' . $absolute . '">' . (isset($_names[$absolute]) ? $_names[$absolute] : $name) . '</a></li>';
            }
			echo '</ul>
			</li>';
        }
		

		echo '</ul>
				</div>
			</nav>
		</section>
		</div>
		</div>';	

		if(isset($save_is)) $adminTpl->info(_TPL_INFO_6);
		$file = (isset($url[3]) && file_exists('usr/tpl/' . $config['tpl'] . '/'.str_replace(array('=', '_css'), array('/', '.css'), $url[3]))) ? str_replace(array('=', '_css'), array('/', '.css'), $url[3]) : 'assest/css/engine.css';
		$text = htmlspecialchars(file_get_contents(ROOT . 'usr/tpl/' . $config['tpl'] . '/'.$file), ENT_QUOTES);
		$count_rows = count(explode("\n", $text))*16;
		if (file_exists(ROOT.'usr/tpl/' . $config['tpl'] . '/'.$file))
		{
		echo '<div class="row">
						<div class="col-lg-12">
							<section class="panel">
								<div class="panel-heading no-border">
									<b>'._TPL_EDET.' ('.$file.')</b>
								</div>
								<div class="panel-body">
									<div class="switcher-content">';		
		echo '<div class="_edit_right">';
							
			echo '<form action="{ADMIN}/templates/savecss/' .  str_replace(array('/', '.css'), array('=', '_css'), $file) . '"  method="post" style="margin:0; padding:0;"><div class="_code"><textarea name="usr/tpl/' . $config['tpl'] . '/'. $file . '" class="textarea" id="_code">' .$text . '</textarea><br /><div class="_save_me"><input name="submit" type="submit" class="btn btn-success" value="'._SAVE.'" /> </div></div></form>';
		
		echo '</div><br style="clear:both" />';
		echo '<script src="' . $config['url'] . '/usr/plugins/highlight_code/codemirror.js" type="text/javascript"></script><script type="text/javascript">var editor = CodeMirror.fromTextArea(\'_code\', {height: "dynamic",parserfile: ["parsecss.js"],stylesheet: ["' . $config['url'] . '/usr/plugins/highlight_code/csscolors.css"], path: "' . $config['url'] . '/usr/plugins/highlight_code/", lineNumbers: true});
</script>';
		echo '										
							</div>
						</div>
					</section>
				</div>
			</div>';
			}
		else
		{
			$adminTpl->info(_TPL_NO_CSS);	
		}
		$adminTpl->close();
		$adminTpl->admin_foot();
		break;
		
	case 'download':
		$tpl_dir = ROOT.'usr/tpl/'.$url[3];
		if(is_dir($tpl_dir))
		{
			require_once(ROOT.'boot/sub_classes/pclzip.lib.php');
			$archive = new PclZip('_temp_archive.zip');
			$v_list = $archive->create($tpl_dir, PCLZIP_OPT_REMOVE_PATH, ROOT.'usr/tpl/');
			if ($v_list == 0) 
			{
				die("Error : ".$archive->errorInfo(true));
			}

			header('Content-type: application/zip'); 
			header('Content-Disposition: attachment; filename="' . $url[3] . '(JMY_CMS_TEMPLATES).zip"');          
			print file_get_contents('_temp_archive.zip');
			@unlink('_temp_archive.zip');          
			exit();
		}
		break;
		
	case 'delete':
		$tpl_dir = ROOT.'usr/tpl/'.$url[3];
		if($config['tpl'] != $url[3])
		{
			location(ADMIN.'/templates/del_ok');
			full_rmdir($tpl_dir);
		}
		else
		{
			location(ADMIN.'/templates/del_error');
		}
		break;
		
	case 'choose':
		$tpl_choose = $url[3];
		if(is_dir(ROOT.'usr/tpl/'.$tpl_choose))
		{
			$file = ROOT.'etc/global.config.php';
			$content = file_get_contents($file);
			$content = str_replace('$config[\'tpl\'] = "' . $config['tpl'] . '";', '$config[\'tpl\'] = "' . $tpl_choose . '";', $content);
			$fp = fopen($file, "wb");
			fwrite($fp, $content);
			fclose($fp);
			setcookie('theme', $tpl_choose, time(), '/');
			location(ADMIN.'/templates/choose_ok');
		}
		break;
		
}