var userMod = false;

function shareWindow(e) {
  var h = 500,
      w = 500;
  window.open(e, '', 'scrollbars=1,height='+Math.min(h, screen.availHeight)+',width='+Math.min(w, screen.availWidth)+',left='+Math.max(0, (screen.availWidth - w)/2)+',top='+Math.max(0, (screen.availHeight - h)/2));
}

function gid(elemid) 
{ 
	return document.getElementById(elemid); 
}

function isValidEmail (email)
{
	return (/^([a-z0-9_\-]+\.)*[a-z0-9_\-]+@([a-z0-9][a-z0-9\-]*[a-z0-9]\.)+[a-z]{2,4}$/i).test(email);
}

function showload() 
{

}

function setCookie(name, value, exp_time)
{
    var exp = new Date ();
    exp.setTime(exp.getTime() + (exp_time * 1000));
    document.cookie = name + "=" + escape(value) + "; expires=" + exp.toGMTString() + "; path=/;";
}

function getCookie(name)
{
    var name = name;
    var arg = name + '=';
    var alen = arg.length;
    var clen = document.cookie.length;
    var i = 0;

    while (i < clen) {
        var j = i + alen;

        if (document.cookie.substring(i, j) == arg)
        {
            var endstr = document.cookie.indexOf(';', j);

            if (endstr == -1)
            {
                endstr = document.cookie.length;
            }

            return unescape(document.cookie.substring(j, endstr));
        }

        i = document.cookie.indexOf(' ', i) + 1;

        if (i == 0)
        {
            break; 
        }
    }
    return false;
}

function switchBlock(id)
{
    var img = 'img_' + id;

    if (gid('block_' + id).style.display != 'none')
    {
		showhide('block_' + id);
		gid(img).src = 'media/other/close.png';
        setCookie('Block_' + id, true, 60 * 60 * 24 * 365 * 1000);
    }
    else
    {
		showhide('block_' + id);
		gid(img).src = 'media/other/open.png';
        delCookie('Block_' + id);
    }
}


function fixAP()
{

    if (gid('_adminBar').style.position == 'absolute')
    {
		gid('_adminBar').style.position = 'fixed';
        setCookie('fixAP', 'ok', 60 * 60 * 24 * 365 * 1000);
		gid('fixAP').innerHTML = 'фиксировать';
    }
    else
    {
		gid('_adminBar').style.position = 'absolute';
        delCookie('fixAP');
		gid('fixAP').innerHTML = 'убрать фиксацию';
    }
}

function delCookie(name)
{
    setCookie(name, getCookie(name), -1);
}

function showhide(id, disable) 
{
	gid(id).style.display = gid(id).style.display == "none" ? "block" : "none";
}

function hide(id)
{
	gid(id).style.display = 'none';
}

function show(id)
{
	gid(id).style.display = 'block';
}

function getConfirm(msg) 
{
	if (confirm(msg))
	{
		return true;
	} 
	else 
	{
		return false;
	}
}


function htmlSpecialChars(str)
{
    str = str.replace('&', '||and||');
    str = str.replace('<', '||men||');
    str = str.replace('>', '||bol||');
	
	return str;
}

function setCheckboxes(the_form, do_check)
{
    var elts      = (typeof(document.forms[the_form].elements['selected_db[]']) != 'undefined')
                  ? document.forms[the_form].elements['selected_db[]']
                  : (typeof(document.forms[the_form].elements['checks[]']) != 'undefined')
          ? document.forms[the_form].elements['checks[]']
          : document.forms[the_form].elements['selected_fld[]'];
    var elts_cnt  = (typeof(elts.length) != 'undefined')
                  ? elts.length
                  : 0;

    if (elts_cnt) 
	{
        for (var i = 0; i < elts_cnt; i++) 
		{
			if(elts[i].checked == true)
			{
				elts[i].checked = false;
			}
			else
			{
				elts[i].checked = true;
			}
        } 
    } else {
		if(elts.checked == true)
		{
			elts.checked = false;
		}
		else
		{
			elts.checked = true;
		}
    }

    return true;
}

function ButtonDisable(form) 
{
	if (document.all || document.getElementById) 
	{
		for (i = 0; i < form.length; i++) 
		{
			var tempobj = form.elements[i]
			if (tempobj.type.toLowerCase() == "submit" || tempobj.type.toLowerCase() == "reset") 
			{
				tempobj.disabled = true
			}
		}
	}
}

function ButtonAllow(form) 
{
	if (document.all || document.getElementById) 
	{
		for (i = 0; i < form.length; i++) 
		{
			var tempobj = form.elements[i]
			if (tempobj.type.toLowerCase() == "submit" || tempobj.type.toLowerCase() == "reset") 
			{
				tempobj.disabled = false
			}
		}
	}
}

