<h4 class="mt-0">{%TITLE%}<div style="float:right">{%NEW_TOPIC%}</div></h4>
<div class="table-responsive mb-20">
[moder]<form id="tablesForm" method="post" action="board/do">[/moder]
<table cellspacing="1" class="table table-striped table-success table-bordered">
<thead>
[noempty]
			<tr>
				<th colspan="3" width="68%">Название темы</th>
				<th width="6%" align="center">Ответов</th>
				<th width="6%" align="center">Автор</th>
				<th width="7%" align="center">Просмотров</th>
				<th width="35%">Последнее</th>
				[moder]<th width="1%"><input type="checkbox" name="all" onclick="setCheckboxes('tablesForm', true); return false;"></th>[/moder]
			</tr>			
			[important]<tr class="thTitle"><td class="thTitle" colspan="8" align="left">Важные темы</td></tr>[/important]
			{%TOPIC_IMPORTANT%}		
			[last]<tr class="thTitle"><td class="thTitle" colspan="8" align="left">Последние темы</td></tr>[/last]
			{%TOPIC_LAST%}
			<tr class="thNOanim"><td class="thNOanim" colspan="8">
				[search]
				[admin]
				<div style="float:left">
					
						<input type="hidden" name="tid" value="2">
						Админу: <select name="deiv">
							<option value="close_forum">Закрыть форум</option>
							<option value="open_forum">Открыть форум</option>
						</select>
						<input type="submit" class="btn btn-theme" value="Сделать">
					
					</div>
				[/admin]
				[/search]
				[nomoder]
				<div style="float:right">
							<form method="post" name="forumSearch" action="board/search">
								<input type="hidden" name="tid" value="{%ID%}"/>
								<input type="text" name="query" value="{%QUERY%}" />
								<input type="submit" class="btn btn-theme" value="Искать" />
							</form>
						</div>
				[/nomoder]
				[search]
				[moder]
					<div style="float:right">
						<input type="hidden" name="fid" value="{%T_ID%}">
						<select name="deiv">
							<option value="important">Важная</option>
							<option value="noimportant">Обычная</option>
							<option value="close">Закрытая тема</option>
							<option value="open">Открыть</option>
							<option value="delete">Удалить</option>
						</select>
						<input type="submit" class="btn btn-theme" value="Поехали">
					</div>
				[/moder]
				[/search]
				</td></tr>
			[/noempty]
			[empty]<tr class="thNOanim"><td colspan="8" align="center" class="thNOanim"><strong>{%MASSAGE%}</strong></td></tr>[/empty]
			</thead>
		</table>
[moder]</form>[/moder]
</div>
<br />