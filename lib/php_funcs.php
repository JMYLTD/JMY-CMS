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

function is_utf8($string) 
{ 
    return preg_match('%^(?: 
          [\x09\x0A\x0D\x20-\x7E]            # ASCII 
        | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte 
        |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs 
        | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte 
        |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates 
        |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3 
        | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15 
        |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16 
    )*$%xs', $string); 

}

function listFiles($from = '.', $type = '') {
	if(!is_dir(ROOT . $from))
		return false;

	$files = array();
	$dir = $from;
	$dirs = array($dir);
	
	while( NULL !== ($dir = array_pop( $dirs))) {
		if($dh = opendir(ROOT . $dir)) {
			while( false !== ($file = readdir($dh))) {
				if( $file == '.' || $file == '..')
				continue;
				$path = $dir . $file;
				if(is_dir($path))
					$dirs[] = $path.'/';
				elseif($type == '' || eregStrt($type, $file))
					$files[] = $path;
			}
			
			closedir($dh);
		}
	}
	
	return $files;
}
        function escape($str)
        {
                $search=array("\\","\0","\n","\r","\x1a","'",'"');
                $replace=array("\\\\","\\0","\\n","\\r","\Z","\'",'\"');
                return str_replace($search,$replace,$str);
        }

function in_array_like($referencia, $array) { 
	foreach($array as $ref) { 
		if (strstr($referencia,$ref)) return true; 
	} 
	return false; 
}

function cyr_strtolower($text) {
	$offset = 32;
	$letters = array();
	for($i = 192; $i < 224; $i++) $letters[chr($i)] = chr($i+$offset);
	return strtr($text, $letters);
}

function is_ruUTF8($str) {
    return !preg_replace(
        '#[\x00-\x7F]|\xD0[\x81\x90-\xBF]|\xD1[\x91\x80-\x8F]#s',
      '',
      $str
    );
}

function utf_decode($str) {
    /*static $table = array("\xD0\x81" => "\xA8", "\xD1\x91" => "\xB8",);
    return preg_replace('#([\xD0-\xD1])([\x80-\xBF])#se', 'isset($table["$0"]) ? $table["$0"] : chr(ord("$2")+("$1" == "\xD0" ? 0x30 : 0x70))', $str);*/
	return $str;
}

function u8($str)
{
static $table = array("\xD0\x81" => "\xA8", "\xD1\x91" => "\xB8",);
    return preg_replace('#([\xD0-\xD1])([\x80-\xBF])#se', 'isset($table["$0"]) ? $table["$0"] : chr(ord("$2")+("$1" == "\xD0" ? 0x30 : 0x70))', $str);
}

function getRealIpAddr() {
	if (!empty($_SERVER['HTTP_CLIENT_IP']))
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else
		$ip = $_SERVER['REMOTE_ADDR'];

	if(strpos($ip, ',')) {
		$e = explode(',', $ip);
		$ip = $e[0];
	}
	
	return filter($ip, 'ip');
}

function ext_str_ireplace($findme, $replacewith, $subject) {
	return mb_substr($subject, 0, stripos($subject, $findme)).str_replace('$1', mb_substr($subject, stripos($subject, $findme), mb_strlen($findme)), $replacewith).mb_substr($subject, stripos($subject, $findme)+mb_strlen($findme));
}


function full_rmdir($directory, $empty = false) { 
    if(substr($directory,-1) == "/") { 
        $directory = substr($directory,0,-1); 
    } 

    if(!file_exists($directory) || !is_dir($directory)) { 
        return false; 
    } elseif(!is_readable($directory)) { 
        return false; 
    } else { 
        $directoryHandle = opendir($directory); 
        
        while ($contents = readdir($directoryHandle)) { 
            if($contents != '.' && $contents != '..') { 
                $path = $directory . "/" . $contents; 
                
                if(is_dir($path)) { 
                    full_rmdir($path); 
                } else { 
                    unlink($path); 
                } 
            } 
        } 
        
        closedir($directoryHandle); 

        if($empty == false) { 
            if(!rmdir($directory)) { 
                return false; 
            } 
        } 
        
        return true; 
    } 
}

function Only1br($string, $replace = '<br />')
{
    return preg_replace("/(\r\n)+|(\n|\r)+/", $replace, $string);
}

function eregStrt($what, $where)
{
	return preg_match("@$what@i", $where);
}

function unlinkRecursive($dir, $deleteRootToo = false) {
	if(!$dh = @opendir($dir)) return; 
	while (false !== ($obj = readdir($dh))) {
		if($obj == '.' || $obj == '..') continue; 
		if (!@unlink($dir . '/' . $obj)) unlinkRecursive($dir.'/'.$obj, true); 
	} 
	closedir($dh); 
	if ($deleteRootToo) @rmdir($dir); 
	return; 
}
function chmod_R($path, $perm) {
  $handle = opendir($path);
  while ( false !== ($file = readdir($handle)) ) {
    if ( ($file !== "..") ) {
      @chmod($path . "/" . $file, $perm);
      if ( !is_file($path."/".$file) && ($file !== ".") )
        chmod_R($path . "/" . $file, $perm);
    }
  }
  closedir($handle);
}

function showText($text)
{
	return stripslashes($text);
}

function mysql_escape($str)
{
	return $str;
}

?>