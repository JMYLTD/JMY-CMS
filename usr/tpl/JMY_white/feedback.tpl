<div id="comments" class="comments-area blogghiamoanim fadeInUp animated" style="visibility: visible; -webkit-animation: fadeInUp;">
	<div id="respond" class="comment-respond">
		<h3 id="reply-title" class="comment-reply-title">Обратная связь</h3>
		<form action="feedback/send" method="post" id="commentform" class="comment-form" novalidate="" onsubmit="return validate(this)" enctype="multipart/form-data">
			<p class="comment-notes">Ваш электронный адрес не будет опубликован. Обязательные поля помечены <span class="required" color="red">*</span></p>				<p class="comment-form-author">
				<label for="author"><span class="screen-reader-text">Имя *</span></label>
				<input id="author" name="name" type="text" value="" aria-required="true" placeholder="Имя *">
			</p>
			<p class="comment-form-email">
				<label for="email"><span class="screen-reader-text">Email *</span></label>
				<input id="email" name="email" type="text" value="" aria-required="true" placeholder="Email *">
			</p>
			<p class="comment-form-url">
				<label for="url"><span class="screen-reader-text">Тема *</span></label>
				<input id="url" name="topic" type="text" value="" placeholder="Тема *">
			</p>
			<div class="clear"></div>
			<p class="comment-form-comment">
				<label for="comment"><span class="screen-reader-text">Сообщения *</span></label>
				<textarea id="comment" name="message" rows="8" aria-required="true" placeholder="Сообщения *"></textarea>
			</p>
			[captcha]
			<p class="comment-form-comment">
				<td valign="top" >Код безопасности <font color="red">*</font>:</td>
				<td><br>{%CAPTCHA%}[recaptcha:0]<br><input type="text" name="securityCode" id="securityCode" size="15" maxlength="6">[/recaptcha]</td>
			</p>
			[/captcha]			
			<p class="form-allowed-tags" id="form-allowed-tags">Вы можете использовать <abbr title="HyperText Markup Language">HTML</abbr> теги и атрибуты:  <code>&lt;a href="" title=""&gt; &lt;abbr title=""&gt; &lt;acronym title=""&gt; &lt;b&gt; &lt;blockquote cite=""&gt; &lt;cite&gt; &lt;code&gt; &lt;del datetime=""&gt; &lt;em&gt; &lt;i&gt; &lt;q cite=""&gt; &lt;strike&gt; &lt;strong&gt; </code></p>					
			<p class="form-submit">
				<input name="submit" type="submit" id="submit" class="submit" value="Отправить">
				<input type="hidden" name="comment_post_ID" value="13" id="comment_post_ID">
				<input type="hidden" name="comment_parent" id="comment_parent" value="0">
			</p>
		</form>
	</div>			
</div>