<h4>Создание блога</h4>
<form action="blog/saveBlog" method="post" onsubmit="return checkAddPost();"  enctype="multipart/form-data" class="contactForm" id="contactForm">   
<div class="formFieldWrap">
<label class="field-title contactNameField">[lang:_BLOG_TITLE]:</label>
<input type="text" name="title" value="{%TITLE%}" id="title" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">[lang:_BLOG_URL_TITLE]:</label>
<input type="text" name="altname" value="{%ALTNAME%}" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formTextareaWrap">
<label class="field-title contactMessageTextarea" for="contactMessageTextarea">[lang:_BLOG_DESCRIPTION]:</label>
<textarea name="description" id="description" class="contactTextarea requiredField">{%DESCRIPTION%}</textarea>
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">[lang:_BLOG_AVATAR]:</label>
{%AVATAR_REPLACE%}<input name="blogAvatar" type="file"/>
</div>
<div class="formSubmitButtonErrorsWrap">
<input type="submit" class="buttonWrap button button-green contactSubmitButton" value="[lang:_BLOG_CREATE2]"/>
</div>
</fieldset>
</form>