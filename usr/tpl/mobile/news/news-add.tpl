<script language="javascript">function checkAddPost(){var err = 0;gid('titleErr').innerHTML = '';gid('postTextErr').innerHTML = '';if(gid('title').value == ''){err = 1;gid('titleErr').innerHTML = '<font color="red">Вы не заполнили заголовок новости!</font>';} if(gid('short').value == ''){   err = 1;gid('postTextErr').innerHTML = '<font color="red">Вы не заполнили анонс новости!</font>';}if(err == 1){alert('Одно из обязательных полей не заполнено, проверте правильность ввода данных!');return false;}else{return true;}}</script>
<h4>Добавить новость</h4>
<form action="news/savePost" method="post" onsubmit="return checkAddPost();" class="contactForm" id="contactForm">
<fieldset>   
<div class="formFieldWrap">
<label class="field-title contactNameField">Заголовок новости:</label>
<input type="text" name="title" id="title" class="contactField requiredField"/>
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">Выберите категорию для публикации:</label>
<select name="category[]" class="contactField requiredField"><option value="0">Без категории</option>{%CATS%}</select>
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">Краткая новость:</label>
{%BB_SHORT%}
</div>
<div class="formFieldWrap">
<label class="field-title contactNameField">Полная новость:</label>
{%BB_FULL%}
</div>
{%XFILEDS%}
[fileupload]
<div class="formFieldWrap">
<label class="field-title contactNameField" onclick="uploaderStart(); showhide('file_upload', true);" style="text-decoration:underline; cursor:pointer;">Файловый редактор:</label>
<div style="padding-bottom:10px; display:none;" id="file_upload">{%FILE_UPLOAD%}</div>
</div>
[/fileupload]
<div class="formSubmitButtonErrorsWrap">
<input type="submit" class="buttonWrap button button-green contactSubmitButton" value="Добавить новость"/>
</div>
</fieldset>
</form>