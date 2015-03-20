[menu:Просмотр приватного сообщения]
<div id="pm_content">
<article class="hentry">
[open]
{%MESSAGE%}
<hr />
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="110" valign="top"><img src="{%AVATAR%}" border="0" /></td>
    <td valign="top">От кого: <a href="profile/{%FROM%}">{%FROM%}</a><br />Кому: <a href="profile/{%TO%}">{%TO%}</a><br />Дата: {%DATE%}<br />Статус: [status=0]<span style="color:red">Не прочитано</span>[else]<span  style="color:green">Прочитано</span>[/status] [actions][ <span id="msgNo{%ID%}"><a href="javascript://" onclick="ajaxSimple('index.php?url={%MOD_NAME%}/del/{%ID%}/', 'msgNo{%ID%}', true)">Удалить сообщение</a></span> ]<br /><a href="pm/write/{%FROM%}/{%ID%}">Ответить на сообщение</a>[/actions]</td></tr></table>
[/open]
</article>
</div>