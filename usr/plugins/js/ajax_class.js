this.JAjax = function()
{
    this.replace_id = false;
    this.xmlhttp = false;
    this.postVars = false;
    this.queryType = false;   
    this.async = true;
    this._response = '';

	

	
	
    this.run = function ()
    {
        try
        {
            this.xmlhttp = new XMLHttpRequest();
        }
        catch (e)
        {
            try
            {
                this.xmlhttp = new ActiveXObject('Msxml2.XMLHTTP')
            }
            catch (e)
            {
                try
                {
                    this.xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
                }
                catch(e)
                {
                    alert('Хъюстон у нас проблемы...');
                }
            }
        }
		if(this.async == true)
			this.xmlhttp.onreadystatechange = this.onReady;
    }
	
	var _this = this;
    this.onReady = function ()
    {
        if (_this.xmlhttp.readyState == 4)
        {
            if (_this.xmlhttp.status == 200)
            {
				if(this.replace_id != '') document.getElementById(_this.replace_id).innerHTML = _this.xmlhttp.responseText;
				if(this.async == false) _this._response = _this.xmlhttp.responseText;
				

                this.replace_id = false;
                this.xmlhttp = false;
                this.postVars = false;
				
            }
            else
            {
                if (_this.isAlert)
                    alert(_this.messageConnectError);
            }
        }
    }

    this.setPostVar = function (key, value)
    {

        if (this.postVars == false)
            this.postVars = '';
        else
            this.postVars += '&';

        this.postVars += key + '=' + encodeURIComponent(value);
    }
    
    this.notAsyncReq = function (url)
    {
       
        
        var xmlhttp;
        try {
          xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            } catch (E) {
                xmlhttp = false;
            }
        }
        
        if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
          xmlhttp = new XMLHttpRequest();
        }
        
        xmlhttp.open('POST', url, false);
        xmlhttp.send(null);
        
        if(xmlhttp.status == 200) {
           
            return xmlhttp.responseText;
        }
    }

    this.sendRequest = function (url, id)
    {
        this.run();
        this.replace_id = id;
        var noCache = ((-1 < url.indexOf('?')) ? '&' : '?') + 'nocache=' + Math.random();
		
		
        this.xmlhttp.open('POST', url + noCache, this.async);
        this.xmlhttp.setRequestHeader('If-Modified-Since', 'Sat, 1 Jan 2000 00:00:00 GMT');
        this.xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        if (this.postVars)
            this.xmlhttp.send(this.postVars);
        else
            this.xmlhttp.send(null);
			
		if(this.async == false)
		{
			document.getElementById(this.replace_id).innerHTML = this.xmlhttp.responseText;
			this._response = this.xmlhttp.responseText;
			
		}
    }
}

var AJAXEngine = new JAjax;

function ajaxSimple(uri, id, loader)
{
	AJAXEngine.sendRequest(uri, id);
}

function fastCancel(id, module, type, div)
{
	AJAXEngine.setPostVar('blocked', '');
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('module', module);
	AJAXEngine.setPostVar('type', type);
	AJAXEngine.sendRequest('ajax.php?do=fast_edit', div);
}

function commentCancel(id)
{
	AJAXEngine.setPostVar('blocked', '');
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.sendRequest('ajax.php?do=commentEditAjax', 'comment_'+id);
}

function fileList(id, mod, does, val)
{
	AJAXEngine.setPostVar(does, val);
	AJAXEngine.setPostVar('module', mod);
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.sendRequest('ajax.php?do=fileList', 'filelist');
}


function fast_edit(id, type, module) 
{
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('module', module);
	AJAXEngine.setPostVar('type', type);
	AJAXEngine.sendRequest('ajax.php?do=fast_edit', type + '-' + id);
}

function fast_post(div) 
{
	AJAXEngine.setPostVar('text', htmlSpecialChars(gid("edit").value));
	AJAXEngine.setPostVar('id', gid("id").value);
	AJAXEngine.setPostVar('module', gid("module").value);
	AJAXEngine.setPostVar('type', gid("type").value);
	AJAXEngine.sendRequest('ajax.php?do=fast_save', div);
}

function initRating(id, module, score, votes)
{

	AJAXEngine.showedLoadBar = '';
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('module', module);
	AJAXEngine.setPostVar('score', score);
	AJAXEngine.setPostVar('votes', votes);
	AJAXEngine.setPostVar('ajaxR', '1');
	AJAXEngine.sendRequest('ajax.php?do=genRating', 'rating_'+id);
}

