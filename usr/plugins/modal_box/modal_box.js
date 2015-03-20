function modal_box(id)
{
	var title = eval('title'+id);
	var subtitle = eval('subtitle'+id);
	var content = eval('content'+id);
	if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) 
		clHeight = document.documentElement.clientHeight;
	else if (document.body && (document.body.clientWidth || document.body.clientHeight)) 
		clHeight = document.body.clientHeight;
	topOffset = ((clHeight-400)/2);
	
	if(eval('nowModBox') != '')
	{
		gid('boxes'+eval('nowModBox')).innerHTML = '';
		nowModBox = '';
	}

	gid('boxes'+id).innerHTML = '<div class="generic_dialog" id="boxHide" style="top: ' + topOffset + 'px;"><div class="generic_dialog_popup"><table class="pop_dialog_table" id="pop_dialog_table" style="width: 532px;"><tbody><tr><td class="pop_topleft"/><td class="pop_border pop_top"/><td class="pop_topright"/></tr><tr><td class="pop_border pop_side"/><td id="pop_content" class="pop_content"><h2 class="dialog_title"><span>' + title + '</span></h2><div class="dialog_content"><div class="dialog_summary">' + subtitle + '</div><div class="dialog_body"><div class="ubersearch search_profile"><div class="result clearfix"><div class="info11"><p>' + content + '</p></div><div class="clear" style="clear:both;"></div></div></div></div><div class="dialog_buttons"><input type="button" value="Закрыть" name="close" class="inputsubmit" onclick="clearModal_box(\'' + id + '\')" /></div></div></td><td class="pop_border pop_side"/></tr><tr><td class="pop_bottomleft"/><td class="pop_border pop_bottom"/><td class="pop_bottomright"/></tr></tbody></table></div></div>';
	
	nowModBox = id;
}

function clearModal_box(id)
{
	gid('boxes'+id).innerHTML = '';
	var scrollTop = document.documentElement.scrollTop;
	window.scroll(0,scrollTop+1);
	nowModBox = '';
}