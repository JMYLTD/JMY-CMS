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
	var $link;
	
	
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
	
	
	function connect( $server_info=array() )
	{
		if( !count($server_info) )
		{
			$this->crashed = 1;
			return FALSE;
		}
		
		if( !isset($server_info['memcache_server_1']) OR !isset($server_info['memcache_port_1']) )
		{
			$this->crashed = 1;
			return FALSE;
		}
		
		$this->link = memcache_connect( $server_info['memcache_server_1'], $server_info['memcache_port_1'] );
		
		if( !$this->link )
		{
			$this->crashed = 1;
			return FALSE;
		}
		
		if( isset($server_info['memcache_server_2']) AND isset($server_info['memcache_port_2']) )
		{
			memcache_add_server( $this->link, $server_info['memcache_server_2'], $server_info['memcache_port_2'] );
		}
		
		if( isset($server_info['memcache_server_3']) AND isset($server_info['memcache_port_3']) )
		{
			memcache_add_server( $this->link, $server_info['memcache_server_3'], $server_info['memcache_port_3'] );
		}
		
		if( function_exists('memcache_set_compress_threshold') )
		{
			memcache_set_compress_threshold( $this->link, 20000, 0.2 );
		}
		
		return TRUE;
	}
	
	
	function disconnect()
	{
		if( $this->link )
		{
			memcache_close( $this->link );
		}
		
		return TRUE;
	}
	
	
	function do_put( $key, $value, $ttl=0 )
	{
		memcache_set( $this->link,$this->identifier . md5( $key ),
							$value,
							MEMCACHE_COMPRESSED,
							intval($ttl) );
	}
	
	function do_get( $key )
	{
		$return_val = memcache_get( $this->link, $this->identifier . md5( $key ) );

		return $return_val;
	}
	
	function do_remove( $key )
	{
		memcache_delete( $this->link,$this->identifier . md5( $key ) );
	}
}
?>