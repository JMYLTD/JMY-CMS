<h4>Обратная связь</h4>
<form action="feedback/send" method="post" class="contactForm" id="contactForm" onsubmit="return validate(this)" enctype="multipart/form-data">
<fieldset>   
<div class="formFieldWrap">
<label class="field-title contactNameField" for="contactNameField">Имя *:</label>
<input type="text" id="author" name="name" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField" for="contactEmailField">Email *:</label>
<input type="text" id="email" name="email" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField" for="contactEmailField">Тема *:</label>
<input type="text" id="url" name="topic" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formTextareaWrap">
<label class="field-title contactMessageTextarea" for="contactMessageTextarea">Сообщение *:</label>
<textarea id="comment" name="message" class="contactTextarea requiredField"></textarea>
</div>
<div class="formFieldWrap">
<center style="margin-bottom:10px;">{%CAPTCHA%}</center>
<input class="reg-captcha" type="text" name="securityCode" id="securityCode" value="">
</div>
<div class="formSubmitButtonErrorsWrap">
<input name="submit" type="submit" id="submit" class="buttonWrap button button-green contactSubmitButton" value="Отправить"/>
<input type="hidden" name="comment_post_ID" value="13" id="comment_post_ID">
<input type="hidden" name="comment_parent" id="comment_parent" value="0">
</div>
</fieldset>
</form>