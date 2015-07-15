<article class="hentry inform">
<div id="comments" class="comments-area">	
	<ol class="comment-list">
		<li id="comment-114" class="comment even thread-even depth-1">
[open]
<div class="commentAdd" id="comment_add">
[guest]
<div style="margin-bottom:2px;">
<input type="text" action="javascript:void(0);" name="author" value="{%COOKIE_NAME%}" size="40" id="author" />
<label for="author" id="name" class="ajax_error"><small class="content">Ваше имя(обяз.)</small></label>
</div>
<div style="margin-bottom:2px;">
<input type="text" name="email" value="{%COOKIE_MAIL%}" size="40" id="email" />
<label for="email" id="mail" class="ajax_error"><small class="content">Ваш e-mail(обяз. не публикуется)</small></label>
</div>
[/guest]
[user]
<div><strong>Ваше имя:</strong> {%COOKIE_NAME%}</div>
[/user]
<div id="reply_comment"></div>
<table width="500" border="0" cellspacing="0" cellpadding="0"><tr><td>{%BB_AREA%}</td></tr></table>
[captcha]
<table width="100%" border="0" cellpadding="6" cellspacing="0">
<tr><td width="150"><strong>Картинка с кодом </strong><span class="must" title="Обязательный пункт">*</span><br/>
{%CAPTCHA%}</td>[recaptcha:0]<td align="left"><b>Повторите</b><span class="must" title="Обязательный пункт">*</span><br/>
<input name="securityCode" id="securityCode" type="text" maxlength="255" value="" /><br/>
<sup>Повторите код каптчи</sup></td>[/recaptcha]
</tr>
</table>
[/captcha]
<input type="hidden" name="nid" value="{%ID%}" id="nid" />
<input type="hidden" value="0" id="reply_to" />
<input type="hidden" name="mod" value="{%MOD%}" id="mod" />
[noSubscribe]<br /><div id="subsribe{%ID%}"><a href="javascript:void(0)" onclick="subscribeComments(1, 'subsribe{%ID%}');">Подписаться на комментарии</a><input type="hidden" value="0" id="subscribe" /></div>[/noSubscribe]
[yesSubscribe]<br /><div id="subsribe{%ID%}"><a href="javascript:void(0)" onclick="subscribeComments(0, 'subsribe{%ID%}');">Отписаться от комментариев</a><input type="hidden" value="1" id="subscribe" /></div>[/yesSubscribe]
<br /><input name="submit" type="button" id="sub" value="Разместить" onclick="comment_post('commentBox'); return false;" />
</div>
[/open]
<script type="text/javascript">
function reply_comment(id){gid('reply_to').value = id; gid('reply_comment').innerHTML = (id > 0 ? '<strong>Ответ на комментарий №:</strong> '+id + ' <a href="javascript:void(0)" onclick="reply_comment(0);">не отвечать</a>' : ''); window.scroll(0, parseInt(gid('comment_add').offsetTop));}
</script>	
</li>
</ol>					
</div>
</article>