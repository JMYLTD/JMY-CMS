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
	var $crashed = 0;	
	
	function cache_lib( $identifier='' )
	{
		if( !is_writeable( ROOT.'tmp/cache' ) )
		{
			$this->crashed = 1;
			return FALSE;
		}
		
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
			
		$fh = fopen( ROOT.'tmp/cache/'.$this->identifier . md5( $key ).'.cache', 'wb' );		
		if( !$fh )
		{
			return FALSE;
		}		
		$extra_flag = "";		
		if( is_array( $value ) )
		{
			$value = serialize($value);
			$extra_flag = "\n".'$is_array = 1;'."\n\n";
		}		
		$extra_flag .= "\n".'$ttl = '.$ttl.";\n\n";		
		$value = '"'.addslashes( $value ).'"';		
		$file_content = "<?"."php\n\n".'$value = '.$value.";\n".$extra_flag."\n?".'>';		
		flock( $fh, LOCK_EX );
		fwrite( $fh, $file_content );
		flock( $fh, LOCK_UN );
		fclose( $fh );		
		@chmod( ROOT.'tmp/cache/'.$this->identifier . md5( $key ).'.cache', 0777 );
	}
	
	function do_get( $key )
	{
		$return_val = "";		
		if( file_exists( ROOT.'tmp/cache/'.$this->identifier . md5( $key ).'.cache' ) )
		{
			require ROOT.'tmp/cache/'.$this->identifier . md5( $key ).'.cache';			
			$return_val = stripslashes($value);
			if( isset($is_array) AND $is_array == 1 )
			{
				$return_val = unserialize($return_val);
			}
			
			if( isset($ttl) AND $ttl > 0 )
			{
				if( $mtime = filemtime( ROOT.'tmp/cache/'.$this->identifier . md5( $key ).'.cache' ) )
				{
					if( time() - $mtime > $ttl )
					{
						return FALSE;
					}
				}
			}
		}

		return $return_val;
	}
	
	function do_remove( $key )
	{
		if( file_exists( ROOT.'tmp/cache/'.$this->identifier . md5( $key ).'.cache' ) )
		{
			@unlink( ROOT.'tmp/cache/'.$this->identifier . md5( $key ).'.cache' );
		}
	}
}
?>