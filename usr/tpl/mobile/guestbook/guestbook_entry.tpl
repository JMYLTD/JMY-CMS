<h4>Добавить отзыв</h4>
<form class="contactForm" id="contactForm" action="guestbook/send" method="post" novalidate="" onsubmit="return validate(this)" enctype="multipart/form-data">
<fieldset>   
<div class="formFieldWrap">
<label class="field-title contactNameField" for="contactNameField">Ваше имя(обяз.):<span>(*)</span></label>
<input type="text" id="author" name="name" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField" for="contactEmailField">Ваш e-mail(обяз. не публикуется):<span>(*)</span></label>
<input type="text" id="email" name="email" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField" for="contactEmailField">Ваш сайт:<span>(*)</span></label>
<input type="text" id="url" name="site" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField" for="contactEmailField">Ваш пол:<span>(*)</span></label>
<select class="contactField requiredField requiredEmailField" name="gender">
<option value="1">Мужской</option>
<option value="2">Женский</option>
</select>
</div>
<div class="formTextareaWrap">
<label class="field-title contactMessageTextarea" for="contactMessageTextarea">Сообщение:<span>(*)</span></label>
<textarea id="comment" name="message" rows="8" aria-required="true" class="contactTextarea requiredField"></textarea>
</div>
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
<div class="formSubmitButtonErrorsWrap">
<input name="submit" type="submit" id="submit" class="buttonWrap button button-green contactSubmitButton" value="Добавить"/>
</div>
</fieldset>
</form><br/>