function check (box, max)
{
	var form = box.form;
	var checked = 0;
	if (form)
	{
		for (var i = 0; i < form.elements.length; i++)
		{
			var el = form.elements[i];
			
			if (el.type == 'checkbox' && el.name == 'check[]')
			{
				if (el.checked) checked++;
			}
		}
		if (checked >= max) lock (form, true);
		else lock (form, false);
	}
}

function lock (form, un)
{
    for (var i in form.elements)
    {
        var el = form.elements[i];
        if (el.type == 'checkbox' && el.name == 'check[]')
        {
            if (!el.checked) el.disabled = un;
        }
    }
}

function reloadCaptcha()
{
	gid('captcha').style.background = 'none';
	gid('captcha').innerHTML = "<img src=\"captcha?nocache=" + getrandom() + "\" />";
}

function getrandom() 
{ 

	var min_random = 200; 
	var max_random = 1000; 

	max_random++; 

	var range = max_random - min_random; 
	var n=Math.floor(Math.random()*range) + min_random; 

	return n; 
}

function switchAdminBar()
{

    if (gid('_adminBar').style.display != 'none')
    {
		showhide('_adminBar');
		showhide('_adminBarC');
        setCookie('_adminBarCookie', true, 60 * 60 * 24 * 365 * 1000);
    }
    else
    {
		showhide('_adminBar');
		showhide('_adminBarC');
        delCookie('_adminBarCookie');
    }
}
/*
function genPreview(id, title, shortText, fullText)
{
	var _title = gid(title).value;
	var _short = gid(shortText).value;
	var _full = gid(fullText).value;
	
	if(_title != '' && _short != '')
	{
		gid(id).innerHTML = '<fieldset><legend>Предпросмотр контента</legend> <b>' + _title + '</b><br /><br/>' + parseBB(_short) + (_full != '' ? '<hr />' + parseBB(_full) : '') +'</fieldset>';
	}
	else
	{
		alert('Заполните обязательные поля!');
	}
}

function parseBB(text)
{
	text = text.replace(/\[(left|center|right)\](.*?)\[\/(left|center|right)\]/ig, "<div align=\"$1\">$2</div>");
	text = text.replace(/\[color=(red|blue|black|green|white|yellow|orange|grey)\](.+?)\[\/color\]/ig, "<span style=\"color:$1\">$2</span>");
	text = text.replace(/\[(b|i|u|s)\](.*?)\[\/(b|i|u|s)\]/ig, "<$1>$2</$1>");
	text = text.replace(/\[url=(.+?)\](.+?)\[\/url\]/ig, "<a href=\"$1\">$2</a>");
	text = text.replace(/\[size=([0-9]*)\](.+?)\[\/size\]/ig, "<span style=\"font-size:$1 pt\">$2</span>");
	text = text.replace(/\[hr\]/ig, "<hr />");
	text = text.replace(/\[br\]/ig, "<br />");
	text = text.replace(/\[quote\]/ig, '<div class="quote"><strong>Цитата:</strong><br />');
	text = text.replace(/\[\/quote\]/ig, '</div>');
	return text;
}*/


function changeIcon(path, id)
{
	gid(id).src = path;
}

function in_array(arr, val)
{
   for (key in arr) 
   {
      if (arr[key] == val) 
      {
          return true;
      }
   }
   return false;
}

function resetForm(box)
{
	var form = box.form;
	if (form)
	{
		for (var i in form.elements)
		{
			var el = form.elements[i];
			
			alert(el.type);
		}
	}
}

function genRating(starStyle, starWidth, limitStar, score, votes, blocked, id, module, msg)
{
	var prepCh = (votes == 0) ? 0 : (score/votes)*starWidth;
	var choosen = (prepCh > (starWidth*limitStar)) ? (starWidth*limitStar) : prepCh;
	rat = '<ul class="' + starStyle + '" style="width:' + (starWidth*limitStar) + 'px;">';
	rat += '<li class="choosen" style="width:' + choosen + 'px;" title="' + msg + '">&nbsp;</li>';
	if(blocked == '')
	{
		var num = ['one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten'];
		for(var star=1;star<=limitStar;star++) 
		{
			rat += '<li><a href="javascript:void(0)" onclick="vote(' + id + ', \'' + module + '\', ' + star + ')" class="' + num[star-1] + '-stars">' + star + '</a></li>';
		}
	}
	rat += '</ul>';
	gid('rating'+id).innerHTML = rat;
}

function vote(id, module, num) 
{
	AJAXEngine.async = false;
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('module', module);
	AJAXEngine.setPostVar('num', num);
	AJAXEngine.sendRequest('ajax.php?do=vote', 'rat' + id);
	eval("genRating(" + gid('rat' + id).innerHTML + ");");
}

function do_carma(id, type) 
{
	AJAXEngine.async = false;
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('type', type);
	AJAXEngine.sendRequest('ajax.php?do=carma_vote', 'rating' + id);
}

Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++)    {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
}
