<div id="comments" class="comments-area blogghiamoanim fadeInUp animated" style="visibility: visible; -webkit-animation: fadeInUp;">
	<div id="respond" class="comment-respond">
		<h3 id="reply-title" class="comment-reply-title">Ваш отзыв:</h3>
			<form action="guestbook/send" method="post" id="commentform" class="comment-form" onsubmit="return validate(this)" enctype="multipart/form-data">
			<p class="comment-notes">Обязательные поля помечены <span class="required" color="red">*</span></p>	
			[guest]
			<p class="comment-form-author">
				<label for="author"><span class="screen-reader-text">Имя *</span></label>
				<input id="author" name="name" type="text" value="" aria-required="true" placeholder="Имя *">
			</p>
			<p class="comment-form-email">
				<label for="email"><span class="screen-reader-text">Email *</span></label>
				<input id="email" name="email" type="text" value="" aria-required="true" placeholder="Email *">
			</p>
			<p class="comment-form-url">
				<label for="url"><span class="screen-reader-text">Сайт *</span></label>
				<input id="url" name="site" type="text" value="" placeholder="Сайт">
			</p>
			<p class="comment-form-url">
				<label for="url"><span class="screen-reader-text">Пол *</span></label>
				<select  class="uk-width-1-1" name="gender">
					<option value="1">Мужской</option>
					<option value="2">Женский</option>
				</select>
			</p>
			[/guest]
			[user]
			<p class="comment-notes">Вы вошли как: {%UNAME%}</p>	
			<input type="hidden" name="uid" value="{%UID%}">
			[/user]
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
			<p class="form-submit">
				<input name="submit" type="submit" id="submit" class="submit" value="Отправить">				
			</p>
		</form>
	</div>			
</div>										