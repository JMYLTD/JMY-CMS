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
		if( function_exists( 'eaccelerator_gc' ) )
		{
			eaccelerator_gc();
		}
		
		return TRUE;
	}
	
	
	function do_put( $key, $value, $ttl=0 )
	{
		eaccelerator_lock($this->identifier . md5( $key ) );
		
		eaccelerator_put($this->identifier . md5( $key ),
							$value,
							intval($ttl) );
							
		eaccelerator_unlock($this->identifier . md5( $key ) );
	}
	
	function do_get( $key )
	{
		$return_val = eaccelerator_get($this->identifier . md5( $key ) );

		return $return_val;
	}
	
	function do_remove( $key )
	{
		eaccelerator_rm($this->identifier . md5( $key ) );
	}
}
?>