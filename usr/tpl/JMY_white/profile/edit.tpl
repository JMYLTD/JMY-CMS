[open]
<article class="inform hentry">
<form action="profile/edit" method="post" enctype="multipart/form-data" onsubmit="showload();">
<table width="100%" border="0" cellspacing="1" cellpadding="3"  style="margin-bottom:5px;">
  <tr >
    <th colspan="3" >Личные данные</th>
  </tr>
  <tr >    
    <td class="in_conf_input" align="center">Фамилия</td><td class="in_conf_input"><input name="surname" value="{%SURNAME%}" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>
  <tr >
    <td class="in_conf_input" align="center">Имя</td><td class="in_conf_input"><input name="name" value="{%NAME%}" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>
  <tr >
    <td class="in_conf_input" align="center">Очество</td><td class="in_conf_input"><input name="ochestvo" value="{%OCH%}" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>
  <tr >	
    <td class="in_conf_input" align="center">Дата рождения</td><td class="in_conf_input"><select name="birthDay" style="width:130px;" >{%DAY_LIST%}</select> <select name="birthMonth" style="width:130px;" >{%MONTH_LIST%}</select> <select name="birthYear" style="width:130px;" >{%YEAR_LIST%}</select></td>
  </tr>
  <tr >
    <td class="in_conf_input" align="center">Пол</td><td class="in_conf_input"><select name="gender" style="width:394px;" >{%GENDER_LIST%}</select></td>
  </tr>
   <tr >
    <td class="in_conf_input" align="center">Хобби</td><td class="in_conf_input"><input name="hobby" value="{%HOBBY%}" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>   
  <tr >
    <td class="in_conf_input" align="center">Место проживания</td><td class="in_conf_input"><input name="place" value="{%PLACE%}" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="3"  style="margin-bottom:5px;">
  <tr >
    <th colspan="3" >Настройки аватарки</th>
  </tr>
  <tr >   
    <td class="in_conf_input"><input name="avatar_link" value="Ведите url адрес картинки" style="width:98%;" type="text" size="11" /></td>
	<td width="150" rowspan="2"><div align="center"><img src="{%AVATAR%}" border="0" alt="" />
<div style="margin-top:5px;"><input type="checkbox" name="deleteAvatar" value="1" /> Удалить?</div>
	</div></td>
  </tr>
  {%AVATAR_LOAD%}<tr >
    <td class="in_conf_input"><input type="file" name="avatar" style="width:335px;" /></td>
  </tr>{%/AVATAR_LOAD%}
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3"  style="margin-bottom:5px;">
  <tr >
    <th >Настройки подписи</th>
  </tr>
  <tr >
    <td class="in_conf_input">{%BB_AREA%}</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="1" cellpadding="3"  style="margin-bottom:5px;">
  <tr >
    <th colspan="3" >Другие настройки</th>
  </tr>
  <tr >   
    <td class="in_conf_input" align="center">E-Mail</td><td class="in_conf_input"><input name="mail" value="{%EMAIL%}" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>
  <tr >
    <td class="in_conf_input" align="center">Icq</td><td class="in_conf_input"><input name="icq" value="{%ICQ%}" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>
  <tr >
    <td class="in_conf_input" align="center">Skype</td><td class="in_conf_input"><input name="skype" value="{%SKYPE%}" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>  
  <tr >    
    <td class="in_conf_input" align="center">Новый пароль</td><td class="in_conf_input"><input name="newpass" value="" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>
  <tr >
    <td class="in_conf_input" align="center">Повторите</td><td class="in_conf_input"><input name="renewpass" value="" style="width:394px;" type="text" size="11" maxlength="40" /></td>
  </tr>
</table>
[xfield_tpl]
  <tr >
    <td class="in_conf_input" align="center" width="100%">{%XTITLE%}</td><td class="in_conf_input">{%XBODY:[style="width:394px;"]%}</td>
  </tr>
[/xfield_tpl]
[fields]
<table width="100%" border="0" cellspacing="1" cellpadding="3"  style="margin-bottom:5px;">
  <tr >
    <th colspan="2" >Дополнительный настройки</th>
  </tr>
{%XFIELDS%}
</table>
[/fields]

<table width="100%" border="0" cellspacing="1" cellpadding="3"  style="margin-bottom:5px;">
  <tr >
    <td class="in_conf_input"><div align="center"><input value="Сохранить" type="submit" size="11" maxlength="40" /></div></td>
  </tr>
</table>
</form>
</article>
[/open]