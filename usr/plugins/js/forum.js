function sendPost()
{
	if(gid('qickMessage').value == '')
	{
		alert('Вы не заполнили текст сообщения!'); 
		return false;
	}
	else
	{
		return true;
	}
}