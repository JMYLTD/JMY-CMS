<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

class cache_lib
{
	var $identifier;

	function cache_lib( $identifier='' )
	{
	
		if( !$identifier )
		{
			$this->identifier = md5( uniqid( rand(), TRUE ) );
		}
		else
		{
			$this->identifier = $identifier;
		}
		
		unset( $identifier );
		
	}
	
	
	function disconnect()
	{
		return TRUE;
	}
	
	
	function do_put( $key, $value, $ttl=0 )
	{
		$ttl = $ttl > 0 ? intval($ttl) : '';
		
		if( $ttl )
		{
			xcache_set($this->identifier . md5( $key ),
							$value,
							$ttl );
		}
		else
		{
			xcache_set($this->identifier . md5( $key ),
				$value );
		}
	}
	
	function do_get( $key )
	{
		$return_val = "";
		
		if( xcache_isset($this->identifier . md5( $key ) ) )
		{
			$return_val = xcache_get($this->identifier . md5( $key ) );
		}
		
		return $return_val;
	}
	
	function do_remove( $key )
	{
		xcache_unset($this->identifier . md5( $key ) );
	}
}
?>