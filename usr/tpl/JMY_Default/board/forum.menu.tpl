<div class="table-responsive mb-20">
<div id="block_menu" style="display: {%COOKIE_DIS%};">
<table class="table table-striped table-primary">
<thead>
<tr>
<th class="text-center border-right" colspan="2">Меню форума</th>
</tr>
</thead>
<tbody>
<tr>
<td width=50% valign="top">
[user]
Приветствуем вас, <strong>{%NAME%}</strong><br />
<a href="board" title="Перейти на главную">Главная форума</a> - <a href="profile" title="Перейти в профиль">Ваш профиль</a>
[/user]
[guest]
Приветствуем вас, <strong>Гость</strong><br />
<a href="board" title="Перейти на главную">Главная форума</a> - <a href="profile" title="Авторизация">Авторизация</a>
[/guest]
</td>
<td width=50% valign="top">
<form class="register" method="post" name="fSearch" action="board/search">
<div class="rightreg">
<input type="text" class="form-control" name="query" placeholder="Поиск по форуму…" value="{%QUERY%}" />
</div>
</form>
</td>
</tr>
</tbody>
</table>
</div>
</div>
