<script language="javascript">function checkAddPost(){var err = 0;gid('titleErr').innerHTML = '';gid('postTextErr').innerHTML = '';if(gid('title').value == ''){err = 1;gid('titleErr').innerHTML = '<font color="red">Вы не заполнили заголовок новости!</font>';} if(gid('short').value == ''){	err = 1;gid('postTextErr').innerHTML = '<font color="red">Вы не заполнили анонс новости!</font>';}if(err == 1){alert('Одно из обязательных полей не заполнено, проверте правильность ввода данных!');return false;}else{return true;}}</script>
<article class="hentry">
<form action="news/savePost" method="post" onsubmit="return checkAddPost();">
<div style="width:90%">
<div class="padding inputTitle">Заголовок новости:</div>
<div class="padding" style="padding-bottom:10px;"><input name="title" type="text" id="title" value="" size="35" />
<br /><sup>Название вашей новости (не более 35 символов)</sup><div id="titleErr"></div></div>
<div class="padding inputTitle">Выберите категорию для публикации:</div>
<div class="padding" style="padding-bottom:10px;"><select name="category[]" ><option value="0">Без категории</option>{%CATS%}</select>
</div>
<div class="padding inputTitle">Связанные категории:</div>
<div class="padding" style="padding-bottom:10px;"><select name="category[]" style="min-width:150px; height:120px;" multiple >{%CATS%}</select>
</div>

<div class="padding inputTitle">Краткая новость:</div>
<div class="padding" style="padding-bottom:10px;">
{%BB_SHORT%}
<sup>Этот текст выводится на главной странице и категориях</sup>
<div id="postTextErr"></div></div>
<div class="padding inputTitle">Полная новость:</div>
<div class="padding" style="padding-bottom:10px;">
{%BB_FULL%}
<br /><sup>А этот выводится при подробном просмотре (необязательно)</sup>
{%XFILEDS%}
[fileupload]
<div class="padding inputTitle" onclick="uploaderStart(); showhide('file_upload', true);" style="text-decoration:underline; cursor:pointer;"><span>Файловый редактор:</span></div>
<div class="padding" style="padding-bottom:10px; display:none;" id="file_upload">
{%FILE_UPLOAD%}
</div>
[/fileupload]
</div>
<div class="padding"><input name="" type="submit" value="Отправить новость" /> 
</div>
</form>
</article>