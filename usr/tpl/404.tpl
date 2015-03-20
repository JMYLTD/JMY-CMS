<script> 

function Homepage(){
	DocURL = document.location.href;
	protocolIndex=DocURL.indexOf("://",4);
	serverIndex=DocURL.indexOf("/",protocolIndex + 3);
	BeginURL=DocURL.indexOf("#",1) + 1;
	if (protocolIndex - BeginURL > 7)
		urlresult=""
	urlresult=DocURL.substring(BeginURL,serverIndex);
	displayresult=DocURL.substring(protocolIndex + 3 ,serverIndex);
	forbiddenChars = new RegExp("[<>\'\"]", "g");	// Global search/replace
	urlresult = urlresult.replace(forbiddenChars, "");
	displayresult = displayresult.replace(forbiddenChars, "");
	document.write('<A target=_top HREF="' + urlresult + '">' + displayresult + "</a>");

}

</script>

<body bgcolor="white">
<object id=saOC CLASSID='clsid:B45FF030-4447-11D2-85DE-00C04FA35C89' HEIGHT=0 width=0></object>

<table width="400" cellpadding="3" cellspacing="5">
  <tr>
    <td id="tableProps" valign="top" align="left"><h1 style='color: #0000ff'>i</h1></td>
    <td id="tableProps2" align="left" valign="middle" width="360"><h1 id="errortype"
    style="COLOR: black; FONT: 13pt/15pt verdana"><span id="errorText">Невозможно найти страницу</span></h1>
    </td>
  </tr>
  <tr>
    <td id="tablePropsWidth" width="400" colspan="2"><font
    style="COLOR: black; FONT: 8pt/11pt verdana">Возможно, эта страница была удалена,
 переименована, или она временно недоступна. (Но скорее всего, она никогда и не существовала!)</font></td>

  </tr>
  <tr>
    <td id="tablePropsWidth2" width="400" colspan="2"><font id="LID1"
    style="COLOR: black; FONT: 8pt/11pt verdana"><hr color="#C0C0C0" noshade>
    <p id="LID2">Попробуйте следующее:</p><ul>
      <li id="list1">Проверьте правильность адреса страницы
 в строке адреса.<BR>
</li>
      <li id="list2">Откройте <script> Homepage(); </script> , затем найдите там ссылки
        на нужные данные. </li>

      <li id="list3">Нажмите кнопку <a href="javascript:history.back(1)">Назад</a>, чтобы использовать другую ссылку. </li>    
      <li ID="list4">Воспользуйтесь <a href="http://google.com">поиском</a> в Интернете (а не на msn). </li>
</ul>
    <p><br>
    </p>
    <h2 id="ietext" style="font:8pt/11pt verdana; color:black">HTTP 404 - Файл не найден<br>

	<div align='right'>
    Нежно любящий, <br>Ваш <i>Браузер</i> и весёлая кмска :). 
	</div>
    </h2>
    </font></td>

  </tr>
</table>