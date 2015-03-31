<div class="panel panel-default">
<div class="panel-heading">Редактирование темы</div><!-- /.panel-heading -->
<div class="panel-body">
<form class="form-horizontal form-bordered" role="form" name="topic" method="post" action="board/userSave/{%HASH%}" enctype="multipart/form-data">
<div class="form-body">
<div class="form-group">
<label for="firstname-1" class="col-sm-3 control-label">Название темы:</label>
<div class="col-sm-9">
<input type="text" value="{%NAME%}" name="title" class="form-control input-sm">
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
</div><!-- /.form-body -->
<div class="form-footer">
<div class="col-sm-12">
<input type="hidden" name="forum" value="{%FORUM_NAME%}" />
<input type="hidden" name="tid" value="{%ID%}" />
<input type="hidden" name="type" value="topic" />                                            
<button type="submit" class="btn btn-success">Редактировать тему</button>
</div>
</div><!-- /.form-footer -->
</form>
</div><!-- /.panel-body -->
</div><!-- /.panel -->
