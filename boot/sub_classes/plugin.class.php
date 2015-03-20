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
 
class plugin {

	/*
	* Дополнительная функция входа
	*/
	function login($nick, $password) 
	{
	
	}
	
	/*
	* Дополнительная функция выхода
	*/
	function logout() 
	{
	
	}
	
	/*
	* Дополнительная функция регистрации
	*/
	function registration($user_login, $password, $tail, $email, $icq, $skype, $family, $name, $ochestvo, $age, $sex, $about, $signature, $activate, $ip, $group = '2') 
	{
		
	}
	
	/*
	* Дополнительная функция восстановления забытого пароля
	*/
	function forgot_pass($new_pass, $tail, $uid) 
	{
	
	}
	
	/*
	* Дополнительная функция обновления профиля
	*/
	function updateProfile($mail, $icq, $skype, $surname, $name, $ochestvo, $place, $age, $gender, $birthDate, $hobby, $signature, $fields, $uid = false)
	{
	
	}	
	
	/*
	* Дополнительная функция обновления пароля
	*/
	function updatePassword($password, $uid = false)
	{
	
	}
	
}

$plugin = new plugin;