<div class="panel panel-default">
<div class="panel-heading">
<div class="pull-left">
<img src="{%AVATAR%}" alt="" class="img-circle" style="width:40px; height:40px;">
<span class="media-heading block mb-0 h4 text-white">{%BLOG_NAME%}</span>
</div><!-- /.pull-left -->
<div class="pull-right">
<a href="javascript:void(0)" onclick="reply_comment('{%ID%}');" class="text-white h4"><i class="fa fa-star"></i> [lang:_BLOG_INDEX]: {%RATING%}</a>
</div><!-- /.pull-right -->
<div class="clearfix"></div>
</div><!-- /.panel-heading -->
<div class="panel-body">
<span class="text-white text-sm">[lang:_BLOG_POSTS]: {%POSTS%}</span>
<span class="text-white text-sm">[lang:_BLOG_READERS2]: {%READERS%}</span>
<div class="inner-all block">
<p><a href="javascript:void(0)" onclick="showhide('usr')">[lang:_BLOG_SHOW_PARTICIPANTS]</a> - [user]<a href="blog/becomeReader/{%ID%}">{%BECOME_READER%}</a>[/user]</p>
<p>{%DESCRIPTION%}</p>
<blockquote class="mb-10">[lang:_BLOG_ADMINS]: {%ADMINS%}</blockquote>
</div><!-- /.inner-all -->
</div><!-- /.panel-body -->
</div>