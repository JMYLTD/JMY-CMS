[open]
<div class="row">
<div class="col-md-4">
<section><img src="{%AVATAR%}" class="img-responsive imageborder">{%GROUP_ICON%}</section>
<section>
<h3 class="section-title">{%NICK%}</h3>
<form role="form">
{%ADD_FRIEND%}
</form>
<hr>
<span class="btn btn-ar btn-block btn-default">{%EDIT%}</span>
<hr>
<div align="center" class="_carmaProfileBox"><div><a href="javascript:void(0)" onclick="carmaHistory();"><div id="pcarma">{%CARMA%}</div></a></div><span>[ <a href="javascript:void(0)" title="Прибавить карму" onclick="javascript:modal_box('carma')">+</a> | <a href="javascript:void(0)" title="Убавить карму" onclick="javascript:modal_box('carma')">-</a> ]</span></div>
</section>
</div>
<div class="col-md-8">
<section>
<div class="panel panel-primary">
<div class="panel-heading"><i class="fa fa-user"></i> Информация о пользователе</div>
<table class="table table-striped">
<tr>
<td><strong>Группа:</strong> {%GROUP%}</td>
</tr>
<tr>
<td><strong>Ф.И.О:</strong> {%SURNAME%} {%NAME%} {%OTCH%}</td>
</tr>
<tr>
<td><strong>Возраст:</strong> {%AGE%}</td>
</tr>
<tr>
<td><strong>Пол:</strong> {%SEX%}</td>
</tr>
<tr>
<td><strong>ICQ:</strong> {%ICQ%}</td>
</tr>
<tr>
<td><strong>Skype:</strong> {%SKYPE%}</td>
</tr>
<tr>
<td><strong>Хобби:</strong> {%HOBBY%}</td>
</tr>
<tr>
<td><strong>Последний визит:</strong> {%LASTVIZIT%}</td>
</tr>
<tr>
<td><strong>Комментариев добавлено:</strong> {%USER_COMMENTS%}</td>
</tr>
<tr>
<td><strong>Новостей добавлено:</strong> {%USER_NEWS%}</td>
</tr>
[exgroup]
<tr>
<td><strong>Специальная группа:</strong> {%EXGROUP%}</td>
</tr>
[/exgroup]
<tr>
<td><strong>Подпись:</strong> {%SIG%}</td>
</tr>
[friends]
<tr>
<td><strong>Друзья:</strong> {%FRIENDS%}</td>
</tr>
[/friends]
[newFriends]
<tr>
<td><strong>Заявки в друзья ({%NEWFRIENDSNUM%}):</strong> {%NEWFRIENDS%}</td>
</tr>
[/newFriends]
[userGuests]
<tr>
<td><strong>Гости:</strong> {%GUESTS%} {%CLEAN_GUESTS%}</td>
</tr>
[/userGuests]
[blog]
<tr>
<td><strong>Блог пользователя:</strong> <a href="blog/user/{%UID%}" title="Перейти в персональный блог">Перейти</a></td>
</tr>
[/blog]
[blogRead]
<tr>
<td><strong>Читает блоги:</strong> {%BLOG_READ%}</td>
</tr>
[/blogRead]
<tr>
<td>{%PROFILE_LINK%}</td>
</tr>
<tr>
<td>{%ADMIN%}</td>
</tr>
{%FIELDS%}
</table>
</div>
</section>
</div>
</div>
[/open]
{%USER_WALL%}    