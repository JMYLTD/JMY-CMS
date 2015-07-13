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

global $config, $core;
if($core->auth->isUser) 
{
	echo '<div align="center">Привет, <b>' . $core->auth->user_info['nick'] . '</b>!<br/><br/><img src="'.avatar($core->auth->user_info['id']).'" border="0" alt="" /><br/><br/></div>';
	echo '<a href="profile">Профиль</a><br/><a href="pm">Приватные сообщения</a><br/><a href="profile/logout">Выход</a>';

	if($core->auth->isAdmin) 
	{
		echo '<hr/><a href="' . ADMIN . '">Панель управления</a>';
	}
}
else 
{
	echo '<form action="profile/login" method="post" onkeypress="ctrlEnter(event, this);">
			<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center">
				<tr>
					<td>Ник:</td><td><input type="text" name="nick" size="10" maxlength="25" class="binput" /></td>
				</tr>
				<tr>
					<td>Пароль:</td><td><input type="password" name="password" size="10" maxlength="25" class="binput" /></td>
				</tr>
				<tr>
					<td colspan="2" align="center"><input type="submit" value="Войти" class="fbutton" /></td>
				</tr>
			</table>
		</form>';
	echo '<a href="profile/register">Регистрация</a>';
}
