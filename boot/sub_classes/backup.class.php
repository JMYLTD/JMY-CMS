<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
* @outside     MySQL DB backup class, version 1.0.1
*/

define('MSX_VERSION', '1.0.1');
define('MSX_NL', "\n");
define('MSX_STRING', 0);
define('MSX_DOWNLOAD', 1);
define('MSX_SAVE', 2);
define('MSX_APPEND', 3);


class MySQL_DB_Backup
{

	var $server = 'localhost';
	var $port = 3306;
	var $username = 'root';
	var $password = '';
	var $database = '';
	var $link_id = -1;
	var $connected = false;
	var $tables = array();
	var $create_tables = true;
	var $drop_tables = true;
	var $struct_only = false;
	var $locks = true;
	var $comments = true;
	var $backup_dir = '';
	var $fname_format = 'd_m_y__H_i_s';
	var $error = '';
	var $null_values = array( '0000-00-00', '00:00:00', '0000-00-00 00:00:00');

	function Execute($task = MSX_STRING, $dname = '', $compress = false)
	{
		$fp = false;
		if ($task == MSX_APPEND || $task == MSX_SAVE || $task == MSX_DOWNLOAD)
		{
			$tmp_name = $dname;
			if (empty($tmp_name) || $task == MSX_DOWNLOAD)
			{
				$tmp_name = date($this->fname_format);
				$tmp_name .= ($compress ? '.sql.gz' : '.sql');
				if (empty($dname))				
				{
					$dname = $tmp_name;
				}
			}
			$fname = $this->backup_dir.$tmp_name;
			if (!($fp = $this->_OpenFile($fname, $task, $compress)))
			{
				return false;
			}
		}
		if (!($sql = $this->_Retrieve($fp, $compress)))
		{
			return false;
		}

		if ($task == MSX_DOWNLOAD)
		{
			$this->_CloseFile($fp, $compress);
			return $this->_DownloadFile($fname, $dname);
		}
		else if ($task == MSX_APPEND || $task == MSX_SAVE)
		{
			$this->_CloseFile($fp, $compress);
			return true;
		}
		else
		{
			return $sql;
		}
	}


	function _Connect()
	{
		$value = false;
		if (!$this->connected)
		{
			if (!$this->server) $this->server = 'localhost';
			$host = $this->server;
			if ($this->port) $host .= ':' . $this->port;
			$this->link_id = mysql_connect($host, $this->username, $this->password);
		}
		if ($this->link_id > 0)
		{
			if (empty($this->database))
			{
				$value = true;
			}
			elseif ($this->link_id !== -1)
			{
				$value = mysql_select_db($this->database, $this->link_id);
			}
			else
			{
				$value = mysql_select_db($this->database);
			}
		}
		if (!$value)
		{
			$this->error = mysql_error();
		}
		return $value;
	}

	function _Query($sql)
	{
		if ($this->link_id !== -1)
		{
			$result = mysql_query($sql, $this->link_id);
		}
		else
		{
			$result = mysql_query($sql);
		}
		if (!$result)
		{
			$this->error = mysql_error();
		}
		return $result;
	}

	function _GetTables()
	{
		$value = array();
		if (!($result = $this->_Query('SHOW TABLES')))
		{
			return false;
		}
		while ($row = mysql_fetch_row($result))
		{
			if (empty($this->tables) || in_array($row[0], $this->tables))
			{
				$value[] = $row[0];
			}
		}
		if (!sizeof($value))
		{
			$this->error = 'Таблицы в базе данных не найдены!';
			return false;
		}
		return $value;
	}

