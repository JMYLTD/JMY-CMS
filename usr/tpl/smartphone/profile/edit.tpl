[open]
<div class="decoration"></div>
<div class="content">
<div class="blog-posts">
<h4>Данные пользователя</h4>
<form action="profile/edit" method="post" enctype="multipart/form-data" onsubmit="showload();" class="contactForm" id="contactForm">
<fieldset>   
<div class="formFieldWrap">
<label class="field-title contactNameField">Фамилия:</label>
<input type="text" name="surname" value="{%SURNAME%}" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">Имя:</label>
<input type="text" name="name" value="{%NAME%}" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">Отчество:</label>
<input type="text" name="ochestvo" value="{%OCH%}" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">Дата рождения:</label>
<select name="birthDay" class="contactField requiredField requiredEmailField">{%DAY_LIST%}</select> <select name="birthMonth" class="contactField requiredField requiredEmailField">{%MONTH_LIST%}</select> <select name="birthYear" class="contactField requiredField requiredEmailField">{%YEAR_LIST%}</select>
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">Пол:</label>
<select name="gender" class="contactField requiredField requiredEmailField">{%GENDER_LIST%}</select>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">Email:</label>
<input type="text" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">Хобби:</label>
<input type="text" name="hobby" value="{%HOBBY%}" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">Место проживания:</label>
<input type="text" name="place" value="{%PLACE%}" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">Настройки аватарки:</label>
<input type="text" name="avatar_link" value="Ведите url адрес картинки" class="contactField requiredField"/>
<a class="checkbox checkbox-one" type="checkbox" name="deleteAvatar" value="1" /> Удалить?</a>
</div>
{%AVATAR_LOAD%}
<div class="formFieldWrap">
<label class="field-title contactNameField">Загрузить аватар:</label>
<input type="file" name="avatar"/>
</div><br/>
{%/AVATAR_LOAD%}
<div class="formFieldWrap">
<label class="field-title contactEmailField">Настройки подписи:</label>
{%BB_AREA%}
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">E-Mail:</label>
<input type="text" name="mail" value="{%EMAIL%}" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">Icq:</label>
<input type="text" name="icq" value="{%ICQ%}" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">Skype:</label>
<input type="text" name="skype" value="{%SKYPE%}" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">Новый пароль:</label>
<input type="password" name="newpass" value="" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">Повторите новый пароль:</label>
<input type="password" name="renewpass" value="" class="contactField requiredField requiredEmailField"/>
</div>
[xfield_tpl]
<div class="formFieldWrap">
<label class="field-title contactNameField">{%XTITLE%}:</label>
{%XBODY%}
</div>
[/xfield_tpl]
[fields]
<div class="formFieldWrap">
{%XFIELDS%}
</div>
[/fields]
<div class="formSubmitButtonErrorsWrap">
<input type="submit" class="buttonWrap button button-green contactSubmitButton" value="Сохранить"/>
</div>
</fieldset>
</form>
</div>
</div>                    
[/open]                    