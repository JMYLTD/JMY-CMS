<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @revision	   01.03.2015
*/

if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

function main() {
	global $adminTpl, $config, $core, $configs, $clear;
}

switch(isset($url[2]) ? $url[2] : null) 
{
	default:
		$adminTpl->admin_head(_SMAILE_TITLE);
		if(isset($url[2]) && $url[2] == 'saveOk')
		{
			$adminTpl->info(_SMAILE_INFO_1);
		}
		elseif(isset($url[2]) && $url[2] == 'addOk')
		{
			$adminTpl->info(_SMAILE_INFO_2);
		}		
		elseif(isset($url[2]) && $url[2] == 'errorOk')
		{
			$adminTpl->info(_SMAILE_INFO_3);
		}
		$adminTpl->open();	
		echo '<div id="addSmile"  style="display:none">
		<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading no-border">
						<b>' . _SMAILE_ADD . '</b>
					</div>
					<div class="panel-body">';	
		
		echo '<form style="margin:0; padding:0" method="POST" action="{ADMIN}/smiles/add" class="form-horizontal parsley-form" >
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _SMAILE_TAG.'</label>
					<div class="col-sm-4">
						<input type="text" name="tag"  class="form-control"   data-parsley-required="true" data-parsley-trigger="change" >
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _SMAILE_DESC.'</label>
					<div class="col-sm-4">
						<input type="text" name="desc"  class="form-control"   data-parsley-required="true" data-parsley-trigger="change" >
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _SMAILE_URL.'</label>
					<div class="col-sm-4">
						<input type="text" name="url"  class="form-control"   data-parsley-required="true" data-parsley-trigger="change" >
					</div>
		 </div>
		 <div class="form-group">
					<label class="col-sm-3 control-label">'. _SMAILE_FOLDER.'</label>
					<div class="col-sm-4">';
	echo "<select class=\"form-control\"  name=\"icon\" id=\"icon\" onchange=\"changeIcon('media/smiles/' + this.value, 'iconImg')\" ><option value=\"\">Выберите файл</option>";
	foreach(glob(ROOT.'media/smiles/*') as $file) 
	{
		$img = getimagesize($file);
		if($img) 
		{
			echo '<option value="' . basename($file) . '">' . basename($file) . '</option>';
			$ic = basename($file);
		}
	}
	echo "</select>";
	
	echo'</div>
		 </div>';
    echo '<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'._ADD.'">						
					</div>
		</div>
  
</form></div>';
echo'</section></div></div> </div>';
		
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _SMAILE_LIST . '</b> - [<a href="javascript:void(0)" onclick="showhide(\'addSmile\')">'. _SMAILE_ADD .'</a>]
					</div>';
		$k=0;
		foreach($smiles as $tag => $param)
		{ $k=$k+1; }
		if ($k<>0) {
		
		echo '<div class="panel-body no-padding">
					<form id="tablesForm" style="margin:0; padding:0" method="POST"  action="{ADMIN}/smiles/action">
						<table class="table no-margin">
							<thead>
								<tr>
									<th class="col-md-3"><span class="pd-l-sm"></span>'. _SMAILE_TAG .'</th>
									<th class="col-md-3">' . _SMAILE_DESC . '</th>
									<th class="col-md-3">' . _SMAILE_URL . '</th>
									<th class="col-md-2">' . _SMAILE_SMAILE . '</th>
									<th class="col-md-2">' . _SMAILE_DEL .'</th>											
								</tr>
							</thead>
							<tbody>';
		foreach($smiles as $tag => $param)
		{
		echo   '<tr>
				<td><input name="tag[]" value="' . $tag . '" type="text" class="form-control" /></td>
				<td><input name="title[]" value="' . $param['title'] . '" type="text" class="form-control" /></td>
				<td>' . $param['url'] . ' [' . (file_exists(ROOT.$param['url']) ? '<font color="green">'._SMAILE_FOUND.'</font>' : '<font color="red">'._SMAILE_NOT_FOUND.'</font>') . ']<input name="url[]" value="' . $param['url'] . '" type="hidden" /></td>
				<td><img src="' . $param['url'] . '" border="0" title="' . $param['title'] . '" alt="" /></td>
				<td align="right">  <input type="checkbox" name="checks[' . $tag . ']" value="' . $tag . '" ><span class="pd-l-sm"></span></td>
			</tr>';
		}
		echo '<tr><td></td><td></td><td></td><td></td><td></td></tr></tbody></table>
		
	<div align="right">
	<table>
	<tr>		
	<td valign="top">
	<input name="submit" type="submit" class="btn btn-success" id="sub" value="' . _SAVE . '" /><span class="pd-l-sm"></span>
	</td>
	</tr>
	</table>
	<br>	
	</div>
	</form></div>';	
	} 
	
	else 
	{
	echo '<div class="panel-heading">' . _DOP_EMPTY . '</div>';
	}
	echo'</section></div></div>';
		$adminTpl->close();
		$adminTpl->admin_foot();
		break;	
		
	case 'action':
		if(!empty($_POST['checks']))
		{
			$tag = $_POST['tag'];
			$title = $_POST['title'];
			$url = $_POST['url'];
			$checks = $_POST['checks'];
			foreach($tag as $id => $tagg)
			{
				if(!isset($checks[$tagg]) && !empty($title[$id]))
				{
					$array[$tagg] = array('title' => $title[$id], 'url' => $url[$id]);
				}
			}
			
			$content = "global \$smiles;\n";
			$content .= '$smiles = '.arr2str($array).';';
			
			save_conf(ROOT . 'etc/smiles.config.php', $content);
		}
		location(ADMIN.'/smiles/saveOk');
		break;
		
	case 'add':
		$tag = filter($_POST['tag'], 'a');
		$desc = filter($_POST['desc'], 'a');
		$url = filter($_POST['url']);
		
		if(file_exists(ROOT . $url) && $tag && $desc)
		{
			$smiles[$tag] = array('title' => $desc, 'url' => $url);
			$content = "global\$smiles;\n";
			$content .= '$smiles = '.arr2str($smiles).';';
		
			save_conf(ROOT . 'etc/smiles.config.php', $content);
			location(ADMIN . '/smiles/addOk');
		}
		else
		{
			location(ADMIN . '/smiles/errorOk');
		}
		break;
}

	function arr2str (&$arr, $depth = 0)
	{
			$ret = array();
			if (is_array($arr) && sizeof($arr) > 0)
			{
					foreach ($arr AS $key => $value)
					{
							$key = str_replace("'", "\'", $key);
							if (is_array($value)) $ret[] = "'{$key}'=>".arr2str($value, $depth+1);
							elseif (is_int($value)) $ret[] = "'{$key}'=>$value";
							else
							{
									if (is_string($value)) $value = str_replace("'", '"', $value);
									$ret[] = "'{$key}'=>'".strval($value)."'";
							}
					}
			}
			return 'array('.implode(',', $ret).')';
	}