	function _DumpTable($table, $fp, $compress)
	{
		$value = '';
		$this->_Query('LOCK TABLES ' . $table . ' WRITE');
		if ($this->create_tables)
		{
			if ($this->comments)
			{
				$value .= '# ' . MSX_NL;
				$value .= '# Table structure for table `' . $table . '`' . MSX_NL;
				$value .= '# ' . MSX_NL . MSX_NL;
			}
			if ($this->drop_tables)
			{
				$value .= 'DROP TABLE IF EXISTS `' . $table . '`;' . MSX_NL;
			}
			if (!($result = $this->_Query('SHOW CREATE TABLE ' . $table)))
			{
				return false;
			}
			$row = mysql_fetch_assoc($result);
			$value .= str_replace("\n", MSX_NL, $row['Create Table']) . ';';
			$value .= MSX_NL . MSX_NL;
		}
		if (!$this->struct_only)
		{
			if ($this->comments)
			{
				$value .= '# ' . MSX_NL;
				$value .= '# Dumping data for table `' . $table . '`' . MSX_NL;
				$value .= '# ' . MSX_NL . MSX_NL;
			}
			if ($fp)
			{
				if ($compress) gzwrite($fp, $value);
				else fwrite ($fp, $value);
				$value = '';
			}
			$value .= $this->_GetInserts($table,$fp,$compress);
		}
		$value .= MSX_NL . MSX_NL;
		if ($fp)
		{
			if ($compress) gzwrite($fp, $value);
			else fwrite ($fp, $value);
			$value = true;
		}
		$this->_Query('UNLOCK TABLES');
		return $value;
	}

	function _GetInserts($table, $fp, $compress)
	{
		$value = '';
		if (!($result = $this->_Query('SELECT * FROM ' . $table)))
		{
			return false;
		}
		$num_rows = mysql_num_rows($result);
		if ($num_rows == 0)
		{
			return $value;
		}
		$insert = 'INSERT INTO `' . $table . '`';
		$row = mysql_fetch_assoc($result);
		$insert .= ' (`' . implode('`,`', array_keys($row)) . '`)';
		$insert .= ' VALUES ';
			
		$fields = count($row);
		mysql_data_seek($result, 0);
		
		if ($this->locks)
		{
			$value .= 'LOCK TABLES ' . $table . ' WRITE;' . MSX_NL;
		}
		$value .= $insert;
		if ($fp)
		{
			if ($compress) gzwrite($fp, $value);
			else fwrite ($fp, $value);
			$value = '';
		}
		
		$j=0;
		$size = 0;
		while ($row = mysql_fetch_row($result))
		{
			if ($fp)
			{
				$i = 0;
				$value = true;
				if ($compress) { $size += gzwrite($fp, '('); }
				else { $size += fwrite ($fp, '('); }
				for($x =0; $x < $fields; $x++)
				{
					if (!isset($row[$x]) || in_array($row[$x], $this->null_values))
					{
						$row[$x] = 'NULL';
					}
					else 
					{
						$row[$x] = '\'' . str_replace("\n","\\n",addslashes($row[$x])) . '\'';
					}
					if ($i > 0)
					{
						if ($compress) { $size += gzwrite($fp, ','); }
						else { $size += fwrite ($fp, ','); }
					}

					if ($compress) { $size += gzwrite($fp, $row[$x]); }
					else { $size += fwrite ($fp,  $row[$x]); }

					$i++;
				}
				if ($compress) { $size += gzwrite($fp, ')'); }
				else { $size += fwrite ($fp, ')'); }

				if ($j+1 < $num_rows && $size < 900000 )
				{
					if ($compress) { $size += gzwrite($fp, ','); }
					else { $size += fwrite ($fp, ','); }
				}
				else
				{
					$size = 0;
					if ($compress) gzwrite($fp, ';' . MSX_NL);
					else fwrite ($fp, ';' . MSX_NL);
					
					if ($j+1 < $num_rows)
					{
						if ($compress) gzwrite($fp, $insert);
						else fwrite ($fp, $insert);
					}
					else if ($this->locks)
					{
						if ($compress) gzwrite($fp, 'UNLOCK TABLES;' . MSX_NL);
						else fwrite ($fp, 'UNLOCK TABLES;' . MSX_NL);
					}
				}
				unset ($value);
				$value = '';
			}
			else
			{
				$values = '(';
				for($x =0; $x < $fields; $x++)
				{
					if (!isset($row[$x]) || in_array($row[$x], $this->null_values))
					{
						$row[$x] = 'NULL';
					}
					else 
					{
						$row[$x] = '\'' . str_replace("\n","\\n",addslashes($row[$x])) . '\'';
					}
					$values .= $row[$x] . ',';
				}
				$values = substr($values, 0, -1). '),';
				if ($j+1 == $num_rows || ($j+1)%5000==0 )
				{
					$values = substr($values, 0, -1);
					$values = $values . ';' . MSX_NL;
					if ($j+1 < $num_rows)
					{
						$values .= $insert;
					}
					else
					{
						if ($this->locks)
						{
							$values .= 'UNLOCK TABLES;' . MSX_NL;
						}
						$values .= MSX_NL;
					}
				}
				$value .= $values;
			}
			$j++;
			unset ($row);
		}
		
		return $value;
	}

