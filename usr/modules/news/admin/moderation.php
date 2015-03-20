<?php


if(empty($url[1]))
{
	$count = $db->numRows($db->query('SELECT id FROM '.DB_PREFIX.'_news WHERE active=2'));
	if($count > 0)
	{
		$onModer['news'] = '<img src="media/admin/news.png" border="0" style="vertical-align:middle" /> <a href="administration/publications/mod/news">Новости: '.$count.'</a>';
	}
}
else
{
	$adminTpl->admin_head(_NEW_NEWS);
	$page = init_page();
	$cut = ($page-1)*$admin_conf['num'];
	$where = '';
	$cat = 0;
	echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _LIST_NEWS . '</b>						
					</div>';	
	$all = $db->numRows($db->query("SELECT * FROM " . DB_PREFIX . "_news $whereC"));
	$adminTpl->a_pages($page, $admin_conf['num'], $all, ADMIN.'/module/news/{page}');
	$query = $db->query("SELECT n.*, l.*, c.id as cid, c.name, c.altname as alturl FROM ".DB_PREFIX."_news AS n LEFT JOIN ".DB_PREFIX."_categories AS c ON (n.cat=c.id) LEFT JOIN ".DB_PREFIX."_langs as l on(l.postId=n.id and l.module='news') WHERE n.active='2' ORDER BY n.date DESC LIMIT " . $cut . ", " . $admin_conf['num'] . "");
	if($db->numRows($query) > 0) 
	{
	echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/module/news/action&moderate">
						<table class="table no-margin">
							<thead>
								<tr>
									<th class="col-md-1"><span class="pd-l-sm"></span>ID</th>
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
			$status_icon = ($news['active'] == 2) ? '<a href="{ADMIN}/module/news/activate/' . $news['id'] . '" onClick="return getConfirm(\'' .  _ACTIVATE_NEWS .' - ' . $news['title'] . '?\')"><button  type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _NEWS_ACTIVE .'">A</button></a>' : '<a href="{MOD_LINK}/deactivate/' . $news['id'] . '" onClick="return getConfirm(\'' . _DEACTIVATE_NEWS .' - ' . $news['title'] . '?\')" ><button  type="button" class="btn btn btn-primary btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _NEWS_DEACTIVE .'">A</button></a>';
			
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
										<option value="activate">' . _ACTIVATE . '</option>
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
	$all_query = $db->query("SELECT * FROM " . DB_PREFIX . "_news WHERE active=2");
	$all = $db->numRows($all_query);
	$adminTpl->pages($page, $admin_conf['num'], $all, ADMIN.'/administration/publications/mod/news/{page}');
	$adminTpl->admin_foot();
}