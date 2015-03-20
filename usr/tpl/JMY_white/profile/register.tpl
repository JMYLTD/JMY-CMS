[open]
<article class="inform hentry">
<h3>Регистрация пользователя:</h3><br>
<form action="profile/register"  method="post" enctype="multipart/form-data">
<div class="register">
	<div class="leftreg">Логин</div><div class="rightreg"><input name="user_login" id="user_login" class="inputreg" type="text" value="" onchange="javascript:check_login(gid('user_login').value, 'check_result');" /><div class="descreg">Используйте буквы в диапазоне (a-z) и цифры (0-9).<br />
Разрешенные специальные символы "-_[]().".<div id="check_result"></div></div></div>	
</div> 
<div class="register">
<div class="leftreg">Пароль</div><div class="rightreg">
<input type="password" id="password" name="password" class="inputreg" onblur="javascript:check_password(this.value, 'repassword');" />
<div class="descreg">Пароль должен содержать минимум 6 символов<br />
и не являться вашим именем.</div></div>
</div>  
<div class="register">
<div class="leftreg">Пароль еще раз</div><div class="rightreg">
<input name="repassword" id="repassword" type="password" class="inputreg" value="" onblur="javascript:check_password(this.value, 'password');" />
<div class="descreg">Введённые пароли должны совпадать.<div id="checkPassword"></div></div></div>
</div>   
<div class="register">
<div class="leftreg">E-mail</div><div class="rightreg">
<input name="email" id="email" type="text" class="inputreg" value="" />
<div class="descreg">Введите правильный адрес. Например example@domain.com.<br />
На этот адрес придет ссылка для активации.</div></div>
</div>
<div class="register">
<div class="leftreg"></div><div class="rightreg descreg">{%CAPTCHA%}</div>
</div> 
<div class="register">
<div class="leftreg">Проверочный код</div><div class="rightreg">
<input name="securityCode" id="securityCode" type="text" class="inputreg" value="" />
<div class="descreg">Введите код с картинки, ну пожалуйста.</div></div>
</div>
<div class="register">
<div class="leftreg"></div><div class="rightreg"><a href="javascript:void(0)" onclick="showhide('addition')"><font size="3pt">Заполнить дополнительные данные</font></a></div>
</div> 
<div id="addition" class="register" style="display:none;">
	<div class="register">
	<div class="leftreg">Ваш ICQ</div><div class="rightreg">
	<input name="icq" type="text" maxlength="40" value="" class="inputreg" />
	<div class="descreg">Ваш номер в системе ICQ</div></div>
	</div>
	<div class="register">
	<div class="leftreg">Ваша фамилия</div><div class="rightreg">
	<input name="family" type="text" maxlength="40" value="" class="inputreg" />
	<div class="descreg">Ваша фамилия. Например: Петров</div></div>
	</div>
	<div class="register">
	<div class="leftreg">Ваше настоящее имя</div><div class="rightreg">
	<input name="name" type="text" maxlength="40" value="" class="inputreg" />
	<div class="descreg">Ваше настоящее имя. Например: Иван</div></div>
	</div>
	<div class="register">
	<div class="leftreg">Ваше отчество</div><div class="rightreg">
	<input name="ochestvo" type="text" maxlength="40" value="" class="inputreg" />
	<div class="descreg">Ваше отчество. Например: Иванович</div></div>
	</div>
	<div class="register">
	<div class="leftreg">Ваш пол</div><div class="rightreg">
	<select name="sex" ><option value="0" >-- Скрыто --</option><option value="1" >Мужской</option><option value="2" >Женский</option></select>
	<div class="descreg">Выберите вашу принадлежность к полу</div></div>
	</div>
</div>
<div class="register">
<div class="leftreg"></div><div class="rightreg"><div class="descreg"><br />Нажимая "Зарегистрироваться", Вы подтверждаете,<br />
что ознакомились и согласились с Нашими правилами.</div></div>
</div> 
<div class="register">
<div class="leftreg"></div><div class="rightreg"><br /><input type="submit" value="Зарегистрироваться"  id="submit" /></div>
</div> 
</form>
<br style="clear:both" />
</article>
[/open]
<script type="text/javascript">
function check_password(repass,id){password=gid(id).value;if(password.length>0&&repass.length>0){if(password==repass){text="<font color=\"green\">Пароли совпадают, всё в порядке.</font>";gid('submit').disabled=false}else{text="<font color=\"red\">Пароли не совпадают! Проверьте правильность ввода!</font>";gid('submit').disabled=true}gid('checkPassword').innerHTML=text}else{gid('checkPassword').innerHTML=''}}
function check_login(val,id){if(val.length>3){AJAXEngine.setPostVar('uname',encodeURI(val,true));AJAXEngine.sendRequest('ajax.php?do=check_login',id)}}
</script>