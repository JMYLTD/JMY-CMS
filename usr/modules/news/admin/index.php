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
    location(' /');
    exit;
}

function news_main() 
{
global $adminTpl, $core, $db, $admin_conf, $url;
	$adminTpl->admin_head(_MODULES . ' | ' . _NAME);
	$page = init_page();
	$cut = ($page-1)*$admin_conf['num'];
	$query_search = isset($_POST['query']) ? filter($_POST['query'], 'text') : '';
	$where = '';
	$cat = 0;
	
	if(isset($url[3]) && $url[3] == 'cat')
	{
		$cat = $url[4];
		$where = "WHERE cat LIKE '%," . $db->safesql($url[4]) . ",%' ";
	}	
	$whereC = $where;	
	if($where == '')
	{
		$where .= ' WHERE l.lang = \'' . $core->InitLang() . '\'';
	}
	else
	{
		$where .= ' AND l.lang = \'' . $core->InitLang() . '\'';
	}
	$cats_arr = $core->aCatList('news');
	echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _LIST_NEWS . '</b>						
						<p class="text-left mg-b"><b>' . _SORT . '</b><span class="pd-l-sm">
						<a href="' . ADMIN . '/module/news/cat/0"><span class="label ' . (($cat == 0 && isset($url[3]) && $url[3] == 'cat') ? 'label-dark' : 'label-default') . '">Без категории</span></a><span class="pd-l-sm">';	
						foreach ($cats_arr as $cid => $name) 
						{
						echo '<a href="' . ADMIN . '/module/news/cat/' . $cid . '"><span class="label ' . (($cid == $cat) ? 'label-dark' : 'label-default') . '">'.$name.'</span></a><span class="pd-l-sm">';	
						}
	echo'</p></div>';
	$count = $db->numRows($db->query('SELECT id FROM '.DB_PREFIX.'_news WHERE active=2'));
	if($count > 0)
	{
		echo '<div style="clear:both"></div>';
		$adminTpl->info(_MODER);
	}
	$all = $db->numRows($db->query("SELECT * FROM " . DB_PREFIX . "_news $whereC"));
	$adminTpl->a_pages($page, $admin_conf['num'], $all, ADMIN.'/module/news/{page}');
	$query = $db->query("SELECT n.*, l.*, c.id as cid, c.name, c.altname as alturl FROM ".DB_PREFIX."_news AS n LEFT JOIN ".DB_PREFIX."_categories AS c ON (n.cat=c.id) LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=n.id and l.module='news') $where AND active!='2' ORDER BY n.date DESC LIMIT " . $cut . ", " . $admin_conf['num'] . "");
	if($db->numRows($query) > 0) 
	{
	echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/module/news/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th><span class="pd-l-sm"></span>ID</th>
									<th class="col-md-3">' . _TITLE . '</th>
									<th class="col-md-1">' . _DATE . '</th>
									<th class="col-md-3">' . _CATS .'</th>
									<th class="col-md-2">' . _AUTHOR . '</th>
									<th class="col-md-2">' . _ACTIONS . '</th>								
									<th class="col-md-1"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';
		while($news = $db->getRow($query)) 
		{
			$status_icon = ($news['active'] == 0) ? '<a href="{MOD_LINK}/activate/' . $news['id'] . '" onClick="return getConfirm(\'' .  _ACTIVATE_NEWS .' - ' . $news['title'] . '?\')"><button  type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _NEWS_ACTIVE .'">A</button></a>' : '<a href="{MOD_LINK}/deactivate/' . $news['id'] . '" onClick="return getConfirm(\'' . _DEACTIVATE_NEWS .' - ' . $news['title'] . '?\')" ><button  type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _NEWS_DEACTIVE .'">A</button></a>';
			
			echo '
			<tr '.(($news['active'] == 0) ? 'class="danger"' : '' ).'>
				<td><span class="pd-l-sm"></span>' . $news['id'] . '</td>
				<td>' . $news['title'] . '</td>
				<td>' . formatDate($news['date'], true) . '</td>				
				<td>' . ($news['cat'] !== ',0,' ? $core->getCat('news', $news['cat'], 'short', 3) : 'Нет') . '</td>
				<td>' . $news['author'] . '</td>
				<td>
				' . $status_icon . '
				<a href="{MOD_LINK}/edit/' . $news['id'] . '">
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _EDIT .'">E</button>
				</a>
				<a href="{MOD_LINK}/delete/' . $news['id'] . '" onClick="return getConfirm(\'' . _NEWS_DEL .' - ' . $news['title'] . '?\')">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .'">X</button>
				</a>
				</td>
				<td> <input type="checkbox" name="checks[]" value="' . $news['id'] . '"></td>
			</tr>';
		}
			echo '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>';		
			echo '
				<div class="_tableBottom">
					<div align="right">
						<table>
							<tr>
								<td valign="top">
									<select name="act">
										<option value="blockComment">' . _FORBID_COMMENTS . '</option>
										<option value="blockIndex">' . _REMOVE_MAIN .'</option>
										<option value="nowDate">' . _SET_NOWDATE . '</option>
										<option value="activate">' . _ACTIVATE . '</option>
										<option value="deActivate">' . _DEACTIVATE . '</option>
										<option value="reActivate">' . _REACTIVATE . '</option>									
										<option value="cat">' . _CHANGE_CAT . '</option>
										<option value="delete">' . _DELETE . '</option>
									</select>
								</td>
								<td>&nbsp&nbsp</td>	
								<td valign="top">
								<input name="submit" type="submit" class="btn btn-success" id="sub" value="' .  _DOIT . '" /><span class="pd-l-sm"></span>
								</td>
							</tr>
						</table>	
					</div>
				</div>
			</form>
		</div>';
	} else {
		echo '<div class="panel-heading">'  . _NEWS_NO_NEWS . '</div>';		
	}
	echo'</section></div></div>';	
	
	$all = $db->numRows($db->query("SELECT * FROM " . DB_PREFIX . "_news $whereC"));
	$adminTpl->pages($page, $admin_conf['num'], $all, ADMIN.'/module/news/{page}');
	
	$adminTpl->admin_foot();
} 
//добавить новости
function news_add($nid = null) 
{
global $adminTpl, $core, $db, $core, $config;
	if(isset($nid)) 
	{
	
		$bb = new bb;
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_news WHERE id = '" . $nid . "'");
		$news = $db->getRow($query);
		$id = $news['id']; 
		$author = $news['author']; 
		$date = gmdate('d.m.Y H:i', $news['date']); 
		$tags = $news['tags']; 
		$groups = $news['groups']; 
		$altname = $news['altname']; 
		$keywords = $news['keywords']; 
		$description = $news['description']; 		
		$allow_comments = $news['allow_comments']; 
		$allow_rating = $news['allow_rating']; 
		$allow_index = $news['allow_index']; 
		$score = $news['score']; 
		$votes = $news['votes']; 
		$views = $news['views']; 
		$comments = $news['comments']; 
		$fields = unserialize($news['fields']); 
		$fix = $news['fixed']; 
		$active = ($news['active'] == 2 ? 0 : $news['active']);
		$cat = $news['cat']; 
		$cat_array = explode(',', $cat);
		$catttt = explode(',', $cat);
		$edit = true;
		$grroups = explode(',', $groups);
		$firstCat = $catttt[1];
		$deleteKey = array_search($firstCat, $catttt);
		unset($catttt[$deleteKey]);
		$langMassiv = $core->getLangList(true);
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_langs WHERE postId = '" . $id . "' AND module='news'");
		while($langs = $db->getRow($query))
		{
			$title[$langs['lang']] = prepareTitle($langs['title']);
			$short[$langs['lang']] = $bb->htmltobb($langs['short']);
			$full[$langs['lang']] = $bb->htmltobb($langs['full']);
		}
		
		$lln = _NEWS_EDIT_NEWS;
		$dosave = _UPDATE;
	} 
	else 
	{
		$id = false; 
		$title = false; 
		$short = false; 
		$full = false; 
		$author = $core->auth->user_info['nick']; 
		$date = false; 
		$tags = false; 
		$cat = false; 
		$altname = false; 
		$keywords = false; 
		$description = false; 		
		$allow_comments = 1; 
		$allow_rating = 1; 
		$allow_index = 1; 
		$score = false; 
		$votes = false; 
		$views = false; 
		$comments = false; 
		$fields = false; 
		$fix = ''; 
		$active = 1;
		$lang = '';
		$edit = false;
		$catttt = array();
		$grroups = array();
		$firstCat = '';
		$lln = _NEWS_ADDPAGE;
		$dosave = _ADD;
	}
	$adminTpl->admin_head(_MODULES . ' | ' . $lln);
	require ROOT . 'usr/plugins/ajax_upload/init.php';
	$cats_arr = $core->aCatList('news');	
	echo '<section>
			<ul id="myTab2" class="nav nav-tabs">
				<li class="active">
					<a href="#home" data-toggle="tab">'. _MAIN .'</a>
				</li>
				<li class="">
					<a href="#addition" data-toggle="tab">'. _ADDITION .'</a>
				</li>
				<li class="">
					<a href="#access" data-toggle="tab">'. _ACCESS .'</a>
				</li>';
	$queryXF = $db->query("SELECT * FROM ".DB_PREFIX."_xfields WHERE module='news'");
	if($db->numRows($queryXF) > 0) 
	{
		echo'	<li class="">
					<a href="#dop" data-toggle="tab">'. _NEWS_DOP .'</a>
				</li>';
	}
	echo'		<li class="">
					<a href="#file" data-toggle="tab" onclick="uploaderStart();">'. _NEWS_FILE .'</a>
				</li>
			</ul>		
			<section class="panel">
				<div class="panel-body">
					<div id="myTabContent2" class="tab-content">				
						<div class="tab-pane active" id="home">					
							<div class="panel-heading no-border"><b>'. $lln .'</b></div>
							<div class="panel-body">
								<div class="switcher-content">
									<form action="{MOD_LINK}/save" onsubmit="return caa(false);" method="post" name="content" role="form">
									<div class="form-horizontal parsley-form" data-parsley-validate>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _NEWS_ADDTITLE .'</label>
											<div class="col-sm-4">
												<input type="text" name="title" '.(isset($nid) ? '' : 'onchange="getTranslit(gid(\'title\').value, \'translit\'); caa(this);"').' value="' . (isset($title[$config['lang']]) ? $title[$config['lang']] : '') . '" class="form-control" id="title"  data-parsley-required="true" data-parsley-trigger="change">
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _NEWS_ADDALT .'</label>
											<div class="col-sm-4">
												<input type="text" name="translit" value="'.$altname.'" class="form-control" id="translit"  data-parsley-required="true" data-parsley-trigger="change">
											</div>
										</div>			
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _NEWS_ADDTAG .'</label>
											<div class="col-sm-4">
												<input type="text" name="tags"  value="' . $tags . '" class="form-control" id="tags"  data-parsley-trigger="change">
											</div>
										</div>
										<div class="form-group">
<label class="col-sm-2 control-label">Datepicker</label>
<div class="col-sm-3">
<div class="input-group mg-b-md input-append date datepicker" data-date="12-02-2013" data-date-format="dd-mm-yyyy">
<input type="text" class="form-control" value="12-02-2013">
<span class="input-group-btn">
<button class="btn btn-white add-on" type="button">
<i class="fa fa-calendar"></i>
</button>
</span>
</div>
</div>
</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _NEWS_DATE_PUB .'</label>
											<div class="col-sm-4">
												<div class="input-group mg-b-md input-append date datepicker" data-date-format="dd-mm-yyyy">
													<input name="date" type="text" class="form-control" value="12-02-2013">
													<span class="input-group-btn">
														<button class="btn btn-white add-on" type="button"><i class="fa fa-calendar"></i></button>
													</span>
												</div>
											</div>
										</div>				
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _NEWS_AUTHOR .'</label>
											<div class="col-sm-4">
												<input type="text" name="author"  value="' . $author . '" class="form-control" id="author" data-parsley-trigger="change">				
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _NEWS_ADDCAT .'</label>
											<div class="col-sm-4">
												<select name="category[]" id="maincat" style="width:auto;" onchange="if(this.value != \'0\') {show(\'catSub\');}" >
													<option value="0">'._NEWS_ADD_NOCAT.'</option>';	
	foreach ($cats_arr as $cid => $name) 
	{
		$selected = ($cid == $firstCat) ? "selected" : "";
		echo '<option value="' . $cid . '" ' . $selected . '>' . $name . '</option>';
	}
	echo '
												</select>
											</div>
										</div>
										<div class="form-group" id="catSub" style="' . (isset($nid) ? '' : 'display:none;') . '">
											<label class="col-sm-3 control-label">'. _NEWS_ADDALTCAT .'</label>
											<div class="col-sm-4">
												<select name="category[]" id="category"  style="width:auto;" multiple >';
	foreach ($cats_arr as $cid => $name) 
	{
		if($catttt) $selected = in_array($cid, $catttt) ? "selected" : "";
		echo '<option value="' . $cid . '" ' . $selected . ' id="cat_' . $cid . '">' . $name . '</option>';
	}
	echo '										</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="addition">	
							<div class="panel-heading no-border">
								<b>'. _NEWS_ADDITION .'</b>
							</div>
							<div class="panel-body">
								<div class="switcher-content">
									<div class="form-horizontal parsley-form">
									
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _ACTIVATE_NEWS .'?</label>
											<div class="col-sm-4">
												'.checkbox('status', $active).'
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _ACTIVATE_RATING .'?</label>
											<div class="col-sm-4">
												'.checkbox('rating', $allow_rating).'
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _ACTIVATE_COMMENTS .'?</label>
												<div class="col-sm-4">
													'.checkbox('comments', $allow_comments).'
												</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'.  _TOP_SET .'?</label>
											<div class="col-sm-4">
												'.checkbox('fix', $fix).'
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _PUBLIC_INDEX .'?</label>
											<div class="col-sm-4">
												'.checkbox('index', $allow_index).'
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Keywords:</label>
											<div class="col-sm-4">
												<input type="text" name="keywords"  value="' . $keywords . '" class="form-control" id="keywords" data-parsley-trigger="change">	
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">Description:</label>
											<div class="col-sm-4">
												<input type="text" name="description"  value="' . $description . '" class="form-control" id="description" data-parsley-trigger="change">	
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="access">
							<div class="panel-heading no-border">
								<b>'. _NEWS_ACCESS .'</b>
							</div>
							<div class="panel-body">
								<div class="switcher-content">
									<div class="form-horizontal parsley-form">
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _NEWS_ABOUT .'</label>
											<div class="col-sm-4">
												<p class="form-control-static">'. _HOW_GROUPS_WORKS .'</p>
											</div>
										</div>
										<div class="form-group">
											<label class="col-sm-3 control-label">'. _GROUP_ACCESS .'?</label>
											<div class="col-sm-4">
												<select name="groups[]" id="group" class="cat_select" multiple>
													<option value="" ' . (empty($grroups) ? 'selected' : '') . '">'. _NEWS_ALL_GROUP .'</option>';
	$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups` ORDER BY admin DESC,moderator DESC,user DESC,guest DESC,banned DESC");
	while($rows = $db->getRow($query)) 
	{
		$selected = in_array($rows['id'], $grroups) ? "selected" : "";
		echo '<option value="' . $rows['id'] . '" ' . $selected . '>' . $rows['name'] . '</option>';
	}
	echo'										</select>		
											</div>
										</div>
									</div>	
								</div>		
							</div>			
						</div>
						<div class="tab-pane" id="dop">
							<div class="panel-heading no-border">
								<b>'. _NEWS_DOP .'</b>
							</div>
							<div class="panel-body">
								<div class="switcher-content">
									<div class="form-horizontal parsley-form">';
	if($db->numRows($queryXF) > 0) 
	{		
		while($xfield = $db->getRow($queryXF)) 
		{
			echo '						<div class="form-group">
											<label class="col-sm-3 control-label">'. $xfield['title'] .'</label>
											<div class="col-sm-4">';
			if($xfield['type'] == 3)
			{
				$dxfield = array_map('trim', explode("\n", $xfield['content']));
				$xfieldChange = '<select class="form-control" name="xfield[' . $xfield['id'] . ']"><option value="">Пусто</option>';

				foreach($dxfield as $xfiled_content)
				{
					$xfieldChange .= '<option value="' . $xfiled_content . '" ' . (isset($fields[$xfield['id']][1]) && $fields[$xfield['id']][1] == $xfiled_content ? 'selected' : ''). '>' . $xfiled_content . '</option>';
				}
				$xfieldChange .= '</select>';
			}
			elseif($xfield['type'] == 2)
			{
				$xfieldChange = '<textarea class="form-control" name="xfield[' . $xfield['id'] . ']" >' . (!empty($fields[$xfield['id']][1]) ? $fields[$xfield['id']][1] : ($id ? '' : $xfield['content'])) . '</textarea>';
			}
			else
			{
				$xfieldChange = '<input type="text" class="form-control" name="xfield[' . $xfield['id'] . ']" value="' . (!empty($fields[$xfield['id']][1]) ? $fields[$xfield['id']][1] : ($id ? '' : $xfield['content'])) . '" />';
			}
			echo $xfieldChange;
			echo '</div></div>
					<input type="hidden" name="xfieldT[' . $xfield['id'] . ']" value="' . $xfield['title'] . '" />';
		}
	}							
	echo'							</div>	
								</div>		
							</div>			
						</div>
						<div class="tab-pane" id="file">
							<div class="panel-heading no-border">
								<b>'. _NEWS_FILE .'</b>
							</div>
							<div class="panel-body">
								<div class="switcher-content">
									<div class="form-horizontal parsley-form">';									
									

	
	echo file_upload('news', $id);
									
									
							
echo'								</div>		
								</div>			
							</div>
						</div>
					</div>
				</section>
			</section> 
			<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<header class="panel-heading">'. _NEWS_TEXT_EDIT .'</header>			
					<div class="panel-body">
						<div class="form-horizontal bordered-group">
							<div class="form-group">
								<div class="panel-heading no-border">
									<b>'. _NEWS_SHORT .'</b>
								</div>
								<div style="padding-left:34px;padding-right:34px"  class="col-sm-12">'
									.adminArea('short[' . $config['lang'] . ']', (isset($short[$config['lang']]) ? $short[$config['lang']] : ''), 5, 'textarea', 'onchange="caa(this);"', true).'
								</div>
							</div>			
							<div class="form-group">
								<div class="panel-heading no-border">
									<b>'. _NEWS_FULL .'</b>
								</div>
								<div style="padding-left:34px;padding-right:34px"  class="col-sm-12">'
									.adminArea('full[' . $config['lang'] . ']', (isset($full[$config['lang']]) ? $full[$config['lang']] : ''), 5, 'textarea', 'onchange="caa(this);"', true).'
									
								</div>
								<div style="padding-left:17px;">
								<input  name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="' . $dosave . '" />
								</div>
							</div>
						</div>
					</div>					
					';		
	if($edit) 
	{
		echo "<input type=\"hidden\" name=\"edit\" value=\"1\" />";
		if($news['active'] == 2) echo "<input type=\"hidden\" name=\"from_user\" value=\"1\" />";
		echo "<input type=\"hidden\" name=\"edit_id\" value=\"".$id."\" />";
	}	
	if(isset($nid)) echo "<input type=\"hidden\" name=\"oldAltName\" value=\"$altname\" />";
	echo'				</form>
				</section>
			</div>
		</div>';	
	$adminTpl->admin_foot();
} 
 
//созранение новости
function news_save() 
{
global $adminTpl, $core, $db, $cats, $groupss, $config;
	$bb = new bb;
	$word_counter = new Counter();
	$gen_tag = $word_counter->get_keywords(($_POST['full'][$config['lang']] !== '') ? $_POST['full'][$config['lang']] : $_POST['short'][$config['lang']]);
	$title = $_POST['title'];
	$langTitle = isset($_POST['langtitle']) ? $_POST['langtitle'] : '';
	$langTitle[$config['lang']] = $title;
	$author = filter($_POST['author'], 'nick');
	$ttime = 'UNIX_TIMESTAMP(NOW())';
	$date = !empty($_POST['date']) ? filter($_POST['date']) : $ttime;
	$oldAltName = !empty($_POST['oldAltName']) ? filter($_POST['oldAltName']) : '';
	$tags = isset($_POST['tags']) ? mb_strtolower(filter($_POST['tags'], 'a'), 'UTF-8') : mb_strtolower(filter($gen_tag, 'a'), 'UTF-8');
	$translit = ($_POST['translit'] !== '') ? mb_strtolower(str_replace(array('-', ' '), array('_', '_'), $_POST['translit']), 'UTF-8') : translit($title);
	$short = ($_POST['short']);
	$full = ($_POST['full']);
	$xfield = isset($_POST['xfield']) ? $_POST['xfield'] : '';
	$xfieldT = isset($_POST['xfieldT']) ? ($_POST['xfieldT']) : '';
	$category = isset($_POST['category']) ? array_unique($_POST['category']) : '0';
	$groups = isset($_POST['groups']) ? $_POST['groups'] : '0';
	$comment = isset($_POST['comments']) ? 1 : 0;
	$rating = isset($_POST['rating']) ? 1 : 0;
	$index = isset($_POST['index']) ? 1 : 0;
	$status = isset($_POST['status']) ? 1 : 0;
	$fix = isset($_POST['fix']) ? 1 : 0;	
	$cnt = (($_POST['full'][$config['lang']] !== '') ? $_POST['full'][$config['lang']] : $_POST['short'][$config['lang']]);	
	$newcnt = $bb->parse(processText(filter(fileInit('news', $edit_id, 'content', $cnt), 'html')), $edit_id, true);	
	$keywords = !empty($_POST['keywords']) ? $_POST['keywords'] : $word_counter->get_keywords(substr($cnt, 0, 500)); 	
	$description = !empty($_POST['description']) ? $_POST['description'] : substr(strip_tags($newcnt), 0, 150); 	
	$edit_id = isset($_POST['edit_id']) ? intval($_POST['edit_id']) : '';
	
	if($edit_id > 0)
	{			
		$old_dataQ = $db->query("SELECT * FROM ".DB_PREFIX."_news WHERE id = '" . $edit_id . "'");
		$old_data = $db->getRow($old_dataQ);
	}
	
	if($date != $ttime)
	{
		$parseDate = explode(' ', $date);
		$subDate = explode('.', $parseDate[0]);
		if(isset($parseDate[1]))
		{
			$subTime = explode(':', $parseDate[1]);
		}
		else
		{
			$subTime[0] = 12;
			$subTime[1] = 0;
		}
		$date = gmmktime($subTime[0], $subTime[1], 0, $subDate[1], $subDate[0], $subDate[2]);
	}
	if(is_array($category)) 
	{
		$firstCat = $category[0];
		unset($category[0]);
		$deleteCat = array_search($firstCat, $category);
		unset($category[$deleteCat]);
		$category[0] = $firstCat;
		ksort($category);
		foreach($category as $cid) 
		{
			$cats .= intval($cid) . ",";
		}
	}
	else 
	{
		$cats  = $category . ',';
	}
	
	$fieldsSer = '';
	if(!empty($xfield))
	{
		foreach($xfield as $xId => $xContent)
		{
			if(!empty($xContent) && $xId > 0 && !empty($xfieldT[$xId]))
			{
				$xContent = processText(filter($xContent, 'html'));
				$xId = intval($xId);
				$xfieldT[$xId] = filter($xfieldT[$xId], 'title');
				$fileds[$xId] = array($xfieldT[$xId], $xContent);
			}
		}
		
		$fieldsSer = serialize($fileds);
	}
	
	$cats = ',' . $cats;	
	
	if(is_array($groups)) 
	{
		foreach($groups as $gid) 
		{
			$groupss .= intval($gid) . ",";
		}
	}
	else 
	{
		$groupss  = $groups . ',';
	}
	$groupss = ',' . $groupss;
	$adminTpl->admin_head(_MODULES . ' | ' . _NADD);

	if($title && $short['ru'] && $author && $translit) 
	{
		
		
		if(isset($_POST['edit'])) 
		{
			foreach($langTitle as $k => $v)
			{
				$ntitle = filter(trim(htmlspecialchars_decode($v, ENT_QUOTES)), 'title');
				$nshort = $bb->parse(processText(filter(fileInit('news', $edit_id, 'content', $short[$k]), 'html')), $edit_id, true);
				
				
				
				
				$nfull = $bb->parse(processText(filter(fileInit('news', $edit_id, 'content', $full[$k]), 'html')), $edit_id, true);	
				if(isset($_POST['empty'][$k]) && trim($v) != '' && trim($short[$k]) != '')
				{
					$db->query("INSERT INTO `" . DB_PREFIX . "_langs` ( `postId` , `module` , `title` , `short` , `full` , `lang` ) 
	VALUES ('" . $edit_id . "', 'news', '" . $db->safesql(processText($ntitle)) . "', '" . $db->safesql($nshort) . "', '" . $db->safesql($nfull) . "' , '" . $k . "');");
				}
				elseif(!isset($_POST['empty'][$k])  && (trim($v) == '' OR trim($short[$k]) == ''))
				{
					$db->query("DELETE FROM `" . DB_PREFIX . "_langs` WHERE `postId` ='" . $edit_id . "' AND `module` ='news' AND `lang`='" . $k . "' LIMIT 1");
				}
				elseif(!isset($_POST['empty'][$k]) && trim($v) != '' && trim($short[$k]) != '')
				{
					$db->query("UPDATE `" . DB_PREFIX . "_langs` SET `title` = '" . $db->safesql(processText($ntitle)) . "', `short` = '" . $db->safesql($nshort) . "', `full` = '" . $db->safesql($nfull) . "' WHERE `postId` ='" . $edit_id . "' AND `module` ='news' AND `lang`='" . $k . "' LIMIT 1 ;");
				}
			}
			
			if(!empty($tags) && $status == 1)
			{
				if($old_data['tags'] != $tags)
				{
					workTags($edit_id, $old_data['tags'], 'delete');
					workTags($edit_id, $tags, 'add');
				}
			}			
			
			if(isset($_POST['from_user']) && $status == 0)
			{
				$status = 2;
			}
			
			if(($old_data['active'] == 2 && $status == 1) || ($old_data['active'] == 0 && $status == 1))
			{
				$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET user_news=user_news+1 WHERE `nick` ='" . $db->safesql($author) . "' LIMIT 1", true);
				user_points($author, 'add_news');
				$access = $db->getRow($db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick = '" . $db->safesql($author) . "'"));
				delcache('userInfo_'.$access['id']);
			}
			
			if($old_data['active'] == 1 && $status == 0)
			{
				$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET user_news=user_news-1 WHERE `nick` ='" . $db->safesql($author) . "' LIMIT 1", true);
				$access = $db->getRow($db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick = '" . $db->safesql($author) . "'"));
				delcache('userInfo_'.$access['id']);
			}

		
			$update = $db->query("UPDATE `" . DB_PREFIX . "_news` SET `author` = '" . $author . "', `tags` = '" . $tags . "', `cat` = '" . $cats . "', `altname` = '" . $translit . "', `keywords` = '" . $keywords . "', `description` = '" . $description . "', `allow_comments` = '" . $comment . "', `allow_rating` = '" . $rating . "', `allow_index` = '" . $index . "', `fields` = '" . $fieldsSer . "', `groups` = '" . $groupss . "', `fixed` = '" . $fix . "', `active` = '" . $status . "' WHERE `id` = '" . $edit_id . "' LIMIT 1 ;");
			if($update)
			{
				$adminTpl->info(_SUCCESS_UPDATE .' ' . _NEWS_NAVS . '?');
			}
		} 
		else 
		{
			$insert = $db->query("INSERT INTO `" . DB_PREFIX . "_news` ( `id` , `author` , `date` , `tags` , `cat` , `altname` ,`keywords`,`description`, `allow_comments` , `allow_rating` , `allow_index` , `score` , `votes` , `views` , `comments` , `fields` , `groups` , `fixed` , `active` ) VALUES (NULL, '" . $author . "', " . $date . ", '" . $tags . "', '" . $cats . "', '" . $db->safesql($translit) . "', '" . $keywords . "', '" . $description . "', '" . $comment . "', '" . $rating . "', '" . $index . "', '0', '0', '0', '0', '" . $fieldsSer . "', '" . $groupss . "', '" . $fix . "', '" . $status . "');");
			if($insert) 
			{
				$adminTpl->info(_SUCCESS_ADD . ' ' . _NEWS_NAVS . '?');
				
				if($status == 1)
				{
					$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET user_news=user_news+1 WHERE `nick` ='" . $db->safesql($author) . "' LIMIT 1", true);
					user_points($author, 'add_news');
					$access = $db->getRow($db->query("SELECT id FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick = '" . $db->safesql($author) . "'"));
					delcache('userInfo_'.$access['id']);
				}
				
				$query = $db->query("SELECT * FROM ".DB_PREFIX."_news WHERE altname = '" . $db->safesql($translit) . "'");
				$news = $db->getRow($query);
				foreach($langTitle as $k => $v)
				{
					if(trim($v) != '' && trim($short[$k]) != '')
					{
						$ntitle = filter(trim(htmlspecialchars_decode($v, ENT_QUOTES)), 'title');
						$nshort = fileInit('news', $news['id'], 'content', $bb->parse(processText(filter($short[$k], 'html')), $news['id'], true));
						$nfull = fileInit('news', $news['id'], 'content', $bb->parse(processText(filter($full[$k], 'html')), $news['id'], true));	
						$db->query("INSERT INTO `" . DB_PREFIX . "_langs` ( `postId` , `module` , `title` , `short` , `full` , `lang` ) 
	VALUES ('" . $news['id'] . "', 'news', '" . $db->safesql(processText($ntitle)) . "', '" . $db->safesql($nshort) . "', '" . $db->safesql($nfull) . "' , '" . $k . "');");
					}
				}
			
				fileInit('news', $news['id']);
				
				workTags($news['id'], $tags, 'add');

			}
		}
	}
	else 
	{
		$adminTpl->info(_NOT_FILLEDN, 'error');
	}
	$adminTpl->admin_foot();
}

//удаление
function delete($id) {
global $db;
	$query = $db->query("SELECT * FROM ".DB_PREFIX."_news WHERE id = '" . $id . "'");
	$news = $db->getRow($query);
	$db->query("DELETE FROM `" . DB_PREFIX . "_langs` WHERE `postId` = '" . $news['id'] . "' AND `module` = 'news'");
	full_rmdir(ROOT . initDC('news', $news['id']));
	$db->query("DELETE FROM `" . DB_PREFIX . "_news` WHERE `id` = " . $id . " LIMIT 1");
	$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET user_news=user_news-1 WHERE `nick` ='" . $news['author'] . "' LIMIT 1", true);
	workTags($id, $news['tags'], 'delete');
	deleteComments('news', $id);
}

//д теги
function workTags($id, $tags, $do = 'add')
{
global $db;
	if(!empty($tags))
	{
		$tag = array_map('trim', explode(',', $tags));
		foreach($tag as $t)
		{
			if($do == 'add')
			{
				$db->query("INSERT INTO `" . DB_PREFIX . "_tags` ( `id` , `tag` , `module` ) VALUES (NULL, '" . $db->safesql($t) . "', 'news');");
			}
			elseif($do == 'delete')
			{
				$db->query("DELETE FROM `" . DB_PREFIX . "_tags` WHERE `tag` = '" . $t . "' and module='news' LIMIT 1");			
			}
		}
	}
}

switch(isset($url[3]) ? $url[3] : null) {
	default:
		news_main();
	break;
//теги	
	case 'tags':
		$adminTpl->admin_head(_MODULES . ' | ' . _TAGS);
		if(isset($url[4]))
		{
			switch($url[4])
			{
				case 'addOk':
					$adminTpl->info(_ADD_TAGSUC);
					break;				
					
				case 'delOk':
					$adminTpl->info(_DEL_TAGSUC);
					break;
			}
		}
		$adminTpl->open();
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">	
					<form style="margin:0; padding:0" method="POST" action="{MOD_LINK}/addTag">
					<div class="col-sm-5 input-group mg-b-md">
					<input type="text" name="tag" class="form-control">
					<span class="input-group-btn"><input type="submit" class="btn btn-white" value="' . _NEWS_ADD_TAG . '"></span>
				</div>	
				</form>				
		<b>' . _LIST . '</b></div>';		
		$query = $db->query("SELECT tag FROM " . DB_PREFIX . "_tags WHERE module = 'news'");
		if($db->numRows($query) > 0) {
			echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{MOD_LINK}/actionTag"">
						<table class="table no-margin">
							<thead>
								<tr>									
									<th class="col-md-5"><span class="pd-l-sm"></span>' . _TAG . '</th>
									<th class="col-md-5">' . _TAGIN .  '</th>
									<th class="col-md-3">' . _ACTIONS .'</th>									
									<th class="col-md-1"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
								</tr>
							</thead>
							<tbody>';
			while($tag = $db->getRow($query)) 
			{
				$tags[] = $tag['tag'];
			}			
			$tag_cloud = new TagsCloud;
			$tag_cloud->tags = $tags;
			$cloud = Array();
			$tags_list = $tag_cloud->tags_cloud($tag_cloud->tags);
			$min_count = $tag_cloud->get_min_count($tags_list);			
			foreach ($tags_list as $tag => $count) 
			{
				echo '
				<tr>
					<td><span class="pd-l-sm"></span>' . $tag . '</td>
					<td>' . $count . '</td>
					<td>
					<a href="{MOD_LINK}/tagDelete/' . $tag . '" onClick="return getConfirm(\'' . _DELETE . ' ' . _TAG . ' - ' . $tag . '?\')">
					<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _DELETE .	'">X</button>
					</a>
					</td>
					<td> <input type="checkbox" name="checks[]" value="' . $tag . '"></td>
				</tr>';	
			}
		echo '<tr><td></td><td></td><td></td><td></td></tr></tbody></table>		
		<div align="right">
			<table>
				<tr>		
					<td valign="top">
						<input name="submit" type="submit" class="btn btn btn-danger" id="sub" value="' . _DELETE . '" /><span class="pd-l-sm"></span>
					</td>
				</tr>
			</table>
			<br>	
			</div>
			</form></div>';	
		} else {
			echo '<div class="panel-heading">'  . _NEWS_NO_TAG . '</div>';		
			}
			echo'</section></div></div>';
		$adminTpl->close();
		$adminTpl->admin_foot();
		break;
//добавить теги		
	case 'addTag':
		if(!empty($_POST['tag']))
		{
			$db->query("INSERT INTO `" . DB_PREFIX . "_tags` ( `id` , `tag` , `module` ) VALUES (NULL, '" . filter($_POST['tag']) . "', 'news');");
			location(ADMIN.'/module/news/tags/addOk');
		}
		else
		{
			location(ADMIN.'/module/news/tags');
		}
		break;
//теги удаолить		
	case 'tagDelete':
		if(isset($url[4]))
		{
			$tag = filter(utf_decode($url[4]));
			$db->query("DELETE FROM `" . DB_PREFIX . "_tags` WHERE `tag` = '" . $tag . "'");
			location(ADMIN.'/module/news/tags/delOk');
		}
		else
		{
			location(ADMIN.'/module/news/tags');
		}
		break;
//теги	 удалить через action	
	case 'actionTag':
		if(!empty($_POST['checks']))
		{
			foreach($_POST['checks'] as $id) 
			{
				if(trim($id))
				{
					$db->query("DELETE FROM `" . DB_PREFIX . "_tags` WHERE `tag` = '" . $id . "'");
				}
			}
			
			location(ADMIN.'/module/news/delOk');
		}
		else
		{
			location(ADMIN.'/module/news/tags');
		}
		break;
	
	case "add":
		news_add();
	break;
	
	case "save":
		news_save();
	break;
	
	case "edit":
		$id = intval($url[4]);
		news_add($id);
	break;
	
	case "delete":
		$id = intval($url[4]);
		delete($id);
		location(ADMIN.'/module/news');
	break;
	
	case "activate":
		$id = intval($url[4]);
		$db->query("UPDATE `" . DB_PREFIX . "_news` SET `active` = '1' WHERE `id` = " . $id . " LIMIT 1 ;");
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_news WHERE id = '" . $id . "'");
		$news = $db->getRow($query);
		$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET user_news=user_news+1 WHERE `nick` ='" . $news['author'] . "' LIMIT 1", true);
		location(ADMIN.'/module/news');
	break;	
	
	case "deactivate":
	global $adminTpl, $db;
		$id = intval($url[4]);
		$db->query("UPDATE `" . DB_PREFIX . "_news` SET `active` = '0' WHERE `id` = " . $id . " LIMIT 1 ;");
		$query = $db->query("SELECT * FROM ".DB_PREFIX."_news WHERE id = '" . $id . "'");
		$news = $db->getRow($query);
		$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET user_news=user_news-1 WHERE `nick` ='" . $news['author'] . "' LIMIT 1", true);
		location(ADMIN.'/module/news');
	break;

	case "action":
	$type = $_POST['act'];
	if(is_array($_POST['checks'])) {
		switch($type) {
			case "activate":
				foreach($_POST['checks'] as $id) {
					$db->query("UPDATE `" . DB_PREFIX . "_news` SET `active` = '1' WHERE `id` = " . intval($id) . " LIMIT 1 ;");
				}
				break;			
			
			case "deActivate":
				foreach($_POST['checks'] as $id) {
					$db->query("UPDATE `" . DB_PREFIX . "_news` SET `active` = '0' WHERE `id` = " . $id . " LIMIT 1 ;");
				}
				break;			
				
			case "reActivate":
				foreach($_POST['checks'] as $id) {
					$db->query("UPDATE `" . DB_PREFIX . "_news` SET `active` = NOT `active` WHERE `id` = " . $id . " LIMIT 1 ;");
				}
				break;
				
			case "nowDate":
				foreach($_POST['checks'] as $id) {
					$db->query("UPDATE `" . DB_PREFIX . "_news` SET `date` = '" . time() . "' WHERE `id` = " . $id . " LIMIT 1 ;");
				}
				break;			
				
			case "blockIndex":
				foreach($_POST['checks'] as $id) {
					$db->query("UPDATE `" . DB_PREFIX . "_news` SET `allow_index` = '0' WHERE `id` = " . $id . " LIMIT 1 ;");
				}
				break;			
				
			case "blockComment":
				foreach($_POST['checks'] as $id) {
					$db->query("UPDATE `" . DB_PREFIX . "_news` SET `	allow_comments` = '0' WHERE `id` = " . $id . " LIMIT 1 ;");
				}
				break;
			
			case "delete":
				foreach($_POST['checks'] as $id) {
					delete(intval($id));
				}
				break;
		}
	}
		if(isset($_GET['moderate']))
		{
			location(ADMIN.'/publications/mod/news');
		}
		else
		{
			location(ADMIN.'/module/news');
		}
	break;
//настройки	
	case 'config':
		require (ROOT.'etc/news.config.php');
		
		$configBox = array(
			'news' => array(
				'varName' => 'news_conf',
				'title' => _APNEWS,
				'groups' => array(
					'main' => array(
						'title' => _APNEWS_MAIN,
						'vars' => array(
							'num' => array(
								'title' => _APNEWS_MAIN_NUMT,
								'description' => _APNEWS_MAIN_NUMD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'comments_num' => array(
								'title' => _APNEWS_MAIN_COMMENTS_NUMT,
								'description' => _APNEWS_MAIN_COMMENTS_NUMD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),							
							'fullLink' => array(
								'title' => _APNEWS_MAIN_FULLLINKT,
								'description' => _APNEWS_MAIN_FULLLINKD,
								'content' => radio("fullLink", $news_conf['fullLink']),
							),							
							'preModer' => array(
								'title' => _APNEWS_PREMODERT,
								'description' => _APNEWS_PREMODERD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),									
							'related_news' => array(
								'title' => _APNEWS_RELATEDT,
								'description' => _APNEWS_RELATEDD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),					
							'addNews' => array(
								'title' => _APNEWS_ADDNEWST,
								'description' => _APNEWS_ADDNEWSD,
								'content' => radio("addNews", $news_conf['addNews']),
							),
						)
					),
					'cats' => array(
						'title' => _APNEWS_CATS,
						'vars' => array(
							'showCat' => array(
								'title' => _APNEWS_CATS_SHOWCATT,
								'description' => _APNEWS_CATS_SHOWCATD,
								'content' => radio("showCat", $news_conf['showCat']),
							),							
							'subLoad' => array(
								'title' => _APNEWS_CATS_SUBLOADT,
								'description' => _APNEWS_CATS_SUBLOADD,
								'content' => radio("subLoad", $news_conf['subLoad']),
							),
							'catCols' => array(
								'title' => _APNEWS_CATS_CATCOLST,
								'description' => _APNEWS_CATS_CATCOLSD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'showBreadcumb' => array(
								'title' => _APNEWS_CATS_SHOWBREADCUMBT,
								'description' => _APNEWS_CATS_SHOWBREADCUMBD,
								'content' => radio("showBreadcumb", $news_conf['showBreadcumb']),
							),
						)
					),					
					'tags' => array(
						'title' => _APNEWS_TAGS,
						'vars' => array(
							'tags' => array(
								'title' => _APNEWS_TAGS_TAGST,
								'description' => _APNEWS_TAGS_TAGSD,
								'content' => radio("tags", $news_conf['tags']),
							),							
							'tags_num' => array(
								'title' => _APNEWS_TAGS_TAGS_NUMT,
								'description' => _APNEWS_TAGS_TAGS_NUMD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'tagIll' => array(
								'title' => _APNEWS_TAGS_TAGILLT,
								'description' => _APNEWS_TAGS_TAGILLD,
								'content' => radio("tagIll", $news_conf['tagIll']),
							),
							'illFormat' => array(
								'title' => _APNEWS_TAGS_ILLFORMATT,
								'description' => _APNEWS_TAGS_ILLFORMATD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
						)
					),
					'ratings' => array(
						'title' => _APNEWS_RATINGS,
						'vars' => array(
							'limitStar' => array(
								'title' => _APNEWS_RATINGS_LIMITSTART,
								'description' => _APNEWS_RATINGS_LIMITSTARD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'starStyle' => array(
								'title' => _APNEWS_RATINGS_STARSTYLET,
								'description' => _APNEWS_RATINGS_STARSTYLED,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'carma_rate' => array(
								'title' => _APNEWS_CARMA_RATET,
								'description' => _APNEWS_CARMA_RATED,
								'content' => radio("carma_rate", $news_conf['carma_rate']),
							),
							'carma_summ' => array(
								'title' => _APNEWS_CARMA_SUMMT,
								'description' => _APNEWS_CARMA_SUMMD,
								'content' => radio("carma_summ", $news_conf['carma_summ']),
							),
						)
					),
					'files' => array(
						'title' => _APNEWS_FILES,
						'vars' => array(
							'fileEditor' => array(
								'title' => _APNEWS_FEDITORT,
								'description' => _APNEWS_FEDITORD,
								'content' => radio("fileEditor", $news_conf['fileEditor']),
							),
							'imgFormats' => array(
								'title' => _APNEWS_FILE_IMGFORMATST,
								'description' => _APNEWS_FILE_IMGFORMATSD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'attachFormats' => array(
								'title' => _APNEWS_FILE_ATTACHFORMATST,
								'description' => _APNEWS_FILE_ATTACHFORMATSD,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),
							'max_size' => array(
								'title' => _APNEWS_FILE_MAX_SIZET,
								'description' => _APNEWS_FILE_MAX_SIZED,
								'content' => '<input type="text" size="20" name="{varName}" class="form-control" value="{var}" />',
							),			
							'thumb_width' => array(
								'title' => _APNEWS_THUMB_THUMB_WIDTHT,
								'description' => _APNEWS_THUMB_THUMB_WIDTHD,
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
		
		generateConfig($configBox, 'news', '{MOD_LINK}/config', $ok);
		break;
		
}