function dopoll(box, id)
{
	var vote_checks = '';
    var form = gid(box);
	
	if (form)
	{
		for (var i = 0; i < form.elements.length; i++)
		{
			var el = form.elements[i];

			if (el.type == 'checkbox' && el.name == 'check[]')
			{
				if (el.checked) vote_checks = vote_checks + '|' + el.value;
			}
			
			if (el.type == 'radio' && el.name == 'check[]')
			{
				if (el.checked) vote_checks = vote_checks + '|' + el.value;
			}
		}
	}
	
	if(vote_checks)
	{
		AJAXEngine.setPostVar('checks', vote_checks);
		AJAXEngine.setPostVar('pid', id);
		AJAXEngine.sendRequest('ajax.php?do=poll/doVote', id);
	}
	else
	{
		alert('Вы не выбрали ни одного варианта ответа!');
	}
}

function comment_post(id) 
{
	if(gid('text').value == '') 
	{
		alert('Поле с текстом оставлено пустым!');
		return false;
	}
	
	AJAXEngine.setPostVar('text', htmlSpecialChars(gid("text").value));
	AJAXEngine.setPostVar('id', gid("nid").value);
	AJAXEngine.setPostVar('author', (gid("author") ? gid("author").value : ''));
	AJAXEngine.setPostVar('email', (gid("email") ? gid("email").value : ''));
	AJAXEngine.setPostVar('url', (gid("url") ? gid("url").value : ''));
	AJAXEngine.setPostVar('mod', gid("mod").value);
	AJAXEngine.setPostVar('reply_to', gid("reply_to").value);
	AJAXEngine.setPostVar('securityCode', (gid("securityCode") ? gid("securityCode").value : ''));
	AJAXEngine.setPostVar('subscribe', (gid("subscribe") ? gid("subscribe").value : ''));
	AJAXEngine.setPostVar('commNum', gid('hideCommNum').innerHTML);
	AJAXEngine.sendRequest('ajax.php?do=comment_savea', id);
	gid('securityCode').value = '';
}

function commentPage(mod, pid, page)
{
	AJAXEngine.setPostVar('mod', encodeURI( mod, true));
	AJAXEngine.setPostVar('pid', pid);
	AJAXEngine.setPostVar('page', page);
	AJAXEngine.setPostVar('commNum', gid('hideCommNum').innerHTML);
	AJAXEngine.sendRequest('ajax.php?do=commentPage', 'commentBox');
}

function commentEditSave(div)
{
	AJAXEngine.setPostVar('text', htmlSpecialChars( gid("edit").value));
	AJAXEngine.setPostVar('id', gid("id").value);
	AJAXEngine.sendRequest('ajax.php?do=commentEditAjaxSave', div);
}

function commentEdit(cid, id)
{
	AJAXEngine.setPostVar('id', cid);
	AJAXEngine.sendRequest('ajax.php?do=commentEditAjax', id);
}

function commentDelete(cid, id, mod, nid, page)
{
	if(getConfirm('Удалить комментарий под №' + cid + '?'))
	{
		AJAXEngine.setPostVar('id', cid);
		AJAXEngine.setPostVar('mod', mod);
		AJAXEngine.setPostVar('nid', nid);
		AJAXEngine.sendRequest('ajax.php?do=commentDeleteAjax', id);
		setTimeout('commentPage(\'' + mod + '\', ' + nid + ', ' + page + ')', 5000);
	}
}

function subscribeComments(does, div)
{
	AJAXEngine.setPostVar('mod', gid("mod").value);
	AJAXEngine.setPostVar('nid', gid("nid").value);
	AJAXEngine.setPostVar('do', does);
	AJAXEngine.sendRequest('ajax.php?do=commentSubscribe', div);
}

function searchList(val, id) 
{
	if(val.length > 3) 
	{
		if(gid(id).style.display=="none")
		{
			showhide(id);
		}
		
		
		AJAXEngine.setPostVar('query', encodeURI(val, true));
		AJAXEngine.sendRequest('ajax.php?do=searchList', id);
	}
}

function check_news(elem, id) 
{
	AJAXEngine.setPostVar('query', encodeURI( gid(elem).value));
	AJAXEngine.sendRequest('ajax.php?do=searchList', id);
}


