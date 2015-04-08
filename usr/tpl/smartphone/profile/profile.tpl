[open]
<div class="profile-header full-bottom">
<div class="profile-header-contents">
<img class="profile-header-logo" src="{%AVATAR%}" alt="img">
<h4 class="profile-header-heading">{%NICK%}</h4>
<em class="profile-header-subheading">{%GROUP%}</em>
<div class="profile-header-socials">
<a href="#"><i class="fa fa-plus"></i>Новостей добавил {%USER_NEWS%}</a>
<a href="#"><i class="fa fa-comments"></i>Отзывов добавил {%USER_COMMENTS%}</a>
<a href="#"><i class="fa fa-paper-plane"></i>{%LASTVIZIT%}</a>
</div>
</div>
<div class="profile-header-background"></div>
<div class="profile-header-overlay"></div>
</div>
<div class="content">
<div class="container no-bottom">
<div class="sidebar-left-small staff-sidebar-small">
<h2 class="no-bottom" align="center">{%EDIT%}</h2>
<h4 class="no-bottom" align="center">{%ADD_FRIEND%}</h4>
<p>{%PROFILE_LINK%}</p>
<div class="decoration"></div>
<ul class="font-icon-list">
<li><strong>Ф.И.О</strong>: {%SURNAME%} {%NAME%} {%OTCH%}</li>
<li><strong>Страна</strong>: {%COUNTRICON%}</li>
<li><strong>Возраст</strong>: {%AGE%}</li>
<li><strong>Пол</strong>: {%SEX%}</li>
<li><strong>ICQ</strong>: {%ICQ%}</li>
<li><strong>Skype</strong>: {%SKYPE%}</li>
<li><strong>Хобби</strong>: {%HOBBY%}</li>
[exgroup]<li><strong>Специальная группа</strong>: {%EXGROUP%}</li>[/exgroup]
<li><strong>Подпись</strong>: {%SIG%}</li>
[friends]<li><strong>Друзья</strong>: {%FRIENDS%}</li>[/friends]
[newFriends]<li><strong>Заявки в друзья ({%NEWFRIENDSNUM%})</strong>: {%NEWFRIENDS%}</li>[/newFriends]
[userGuests]<li><strong>Гости</strong>: {%GUESTS%} {%CLEAN_GUESTS%}</li>[/userGuests]
[blog]<li><a href="blog/user/{%UID%}" title="Перейти в персональный блог">Персональный блог пользователя</a></li>
[blogRead]<li><strong>Читает блоги</strong>: {%BLOG_READ%}</li>[/blogRead][/blog]
<li></li>
<li></li>
</ul>
<br>
</div>
<div class="decoration hide-if-responsive"></div>
</div>
</div>
[/open]
<div class="content"><div class="blog-posts">{%USER_WALL%}</div></div>