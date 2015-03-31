<div class="panel panel-default">
<div class="panel-heading">Обратная связь</div>
<div class="panel-body">
<form role="form" action="feedback/send" method="post" onsubmit="return validate(this)" enctype="multipart/form-data">
<div class="well well-sm">Ваш электронный адрес не будет опубликован. Обязательные поля помечены <span class="required" color="red">*</span>
<p>Вы можете использовать <abbr title="HyperText Markup Language">HTML</abbr> теги и атрибуты:  <code>&lt;a href="" title=""&gt; &lt;abbr title=""&gt; &lt;acronym title=""&gt; &lt;b&gt; &lt;blockquote cite=""&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=""&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=""&gt; &lt;strike&gt; &lt;strong&gt; </code></p>
</div>
<div class="form-group">
<label>Имя*</label>
<input type="text" id="author" name="name" class="form-control">
</div>
<div class="form-group">
<label>Email*</label>
<input type="text" id="email" name="email" class="form-control">
</div>
<div class="form-group">
<label>Тема*</label>
<input type="text" id="url" name="topic"  class="form-control">
</div>
<div class="form-group">
<label>Сообщение*</label>
<textarea class="form-control" rows="5" id="comment" name="message"></textarea>
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
<button name="submit" type="submit" id="submit" class="btn btn-ar btn-primary">Отправить</button>
<input type="hidden" name="comment_post_ID" value="13" id="comment_post_ID">
<input type="hidden" name="comment_parent" id="comment_parent" value="0">
</form>
</div>
</div>