<article class="inform hentry">
<h4>Редактирование темы:</h4><br />
<form name="topic" method="post" action="board/userSave/{%HASH%}" enctype="multipart/form-data">
			<table border="0" cellspacing="1" cellpadding="3">
			  <tbody>
			  <tr>
			    <td width="200">Название темы:</td>
			    <td width="500"><input type="text" value="{%NAME%}" name="title" style="width:250px;"></td>
			  </tr>				  
			  <tr>
			    <td width="200">Иконка:
				<br><label><input type="radio" name="icon" value="" checked="">Нет иконки
				</label></td>
			<td width="500"><br />{%ICON%}<br /><br /></td>
			  </tr>	 
			 			  
			  <tr>
			    <td width="200"></td>
			    <td align="right">
				<input type="hidden" name="forum" value="{%FORUM_NAME%}" />
							<input type="hidden" name="tid" value="{%ID%}" />
							<input type="hidden" name="type" value="topic" />
				<input type="submit" value="Редактировать тему" ></td>
			  </tr>			  
			</tbody></table>
			</form>
			</article>