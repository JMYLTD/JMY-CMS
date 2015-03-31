<div class="panel panel-default">
<div class="panel-heading">Добавление темы</div><!-- /.panel-heading -->
<div class="panel-body">
<form class="form-horizontal form-bordered" role="form" name="topic" method="post" action="board/saveTopic" enctype="multipart/form-data">
<div class="form-body">
<div class="form-group">
<label for="firstname-1" class="col-sm-3 control-label">Название темы:</label>
<div class="col-sm-9">
<input type="text" name="title" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="password-1" class="col-sm-3 control-label">Иконка:</label>
<div class="col-sm-9">
<div class="rdio rdio-theme circle">
<input id="radio-type-circle1" type="radio" name="icon" value="" checked="">
<label for="radio-type-circle1">Нет иконки</label>
</div>
</div>
</div><!-- /.form-group -->
<div class="form-group">
<div class="col-sm-offset-3 col-sm-9">
{%ICON%}
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="firstname-1" class="col-sm-3 control-label">Сообщение:</label>
<div class="col-sm-9">
{%TEXTAREA%}
</div>
</div><!-- /.form-group -->
[upload]
<div class="form-group">
<label for="password-1" class="col-sm-3 control-label">Загрузка файлов:</label>
<div class="col-sm-9">
{%FORUM_UPLOAD%}
</div>
</div><!-- /.form-group -->
[/upload]
</div><!-- /.form-body -->
<div class="form-footer">
<div class="col-sm-12">
<input type="hidden" name="forum" value="{%ID%}">
<input type="hidden" name="uniqCode" value="{%UNIQCODE%}">                                           
<button type="submit" class="btn btn-success">Создать тему</button> <button type="reset" class="btn btn-danger">Очистить</button>
</div>
</div><!-- /.form-footer -->
</form>
</div><!-- /.panel-body -->
</div><!-- /.panel -->