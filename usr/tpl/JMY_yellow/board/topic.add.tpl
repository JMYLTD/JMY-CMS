<article class="hentry">
<h4>Добавление темы:</h4><br />
<form name="topic" method="post" action="board/saveTopic" enctype="multipart/form-data">
			<table border="0" cellspacing="1" cellpadding="3">
			  <tbody>
			  <tr>
			    <td width="200">Название темы:</td>
			    <td width="500"><input type="text" name="title" style="width:250px;"></td>
			  </tr>				  
			  <tr>
			    <td width="200">Иконка:
				<br><label><input type="radio" name="icon" value="" checked="">Нет иконки
				</label></td>
			<td width="500">{%ICON%}</td>
			  </tr>			  
			  <tr>
			    <td width="200">Сообщение:</td>
			    <td>
				{%TEXTAREA%}
				</td>
			  </tr>
			  [upload]
				  <tr>				  
					<td width="200">Загрузка файлов:</td>
					<td>
					{%FORUM_UPLOAD%}
					</td>
				  </tr>	
			[/upload]				  
			  <tr>
			    <td width="200"></td>
			    <td align="right">
				<input type="hidden" name="forum" value="{%ID%}">
				<input type="hidden" name="uniqCode" value="{%UNIQCODE%}">
				<input type="submit" value="Создать тему" > <input type="reset" value="Очистить" ></td>
			  </tr>			  
			</tbody></table>
			</form>
			</article>