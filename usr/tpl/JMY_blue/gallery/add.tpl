<div class="panel panel-default">
<div class="panel-heading">Добавить фотографию</div>
<div class="panel-body">
<form class="form-horizontal form-bordered" role="form" action="gallery/save" method="post" novalidate="" onsubmit="return validate(this)" enctype="multipart/form-data">
<div class="form-body">
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_CAPTION]: *</label>
<div class="col-sm-9">
<input id="author"  name="title" type="text" class="form-control input-sm" placeholder="[lang:_CAPTION]: *">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_ALBUM]: *</label>
<div class="col-sm-9">
<select name="album" class="form-control">{%ALBUMS%}</select>
</div>
</div>
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_PHOTO_DATE]:</label>
<div class="col-sm-9">
<input id="url" name="date" type="text" class="form-control input-sm" placeholder="[lang:_PHOTO_DATE]:">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_PHOTO_DESCRIPTION]: *</label>
<div class="col-sm-9">
{%DESCRIPTION%} 
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label class="col-sm-3 control-label">[lang:_PHOTO_UPLOADING]: *</label>
<div class="col-sm-9">
<input type="file" name="urlLocal" size="65" class="form-control input-sm">
[lang:_TYPE_FILES]: <b>{%ALLOW_TYPE%}</b><br>[lang:_MAX_FILE_SIZE] <b>{%MAX_SIZE%}</b>. <br>[lang:_OVER_SIZE_PHOTO]
</div>
</div><!-- /.form-group -->
</div><!-- /.form-body -->
<div class="form-footer">
<div class="col-sm-3">
<button name="submit" type="submit" id="submit" class="btn btn-success">[lang:_SEND]</button>
</div>
</div><!-- /.form-footer -->
</form>
</div>
</div>