	function _Retrieve($fp, $compress)
	{
		$value = '';
		if (!$this->_Connect())
		{
			return false;
		}
		if ($this->comments)
		{
			$value .= '# ' . MSX_NL;
			$value .= '# MySQL database dump' . MSX_NL;
			$value .= '# Created by MySQL_Backup class, ver. ' . MSX_VERSION . MSX_NL;
			$value .= '# ' . MSX_NL;
			$value .= '# Host: ' . $this->server . MSX_NL;
			$value .= '# Generated: ' . date('M j, Y') . ' at ' . date('H:i') . MSX_NL;
			$value .= '# MySQL version: ' . mysql_get_server_info() . MSX_NL;
			$value .= '# PHP version: ' . phpversion() . MSX_NL;
			if (!empty($this->database))
			{
				$value .= '# ' . MSX_NL;
				$value .= '# Database: `' . $this->database . '`' . MSX_NL;
			}
			$value .= '# ' . MSX_NL . MSX_NL . MSX_NL;
			if ($fp)
			{
				if ($compress) gzwrite($fp, $value);
				else fwrite ($fp, $value);
				unset($value);
				$value = '';
			}
		}
		if (!($tables = $this->_GetTables()))
		{
			return false;
		}
		foreach ($tables as $table)
		{
			if (!($table_dump = $this->_DumpTable($table,$fp,$compress)))
			{
				return false;
			}
			if ($fp)
			{
				$value = true;
			}
			else
			{
				$value .= $table_dump;
			}
		}
		return $value;
	}

	function _OpenFile($fname, $task, $compress)
	{
		if ($task != MSX_APPEND && $task != MSX_SAVE && $task != MSX_DOWNLOAD)
		{
			$this->error = 'Tried to open file in wrong task.';
			return false;
		}
		
		$mode = 'w';
		if ($task == MSX_APPEND && file_exists($fname))
		{
			$mode = 'a';
		}
		
		if ($compress) $fp = gzopen($fname, $mode . '9');
		else $fp = fopen($fname, $mode);

		if (!$fp)
		{
			$this->error = 'Can\'t create the output file.';
			return false;
		}
		return $fp;
	}

	function _CloseFile($fp, $compress)
	{
		if ($compress)
		{
			return gzclose($fp);
		}
		else
		{
			return fclose($fp);
		}
	}

	function _DownloadFile($fname, $dname)
	{
		$fp = fopen($fname, 'rb');
		if (!$fp)
		{
			$this->error = 'Can\'t open temporary file.';
			return false;
		}
		header('Content-disposition: filename=' . $dname);
		header('Content-type: application/octetstream');
		header('Pragma: no-cache');
		header('Expires: 0');
		while ($value = fread($fp,8192))
		{
			echo $value;
			unset ($value);
		}
		fclose($fp);
		unlink ($fname);

		return true;
	}

}
?>
