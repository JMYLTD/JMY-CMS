<article class="hentry">
<script language="javascript">function checkAddPost(){var err = 0;gid('titleErr').innerHTML = '';gid('postTextErr').innerHTML = ''; if(gid('title').value == ''){ err = 1;gid('titleErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT1]</font>';} if(gid('description').value == ''){ err = 1;gid('postTextErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT2]</font>';} if(err == 1){alert('[lang:_BLOG_TPL_ALERT3]');return false;}else{return true;}}</script>

<form action="blog/saveBlog" method="post" onsubmit="return checkAddPost();"  enctype="multipart/form-data" >
<div class="padding inputTitle">[lang:_BLOG_TITLE]:</div>
<div class="padding" style="padding-bottom:10px;"><input name="title" value="{%TITLE%}" type="text" size="35" id="title" /><div id="titleErr"></div></div>
<div class="padding inputTitle">[lang:_BLOG_URL_TITLE]:</div>
<div class="padding" style="padding-bottom:10px;"><input name="altname" value="{%ALTNAME%}" type="text" size="35" /><br /><sup>[lang:_BLOG_URL_TITLE2]</sup></div>
<div class="padding inputTitle">[lang:_BLOG_DESCRIPTION]:</div>
<div class="padding" style="padding-bottom:10px;"><textarea name="description" id="description" rows="3" style="width:70%;">{%DESCRIPTION%}</textarea><div id="postTextErr"></div></div>
<div class="padding inputTitle">[lang:_BLOG_AVATAR]:</div>
<div class="padding" style="padding-bottom:10px;">{%AVATAR_REPLACE%}<input name="blogAvatar" type="file" size="35" /><br /><sup>[lang:_BLOG_IMG_FORMAT]</sup></div>
<div class="padding"><input name="" type="submit" value="[lang:_BLOG_CREATE2]"  /></div>

</form>
 </article>