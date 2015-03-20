[open]
<form  style="margin:0; padding:0" method="POST" action="pm/action">

<table class="forum" width="100%" border="0" cellpadding="3" cellspacing="1" bgcolor="#d2d2d4">
			<tr bgcolor="#f7f7f7"  align="center">
			<th><b>{%TH1%}</b></th>
			<th><b>Сообщение</b></th>
			<th style="text-align:center"><b>Дата</b></th>
			<th width="20"><input type="checkbox" name="all" onclick="setCheckboxes(\'tablesForm\', true); return false;"></th>
			</tr>
			[messEmpty]<tr class="thTitle" bgcolor="#f7f7f7"><td class="thTitle" colspan="5" align="center">У вас нет {%MESSNO%}</td></tr>[/messEmpty]
			
[list]<tr bgcolor="#ffffff">
	<td width="120" style="text-align:center"><img src="{%AVATAR%}" alt="" style="border:1px #ccc solid; padding:2px;" /><br /><a href="profile/{%NICK%}">{%NICK%}</a></td>
	<td valign="top"><a href="{%MOD_NAME%}/view/{%MESSID%}">{%MESSAGE%}</a><hr />
	[ [status=0]<span style="color:red">Не прочитано</span>[else]<span  style="color:green">Прочитано</span>[/status] ]
	[initfrom]<span id="msgNo{%MESSID%}"><a href="javascript://" onClick="ajaxSimple(\'index.php?url={%MOD_NAME%}/del/{%MESSID%}/\', \'msgNo{%MESSID%}\', true)">Удалить сообщение</a></span>[/initfrom] </td>
	<td style="text-align:center" width="150">{%DATE%} </td>
	<td width="20" style="text-align:center"><input type="checkbox" name="checks[]" value="{%MESSID%}" /></td>
	</tr>[/list]
	
<tr class="thNOanim" bgcolor="#f7f7f7">
	<td class="thNOanim" colspan="4" align="right">С отмеченными: <input type="submit" name="del" value="Удалить" class="authButton" onclick="return confirm('Увеерны что хотите удалить отмеченные сообщения?');" style="width:100px;"/> [initfrom]<input type="hidden" name="place" value="inbox"/><input type="submit" name="read" value="Прочитать" class="authButton" style="width:100px;"/>[/initfrom]</td>
	</tr>
	</table></form>
[/open]