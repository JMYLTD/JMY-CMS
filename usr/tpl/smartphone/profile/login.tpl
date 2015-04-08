[open]
<form action="/profile/login" method="post"  onsubmit="showload();">
<div class="coverpage coverpage-bg1">
<div class="loginbox-wrapper">
<div class="loginbox">
<h2 class="center-text">Вход на сайт</h2>
<h4 class="center-text">Внимательно заполните форму ниже</h4>
<input class="loginbox-username" type="text" name="nick" id="nick" value="Логин">
<input class="loginbox-password" type="password" name="password" id="password" value="Пароль">
<button type="submit" id="submit" class="button button-green">Войти</button>
<em>Забыли пароль? <a href="{%URL_FORGOT%}">Восстановить</a></em>
<a href="{%URL_REG%}" class="close-loginbox">Зарегистрироваться</a>
</div>
</div>
</div>
</form>
[/open]