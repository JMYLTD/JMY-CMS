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
 
class auth 
{
	var $user_id = 0;
	var $isUser = false;
	var $isAdmin = false;
	var $isModer = false;
	var $group = 0;
	var $user_info = false;
	var $group_info = false;
	var $banUser = false;
	var $groups_array = false;
	var $newPms = false;
	var $newPmsNumb = 0;
	
	function __construct($hash = '') 
	{
	global $db, $url, $user, $cache, $config;
		$ut = str_replace('index.php', '', $_SERVER["REQUEST_URI"]);
		$dsUrl = (isset($_REQUEST['url']) && $url[0]!=='ajax' && $url[0] !== ADMIN) ? '/' . (empty($ut) ?  $config['mainModule'] : str_replace('index.php?url=', '', $_SERVER["REQUEST_URI"])) : '/'.$config['mainModule'];
		$moduleNow = (isset($url[0]) && $url[0]!=='ajax' && $url[0] !== ADMIN) ? $url[0] : $config['mainModule'];
		$hash = empty($hash) ? (isset($_COOKIE[COOKIE_AUTH]) ? $_COOKIE[COOKIE_AUTH] : '') : $hash;		

		$this->groups_array = getcache('groups');
		if(empty($groups_array))
		{
			$query = $db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_groups`");
			while($rows = $db->getRow($query)) 
			{
				$this->groups_array[$rows['id']] = $rows;
			}
			
			setcache('groups', $this->groups_array);
		}
		
		if (empty($hash)) 
		{
			if (!isset($_COOKIE[COOKIE_PAUSE])) 
			{
				if(SpiderDetect($_SERVER['HTTP_USER_AGENT'])) 
				{
					$group = $user['botGroup'];
					$uid = SpiderDetect($_SERVER['HTTP_USER_AGENT'], true);
				} 
				else 
				{
					$group = $user['guestGroup'];
					$uid = 0;
				}
				
				$db->query("UPDATE `" . DB_PREFIX . "_online` SET `time` = '" . time() . "' WHERE `ip` = '" . getRealIpAddr() . "' AND `uid` = '" . $uid . "'", true);
				
				$error = mysql_info();
				preg_match("/^\D+(\d+)/", $error, $result);

				if ($result[1] == 0) 
				{
					$db->query("INSERT INTO " . DB_PREFIX . "_online VALUES ('" . $uid . "', '" . time() . "', '" . getRealIpAddr() . "', '" . $group . "', '" . $dsUrl . "')", true);
				}
				
				$db->query("DELETE FROM `" . DB_PREFIX . "_online` WHERE `time` < '" . (time() - PAUSE_TIME) . "'");
				setcookie(COOKIE_PAUSE, time(), time() + 120, '/');
			}
			
			$this->isUser = false;
			$this->group = $user['guestGroup'];
			$this->group_info = $this->groups_array[$this->group];
		}
		else
		{
			$cookie = @unserialize(engine_decode($hash));

			if (empty($cookie['id']) || empty($cookie['nick']) || empty($cookie['password']) || empty($cookie['hash'])) 
			{
				@setcookie(COOKIE_AUTH, false);
				@header('Location: /');
				exit;
			}

			$this->user_info = getcache('userInfo_'.$cookie['id']);
			if(empty($this->user_info))
			{
				$this->user_info = $db->getRow($db->query("SELECT * FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE id = '" . $cookie['id'] . "' AND password = '" . $cookie['password'] . "' AND nick = '" . $cookie['nick'] . "' AND active='1'"));
				
				foreach($this->groups_array as $id => $arr)
				{
					if($arr['special'] == 1 && $this->user_info['points'] >= $arr['points'] && $arr['points'] > 0) $this->user_info['exgroup'] = $id;
				}
				
				setcache('userInfo_'.$cookie['id'], $this->user_info, 'userInfo');
			}

			$checkHash = md5(@$_SERVER['HTTP_USER_AGENT'].$config['uniqKey']);

			if (($cookie['hash'] == $checkHash || $_SERVER['HTTP_USER_AGENT'] == 'Shockwave Flash') && $this->user_info['nick'] != '') 
			{
				$this->user_id = $cookie['id'];
				$this->group = $this->user_info['group'];
				if(isset($this->groups_array[$this->group]))
				{
					$group_info = $this->groups_array[$this->group];
					$group_info['gname'] = $group_info['name'];
					$group_info['gid'] = $group_info['id'];
					unset($group_info['name'], $group_info['id']);
					if($this->user_info['exgroup'] != 0 && isset($this->groups_array[$this->user_info['exgroup']])) 
					{
						$group_info['exname'] = $this->groups_array[$this->user_info['exgroup']]['name'];
						$group_info['excolor'] = $this->groups_array[$this->user_info['exgroup']]['color'];
						$group_info['exicon'] = $this->groups_array[$this->user_info['exgroup']]['icon'];
					}
					
					$this->group_info = $group_info;
					$this->user_info = array_merge($this->user_info, $this->group_info);
					unset($group_info);
				}

				if($this->user_info['group'] != $user['banGroup'])
				{
					if($this->group_info['user'] == 1) 
						$this->isUser = true;
					
					if($this->group_info['admin'] == 1) 
						$this->isAdmin = true;
					
					if($this->group_info['moderator'] == 1) 
						$this->isModer = true;
					
					if (!isset($_COOKIE[COOKIE_PAUSE])) 
					{
						setcookie(COOKIE_PAUSE, time() + PAUSE_TIME, time() + PAUSE_TIME, '/');
						$db->query("UPDATE `" . DB_PREFIX . "_online` SET `time` = '" . time() . "', `ip` = '" . getRealIpAddr() . "' WHERE `uid` = '" . $this->user_id . "'", true);
						
						$error = mysql_info();
						preg_match("/^\D+(\d+)/", $error, $result);
						if (isset($result[1]) && $result[1] == 0) 
						{
							$db->query("INSERT INTO `" . DB_PREFIX . "_online` VALUES ('" . $this->user_id . "', '" . time() . "', '" . getRealIpAddr() . "', '" . $this->user_info['group'] . "', '" . $dsUrl . "')", true);			
							$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `last_visit` = '" . time() . "', `ip` = '" . getRealIpAddr() . "' WHERE `id` = '" . $this->user_id . "'", true);
						}
						
						$db->query("DELETE FROM `" . DB_PREFIX . "_online` WHERE `time` < '" . (time() - PAUSE_TIME) . "'");
						
						if($user['pmShown'] == 1)
						{
							$where = '';
							if(isset($_COOKIE['PMLASTCHECK'])) $where = " AND time > '".intval($_COOKIE['PMLASTCHECK'])."'";
							$result = $db->query("SELECT pm.*, u.nick FROM `" . DB_PREFIX . "_pm` as pm LEFT JOIN `" . USER_DB . "`.`" . USER_PREFIX . "_users` as u on (pm.fromid = u.id) WHERE pm.toid = '" . $this->user_id . "' AND pm.status='0' $where ORDER BY time DESC");
							if($db->numRows($result) > 0) 
							{
								while($message = $db->getRow($result))
								{
									$this->newPms[] = $message;
									$this->newPmsNumb++;
								}
								setcookie('PMLASTCHECK', time(), time() + 86400, '/');
							}
						}
					}
					else
					{
						if(($_COOKIE[COOKIE_PAUSE]-(PAUSE_TIME*2/3)) < time())
						{
							$db->query("UPDATE `" . DB_PREFIX . "_online` SET `time` = '" . time() . "', `url` = '" . $dsUrl . "' WHERE `uid` = '" . $this->user_id . "'");
							
							$error = mysql_info();
							preg_match("/^\D+(\d+)/", $error, $result);
							if ($result[1] == 0) 
							{
								$db->query("INSERT INTO `" . DB_PREFIX . "_online` VALUES ('" . $this->user_id . "', '" . time() . "', '" . getRealIpAddr() . "', '" . $this->user_info['group'] . "', '" . $dsUrl . "')", true);			
							}
							
							setcookie(COOKIE_PAUSE, false);
							setcookie(COOKIE_PAUSE, time() + PAUSE_TIME, time() + PAUSE_TIME, '/');
						}
					}
					
				}
				else
				{
					$this->banUser = true;
				}
				
				unset($result, $error, $cookie);
			}
			else
			{
				$this->logout();
				location();
				unset($this->user_info);
			}
		} 
	}
	
	function login($nick, $password)
	{
	global $db, $config;
		$db->query("DELETE FROM " . DB_PREFIX . "_online WHERE ip = '" . getRealIpAddr() . "'");
		setcookie(COOKIE_PAUSE, false, time(), '/');
		
		$nick = filter($nick, 'nick');
		$password = md5(md5($password));
		
		$access = $db->getRow($db->query("SELECT id, password, tail FROM `" . USER_DB . "`.`" . USER_PREFIX . "_users` WHERE nick = '" . $db->safesql($nick) . "' AND active='1'"));
	
		if (md5(mb_substr($password, 0, -mb_strlen($access['tail'])) . $access['tail']) == $access['password']) 
		{
			delcache('userInfo_'.$this->user_id);
			$newHash = md5(@$_SERVER['HTTP_USER_AGENT'].$config['uniqKey']);
			setcookie(COOKIE_AUTH, engine_encode(serialize(array('id' => $access['id'], 'nick' => $nick, 'password' => md5(mb_substr($password, 0, -mb_strlen($access['tail'])) . $access['tail']), 'hash' => $newHash))), time() + COOKIE_TIME, '/');
			return true;
		} 
		else 
		{
			setcookie(COOKIE_AUTH, '', time(), '/');
			return false;
		}
	}
	
	function logout()
	{
	global $db;
		setcookie(COOKIE_AUTH, false, time(), '/');
		setcookie(COOKIE_PAUSE, false, time(), '/');
		
		delcache('userInfo_'.$this->user_id);
		$db->query("DELETE FROM " . DB_PREFIX . "_online WHERE uid = '" . $this->user_id . "' OR ip = '" . filter(getRealIpAddr()) . "'");
	}
	
	function updateProfile($mail, $icq, $skype, $surname, $name, $ochestvo, $place, $age, $gender, $birthDate, $hobby, $signature, $fields, $uid = false)
	{
	global $db;
		delcache('userInfo_'.$this->user_id);
		$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `email` = '" . filter($mail, 'mail') . "', `icq` = '" . filter($icq, 'a') . "', `skype` = '" . filter($skype, 'a') . "', `surname` = '" . filter($surname, 'a') . "', `name` = '" . filter($name, 'a') . "', `ochestvo` = '" . filter($ochestvo, 'a') . "', `place` = '" . filter($place, 'a') . "', `age` = '" . intval($age) . "', `sex` = '" . intval($gender) . "', `birthday` = '" . filter($birthDate, 'a') . "', `hobby` = '" . filter($hobby, 'a') . "', `signature` = '" . $db->safesql($signature) . "', `fields` = '" . $db->safesql($fields) . "' WHERE `id` = " . ($uid ? $uid : $this->user_id) . " LIMIT 1 ;");
	}	
	
	function updatePassword($password, $uid = false)
	{
	global $db;
		delcache('userInfo_'.($uid ? $uid : $this->user_id));
		$tail = gencode(rand(6, 11));
		$updPass = md5(mb_substr(md5(md5($password)), 0, -mb_strlen($tail)) . $tail);
		
		$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `password` = '" . $updPass . "', `tail` = '" . $tail . "' WHERE `id` = " . ($uid ? $uid : $this->user_id) . " LIMIT 1 ;");
	}
	
	function forgotPass($new_pass, $tail, $uid)
	{
	global $db;
		delcache('userInfo_'.$uid);
		$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `password` = '" . md5(mb_substr(md5(md5($new_pass)), 0, -mb_strlen($tail)) . $tail) . "', `tail` = '" . filter($tail, 'a') . "' WHERE `id` = '" . $uid . "' LIMIT 1 ;");
	}
	
	function activate($mail)
	{
	global $db;
		$db->query("UPDATE `" . USER_DB . "`.`" . USER_PREFIX . "_users` SET `active` = '1' WHERE `email` = '" . $db->safesql(filter($mail, 'mail')) . "' LIMIT 1 ;");
	}
	
	function register($user_login, $password, $tail, $email, $icq, $skype, $family, $name, $ochestvo, $age, $sex, $about, $signature, $activate, $ip, $group = '2')
	{
	global $db;
		$db->query("INSERT INTO `" . USER_DB . "`.`" . USER_PREFIX . "_users` ( `id` , `nick` , `password` , `tail` , `email` , `icq` , `skype` , `surname` , `name` , `ochestvo` , `place` , `age` , `sex` , `birthday` , `hobby` , `signature` , `points` , `user_comments` , `user_news` , `group` , `last_visit` , `regdate` , `active` , `ip` ) VALUES (NULL, '" . filter($user_login, 'a') . "', '" . md5(mb_substr(md5(md5($password)), 0, -mb_strlen($tail)) . $tail) . "', '" . filter($tail, 'a') . "', '" . filter($email, 'mail') . "', '" . filter($icq, 'a') . "', '" . filter($skype, 'a') . "', '" . filter($family, 'a') . "', '" . filter($name, 'a') . "', '" . filter($ochestvo, 'a') . "', '', '" . intval($age) . "', '" . intval($sex) . "', '', '" . filter($about, 'a') . "', '" . filter($signature) . "', '0', '0', '0', '" . intval($group) . "', '" . time() . "', '" . time() . "', '" . $activate . "', '" . filter($ip, 'ip') . "');");
	}
}
