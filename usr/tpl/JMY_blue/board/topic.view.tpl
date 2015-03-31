<script type="text/javascript">var addition = '';</script>
<h4 class="mt-0">{%TITLE%} <div class="pull-right">{%ACTION%}</div></h4>
<div class="forumBodyWrap">	
<table class="table table-striped table-success table-bordered" width="100%" border="0" cellspacing="1" cellpadding="0">
{%TOPIC%}
</table>
</div>
[close]
<div class="panel rounded shadow" id="qr" style="{%SHOW_EDIT%}">
<div class="panel-heading">
<div class="pull-left">
<h3 class="panel-title">Написать ответ</h3>
</div>
<div class="clearfix"></div>
</div>
<div class="panel-body no-padding">
<form class="form-horizontal form-bordered" role="form" name="quick" method="post" action="board/postMessage" onsubmit="return sendPost();" enctype="multipart/form-data">
<div class="form-body">
<div class="form-group">
<label class="col-sm-3 control-label">Ответ: <span class="asterisk">*</span></label>
<div class="col-sm-9">
{%TEXTAREA%}
</div>
</div><!-- /.form-group -->
<div class="form-group">
<label class="col-sm-3 control-label"></label>
<div class="col-sm-9">
{%UPLOAD%}
</div>
</div><!-- /.form-group -->
</div><!-- /.form-body -->
<div class="form-footer">
<div class="col-sm-offset-9">
<input type="hidden" name="tid" value="{%ID%}" class="b" />
<input type="hidden" name="type" value="quick" class="b" />
<button type="submit" class="btn btn-theme">[lang:_FORUM_ANSWER]</button>
</div>
</div><!-- /.form-footer -->
</form>
</div><!-- /.panel-body -->
</div><!-- /.panel -->
[/close]
[guest]
<div class="panel panel-default rounded shadow">
<div class="panel-body">
<div class="alert alert-danger ">
<span class="alert-icon"><i class="fa fa-comments-o"></i></span>
<div class="notification-info">
<ul class="clearfix notification-meta">
<li class="pull-left notification-sender">Информация</li>
</ul>
<p>[lang:_FORUM_REGISTER_OR_LOGIN_TO_ANSWER]</p>
</div>
</div>
</div>
</div>
[/guest]