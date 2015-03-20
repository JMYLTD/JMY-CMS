function insertBB(code, type) 
{
	if(userMod == true)
	{
		var obj = window.opener.document.getElementById(textareaName);
	}
	else
	{
		var obj = gid(textareaName);
	}

	if (window.getSelection)
	{
		start = obj.selectionStart;
		end = obj.selectionEnd;
		var selectedText = obj.value.substr(start,end-start);
	}
	else if (document.selection)
	{
	
		var txt = document.selection.createRange();  
		var clone = txt.duplicate();
		var oldstr=txt.text; 
		
		txt.collapse();
		clone.moveToElementText(obj);
		clone.setEndPoint('EndToEnd', txt);
		
		var start=clone.text.length;
		var end=start+oldstr.length;
		
		var selectedText = obj.value.substr(start,end-start);
	}
	
	if(selectedText.length > 3)
	{
		var change = true;
	}
	else
	{
		var change = false;
	}
	
	if(code == 'image')
	{
		if (change == true)
		{
			insertCode('img');
		}
		else
		{
			var desc = prompt('Адрес картинки:', 'http://');
			var align = prompt('Выравнивание(center|left|right):', 'center');
			if(desc)
			{
				insertCode('img', align, desc);
			}
		}
	}
	else if(code == 'url')
	{
	
		var url = prompt('Адрес:', 'http://');
		if(url)
		{
			if (change == true)
			{
				insertCode('url', url);
			}
			else
			{
				var desc = prompt('Описание ссылки:', 'Перейти по адресу');
				if(!desc) var desc = 'Перейти по адресу';
				insertCode('url', url, desc);
			}
		}
		else
		{
			alert('Вы не ввели адрес сылки!');
		}
	}
	else if(code == 'mail')
	{
		var mail = prompt('Введите почту:', '');
		if(mail)
		{
			if(isValidEmail(mail))
			{			
				if (change == true)
				{
					insertCode('email', mail);
				}
				else
				{
					var desc = prompt('Описание:', 'мой e-mail');
					if(!desc) var desc = 'мой e-mail';
					insertCode('email', mail, desc);
				}
			}
			else
			{
				alert('E-Mail имеет неправильный формат (email@site.ru)!');
			}
		}
		else
		{
			alert('Вы не ввели адрес почты!');
		}
	}
	else if(type == 'smile')
	{
		insertCode('smile', code);
	}
	else
	{
		insertCode(code, type);
	}
	hideBBPanel();
}

function insertIN(code, module)
{
	if(module != 'user')
	{
		gid(textareaName).value += code;
	}
	else
	{
		window.opener.document.getElementById(textareaName).value += code;
	}
}

function insertCode(code, add, simple) 
{ 
	var start,end;
	if(userMod == true)
	{
		var obj = window.opener.document.getElementById(textareaName);
	}
	else
	{
		var obj = gid(textareaName);
	}
	
	obj.focus();
	var miniCode = new Array('[hr]', 'smile', 'iwannamini');
	
	if(simple)
	{
		if((code == 'img' || code == 'thumb') && add == 'center')
		{
			var toAdd = '[center]['+code +']'+simple+'[/'+code+'][/center]';
		}
		else
		{
			var toAdd = '['+code + (add ? '=' + add : '') +']'+simple+'[/'+code+']';
		}
	}
	else
	{
		var toAdd = '['+code + (add ? '=' + add : '') +']{val}[/'+code+']';
	}
	
	if(in_array(miniCode, code))
	{
		var toAdd = add ? add : code;
	}

	if (window.getSelection)
	{
		start = obj.selectionStart;
		end = obj.selectionEnd;
		obj.value = obj.value.substr(0,start)+toAdd.replace('{val}', obj.value.substr(start,end-start))+obj.value.substr(end); 
		obj.setSelectionRange(start+code.length+2+(add ? add.length+1 : ''),end+code.length+2+(add ? add.length+1 : ''));   
		
	}
	else if (document.selection)
	{
	
		var txt = document.selection.createRange();  
		var clone = txt.duplicate();
		var oldstr=txt.text; 
		
		txt.collapse();
		clone.moveToElementText(obj);
		clone.setEndPoint('EndToEnd', txt);
		
		var start=clone.text.length;
		var end=start+oldstr.length;
		
		obj.value = obj.value.substr(0,start)+toAdd.replace('{val}', obj.value.substr(start,end-start))+obj.value.substr(end);
		
		if (oldstr)
		{
			clone.findText(oldstr); 
			clone.select();
		} 
		else 
		{
			txt.moveStart("character",start+code.length+2+(add ? add.length+1 : ''));
			txt.collapse();
			txt.select();
		}
	}
	hideBBPanel();
}

function changeHeight(action) 
{
	var area = gid(textareaName);

	if (action == 'plus') 
	{
		var rows = +5;
	} 
	else 
	{
		var rows = -5;
	}

	var checkRows = area.rows + rows;

	if (checkRows >= 5 && checkRows < 25) 
	{
		area.rows = checkRows;
		return true;
	}

	return;
}

function QuickQuote(date, id)
{
 var txt = ''; 
 window.txt= '';
      if (document.getSelection)
      {
              window.txt=document.getSelection()
      }
      else if (document.selection)
      {
              window.txt=document.selection.createRange().text;
      }
	  if (window.txt == txt)
      {
            alert('Сначала выделите текст!'); 
      }
	  else 
	  {
	    gid(id).value += '[quote]' + window.txt + '[/quote]';	 
	  }
	  
	  
	 
}

function hideBBPanel()
{
	if(document.getElementById("test") != null)
	{
		alert(123);
		if(gid(textareaName + '_codes').style.display !== "none")
		{
			gid(textareaName + '_codes').style.display = 'none';
		}	
		
		if(gid(textareaName + '_smiles').style.display !== "none")
		{
			gid(textareaName + '_smiles').style.display = 'none';
		}	
		
		if(gid(textareaName + '_fonts').style.display !== "none")
		{
			gid(textareaName + '_fonts').style.display = 'none';
		}
	}
}

function mainArea(name)
{
	if(textareaName != name)
	{
		textareaName = name;
	}
}