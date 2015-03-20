<article class="hentry">
<h4>Редактирование сообщения:</h4><br />
<form name="topic" method="post" action="board/ajax/fastSave" enctype="multipart/form-data">
			<table border="0" cellspacing="1" cellpadding="3">
			  <tbody>							  
			  <tr>
			    <td width="200">Сообщение:</td>
			    <td>
				{%TEXTAREA%}
				</td>
			  </tr>
			  [file]
			    <tr>
					<td width="200">Удаление файлов:</td>
					<td><input type="hidden" name="files" value="1"/>
						{%FILE%}<br />
					</td>
				  </tr>
				[/file]
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
				<input type="hidden" name="id" value="{%ID%}" />
				<input type="hidden" name="page" value="{%PAGE%}" />
				<input type="hidden" name="tid" value="{%TID%}" />
				<input type="submit" value="Редактировать" ></td>
			  </tr>			  
			</tbody></table>
			</form>
			</article>