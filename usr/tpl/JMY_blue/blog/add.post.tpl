<div class="panel panel-default">
<script language="javascript">function checkAddPost(){var err = 0;gid('titleErr').innerHTML = '';gid('postTextErr').innerHTML = '';gid('tagsErr').innerHTML = '';if(gid('title').value == ''){err = 1;gid('titleErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT4]</font>';} if(gid('postText').value == ''){	err = 1;gid('postTextErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT5]</font>';} if(gid('tags').value == '') {err = 1;gid('tagsErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT6]</font>';}if(err == 1){alert('[lang:_BLOG_TPL_ALERT3]');return false;}else{return true;}}</script>
<div class="panel-heading">Добавить запись в блог</div>
<div class="panel-body">
<form class="form-horizontal form-bordered" role="form" action="blog/savePost" method="post" onsubmit="return checkAddPost();">
<div class="form-body">
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_BLOG_SELECT_BLOG]:</label>
<div class="col-sm-9">
<select name="blog" class="form-control" {%BLOGCHOOSE%}>{%BLOGS%}</select>
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_BLOG_POST_TITLE]:</label>
<div class="col-sm-9">
<input name="title" type="text" id="title" value="{%TITLE%}" class="form-control input-sm" placeholder="[lang:_BLOG_POST_MINI]">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_BLOG_TEXT]:</label>
<div class="col-sm-9">
{%TEXTAREA%}
<div id="postTextErr"></div>
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label for="name" class="col-sm-3 control-label">[lang:_BLOG_TAGS]:</label>
<div class="col-sm-9">
<input name="tags" type="text" id="tags" value="{%TAGS%}" class="form-control input-sm" placeholder="[lang:_BLOG_TOPICS]">
</div>
</div><!-- /.form-group -->
<div class="form-group">
<div class="col-sm-offset-3 col-sm-9">
<div class="ckbox ckbox-theme">
<input name="note" type="checkbox" {%NOTE%}>
<label for="rememberme-3">[lang:_BLOG_DRAFT2]</label>
</div>
</div>
</div><!-- /.form-group -->
</div><!-- /.form-body -->
<div class="form-footer">
<div class="col-sm-3">
<button type="submit" class="btn btn-success">[lang:_BLOG_WRITE2]</button> {%ACTIONS%}
</div>
</div><!-- /.form-footer -->
</form>
</div>
</div>