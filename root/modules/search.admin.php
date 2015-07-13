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
global $config, $url, $db, $core, $errorClass;
$adminTpl->admin_head(_AP_SEARCH);
$adminTpl->open();
$search_word = mb_strtoupper(filter($_POST['search']));
$i=0;
if (empty($search_word))
	{
		$adminTpl->info(_SEARCH_ERROR_0, 'error');
	}
else
	{
		echo '
		<section class="panel bg-none">
			<div class="panel-group" id="accordion">';
		$flag=false;
		foreach ($component_array as $key => $value)
		{
			foreach ($value as $key1 => $value1) 
			{			
				if ($key1=='name')
					{						
						$str = mb_strtoupper($value1);
						$pos = strripos($str, $search_word);
						$name = $value1; 
						if ($pos === false)
						{						} 
						else 
						{
							$flag=true;
						}						
					}
				if ($key1=='desc')
					{
						$str = mb_strtoupper($value1);
						$pos = strripos($str, $search_word);
						$desc = $value1;
						if ($pos === false)
						{						} 
						else 
						{
							$flag=true;
						}						
					}
			}
			if ($flag === true)	
			{
				$i++;
				$flag=false;
				echo '	
				<div class="panel">
					<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" class="collapsed">'.$name.'</a> - <a href="'.ADMIN.'/'.$key.'" class="collapsed">'._SEARCH_REF.'</a>
					</div>
					<div id="collapse'.$i.'" class="panel-collapse collapse" style="height: 0px;">
						<div class="panel-body">'.$desc.'</div>
					</div>
				</div>';
			}
		}
		foreach ($services_array as $key => $value)
		{
			foreach ($value as $key1 => $value1) 
			{			
				if ($key1=='name')
					{						
						$str = mb_strtoupper($value1);
						$pos = strripos($str, $search_word);
						$name = $value1; 
						if ($pos === false)
						{						} 
						else 
						{
							$flag=true;
						}						
					}
				if ($key1=='desc')
					{
						$str = mb_strtoupper($value1);
						$pos = strripos($str, $search_word);
						$desc = $value1;
						if ($pos === false)
						{						} 
						else 
						{
							$flag=true;
						}						
					}
			}
			if ($flag === true)	
			{
				$i++;
				$flag=false;
				echo '	
				<div class="panel">
					<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" class="collapsed">'.$name.'</a> - <a href="'.ADMIN.'/'.$key.'" class="collapsed">'._SEARCH_REF.'</a>
					</div>
					<div id="collapse'.$i.'" class="panel-collapse collapse" style="height: 0px;">
						<div class="panel-body">'.$desc.'</div>
					</div>
				</div>';
			}
		}
		foreach(glob(ROOT.'usr/modules/*/admin/list.php') as $file) include($file);
		foreach ($module_array as $key => $value)
		{
			foreach ($value as $key1 => $value1) 
			{			
				if ($key1=='name')
					{						
						$str = mb_strtoupper($value1);
						$pos = strripos($str, $search_word);
						$name = $value1; 
						if ($pos === false)
						{						} 
						else 
						{
							$flag=true;
						}						
					}
				if ($key1=='desc')
					{
						$str = mb_strtoupper($value1);
						$pos = strripos($str, $search_word);
						$desc = $value1;
						if ($pos === false)
						{						} 
						else 
						{
							$flag=true;
						}						
					}
			}
			if ($flag === true)	
			{
				$i++;
				$flag=false;
				echo '	
				<div class="panel">
					<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse'.$i.'" class="collapsed">'.$name.'</a> - <a href="'.ADMIN.'/module/'.$key.'" class="collapsed">'._SEARCH_REF.'</a>
					</div>
					<div id="collapse'.$i.'" class="panel-collapse collapse" style="height: 0px;">
						<div class="panel-body">'.$desc.'</div>
					</div>
				</div>';
			}
		}
		if ($i==0)
		{
			echo '<div class="panel">
					<div class="panel-heading">
						<a data-toggle="collapse" data-parent="#accordion" class="collapsed">'._SEARCH_ERROR_1.'</a>
					</div>					
				</div>';
		}
		echo '</div></section>';
		}
$adminTpl->close();
$adminTpl->admin_foot();
?>