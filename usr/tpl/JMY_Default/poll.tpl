[voted]
<strong>{%TITLE%}</strong>
[viewList]
{%VARIANT%} - {%VARVOTE%} ({%PERCENT%}%)
<div class="bar{%NUMB%}" style="width:{%PERCENTWIDTH%}%;">&nbsp;</div>
[/viewList]

{%LIST%}
Всего проголосовало: {%VOTES%}
[result]<div align="center"><br /><input type="button" value="Вернуться" onclick="ajaxSimple('ajax.php?url=ajax/poll/back_vote/{%ID%}', 'poll_{%ID%}', true)" class="btn btn-primary btn-ar btn-xs"/></div>[/result]
[/voted]

[novoted]
<div id="poll_{%ID%}">
<form action="profile/edit" method="post" id="pollCheck" name="pollCheck">
<strong>{%TITLE%}</strong>
{%LIST%}
<div align="center">
<input type="button" value="Голосовать" onclick="dopoll('pollCheck', 'poll_{%ID%}')" class="btn btn-success btn-ar btn-xs" /> <input type="button" value="Результаты" onclick="ajaxSimple('ajax.php?url=ajax/poll/results/{%ID%}', 'poll_{%ID%}', true)" class="btn btn-primary btn-ar btn-xs"/><br />
Всего проголосовало: {%VOTES%}</div></form>
</div>
[/novoted]