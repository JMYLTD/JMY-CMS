<!DOCTYPE html>
<html lang="en">
<head>
  {%META%}
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!--[if lt IE 9]>
    <script src="{%THEME%}/assest/js/html5.js" type="text/javascript"></script>
  <![endif]-->
  <link rel="stylesheet" href="{%THEME%}/assest/css/engine.css" type="text/css" media="all">
  <link rel="stylesheet" href="{%THEME%}/assest/css/theme.css" type="text/css" media="all">
  <link rel="stylesheet" href="{%THEME%}/assest/css/font-awesome.min.css" type="text/css" media="all">
  <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto+Slab%3A300%2C400%2C700&#038;ver=4.0.1' type='text/css' media='all' />
  <script type='text/javascript' src='{%THEME%}/assest/js/jquery.js'></script>
  <script type='text/javascript' src='{%THEME%}/assest/js/jquery-migrate.min.js'></script>  
</head>
<body class="home blog custom-background">
  <div class="preloader">
		<div class="preloaderInner">
			<i class="fa fa-3x fa-circle-o-notch fa-spin"></i>
		</div>
  </div>
  <div id="page" class="hfeed site">    
    <header id="masthead" class="site-header" role="banner">
      <div class="theTop">
        <div class="site-branding">
          <h1 class="site-title"><a href="{%URL%}" rel="home">{%SITE_NAME%}</a></h1>
          <h2 class="site-description">{%SITE_SLOGAN%}</h2>
        </div>    
		<div class="site-social">
			<div class="socialLine">
				<a href="{%SHARE_VK%}" onclick="shareWindow(this.href); return false;" title="Вконтакте" rel="nofollow">
					<i class="fa spaceLeftDouble fa-vk spaceLeftRight"></i>
				</a>		
				<a href="{%SHARE_FB%}" onclick="shareWindow(this.href); return false;" title="Facebook" rel="nofollow">
					<i class="fa spaceLeftDouble fa-facebook spaceLeftRight"></i>
				</a>
				<a href="{%SHARE_TW%}" onclick="shareWindow(this.href); return false;" title="Twitter" rel="nofollow">
					<i class="fa spaceLeftDouble fa-twitter spaceLeftRight"></i>
				</a>					
				<a href="{%SHARE_GP%}" onclick="shareWindow(this.href); return false;" title="Google Plus" rel="nofollow">
					<i class="fa spaceLeftDouble fa-google-plus spaceLeftRight"></i>
				</a>
				<a href="{%SHARE_TB%}" onclick="shareWindow(this.href); return false;" title="Tumblr" rel="nofollow">
					<i class="fa spaceLeftDouble fa-tumblr spaceLeftRight"></i>
				</a>							
				<a href="{%SHARE_LI%}" onclick="shareWindow(this.href); return false;" title="Linkedin" rel="nofollow">
					<i class="fa spaceLeftDouble fa-linkedin spaceLeftRight"></i>
				</a>				
				<a href="{%SHARE_PT%}" title="Pinterest" rel="nofollow"><i class="fa spaceLeftDouble fa-pinterest spaceLeftRight"></i></a>
				<a href="{%SHARE_MA%}" onclick="shareWindow(this.href); return false;" title="Почта" rel="nofollow"><i class="fa spaceLeftDouble fa-envelope-o spaceLeftRight"></i></a>
			</div>
		</div> 
      <nav id="site-navigation" class="main-navigation smallPart" role="navigation">
        <button class="menu-toggle">Меню<i class="fa fa-align-justify"></i></button>
        <div class="menu-menu-1-container">
          <ul class="menu">
            <li class="menu-item [index:1]current_page_item[/index]">
              <a href="{%URL%}">Главная</a>
            </li>
            <li class="menu-item [index:0][modules:news,board,blog,guestbook,gallery,sitemap:1]current_page_item[/modules][/index]">
              <a href="#">Компоненты</a>
              <ul class="sub-menu">
                <li class="menu-item [index:0][modules:news:1]current_page_item[/modules][/index]">
                  <a href="{%URL_NEWS%}">Новости</a>
                </li>
                <li class="menu-item [modules:board:1]current_page_item[/modules]">
                  <a href="{%URL_FORUM%}">Форум</a>
                </li>
                <li class="menu-item [modules:blog:1]current_page_item[/modules]">
                  <a href="{%URL_BLOG%}">Блоги</a>
                </li>
                <li class="menu-item [modules:guestbook:1]current_page_item[/modules]">
                  <a href="{%URL_GUEST%}">Гостевая книга</a>
                </li>
                <li class="menu-item [modules:gallery:1]current_page_item[/modules]">
                  <a href="{%URL_GALLERY%}">Галерея</a>              
                  <ul class="sub-menu">
                    <li class="menu-item">
                      <a href="{%URL_GALLERY%}/top">Лучшие фото</a>
                    </li>
                    <li class="menu-item">
                      <a href="{%URL_GALLERY%}/search">Поиск фотографий</a>
                    </li>                   
                  </ul>
                </li>
                <li class="menu-item [modules:sitemap:1]current_page_item[/modules]">
                  <a href="{%URL_SITEMAP%}">Карта сайта</a>
                </li>
              </ul>          
            </li>
            <li class="menu-item [modules:content:1]current_page_item[/modules]">
              <a href="content/simple.html">Простая страница</a>
            </li>
            <li class="menu-item [modules:feedback:1]current_page_item[/modules]">
              <a href="{%URL_FEEDBACK%}">Контакты</a>
            </li>
          </ul>
        </div>
      </nav>
    </header>
    <div id="content" class="site-content">
      <div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">		
          {%MODULE%}         
        </main>
      </div>
      <div id="secondary" class="widget-area" role="complementary">
        <aside id="search-2" class="widget widget_search">
		 {%BLOCKS:FILE:search%}           
        </aside>  
		<aside class="widget">
           <div class="widget-title"><h3>Профиль</h3></div>
           [guest]
		   <form action="profile/login" method="post" name="login" onkeypress="ctrlEnter(event, this);">
				<table width="100%" border="0" cellspacing="0" cellpadding="1" align="center" style=" border-spacing: 7px 6px;">
					<tr>
						<td>Логин:</td>
						<td><input type="text" name="nick" size="10" maxlength="25" class="binput" /></td>
					</tr>
					<tr>
						<td>Пароль:</td>
						<td><input type="password" name="password" size="10" maxlength="25" class="binput" /></td>
					</tr>
					<tr>
						<td>
							<div class="socialWidget">
								<input type="submit" value="Войти"  class="socialWidget" />
							</div>
						</td>
						<td>
							<div class="socialWidget">
								<a href="{%AUTH_VK%}" title="Авторизация через Вконтакте" rel="nofollow">
									<i class="fa spaceLeftDouble fa-vk spaceLeftRight"></i>
								</a>
								<a href="{%AUTH_FB%}" title="Авторизация через Facebook"rel="nofollow">
									&#160;<i class="fa spaceLeftDouble fa-facebook spaceLeftRight"></i>&#160;
								</a>
								<a href="{%AUTH_GP%}" title="Авторизация через Google+"rel="nofollow">
									&#160;<i class="fa spaceLeftDouble fa-google-plus spaceLeftRight"></i>
								</a>						
							</div>
						</td>
					</tr>	
					<tr>
						<td colspan="2" align="center">
							<a href="profile/register">Регистрация</a>
						</td>
					</tr>					
				</table>				
			</form>			
		   [/guest]
		   [user]
		   <div align="center">
				Привет, <b> {%USER_NAME%}</b>!<br/><br/>
				<img src="{%USER_AVATAR%}" border="0" alt="" /><br/><br/>
			</div>
			<a href="{%URL_PROFIL%}">Профиль</a><br/>
			<a href="{%URL_PM%}">Приватные сообщения</a> ({%NEW_PM%})<br/>
			<a href="{%URL_LOGOUT%}">Выход</a>
			[admin]<hr/><a href="{%URL_ADMIN%}">Панель управления</a>[/admin]
		   [/user]
        </aside>		
        <aside id="calendar-2" class="widget widget_calendar">
          <div class="widget-title">
            <h3>Календарь</h3>
          </div>
          {%BLOCKS:FILE:calendar%}   
        </aside>
        <aside id="categories-2" class="widget widget_categories">
          <div class="widget-title">
            <h3>Категории</h3>
          </div>
          {%BLOCKS:FILE:cats%}       
        </aside>
        <aside class="widget">
          <div class="widget-title">
            <h3>Опрос</h3>
          </div>
          {%BLOCKS:FILE:poll%}   
        </aside>
        <aside class="widget widget_tag_cloud">
          <div class="widget-title">
            <h3>Облако тегов</h3>
          </div>
          <div class="tagcloud">
          {%BLOCKS:FILE:tags%}          
          </div>
        </aside>
        <aside class="widget">
          <div class="widget-title">
            <h3>Кто онлайн?</h3>
          </div>
          {%BLOCKS:FILE:online%}
        </aside>
        <aside class="widget">
          <div class="widget-title">
            <h3>Выбор шаблона:</h3>
          </div>    
          {%BLOCKS:FILE:themes%}
        </aside>
      </div>
    </div>
    <footer id="colophon" class="site-footer" role="contentinfo">
      <div class="site-info smallPart">Все права защищены - Copyright © 2010-{%D_YEAR%} <a target="_blank" href="http://jmy.su/">JMY LTD</a><br>{%LICENSE%}</div>
    </footer>
  </div>
  <a href="#top" id="toTop"><i class="fa fa-angle-up fa-lg"></i></a>
  <script type='text/javascript' src='{%THEME%}/assest/js/jquery.semplicementepro.js?ver=1.0'></script>  
  <script type='text/javascript' src='{%THEME%}/assest/js/navigation.js?ver=20120206'></script>
  <script type='text/javascript' src='{%THEME%}/assest/js/jquery.powertip.min.js?ver=1.0'></script>
  <script type='text/javascript' src='{%THEME%}/assest/js/owl.carousel.min.js?ver=1.0'></script>
</body>
</html>