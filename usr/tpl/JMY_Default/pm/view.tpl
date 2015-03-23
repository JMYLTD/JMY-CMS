[menu:Просмотр приватного сообщения]
<div id="pm_content">
[open]
<div class="panel panel-default">
<div class="panel-heading">
<div class="pull-left">
<img src="{%AVATAR%}" alt="" class="img-circle" style="width:40px; height:40px;">
<span class="media-heading block mb-0 h4 text-white">От: <a href="profile/{%FROM%}">{%FROM%}</a></span>
<span class="text-white text-sm">Дата: {%DATE%} [actions]<a href="pm/write/{%FROM%}/{%ID%}">Ответить на сообщение</a>[/actions]<br/>Статус: [status=0]<span style="color:red">Не прочитано</span>[else]<span  style="color:green">Прочитано</span>[/status] [actions]<span id="msgNo{%ID%}"><a href="javascript://" onclick="ajaxSimple('index.php?url={%MOD_NAME%}/del/{%ID%}/', 'msgNo{%ID%}', true)">Удалить сообщение</a></span>[/actions]
</span>
</div><!-- /.pull-left -->
</div><!-- /.panel-heading -->
<div class="panel-body">
<p>Сообщение: {%MESSAGE%}</p>
<blockquote class="mb-10">Кому: <a href="profile/{%TO%}">{%TO%}</a></blockquote>
</div><!-- /.panel-body -->
</div>
[/open]
</div>