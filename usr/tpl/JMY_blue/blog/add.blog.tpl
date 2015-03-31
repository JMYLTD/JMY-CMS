<div class="panel panel-default">
<script language="javascript">function checkAddPost(){var err = 0;gid('titleErr').innerHTML = '';gid('postTextErr').innerHTML = ''; if(gid('title').value == ''){ err = 1;gid('titleErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT1]</font>';} if(gid('description').value == ''){ err = 1;gid('postTextErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT2]</font>';} if(err == 1){alert('[lang:_BLOG_TPL_ALERT3]');return false;}else{return true;}}</script>
<div class="panel-heading">Добавить запись в блог</div>
<div class="panel-body">
<form class="form-horizontal form-bordered" role="form" action="blog/saveBlog" method="post" onsubmit="return checkAddPost();" enctype="multipart/form-data">
<div class="form-body">
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_BLOG_TITLE]:</label>
<div class="col-sm-9">
<input name="title" type="text" id="title" value="{%TITLE%}" class="form-control input-sm">
<div id="titleErr"></div>
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_BLOG_URL_TITLE]:</label>
<div class="col-sm-9">
<input name="altname" value="{%ALTNAME%}" type="text" class="form-control input-sm" placeholder="[lang:_BLOG_URL_TITLE2]">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_BLOG_DESCRIPTION]:</label>
<div class="col-sm-9">
<textarea class="form-control" name="description" id="description" rows="5" placeholder="Описание блога...">{%DESCRIPTION%}</textarea>
<div id="postTextErr"></div>
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label class="col-sm-3 control-label">[lang:_BLOG_AVATAR]:</label>
<div class="col-sm-9">
{%AVATAR_REPLACE%}<input type="file" name="blogAvatar" class="form-control" />
[lang:_BLOG_IMG_FORMAT]
</div>
</div><!-- /.form-group -->
</div><!-- /.form-body -->
<div class="form-footer">
<div class="col-sm-3">
<button type="submit" class="btn btn-success">[lang:_BLOG_CREATE2]</button>
</div>
</div><!-- /.form-footer -->
</form>
</div>
</div>