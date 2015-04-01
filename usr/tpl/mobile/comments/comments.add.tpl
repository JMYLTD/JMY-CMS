[open]
<h4>Добавить комментарий [user]<strong>Ваше имя:</strong> {%COOKIE_NAME%}[/user]</h4>
<form class="contactForm" id="contactForm">
<fieldset>
[guest]   
<div class="formFieldWrap">
<label class="field-title contactNameField" for="contactNameField">Ваше имя(обяз.):<span>(*)</span></label>
<input type="text" action="javascript:void(0);" name="author" value="{%COOKIE_NAME%}" id="author" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField" for="contactEmailField">Ваш e-mail(обяз. не публикуется):<span>(*)</span></label>
<input type="text" name="email" value="{%COOKIE_MAIL%}" id="email" class="contactField requiredField requiredEmailField"/>
</div>
[/guest]
<div class="formTextareaWrap">
<label class="field-title contactMessageTextarea" for="contactMessageTextarea">Комментарий: <div id="reply_comment"></div><span>(*)</span></label>
{%BB_AREA%}
</div>
[guest]
<div class="formFieldWrap">
<label class="field-title contactEmailField" for="contactEmailField">Проверка:<span>(*)</span></label>
<center>{%CAPTCHA%}</center>
</div>
[captcha]
<div class="formFieldWrap">
<label class="field-title contactEmailField" for="contactEmailField">Повторите код каптчи:<span>(*)</span></label>
<input type="text" name="securityCode" id="securityCode" class="contactField requiredField requiredEmailField"/>
</div>
[/captcha]
[/guest]
<div class="formSubmitButtonErrorsWrap">
<input type="hidden" name="nid" value="{%ID%}" id="nid" />
<input type="hidden" value="0" id="reply_to" />
<input type="hidden" name="mod" value="{%MOD%}" id="mod" />
<input type="submit" id="sub" name="submit" onclick="comment_post('commentBox'); return false;" class="buttonWrap button button-green contactSubmitButton" value="Добавить комментарий"/>
</div>
</fieldset>
</form><br/>
[/open]
<script type="text/javascript">
function reply_comment(id){gid('reply_to').value = id; gid('reply_comment').innerHTML = (id > 0 ? '<strong>Ответ на комментарий №:</strong> '+id + ' <a href="javascript:void(0)" onclick="reply_comment(0);">не отвечать</a>' : ''); window.scroll(0, parseInt(gid('comment_add').offsetTop));}
</script>