[voted]
<strong>{%TITLE%}</strong><br /><br />
[viewList]
{%VARIANT%} - {%VARVOTE%} ({%PERCENT%}%)
<div class="bar{%NUMB%}" style="width:{%PERCENTWIDTH%}%;">&nbsp;</div>
[/viewList]

{%LIST%}
Всего проголосовало: {%VOTES%}<br />
[result]<div align="center"><br /><input type="button" value="Вернуться" onclick="ajaxSimple('ajax.php?url=ajax/poll/back_vote/{%ID%}', 'poll_{%ID%}', true)" /></div>[/result]
[/voted]

[novoted]
<div id="poll_{%ID%}">
<form action="profile/edit" method="post" id="pollCheck" name="pollCheck">
<strong>{%TITLE%}</strong><br /><br />
{%LIST%}
<div align="center"><br />
<input type="button" value="Голосовать" onclick="dopoll('pollCheck', 'poll_{%ID%}')" /> <input type="button" value="Результаты" onclick="ajaxSimple('ajax.php?url=ajax/poll/results/{%ID%}', 'poll_{%ID%}', true)" /><br />
Всего проголосовало: {%VOTES%}</div></form>
</div>
[/novoted]