<article class="inform hentry">
 <table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td>{%prev%}</td>
      <td><div align="right">{%next%}</div></td>
    </tr>
  </table>
  <br />
<div align="center"><span style="padding-top:21px; padding-left:17px;"><a href="{%MOD_NAME%}/get_photo/{%photo_id%}"><img src="{%normal%}" width="{%size-long%}" border="0" /></a></span><br />
</div>
<hr />
<div align="center">Описание:<br />{%description%}</div>
<hr />
<a href="javascript:void(0)" onclick="javascript:showhide('info')">[lang:_ALL_INFORMATION]</a>
<div id="info" style="display:none;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="50%"><ul>
<li>[lang:_PATH]: <a href="{%MOD_NAME%}/album/{%trans%}">{%ctitle%} 
</a>
<li>[lang:_PHOTO_DATE]: {%photo_date%} 
<li>[lang:_ADD_DATE_PHOTO_]: {%add_date%} 
<li>[lang:_ORIGINAL_PHOTO]: {%img_size%} ({%img_mb%})
<li>[lang:_GET_ORIGINAL]: <a href="{%MOD_NAME%}/get_photo/{%photo_id%}">[lang:_SGET]</a>
</ul></td>
    <td>
<ul style="margin-top:0px;">
<li>[lang:_COMMENTS]: {%comments%} 
<li>[lang:_VIEWS]: {%add_date%} 
<li>[lang:_GETS_ORIGINAL]: {%gets%} 
<li>[lang:_AUTHOR]: <a href="profile/{%author%}">{%author%} 
    </a>
</ul></td>
  </tr>
</table></div>
</article>