[open]
<div class="panel panel-primary">
<div class="panel-heading">Редактировать профиль</div>
<div class="panel-body">
<form class="form-horizontal form-bordered" role="form" action="profile/edit" method="post" enctype="multipart/form-data" onsubmit="showload();">
<div class="form-body">
<div class="form-group">
<label for="username" class="col-sm-3 control-label">Ваше настоящее имя:</label>
<div class="col-sm-9">
<input type="text" name="name" value="{%NAME%}" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="username" class="col-sm-3 control-label">Ваша фамилия:</label>
<div class="col-sm-9">
<input type="text" name="surname" value="{%SURNAME%}" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="username" class="col-sm-3 control-label">Ваше отчество:</label>
<div class="col-sm-9">
<input type="text" name="ochestvo" value="{%OCH%}" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label class="col-sm-3 control-label">Дата Рождения:</label>
<div class="col-sm-9">
<select class="chosen-select" name="birthDay">{%DAY_LIST%}</select>
<select class="chosen-select" name="birthMonth">{%MONTH_LIST%}</select>
<select class="chosen-select" name="birthYear">{%YEAR_LIST%}</select>
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label class="col-sm-3 control-label">Ваш пол:</label>
<div class="col-sm-9">
<select class="form-control" name="gender">{%GENDER_LIST%}</select>
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">Хобби:</label>
<div class="col-sm-9">
<input type="text" name="hobby" value="{%HOBBY%}" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">Место проживания:</label>
<div class="col-sm-9">
<input type="text" name="place" value="{%PLACE%}" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
{%AVATAR_LOAD%}
<div class="form-group">
<label class="col-sm-3 control-label">Загрузка аватара:</label>
<div class="col-sm-9">
<input type="file" name="avatar" class="form-control" />
</div>
</div><!-- /.form-group -->
{%/AVATAR_LOAD%}
<div class="form-group">
<label for="name" class="col-sm-3 control-label">Загрузить через URL:</label>
<div class="col-sm-7">
<input type="text" name="avatar_link" class="form-control input-sm" placeholder="Ведите url адрес картинки">
</div>
<div class="col-sm-2">
<div style="margin-top:5px;"><input type="checkbox" name="deleteAvatar" value="1" /> Удалить?</div>
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">Настройки подписи:</label>
<div class="col-sm-9">
{%BB_AREA%}
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="email" class="col-sm-3 control-label">Email:</label>
<div class="col-sm-9">
<input type="text" name="mail" value="{%EMAIL%}" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="username" class="col-sm-3 control-label">Ваш ICQ:</label>
<div class="col-sm-9">
<input type="text" name="icq" value="{%ICQ%}" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="username" class="col-sm-3 control-label">Skype:</label>
<div class="col-sm-9">
<input type="text" name="skype" value="{%SKYPE%}" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="create-password" class="col-sm-3 control-label">Пароль:</label>
<div class="col-sm-9">
<input type="password" name="newpass" value="" class="form-control input-sm">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="confirm-password" class="col-sm-3 control-label">Повторите пароль:</label>
<div class="col-sm-9">
<input type="password" name="renewpass" value="" class="form-control input-sm">
<div id="checkPassword"></div>
</div>
</div><!-- /.form-group -->
[xfield_tpl]
<div class="form-group">
<label for="name" class="col-sm-3 control-label">{%XTITLE%}:</label>
<div class="col-sm-9">
{%XBODY%}
</div>
</div><!-- /.form-group -->
[/xfield_tpl]
[fields]
<div class="form-group form-group-divider">
<div class="form-inner">
<h4 class="no-margin">Дополнительный настройки</h4>
</div>
</div><!-- /.form-group -->
<div class="form-group">
{%XFIELDS%}
</div><!-- /.form-group -->
[/fields]                                        
</div><!-- /.form-body -->
<div class="form-footer">
<div class="col-sm-2">
<button type="submit" class="btn btn-ar btn-primary">Сохранить</button>
</div>
</div><!-- /.form-footer -->
</form>
</div>
</div>
[/open]