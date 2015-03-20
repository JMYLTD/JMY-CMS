<article class="hentry">
<script language="javascript">function checkAddPost(){var err = 0;gid('titleErr').innerHTML = '';gid('postTextErr').innerHTML = '';gid('tagsErr').innerHTML = '';if(gid('title').value == ''){err = 1;gid('titleErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT4]</font>';} if(gid('postText').value == ''){	err = 1;gid('postTextErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT5]</font>';} if(gid('tags').value == '') {err = 1;gid('tagsErr').innerHTML = '<font color="red">[lang:_BLOG_TPL_ALERT6]</font>';}if(err == 1){alert('[lang:_BLOG_TPL_ALERT3]');return false;}else{return true;}}</script>

<form action="blog/savePost" method="post" onsubmit="return checkAddPost();">
<div style="width:90%">
<div class="padding inputTitle">[lang:_BLOG_SELECT_BLOG]:</div>
<div class="padding" style="padding-bottom:10px;"><select name="blog" {%BLOGCHOOSE%}>{%BLOGS%}</select>
</div>
<div class="padding inputTitle">[lang:_BLOG_POST_TITLE]:</div>
<div class="padding" style="padding-bottom:10px;"><input name="title" type="text" id="title" value="{%TITLE%}" size="35" />
<br /><sup>[lang:_BLOG_POST_MINI]</sup><div id="titleErr"></div></div>
<div class="padding inputTitle">[lang:_BLOG_TEXT]:</div>
<div class="padding" style="padding-bottom:10px;">{%TEXTAREA%}<div id="postTextErr"></div></div>
<div class="padding inputTitle">[lang:_BLOG_TAGS]:</div>
<div class="padding"><input name="tags" type="text" id="tags" value="{%TAGS%}" size="35" />
<br />
<sup>[lang:_BLOG_TOPICS]</sup><div id="tagsErr"></div></div>
</div>
<div class="padding" style="padding-bottom:10px;"><input name="note" type="checkbox" {%NOTE%} />[lang:_BLOG_DRAFT2]</div>
<div class="padding"><input name="" type="submit" value="[lang:_BLOG_WRITE2]"  /> {%ACTIONS%}
</div>
</form>
 </article>