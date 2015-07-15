[open]
<article class="inform hentry">
<h3>Здравствуйте, {%NAME%}.</h3>
<p>Для продолжения вам необходимо ввести пароль указанный при регистрации:</p>
<form action="/profile/social_auth" method="post"  onsubmit="showload();">
<div class="register">
<div class="leftreg">Пароль</div><div class="rightreg">
<input name="password" id="password" type="password" class="inputreg" value="" />
<div class="descreg">Введите ваш пароль указанный при регистрации.</div></div>
</div>
<div class="register">
<div class="leftreg"></div>
<div class="rightreg">
<a href="/profile/forgot_pass" title="Забыли пароль?">Забыли пароль?</a>
</div>
</div>
<div class="register">
<div class="leftreg"></div><div class="rightreg"><br /><input type="submit" value="Войти"  id="submit" /></div>
</div>
<br style="clear:both" />
</form>
</article>
[/open]