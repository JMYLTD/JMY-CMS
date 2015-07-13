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

function main() {
	global $adminTpl, $config, $core, $configs, $clear;
}
switch(isset($url[2]) ? $url[2] : null) {
	default:
		if(isset($url[2]) && $url[2] == 'ok') 
		{
		$adminTpl->admin_head(_LOG_OK_COM);
		$adminTpl->info(_LOG_OK_CLEAR);
		$adminTpl->admin_foot();
		}
		else
		{
		$adminTpl->admin_head(_LOG_LOG);
		$adminTpl->open();		
		
		echo '
		
		<link href="usr/plugins/filemanager/css/jquery-ui.css" rel="stylesheet" type="text/css" media="screen" />
		<script src="usr/plugins/filemanager/jquery.min.js" type="text/javascript"></script>
        <script src="usr/plugins/filemanager/jquery-ui-min.js" type="text/javascript"></script>
		<script src="usr/plugins/filemanager/gsFileManager.js" type="text/javascript"></script>
		<script src="usr/plugins/filemanager/jquery.form.js" type="text/javascript"></script>
		<script src="usr/plugins/filemanager/jquery.Jcrop.js" type="text/javascript"></script>
	
		<link href="usr/plugins/filemanager/gsFileManager.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="usr/plugins/filemanager/jquery.Jcrop.css" rel="stylesheet" type="text/css" media="screen" />
		
	
		<script type="text/javascript">
			
			$(document).ready( function() {
				
				jQuery(\'#fileTreeDemo_1\').gsFileManager({ script: \'usr/plugins/filemanager/GsFileManager.php\' });
				
			});
		</script>

	
	    <a href="../" title="Free Web File Manager">Back to Free Web File Manager</a>
	    <div style="height: 16px; line-height: 16px">&nbsp;</div>
        <div id="fileTreeDemo_1" class="demoto">
        
        </div>
        	
	';
		
		
		$adminTpl->close();
		$adminTpl->admin_foot();
		}
		break;	
	
	case "clear":
		foreach(glob(ROOT.'tmp/*.log') as $file) @unlink($file);		
		location(ADMIN.'/log/ok');
		break;
}