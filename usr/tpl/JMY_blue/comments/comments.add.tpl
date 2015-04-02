[open]
<section class="comment-form">
<h2 class="section-title">Добавить комментарий</h2>
<form role="form">
[user]
<div class="form-group">
<label for="inputEmail">Ваше имя: {%COOKIE_NAME%}</label>
</div>
[/user]
[guest]
<div class="form-group">
<label for="inputName">Ваше имя(обяз.)</label>
<input type="text" action="javascript:void(0);" name="author" value="{%COOKIE_NAME%}" class="form-control" id="author">
</div>
<div class="form-group">
<label for="inputEmail">Ваш e-mail(обяз. не публикуется)</label>
<input type="text" name="email" value="{%COOKIE_MAIL%}" class="form-control" id="email">
</div>
[/guest]
<div class="form-group">
<label for="inputMessage">Комментарий <div id="reply_comment"></div></label><br/>
{%BB_AREA%}
</div>
[guest]
<div class="form-group">
<label for="inputEmail">Картинка с кодом</label>
{%CAPTCHA%}
</div>
<div class="form-group">
<label for="inputEmail">Повторите код каптчи</label>
<input type="text" name="securityCode" id="securityCode" class="form-control" style="width:120px">
</div>
[/guest]
<div class="pull-left">
[noSubscribe]<div id="subsribe{%ID%}"><a href="javascript:void(0)" onclick="subscribeComments(1, 'subsribe{%ID%}');" class="btn btn-ar btn-success">Подписаться на комментарии</a><input type="hidden" value="0" id="subscribe" /></div>[/noSubscribe]
[yesSubscribe]<div id="subsribe{%ID%}"><a href="javascript:void(0)" onclick="subscribeComments(0, 'subsribe{%ID%}');" class="btn btn-ar btn-danger">Отписаться от комментариев</a><input type="hidden" value="1" id="subscribe" /></div>[/yesSubscribe]
</div>
<input type="hidden" name="nid" value="{%ID%}" id="nid" />
<input type="hidden" value="0" id="reply_to" />
<input type="hidden" name="mod" value="{%MOD%}" id="mod" />
<button type="submit" name="submit" id="sub" onclick="comment_post('commentBox'); return false;" class="btn btn-ar pull-right btn-primary">Добавить комментарий</button>
</form>
<div class="section-title"></div>
</section>
[/open]
<script type="text/javascript">
function reply_comment(id){gid('reply_to').value = id; gid('reply_comment').innerHTML = (id > 0 ? '<strong>Ответ на комментарий №:</strong> '+id + ' <a href="javascript:void(0)" onclick="reply_comment(0);">не отвечать</a>' : ''); window.scroll(0, parseInt(gid('comment_add').offsetTop));}
</script>