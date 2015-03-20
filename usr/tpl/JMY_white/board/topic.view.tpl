<script type="text/javascript">var addition = '';</script>
<div class="forumBodyWrap">	
		<div class="forumTitle"> 
			{%TITLE%} {%ACTION%}
		</div>
		<table class="forum" width="100%" border="0" cellspacing="1" cellpadding="0" class="forumTable">
		 {%TOPIC%}
		 </table>
</div>
[close]
					<br>
					<div align="center" id="qr" style="{%SHOW_EDIT%}">					
					<article class="hentry">
					<br style="clear:both" />
					<div style="width:600px;">
					<form name="quick" method="post" action="board/postMessage" onsubmit="return sendPost();" enctype="multipart/form-data">
					{%TEXTAREA%}
					{%UPLOAD%}
					<br />
					<input type="hidden" name="tid" value="{%ID%}" class="b" />
					<input type="hidden" name="type" value="quick" class="b" />
					<div align="right"><input type="submit" value="[lang:_FORUM_ANSWER]" /></div>
					</form>
					</div>
						</article>
					</div>
[/close]
[guest]
<article class="hentry"> <font color="red">[lang:_FORUM_REGISTER_OR_LOGIN_TO_ANSWER]</font></article>
[/guest]