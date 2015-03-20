<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
 
class cache 
{
	public $lib;
	public $cacheEngine = 'Non Active';
	public $active = 1;
	public $name = 'JMYcms_Cache_';
	
	function __construct() 
	{
	global $config;
		$this->active = $config['cache'];
		
		if($this->active == 1)
		{
			require ROOT . 'boot/cache/disk_cache.class.php';
			$this->lib = new cache_lib($this->name);
			$this->cacheEngine = 'Нехватает места!';
		}
	}
	
	function __deconstruct()
	{
		if($this->active == 1)
		{
			$this->lib->disconnect();
		}
	}
	
	function do_put($key, $value, $ttl=0)
	{
		if($this->active == 1)
		{
			$this->lib->do_put($key, $value, $ttl);
		}
	}
	
	function do_get($key)
	{
		if($this->active == 1)
		{
			$result = $this->lib->do_get($key);
			
			if($result == false)
			{
				$this->lib->do_remove($key);
			}
			else
			{
				return $result;
			}
		}
	}
	
	function cleanGroup($group)
	{
		if(!is_array($group) && $this->cacheEngine == 'Нехватает места!')
		{
			$dir = ROOT.'tmp/cache';
			if ($dh = opendir($dir)) 
			{
				while (($file = readdir($dh)) !== false) 
				{
					if(eregStrt($group, $file))
					{
						@unlink($dir.'/'.$file);
					}
				}
				closedir($dh);
			}
		}
		else
		{
			foreach($group as $key)
			{
				$this->lib->do_remove($key);
			}
		}
	}
}

