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
 
class dateBase extends controlDB 
{
    var $prefix = '';

    public function __construct() 
	{
        require_once ROOT . 'etc/db.config.php';
		
		parent::connect($dbhost, $dbuser, $dbpass, $dbname);
		define("DB_PREFIX", $prefix);
		define("USER_PREFIX", $user_prefix);
		define("USER_DB", $user_db);
		unset($dbhost, $dbuser, $dbpass, $prefix, $user_prefix, $user_db);
    }
	
    public function query($str, $ignoreError = false) 
	{
		return parent::doQuery($str, $ignoreError, $this->prefix);
    }
	
	function safesql($str)
	{
		return parent::safesql($str);
	}

    public function freeResult($resource) 
	{
		return parent::freeResult($resource);
    }

    public function getRow($resource) 
	{
		return parent::getRow($resource);
    }
	
    public function fetchRow($resource) 
	{
		return parent::fetchRow($resource);
    }

    public function numRows($resource) 
	{
		return parent::numRows($resource);
    }
}

$db = new dateBase();