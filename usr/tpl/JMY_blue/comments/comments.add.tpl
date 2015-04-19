<div class="comment-item">
	<div class="comment-item-avatar">
		<img id="commentator_avatar" src="{THEME}/images/noavatar.png" alt="Ваш аватар">
	</div>
	<div class="comment-body">
		<div class="comment-header">
			Вы вошли как <b id="commentator_name">Гость</b>
			[not-logged]
			<input type="text" name="name" id="name" placeholder="Ваше имя"> &nbsp;&nbsp; <input type="text" name="mail" id="mail" placeholder="Ваш email">
			[/not-logged]
		</div>
		<div class="comment-text clearfix">
			{editor}
			[question]
			<p>Вопрос:	<b>{question}</b></p>
			Ответ: &nbsp; <input type="text" name="question_answer" id="question_answer" >
			<hr>
			[/question]
			[sec_code]
			<div class="seccode">
				{sec_code}
				<input class="captcha_input" type="text" name="sec_code" id="sec_code" >
			</div>
			[/sec_code]
			<button type="submit" name="submit" class="btn btn-small fright">Добавить</button>
			<div class="clr"></div>
		</div> <!-- .comment-text -->
	</div>
</div> <!-- .comment-item -->


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