[open]
<form action="profile/register"  method="post" enctype="multipart/form-data">
<div class="coverpage coverpage-bg1">
<div class="reg-wrapper">
<div class="reg">
<h2 class="center-text">Регистрация пользователя</h2>
<h4 class="center-text">Внимательно заполните форму ниже</h4>
<input class="reg-username" type="text" name="user_login" id="user_login" value="Логин" onchange="javascript:check_login(gid('user_login').value, 'check_result');">
<input class="reg-password" type="password" name="password" id="password" value="Пароль" onblur="javascript:check_password(this.value, 'repassword');">
<input class="reg-password" type="password" name="repassword" id="repassword" value="Повторите пароль" onblur="javascript:check_password(this.value, 'password');">
<input class="reg-username" type="text" name="email" id="email" value="E-mail">
<center style="margin-bottom:10px;">{%CAPTCHA%}</center>
<input class="reg-captcha" type="text" name="securityCode" id="securityCode" value="">
<button type="submit" id="submit" class="button button-green">Зарегистрироваться</button>
</div>
</div>
</div>
</form>
[/open]
<script type="text/javascript">
function check_password(repass,id){password=gid(id).value;if(password.length>0&&repass.length>0){if(password==repass){text="<font color=\"green\">Пароли совпадают, всё в порядке.</font>";gid('submit').disabled=false}else{text="<font color=\"red\">Пароли не совпадают! Проверьте правильность ввода!</font>";gid('submit').disabled=true}gid('checkPassword').innerHTML=text}else{gid('checkPassword').innerHTML=''}}
function check_login(val,id){if(val.length>3){AJAXEngine.setPostVar('uname',encodeURI(val,true));AJAXEngine.sendRequest('ajax.php?do=check_login',id)}}
</script>