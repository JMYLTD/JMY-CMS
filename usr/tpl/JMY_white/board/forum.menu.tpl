<div class="forumBodyWrap">
<div class="forumTitle">
<div style="float:left;">
Меню форума:
</div>
<div style="float:right">
	<a href="javascript:void(0)" onclick="javascript:switchBlock('menu');"><img src="media/other/{%COOKIE_IMG%}.png" id="img_menu" width="16" height="16" border="0" alt="" /></a>
</div>
<br style="clear:both" />
</div>
<div id="block_menu" style="display: {%COOKIE_DIS%}; padding:1em;">
<table width=100%>
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
<input type="text" name="query" placeholder="Поиск по форуму…" value="{%QUERY%}" />
<div class="descreg">Поиск работает только по модерированным темам форума!</div>
</div>
</form>
</td>
</tr>
</table>
</div>
</div>
<br />
