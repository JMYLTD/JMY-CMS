<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @outside     Youshi
*/
 
if (!defined('ACCESS')) {
    header('Location: /');
    exit;
}
define('NOW_TIME', time());
 
class dateBase extends controlDB 
{
    var $prefix = '';	
	private $dir = 'tmp/mysql';
	private $index_file = 'index';
	private $index = array('drop' => array());
	private $cached = array();
	private $pointer = array();
	private $hashDrop = array();
	private $flush_by = array('INSERT', 'UPDATE', 'DELETE', 'REPLACE', 'ALTER');
	private $real_time_tables = array('online', 'comments', 'plugins', 'logs', 'reffers');
	private $real_time_func = array('NOW', 'UNIX_TIMESTAMP');
	private $no_cache_timeout = 300;

    function __construct() 
	{
        require_once ROOT . 'etc/db.config.php';
		parent::connect($dbhost, $dbuser, $dbpass, $dbname);		
		define("DB_PREFIX", $prefix); 
		define("USER_PREFIX", $user_prefix);
		define("USER_DB", $user_db);		
		if (!is_dir(ROOT.'tmp/mysql/')) {mkdir(ROOT.'tmp/mysql/', 0777); @chmod_R(ROOT.'tmp/mysql/', 0777);}		
		$this->dir = ROOT.'tmp/mysql/';		
		if (file_exists($this->dir.$this->index_file)) include_once($this->dir.$this->index_file);
		unset($dbhost, $dbuser, $dbpass, $prefix, $user_prefix, $user_db);
    }
	
	function __destruct()
	{
		if (sizeof($this->index['drop']) > 0)
		{
			$this->index['drop'] = array_unique($this->index['drop']);
			foreach ($this->index['drop'] AS $hash)
			{
				unset($this->index['lifetime'][$hash]);
				@unlink($this->dir.$hash);
			}
			$this->index['drop'] = array();
		}
		
		$index = '<?php $this->index='.$this->arr2str($this->index).'; ?>';
		$this->write_file($this->index_file, $index);
	}
	

    function query($query) 
	{
		$hash = md5 (preg_replace('~([0-9]{10})~', '', $query));
		$is_realtime = preg_match('~([0-9]{10})~', $query);
		$is_cached = false;
		$do = trim(strtoupper(mb_substr($query, 0, strpos($query, ' '))));
		if (!isset($this->cached[$hash]))
		{
			if ($do == 'SELECT' or $do == 'DO')
			{
				preg_match_all('~' . $this->prefix . '_([^\s`]+)~i', $query, $match);
				$tables =& $match[1]; unset ($match);
				
				if (sizeof($tables) > 0 && sizeof(array_intersect($tables, $this->real_time_tables)) == 0)
				{
					if (in_array($hash, $this->index['drop']) or !file_exists($this->dir.$hash))
					{
						foreach ($tables AS $table)
						{
							$this->index['links'][$table][$hash] = array_diff($tables, array($table));
						}
						$this->index['times'][$hash] = NOW_TIME;
					}
					else
					{
						$modified =& $this->index['times'][$hash];
						if ($is_realtime or preg_match('~('.implode('|', $this->real_time_func).')~i', $query))
						{		
							if ($this->no_cache_timeout && (NOW_TIME - $modified) < $this->no_cache_timeout) $is_cached = true;
							else
							{
								$this->index['times'][$hash] = NOW_TIME;
							}
						}
						else $is_cached = true;
					}
					if ($is_cached) $this->readcache($hash);
					else
					{
						$mysql = parent::doQuery($query, false, $this->prefix);						
						if ($mysql)
						{
							while ($row = parent::getRow($mysql)) $this->cached[$hash]['data'][] = $row;
							$this->cached[$hash]['sizeof'] = parent::numRows($mysql);
							if (!$this->cached[$hash]['sizeof']) $this->cached[$hash]['sizeof'] = 0;
							$this->pointer[$hash] = 0;
							$this->iQuery_id = $hash;
							$this->write_cache($hash);
						}
					}
					return $hash;
				}
			}
			
		
			if (in_array($do, $this->flush_by))
			{					
				if (preg_match('~' . $this->prefix . '_([^\s`]+)~i', $query, $match))
				{		
					$table =& $match[1]; unset ($match);				
					if (isset($this->index['links'][$table]))
					{
						foreach ($this->index['links'][$table] AS $h => $link)
						{
							if(!isset($this->hashDrop[$h]))
							{
								foreach ($link AS $tbl)
								{
									unset($this->index['links'][$tbl][$h]);
								}
								$this->index['drop'][] = $h;
								$this->hashDrop[$h] = true;
							}
						}
					}
				}
			}
			return parent::doQuery($query, false, $this->prefix);
		}
		return $hash;

    }

	function arr2str (&$arr, $depth = 0)
	{
			$ret = array();
			if (is_array($arr) && sizeof($arr) > 0)
			{
					foreach ($arr AS $key => $value)
					{
							$key = str_replace("'", "\'", $key);
							if (is_array($value)) $ret[] = "'{$key}'=>".$this->arr2str($value, $depth+1);
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

    function freeResult($resource) 
	{
		return parent::freeResult($resource);
    }
	
	function &getRow($query_id)
	{
		$ret = false;
		if (isset($this->cached[$query_id]))
		{
			if ($this->pointer[$query_id] < $this->cached[$query_id]['sizeof'])
			{
				$ret = $this->cached[$query_id]['data'][$this->pointer[$query_id]];
				$this->pointer[$query_id]++;
			}
		}
		else $ret = parent::getRow($query_id);
		
		return $ret;
	}
	
    function fetchRow($query_id) 
	{
		$ret =& $this->fetch_assoc($query_id);
		return $ret ? array_values($ret) : $ret;
    }
	
	function &fetch_assoc ($query_id = -1)
	{
		$ret = false;
		if (isset($this->cached[$query_id]))
		{
			if ($this->pointer[$query_id] < $this->cached[$query_id]['sizeof'])
			{
				$ret = $this->cached[$query_id]['data'][$this->pointer[$query_id]];
				$this->pointer[$query_id]++;
			}
		}
		elseif ($query_id != -1) $ret = parent::getRow($query_id);
		return $ret;
	}
	
	function numRows($query_id)
	{
		if (isset($this->cached[$query_id])) return $this->cached[$query_id]['sizeof'];
		else return parent::numRows($query_id);
	}
	

	private function readcache ($hash)
	{
		include_once($this->dir.$hash);
		$this->pointer[$hash] = 0;
		$this->iQuery_id = $hash;
	}
	
	private function write_cache ($hash)
	{
		$data = "<?php \$this->cached['$hash']=".$this->arr2str($this->cached[$hash]).'; ?>';
		$this->write_file($hash, $data);
	}
	
	private function write_file($filename, &$content)
	{
		ignore_user_abort(1);
		$lockfile = $this->dir.$filename . '.lock';
		if (file_exists($lockfile) && (time() - filemtime($lockfile)) > 5) @unlink($lockfile);
		$lock_ex = @fopen($lockfile, 'x');
		for ($i=0; ($lock_ex === false) && ($i < 20); $i++)
		{
			clearstatcache();
			usleep(rand(5, 15));
			$lock_ex = @fopen($lockfile, 'x');
		}
		
		$success = false;
		if ($lock_ex !== false)
		{
			$fp = @fopen($this->dir.$filename, 'wb');
			if (@fwrite($fp, $content)) $success = true;
			@fclose($fp);
			fclose($lock_ex);
			@unlink($lockfile);
		}
		ignore_user_abort(0);
		return $success;
	}
}
$db = new dateBase();