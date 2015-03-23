<div class="panel panel-default">
<div class="panel-heading">Редактирование сообщения</div><!-- /.panel-heading -->
<div class="panel-body">
<form class="form-horizontal form-bordered" role="form" name="topic" method="post" action="board/ajax/fastSave" enctype="multipart/form-data">
<div class="form-body">
<div class="form-group">
<label for="firstname-1" class="col-sm-3 control-label">Сообщение:</label>
<div class="col-sm-9">
{%TEXTAREA%}
</div>
</div><!-- /.form-group -->
[file]
<div class="form-group">
<label for="password-1" class="col-sm-3 control-label">Удаление файлов:</label>
<div class="col-sm-9">
<input type="hidden" name="files" value="1"/>
{%FILE%}
</div>
</div><!-- /.form-group -->
[/file]
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
<input type="hidden" name="id" value="{%ID%}" />
<input type="hidden" name="page" value="{%PAGE%}" />
<input type="hidden" name="tid" value="{%TID%}" />                                           
<button type="submit" class="btn btn-success">Редактировать</button>
</div>
</div><!-- /.form-footer -->
</form>
</div><!-- /.panel-body -->
</div><!-- /.panel -->