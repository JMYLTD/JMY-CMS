<?php

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2014 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/


class controlDB
{
    public $resource = false;
    public $timeQuery = 0;
    public $timeQueries = 0;
    public $numQueries = 0;
    public $listQueries = array();
	
	public function connect($dbhost, $dbuser, $dbpass, $dbname, $dbpersist = false)
	{
		if($dbpersist)
		{
			$this->resource = mysql_pconnect($dbhost, $dbuser, $dbpass);
        }
		else
		{
			$this->resource = mysql_connect($dbhost, $dbuser, $dbpass);
		}
		
		@mysql_query('SET NAMES utf8');
		if ($this->resource) 
		{
            if (!mysql_select_db($dbname)) 
			{
				if(file_exists('install.php')) if(!file_exists('install/lock.install')) { Header('Location: /install.php'); }
                mysqlFatalError('Ошибка в базе данных MySQL', 'База данных ' . $dbname . ' не найдена', '');
				
            }
        } 
		else 
		{
			if(file_exists('install.php')) if(!file_exists('install/lock.install')) { Header('Location: /install.php'); }
			mysqlFatalError('Ошибка в базе данных MySQL', 'Нет подключения к ' . $dbhost, '');
		}
	}
	
	function safesql($str)
	{
		return mysql_real_escape_string($str, $this->resource);
	}
	
	public function doQuery($str, $ignoreError, $prefix)
	{
        $timer = microtime(1);
		
		if($ignoreError)
		{
			$result = mysql_query($str, $this->resource) or writeInLog('[Ошибка в базе данных] - запрос: ' .  str_replace($prefix, '', $str), 'db_query');
		}
		else
		{
			$result = mysql_query($str, $this->resource) or mysqlFatalError('Ошибка выполнения запроса в DB', "Не удалось выполнить запрос '" . str_replace($prefix, '', $str) . "' <br />Ответ с сервера: " . str_replace($prefix, '', mysql_error()), str_replace($prefix, '', $str));
		}
		
		$this->timeQuery += microtime(1) - $timer;
        $this->timeQueries += $this->timeQuery;
		$this->numQueries++;

        if(DEBUG) $this->listQueries[$this->numQueries] = array($str, $this->timeQuery);

		return $result;
	}
	
	public function freeResult($resource)
	{
        if (is_resource($resource)) 
		{
            return mysql_free_result($resource);
        } 
		else 
		{
            return false;
        }
	}
	
	public function getRow($resource)
	{
        if (is_resource($resource)) 
		{
            return @mysql_fetch_assoc($resource);
        } 
		else 
		{
            return false;
        }
	}	
	
	public function fetchRow($resource)
	{
        if (is_resource($resource)) 
		{
            return @mysql_fetch_array($resource);
        } 
		else 
		{
            return false;
        }
	}	
	
	public function numRows($resource)
	{
        if (is_resource($resource)) 
		{
            return mysql_num_rows($resource);
        } 
		else 
		{
            return false;
        }
	}
}