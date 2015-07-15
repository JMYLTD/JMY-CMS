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
  <div id="page" class="hfeed site">    
    <header id="masthead" class="site-header" role="banner">
      <div class="theTop">
        <div class="site-branding">
          <h1 class="site-title"><a href="{%URL%}" rel="home">{%SITE_NAME%}</a></h1>
          <h2 class="site-description">{%SITE_SLOGAN%}</h2>
        </div>    
        <div class="socialLine" role="navigation">
          [guest]
          <a href="{%URL_LOGIN%}" title="Вход">Вход</a>
          <a href="{%URL_REG%}" title="Вход">Регистрация</a>
          [/guest]
          [user] 
          <a href="{%URL_PROFIL%}">Профиль</a>
          {%ADMINLOG%}
          <a href="{%URL_PM%}">Сообщения</a>              
          <a href="{%URL_LOGOUT%}" title="выход">Выход</a>
          [/user]
         </div>      
      </div>
      <nav id="site-navigation" class="main-navigation smallPart" role="navigation">
        <button class="menu-toggle">Меню<i class="fa fa-align-justify"></i></button>
        <div class="menu-menu-1-container">
          <ul class="menu">
            <li class="menu-item menu-item-home">
              <a href="{%URL%}">Главная</a>
            </li>
            <li class="menu-item">
              <a href="#">Компоненты</a>
              <ul class="sub-menu">
                <li class="menu-item">
                  <a href="{%URL_NEWS%}">Новости</a>
                </li>
                <li class="menu-item">
                  <a href="{%URL_FORUM%}">Форум</a>
                </li>
                <li class="menu-item">
                  <a href="{%URL_BLOG%}">Блоги</a>
                </li>
                <li class="menu-item">
                  <a href="{%URL_GUEST%}">Гостевая книга</a>
                </li>
                <li class="menu-item">
                  <a href="{%URL_GALLERY%}">Галерея</a>              
                  <ul class="sub-menu">
                    <li class="menu-item">
                      <a href="{%URL_GALLERY%}/top">Лучшие фото</a>
                    </li>
                    <li class="menu-item">
                      <a href="{%URL_GALLERY%}/album/primer-1">Пример 1</a>
                    </li>
                    <li class="menu-item">
                      <a href="{%URL_GALLERY%}/album/primer-2">Пример 2</a>
                    </li>
                  </ul>
                </li>
                <li class="menu-item">
                  <a href="{%URL_SITEMAP%}">Карта сайта</a>
                </li>
              </ul>          
            </li>
            <li class="menu-item">
              <a href="content/primer_statichesko_straniy.html">Простая страница</a>
            </li>
            <li class="menu-item">
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
        <aside id="calendar-2" class="widget widget_calendar">
          <div class="widget-title">
            <h2>Календарь</h2>
          </div>
          {%BLOCKS:FILE:calendar%}   
        </aside>
        <aside id="categories-2" class="widget widget_categories">
          <div class="widget-title">
            <h2>Категории</h2>
          </div>
          {%BLOCKS:FILE:cats%}       
        </aside>
        <aside class="widget">
          <div class="widget-title">
            <h2>Опрос</h2>
          </div>
          {%BLOCKS:FILE:poll%}   
        </aside>
        <aside class="widget widget_tag_cloud">
          <div class="widget-title">
            <h2>Облако тегов</h2>
          </div>
          <div class="tagcloud">
          {%BLOCKS:FILE:tags%}          
          </div>
        </aside>
        <aside class="widget">
          <div class="widget-title">
            <h2>Кто онлайн?</h2>
          </div>
          {%BLOCKS:FILE:online%}
        </aside>
        <aside class="widget">
          <div class="widget-title">
            <h2>Выбор шаблона:</h2>
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
  <script type='text/javascript' src='{%THEME%}/assest/js/skip-link-focus-fix.js?ver=1.0'></script>
  <script type='text/javascript' src='{%THEME%}/assest/js/jquery.blogghiamo.js?ver=1.0'></script>
  <script type='text/javascript' src='{%THEME%}/assest/js/navigation.js?ver=1.0'></script>
  <script type='text/javascript' src='{%THEME%}/assest/js/SmoothScroll.min.js?ver=1.0'></script>
</body>
</html>