<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/ 
 
if (!defined('ADMIN_ACCESS')) {
    header('Location: /');
    exit;
}

switch(isset($url[2]) ? $url[2] : null) 
{
	default:
		$adminTpl->admin_head(_DB_DB);
		
		$result = $db->query("SHOW TABLE STATUS");
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _BASE_LIST . '</b>
					</div>';
		echo '<div class="panel-body no-padding">					
						<table class="table no-margin">
							<thead>
								<tr>									
									<th><span class="pd-l-sm"></span>' . _DB_TABLE_NAME . '</th>
									<th class="col-md-1">' . _DB_TABLE_CODE . '</th>
									<th class="col-md-3">' . _DB_TABLE_NUMB .'</th>
									<th class="col-md-2">' . _DB_TABLE_SIZE . '</th>									
									<th class="col-md-3">' . _BASE_ACTIONS . '</th>									
								</tr>
							</thead>
							<tbody>';
		while($row = $db->getRow($result)) 
		{
			if(eregStrt(DB_PREFIX, $row["Name"]))
			{			
			echo '
			<tr>
				<td><span class="pd-l-sm"></span>' . $row["Name"] . '</td>
				<td>' . $row["Collation"] . '</td>
				<td>' . $row["Rows"] . '</td>
				<td>' . formatfilesize($row["Data_length"]+$row["Index_length"]) . '</td>
				<td>
				<a href="{ADMIN}/db/truncate/' . $row["Name"] . '" onClick="return getConfirm(\'' . _DB_TRUNCATE. ' - '. $row["Name"]. '\')">
				<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _BASE_CLEAR .'">C</button>
				</a>
				<a href="{ADMIN}/db/delete/' . $row["Name"] . '" onClick="return getConfirm(\'' . _DB_DELETE. ' - '. $row["Name"]. '\')">
				<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _BASE_DELETE .'">X</button>
				</a>
				</td>				
			</tr>
			';
			}
		}
		echo '<tr><td></td><td></td><td></td><td></td></tr></tbody></table></div>';
		echo'</section></div></div>';
		$adminTpl->admin_foot();
		break;	

	case 'optimize':
		$adminTpl->admin_head(_DB_DB .' | '. _DB_OP_NAME);		
		$result = $db->query("SHOW TABLE STATUS");
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _DB_OP_NAME . '</b>
					</div>
					<div class="panel-body"><div class="switcher-content">';		
		$adminTpl->open();		
		echo '<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/db/action" role="form" class="form-horizontal parsley-form" data-parsley-validate>
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DB_ABOUT .'</label>
					<div class="col-sm-4">
						<p class="form-control-static">'. _DB_OP_TEXT .'</p>
					</div>
		</div>		
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DB_CHOICE .'</label>
					<div class="col-sm-4">';
		echo '<select name="db_list[]" size="10" multiple>';
		while($row = $db->getRow($result)) 
		{
			if(eregStrt(DB_PREFIX, $row["Name"]))
			{
				echo "<option value=\"" . $row["Name"] . "\" selected>" . $row["Name"] . "</option>";
			}
		}
		echo '</select>
					</div>
		</div>
		<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'. _DB_OP_DO .'" />						
					</div>
		</div>';
		echo '</form></div></div>';  
		echo'</section></div></div>';		
		$adminTpl->close();
		$adminTpl->admin_foot();
		break;	

	case 'repair':
		$adminTpl->admin_head(_DB_DB .' | '. _DB_RE_NAME);		
		$result = $db->query("SHOW TABLE STATUS");
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _DB_RE_NAME . '</b>
					</div>
					<div class="panel-body"><div class="switcher-content">';		
		$adminTpl->open();		
		echo '<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/db/action" role="form" class="form-horizontal parsley-form" data-parsley-validate>
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DB_ABOUT .'</label>
					<div class="col-sm-4">
						<p class="form-control-static">'. _DB_RE_TEXT .'</p>
					</div>
		</div>		
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DB_CHOICE .'</label>
					<div class="col-sm-4">';
		echo '<select name="db_list[]" size="10" multiple>';
		while($row = $db->getRow($result)) 
		{
			if(eregStrt(DB_PREFIX, $row["Name"]))
			{
				echo "<option value=\"" . $row["Name"] . "\" selected>" . $row["Name"] . "</option>";
			}
		}
		echo '</select>
					</div>
		</div>
		<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'. _DB_RE_DO .'" />						
					</div>
		</div>';
		echo '</form></div></div>';  
		echo'</section></div></div>';		
		$adminTpl->close();
		$adminTpl->admin_foot();
		break;	

	case 'backup':
		$adminTpl->admin_head(_DB_DB .' | '. _DB_BE_NAME);		
		$result = $db->query("SHOW TABLE STATUS");
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _DB_BE_NAME . '</b>
					</div>
					<div class="panel-body"><div class="switcher-content">';		
		$adminTpl->open();		
		echo '<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/db/backup_do" role="form" class="form-horizontal parsley-form" data-parsley-validate>
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DB_ABOUT .'</label>
					<div class="col-sm-4">
						<p class="form-control-static">'. _DB_BE_TEXT .'</p>
					</div>
		</div>		
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DB_CHOICE .'</label>
					<div class="col-sm-4">';
		echo '<select name="db_list[]" size="10" multiple>';
		while($row = $db->getRow($result)) 
		{
			if(eregStrt(DB_PREFIX, $row["Name"]))
			{
				echo "<option value=\"" . $row["Name"] . "\" selected>" . $row["Name"] . "</option>";
			}
		}
		echo '</select>
					</div>
		</div>
		<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'. _DB_BE_DO .'" />						
					</div>
		</div>';
		echo '</form></div></div>';  
		echo'</section></div></div>';		
			
		
		$dir = ROOT . "tmp/";
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _DB_BE_COPY . '</b>
					</div>';
		if (is_dir($dir)) 
		{
			if ($dh1 = opendir($dir)) 
			{
			while (($file1 = readdir($dh1)) !== false) {
					if(eregStrt('db_backup', $file1))
					{						
						$yes = true;
					}
				}
			closedir($dh1);
			}
			if ($dh = opendir($dir)) 
			{			
				if(isset($yes))
				{		
					echo '<div class="panel-body no-padding">					
						<table class="table no-margin">
							<thead>
								<tr>
									<th class="col-md-3"><span class="pd-l-sm"></span>'. _BASE_FILE .'</th>
									<th class="col-md-1">' . _BASE_DATE . '</th>
									<th class="col-md-1">' . _DB_TABLE_SIZE . '</th>
									<th class="col-md-3">' . _BASE_ACTIONS .'</th>									
								</tr>
							</thead>
							<tbody>';	
					while (($file = readdir($dh)) !== false) 
					{
						if(eregStrt('db_backup', $file))
						{
							echo '<tr>
									<td><span class="pd-l-sm"></span>' . $file . '</td>
									<td>' . formatDate(filemtime(ROOT . 'tmp/' . $file)) . '</td>
									<td>' . formatfilesize(filesize(ROOT . 'tmp/' . $file)) . '</td>
									<td>
									<a href="{ADMIN}/db/restore/'.str_replace('.sql.gz', '', $file).'" onclick="return getConfirm(\''. _DB_BE_RES .' - ' . $file . '?\')">
									<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _BASE_RESTORE .'">R</button>
									</a>
									<a href="{ADMIN}/db/delBackup/'.str_replace('.sql.gz', '', $file).'" onclick="return getConfirm(\''. _DB_BE_DEL .' - ' . $file . '?\')">
									<button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="" data-original-title="' . _BASE_DELETE .'">X</button>
									</a>
									</td>
								</tr>';							
						}
					}
					echo '<tr><td></td><td></td><td></td><td></td></tr></tbody></table></div>';
				}
				else
				{
					echo '<div class="panel-heading">'  . _DB_BE_NO . '</div></div>';					
				}
				closedir($dh);
				
		    }
		}		
		echo'</section></div></div>';
		$adminTpl->close();	
		$adminTpl->admin_foot();
		break;
	
	case 'fix':
		$adminTpl->admin_head(_DB_DB .' | '. _DB_BB_NAME);
		if(!isset($url[3]))
		{		
		$adminTpl->open();
		echo '<div class="row">
			<div class="col-lg-12">
				<section class="panel">
					<div class="panel-heading">
						<b>' . _DB_BB_NAME . '</b>
					</div>
					<div class="panel-body"><div class="switcher-content">				
		<form id="tablesForm" style="margin:0; padding:0" method="POST" action="{ADMIN}/db/doFix" role="form" class="form-horizontal parsley-form" data-parsley-validate>
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DB_BB_START .'</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="lstart" value="0" data-parsley-required="true" data-parsley-trigger="change"/>
					</div>
		</div>		
		<div class="form-group">
					<label class="col-sm-3 control-label">'. _DB_BB_END .'</label>
					<div class="col-sm-4">
						<input class="form-control" type="text" name="lend" value="100" data-parsley-required="true" data-parsley-trigger="change"/>
					</div>
		</div>
		<div class="form-group">
					<label class="col-sm-3 control-label"></label>
					<div class="col-sm-4">
						<input name="submit" type="submit" class="btn btn-primary btn-parsley" id="sub" value="'. _DB_BB_DO .'" />						
					</div>
		</div>';
		echo '</form></div></div>';  
		echo'</section></div></div>';	
		$adminTpl->close();		
		}
		else 
		{		
		$adminTpl->info(_BASE_INFO_0);
		}
		$adminTpl->admin_foot();
		break;
		
	case 'action':		
		$tables = '';		
		foreach($_POST['db_list'] as $table)
		{
			$tables .= ", `" . $table . "`";
		}
		$tables = mb_substr($tables, 1);		
		if($_POST['submit'] == _DB_OP_DO)
		{
			$query = "OPTIMIZE TABLE  ";
			$name = _DB_OP_NAME;
		} 
		elseif($_POST['submit'] == _DB_RE_DO)  
		{
			$query = "REPAIR TABLE  ";
			$name = _DB_RE_NAME;
		}		
		if($db->query($query . $tables))
		{	$adminTpl->admin_head( _DB_DB .' | ' . $name);
			$adminTpl->info(_BASE_INFO_0);
			$adminTpl->admin_foot();
		}		
		break;
		
	case 'backup_do':
		require_once ROOT . 'boot/sub_classes/backup.class.php';		
		$backup_obj = new MySQL_DB_Backup();		
		require ROOT . 'etc/db.config.php';		
		$backup_obj->server = $dbhost;
		$backup_obj->port = 3306;
		$backup_obj->username = $dbuser;
		$backup_obj->password = $dbpass;
		$backup_obj->database = $dbname;		
		$backup_obj->tables = $_POST['db_list'];		
		$backup_obj->drop_tables = true;
		$backup_obj->create_tables = true;
		$backup_obj->struct_only = false;
		$backup_obj->locks = true;
		$backup_obj->comments = true;
		$backup_obj->backup_dir = 'tmp/';
		$backup_obj->fname_format = 'm_d_Y';
		$task = MSX_SAVE;
		$use_gzip = true;		
		$filename = 'jmy_DB_backup-' . date('d-m-Y_H-i') . '.sql.gz';		
		$result_bk = $backup_obj->Execute($task, $filename, $use_gzip);
		$adminTpl->admin_head(_DB_DB .' | '. _DB_BE_NAME);		
		if (!$result_bk)
		{
			$output = $backup_obj->error;
			$adminTpl->info($output . '<br>'. _DB_BE_ERROR, 'error');

		}
		else
		{
			$output = 'Operation Completed Successfully At: <b>' . date('g:i:s A') . '</b><i> ( Local Server Time )</i>';
			if ($task == MSX_STRING)
			{
				$output.= '\n' . $result_bk;
			}			
			$adminTpl->info(_DB_BE_COM .' (tmp/). <a href="/'.ADMIN.'/db/backup" >'. _DB_BE_BACK .'</a><br /><br /><a href="/tmp/'.$filename.'" >'. _DB_BE_DOWNLOAD .'</a><br /><br /> '. _DB_BE_SERVER .' <br />' . $output);
		}		
		$adminTpl->admin_foot();
		break;
		
	case 'restore':
		$file = $url[3];		
		if (file_exists('tmp/' . $file . '.sql.gz')) 
		{
			require ROOT . 'etc/db.config.php';			
			$restore_obj = new MySQL_Restore();			
			$restore_obj->server = $dbhost;
			$restore_obj->username = $dbuser;
			$restore_obj->password = $dbpass;
			$restore_obj->database = $dbname;			
			$adminTpl->admin_head(_DB_DB .' | '. _DB_BE_REST);		
			if (!$restore_obj->Execute('tmp/' . $file . '.sql.gz', MSR_FILE, true, false))
			{
				$adminTpl->info(_DB_BE_REST_NO .' <a href="/'.ADMIN.'/db/backup">'. _DB_BE_BACK .'</a>.', 'error');
			}
			else
			{
				$adminTpl->info(_DB_BE_REST_OK .' <a href="/'.ADMIN.'/db">'. _DB_BE_BACK .'</a>');
			}
			$adminTpl->admin_foot();
		}
		break;
		
	case 'delete':
		$table = $url[3];
		
		if(eregStrt(DB_PREFIX, $table))
		{
			$db->query("DROP TABLE `$table`");
		}
		
		$adminTpl->admin_head(_DB_DB .' | '. _DB_TABLE_DEL);	
		$adminTpl->info(_DB_TABLE . $table . _DB_TABLE_DELETE.'  <a href="/'.ADMIN.'/db" >'. _DB_BE_BACK .'</a>');
		$adminTpl->admin_foot();
		break;
		
	case 'truncate':
		$table = $url[3];
		
		if(eregStrt(DB_PREFIX, $table))
		{
			$db->query("TRUNCATE TABLE `" . $table . "`");
		}
		
		$adminTpl->admin_head(_DB_DB .' | '. _DB_CLEAR);	
		$adminTpl->info(_DB_TABLE . $table . _DB_CLEAR_OK .' <a href="/'.ADMIN.'/db/">'. _DB_BE_BACK .'</a>');
		$adminTpl->admin_foot();
		break;
		
	case 'delBackup':
		$file = $url[3];
		unlink('tmp/' . $file . '.sql.gz');
		location(ADMIN.'/db/backup');
		break;	
		
	case 'doFix':
		$lstart = isset($_POST['lstart']) ? intval($_POST['lstart']) : (isset($url[3]) ? intval($url[3]) : '');
		$lend = isset($_POST['lend']) ? intval($_POST['lend']) : (isset($url[4]) ? intval($url[4]) : '');
		$i = 0;
		$board = $db->query("SELECT * FROM " . DB_PREFIX . "_board_posts");
		while($boab = $db->getRow($board)) 
		{
			$messag2e = parseBB(html2bb(processText($boab['message'])));
			if(!empty($messag2e))
			{
				$db->query("UPDATE `" . DB_PREFIX . "_board_posts` SET `message` = '" . $db->safesql($messag2e) . "' WHERE `id` ='" . $boab['id'] . "' LIMIT 1 ;");
			}
			else
			{
				write111($boab['id'], 'board');
			}
		}	
		$langs = $db->query("SELECT * FROM " . DB_PREFIX . "_langs");
		while($lan = $db->getRow($langs)) 
		{
			$short = parseBB(html2bb(processText($lan['short'])), false, true);
			$full = parseBB(html2bb(processText($lan['full'])), false, true);
			if(!empty($short))
			{
				$db->query("UPDATE `" . DB_PREFIX . "_langs` SET `short` = '" . $db->safesql($short) . "', `full` = '" . $db->safesql($full) . "' WHERE `_id` ='" . $lan['_id'] . "' LIMIT 1 ;");
			}
			else
			{
				write111($lan['_id'], 'news');
			}
		}			
		$comments = $db->query("SELECT * FROM " . DB_PREFIX . "_comments");
		while($comm = $db->getRow($comments)) 
		{
			$text = parseBB(html2bb(processText($comm['text'])));
			if(!empty($text))
			{
				$db->query("UPDATE `" . DB_PREFIX . "_comments` SET `text` = '" . $db->safesql($text) . "' WHERE `id` ='" . $comm['id'] . "' LIMIT 1 ;");
			}
			else
			{
				write111($comm['id'], 'comm');
			}
		}
		$pm = $db->query("SELECT * FROM " . DB_PREFIX . "_pm");
		while($ppm = $db->getRow($pm)) 
		{
			$message = parseBB(html2bb(processText($ppm['message'])));
			if(!empty($message))
			{
				$db->query("UPDATE `" . DB_PREFIX . "_pm` SET `message` = '" . $db->safesql($message) . "' WHERE `id` ='" . $ppm['id'] . "' LIMIT 1 ;");
			}
			else
			{
				write111($ppm['id'], 'pm');
			}
		}		
		$blog = $db->query("SELECT * FROM " . DB_PREFIX . "_blog_posts");
		while($blb = $db->getRow($blog)) 
		{
			$text = parseBB(html2bb(processText($blb['text'])));
			if(!empty($text))
			{
				$db->query("UPDATE `" . DB_PREFIX . "_blog_posts` SET `text` = '" . $db->safesql($text) . "' WHERE `id` ='" . $blb['id'] . "' LIMIT 1 ;");
			}
			else
			{
				write111($blb['id'], 'blog');
			}
		}		
		$users = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users`");
		while($usr = $db->getRow($users)) 
		{
			$signature = parseBB(html2bb(processText($usr['signature'])));
			if(!empty($signature))
			{
				$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `signature` = '" . $db->safesql($signature) . "' WHERE `id` ='" . $usr['id'] . "' LIMIT 1 ;");
			}
			else
			{
				write111($usr['id'], 'usr');
			}
		}		
		location(ADMIN.'/db/fix/ok');
		break;		

}

function write111($id, $type) 
{
    $logPath = ROOT . 'tmp/errors.log';
    if (file_exists($logPath)) 
	{
        $data = unserialize(@file_get_contents($logPath));
    }
    $data[] = array('type' => $type, 'id' => $id);
    $data = serialize($data);
    $fp = @fopen($logPath, 'w');
    fwrite($fp, $data);
    fclose($fp);
}