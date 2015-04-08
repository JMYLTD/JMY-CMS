<form action="blog/savePost" method="post" onsubmit="return checkAddPost();" class="contactForm" id="contactForm">   
<div class="formFieldWrap">
<label class="field-title contactNameField">[lang:_BLOG_SELECT_BLOG]:</label>
<select name="blog" class="contactField requiredField" {%BLOGCHOOSE%}>{%BLOGS%}</select>
</div>
<div class="formFieldWrap">
<label class="field-title contactEmailField">[lang:_BLOG_POST_TITLE]:</label>
<input name="title" type="text" id="title" value="{%TITLE%}" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formTextareaWrap">
<label class="field-title contactMessageTextarea" for="contactMessageTextarea">[lang:_BLOG_TEXT]:</label>
{%TEXTAREA%}
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">[lang:_BLOG_TAGS]:</label>
<input name="tags" type="text" id="tags" value="{%TAGS%}" class="contactField requiredField requiredEmailField"/>
</div>
<div class="formSubmitButtonErrorsWrap">
<input type="submit" class="buttonWrap button button-green contactSubmitButton" value="[lang:_BLOG_WRITE2]"/>
</div>
</fieldset>
</form>