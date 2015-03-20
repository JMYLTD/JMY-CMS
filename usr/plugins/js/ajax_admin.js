function ajaxEngine(replaceID, type) {
	var xmlhttp = false;
	/*if(type != 2)
	{
		gid(replaceID).innerHTML = '<div align="center"><img src="media/loading.gif" /></div>';
	}*/
	
	try {
		xmlhttp = new XMLHttpRequest();
	} 
	catch (e) {
		try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		}
		catch (e) {
		xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
		}
	}


	xmlhttp.onreadystatechange = function()	{
		if (xmlhttp.readyState == 4) {
			if (xmlhttp.status == 200) {
				if(type == 2)
				{
					document.getElementById(replaceID).value = xmlhttp.responseText;
				}
				else
				{
					document.getElementById(replaceID).innerHTML = xmlhttp.responseText;
				}
			} else {
				//alert('AJAX: ошибка выполнения!');
				return;
			}
		}
	}

	return xmlhttp;
}


function ajaxGet(uri, id) {
	var xmlhttp = ajaxEngine(id);
	var link = uri;

	xmlhttp.open('GET', link, true);
	xmlhttp.setRequestHeader('X_REQUESTED_WITH', 'XMLHttpRequest');
	xmlhttp.send(null);
}

function ajaxPost(uri, id, data, type) {
	var xmlhttp = ajaxEngine(id, type);
	var link = uri + '/rand-' + Math.random();

	xmlhttp.open('POST', link, true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send(data);
}

function genPreview(title, shortNews, fullNews)
{
	alert(shortNews);
	if(caa(false))
	{
		var xmlhttp = false
		
		try {
			xmlhttp = new XMLHttpRequest();
		} 
		catch (e) {
			try {
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
			}
			catch (e) {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
			}
		}

		xmlhttp.open('POST', 'ajax.php?do=getPreview', false);
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlhttp.send('title='+encodeURIComponent(title)+'&shortNews='+encodeURIComponent(shortNews)+'&fullNews='+encodeURIComponent(fullNews));

		if (document.documentElement && (document.documentElement.clientWidth || document.documentElement.clientHeight)) 
		{
			clHeight = document.documentElement.clientHeight;
			clientWidth = document.documentElement.clientWidth;
		}
		else if (document.body && (document.body.clientWidth || document.body.clientHeight)) 
		{
			clHeight = document.body.clientHeight;
			clientWidth = document.body.clientWidth;
		}
			
		Shadowbox.open({
			content:	xmlhttp.responseText,
			player:     "html",
			title:      "Предпросмотр контента",
			width: (clientWidth-200),
			height:(clHeight-50)
		});
	}
}


function inputTags(tags, id, input)
{
	arr = tags.split(',');
	if(arr[(arr.length-1)].length > 1) 
	{
		if(gid(id).style.display=="none")
		{
			showhide(id);
		}

		ajaxGet('ajax.php?do=inputTags&query='+tags+'&input='+input, id);
	}
}

/*
	Этот участо кода позаимствован из Eleanor CMS Выражаю автору большую благодарность!
*/
function EditTitle(div, mod, id, subarr)
{
	var tempText = gid(div).innerHTML;
	
	if(subarr)
	{
		var text = gid(subarr).innerHTML;
	}
	else
	{
		var text = gid(div).innerHTML;
	}
	
	text = text.replace(/"/g, '&quot;');

	gid(div).innerHTML = '<input type="text" id="inputEdit_' + id + '" value="' + text + '" style="width:90%" class="EditTitle" />';
	gid('inputEdit_' + id).focus();
	gid('inputEdit_' + id).onblur = function()
	{
		gid(div).innerHTML = tempText;
		gid(div).onclick = function()
		{
			EditTitle(div, mod, id, subarr);
		}
	};
	
	gid('inputEdit_' + id).onkeypress = function(e)
	{
		e = e ? e : event;
		if (e.keyCode == 13 || e.keyCode == 10)
		{
			SaveTitle(mod, id, this.value);
			return false;
		}
	}

	gid(div).onclick = function(){}
}

function getTranslit(text, input)
{
	if(text.length > 0)
	{
		var data = "query=" + encodeURIComponent(text);
		ajaxPost('ajax.php?do=getTranslit', input, data, 2);
	}
	else
	{
		gid(input).value = '';
	}
}