function addCarma(uid, id)
{
	if(gid('carmaDo').value == '' || gid('carmaText').value == '') 
	{
		alert('Вы не ввели сообщение для пользователя');
		return false;
	}	
	AJAXEngine.setPostVar('uid', uid);
	AJAXEngine.setPostVar('does', gid('carmaDo').value);
	AJAXEngine.setPostVar('text', gid('carmaText').value);
	AJAXEngine.sendRequest('ajax.php?do=addCarma', 'p'+id);
	clearModal_box(id);
}

function adminBlock(action, id, to, from, topos)
{
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('to', to);
	AJAXEngine.setPostVar('from', from);
	AJAXEngine.setPostVar('topos', topos);
	AJAXEngine.sendRequest('ajax.php?do=' + action, 'blockBox');
}

function adminBlockStatus(id, to)
{
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('to', to);
	AJAXEngine.sendRequest('ajax.php?do=setBlockStatus', 'blockBox');
}

function userBlockStatus(id, to)
{
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('to', to);
	AJAXEngine.sendRequest('ajax.php?do=blockStatus', 'blockOk'+id);
	setTimeout('innerEmpt(\'blockOk'+id+'\')', 4000);
}

function adminCommentStatus(id, to)
{
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('to', to);
	AJAXEngine.sendRequest('ajax.php?do=setCommentStatus', 'statusIcon_'+id);
}


function blockDelete(id)
{
	if(getConfirm('Вы уверены что хотите удалить блок?'))
	{
		AJAXEngine.setPostVar('id', id);
		AJAXEngine.sendRequest('ajax.php?do=deleteBlock', 'blockBox');
	}
}

function userBlockDelete(id)
{
	if(getConfirm('Вы уверены что хотите удалить блок?'))
	{
		AJAXEngine.setPostVar('id', id);
		AJAXEngine.sendRequest('ajax.php?do=userDeleteBlock', 'blockOk'+id);
		setTimeout('innerEmpt(\'blockOk'+id+'\')', 4000);
	}
}

function SaveTitle(mod, id, text)
{
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.setPostVar('module', mod);
	AJAXEngine.setPostVar('text', text);
	AJAXEngine.sendRequest('ajax.php?do=editTitle', 'editTitle_' + id);
}

function innerEmpt(id)
{
	gid(id).innerHTML = '';
}

function updateCatList(mod, id)
{
	AJAXEngine.setPostVar('mod', mod);
	AJAXEngine.sendRequest('ajax.php?do=getCatByModule', id);
}

function checkLogin(login, id, input)
{
	if(login.length > 1) 
	{
		if(gid(id).style.display=="none")
		{
			showhide(id);
		}
		
	
		AJAXEngine.setPostVar('query', encodeURI( login , true));
		AJAXEngine.setPostVar('input', input);
		AJAXEngine.sendRequest('ajax.php?do=loginList', id);
	}
}

function complitLoad(postId, mod, id)
{
	AJAXEngine.setPostVar('id', postId);
	AJAXEngine.setPostVar('mod', mod);
	AJAXEngine.sendRequest('ajax.php?do=fileList', id);
}

function forumPostEdit(id, divid) 
{
	var addition = '';
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.sendRequest('index.php?url=board/ajax/fastForm', divid);
}

function forumSaveEdit(id2, addition) 
{
	AJAXEngine.setPostVar('text', document.getElementById("edit").value);
	AJAXEngine.setPostVar('id', document.getElementById("id").value);
	if(document.getElementById("files").value == 1) AJAXEngine.setPostVar('files', document.getElementById("files").value);
	AJAXEngine.sendRequest('index.php?url=board/ajax/fastSave'+addition, id2);
}

function forumPostDelete(id, divid)
{
	AJAXEngine.setPostVar('id', id);
	AJAXEngine.sendRequest('index.php?url=board/ajax/delete', divid);
}

function delFriend(uid, div)
{
	AJAXEngine.setPostVar('uid', uid);
	AJAXEngine.sendRequest('ajax.php?do=friendsAjax/delete', div);
}

function addFriend(uid)
{
	AJAXEngine.setPostVar('uid', uid);
	AJAXEngine.sendRequest('ajax.php?do=friendsAjax/add', 'friendDo');
}

function acceptFriend(uid, div)
{
	AJAXEngine.setPostVar('uid', uid);
	AJAXEngine.sendRequest('ajax.php?do=friendsAjax/accept', div);
}

function blogRating(id, div)
{
	AJAXEngine.setPostVar('pid', id);
	AJAXEngine.sendRequest('index.php?url=blog/ajaxRating', div);
//async	
}