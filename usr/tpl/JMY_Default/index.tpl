<!DOCTYPE html>
<html lang="en">

<head>
{%META%}
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="{%THEME%}/assets/img/favicon.png">
    <!-- CSS -->
    <link href="{%THEME%}/assets/css/preload.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/yamm.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/bootstrap-switch.min.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/font-awesome.min.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/animate.min.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/slidebars.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/lightbox.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/jquery.bxslider.css" rel="stylesheet">
    <link href="{%THEME%}/assets/css/syntaxhighlighter/shCore.css" rel="stylesheet" media="screen">
    <link href="{%THEME%}/assets/css/style-blue.css" rel="stylesheet" media="screen" title="default">
    <link href="{%THEME%}/assets/css/width-boxed.css" rel="stylesheet" media="screen" title="default">
    <link href="{%THEME%}/assets/css/buttons.css" rel="stylesheet" media="screen">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
        <script src="{%THEME%}/assets/js/html5shiv.min.js"></script>
        <script src="{%THEME%}/assets/js/respond.min.js"></script>
    <![endif]-->
</head>
<!-- Preloader -->
<div id="preloader"><div id="status">&nbsp;</div></div>
<body>
<div id="sb-site">
<div class="boxed">
<header id="header-full-top" class="hidden-xs header-full">
<div class="container">
<div class="header-full-title">
<h1 class="animated fadeInRight"><a href="{%URL%}"><span>{%SITE_NAME%}</span></a></h1>
<p class="animated fadeInRight">{%SITE_SLOGAN%}</p>
</div>
<nav class="top-nav">            
[guest]
<div class="dropdown animated fadeInDown animation-delay-11">
<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Войти</a>
<div class="dropdown-menu dropdown-menu-right dropdown-login-box animated fadeInUp">
<form role="form" action="profile/login" method="post"><h4>Форма входа</h4>
<div class="form-group">
<div class="input-group login-input">
<span class="input-group-addon"><i class="fa fa-user"></i></span>
<input type="text" name="nick" class="form-control" placeholder="Логин" onblur="if(this.value=='') this.value='Логин';" onfocus="if(this.value=='Логин') this.value='';">
</div><br>
<div class="input-group login-input">
<span class="input-group-addon"><i class="fa fa-lock"></i></span>
<input type="password" name="password" class="form-control" placeholder="Пароль" onfocus="if(this.value=='Пароль') this.value='';" onblur="if(this.value=='') this.value='Пароль';">
</div>
<div class="pull-left"><a href="{%URL_FORGOT%}">Забыли пароль?</a></div>
<button type="submit" class="btn btn-ar btn-primary pull-right">Войти</button>
<div class="clearfix"></div>
</div>
</form>
</div>
</div>
<div class="dropdown animated fadeInDown animation-delay-13">
<a href="{%URL_REG%}" class="dropdown-toggle"><i class="fa fa-plus"></i> Регистрация</a>
</div> <!-- dropdown -->
[/guest]
[user]
<div class="dropdown animated fadeInDown animation-delay-13">
<a href="{%URL_PROFIL%}" class="dropdown-toggle"><i class="fa fa-user"></i> Профиль</a>
</div>
<div class="dropdown animated fadeInDown animation-delay-13">
<a href="/news/addPost" class="dropdown-toggle"><i class="fa fa-plus"></i> Опубликовать</a>
</div>
<div class="dropdown animated fadeInDown animation-delay-13">
<a href="{%URL_PM%}" class="dropdown-toggle"><i class="fa fa-envelope-o"></i> Почта</a>
</div>
<div class="dropdown animated fadeInDown animation-delay-13">
<a href="{%URL_LOGOUT%}" class="dropdown-toggle"><i class="fa fa-share"></i> Выйти</a>
</div> <!-- dropdown -->
[/user]
</nav>
</div> <!-- container -->
</header> <!-- header-full -->
<nav class="navbar navbar-static-top navbar-default navbar-header-full navbar-dark" role="navigation" id="header">
<div class="container">
<!-- Brand and toggle get grouped for better mobile display -->
<div class="navbar-header">
<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
<span class="sr-only">Toggle navigation</span>
<i class="fa fa-bars"></i>
</button>
<a class="navbar-brand hidden-lg hidden-md hidden-sm" href="index.html">Artificial <span>Reason</span></a>
</div> <!-- navbar-header -->
<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
<ul class="nav navbar-nav">
<li><a href="{%URL%}" class="dropdown-toggle">Главная</a></li>
<li><a href="{%URL_NEWS%}" class="dropdown-toggle">Новости</a></li>
<li><a href="{%URL_FORUM%}" class="dropdown-toggle">Форум</a></li>
<li><a href="{%URL_BLOG%}" class="dropdown-toggle">Блоги</a></li>
<li><a href="{%URL_GUEST%}" class="dropdown-toggle">Гостевая книга</a></li>
<li><a href="{%URL_GALLERY%}" class="dropdown-toggle">Галерея</a></li>
<li class="dropdown">
<a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Меню</a>
<ul class="dropdown-menu dropdown-menu-left">
<li class="dropdown-submenu">
<a href="javascript:void(0);" class="has_children">Меню</a>
<ul class="dropdown-menu dropdown-menu-left">
<li><a href="/">Меню</a></li>
<li><a href="/">Меню</a></li>
<li><a href="/">Меню</a></li>
<li class="divider"></li>                                        
<li><a href="/">Меню</a></li>
<li><a href="/">Меню</a></li>
</ul>
</li>
<li class="dropdown-submenu">
<a href="javascript:void(0);" class="has_children">Меню</a>
<ul class="dropdown-menu dropdown-menu-left">
<li><a href="/">Меню</a></li>
<li><a href="/">Меню</a></li>
<li class="divider"></li>                                        
<li><a href="/">Меню</a></li>
<li><a href="/">Меню</a></li>
<li class="divider"></li>                                        
<li><a href="/">Меню</a></li>
<li><a href="/">Меню</a></li>
</ul>
</li>
<li class="dropdown-submenu">
<a href="javascript:void(0);" class="has_children">Меню</a>
<ul class="dropdown-menu dropdown-menu-left">
<li><a href="/">Меню</a></li>
<li><a href="/">Меню</a></li>
</ul>
</li>
</ul>
</li>
<li><a href="{%SEARCH%}" class="dropdown-toggle">Поиск</a></li>
<li><a href="{%URL_FEEDBACK%}" class="dropdown-toggle">Обратная связь</a></li>
<li><a href="{%URL_SITEMAP%}" class="dropdown-toggle">Карта сайта</a></li>                
</ul>
</div><!-- navbar-collapse -->
</div><!-- container -->
</nav>
<br/>
<div class="container">
<div class="row">
<div class="col-md-8">
{%MODULE%}
</div> <!-- col-md-8 -->
<div class="col-md-4">
<aside class="sidebar">
<div class="block animated fadeInDown animation-delay-12">
<form role="search" method="post" name="search_form" action="search">
<div class="input-group">
<input type="text" placeholder="Поиск..." class="form-control" name="query">
<span class="input-group-btn">
<button class="btn btn-ar btn-primary" type="submit"><i class="fa fa-search no-margin-right"></i></button>
</span>
</div><!-- /input-group -->
</form>                    
</div>
<div class="block animated fadeInDown animation-delay-10">
<ul class="nav nav-tabs nav-tabs-ar" id="myTab2">
<li><a href="#fav" data-toggle="tab">Топ новости</a></li>
<li class="active"><a href="#categories" data-toggle="tab">Категории</a></li>
</ul>
<div class="tab-content">
<div class="tab-pane" id="fav">
<h3 class="post-title no-margin-top">Топ новости</h3>
<ul class="media-list">{%BLOCKS:FILE:topnews%}</ul>
</div>
<div class="tab-pane active" id="categories">
<h3 class="post-title no-margin-top">Категории</h3>
<ul class="simple">{%BLOCKS:FILE:cats%}</ul>
</div>
</div> <!-- tab-content -->
</div>
<div class="panel panel-primary animated fadeInDown animation-delay-4">
<div class="panel-heading"><i class="fa fa-user"></i> Кто онлайн</div>
<div class="panel-body">
{%BLOCKS:FILE:online%}
</div>
</div>                
<div class="panel panel-primary animated fadeInDown animation-delay-4">
<div class="panel-heading"><i class="fa fa-tags"></i> Теги</div>
<div class="panel-body">
{%BLOCKS:FILE:tags%}
</div>
</div>                
<div class="panel panel-primary animated fadeInDown animation-delay-4">
<div class="panel-heading"><i class="fa fa-align-left"></i> Опросы</div>
<div class="panel-body">
{%BLOCKS:FILE:poll%}
</div>
</div>
<div class="panel panel-default animated fadeInDown animation-delay-4">
<div class="panel-heading"><i class="fa fa-eye"></i> Тема оформления</div>
<div class="panel-body">
{%BLOCKS:FILE:themes%}
</div>
</div>
</aside> <!-- Sidebar -->
</div>
</div> <!-- row -->
</div> <!-- container  -->
<footer id="footer"><p>&copy; {%D_YEAR%} {%LICENSE%}</p></footer>
</div> <!-- boxed --></div> <!-- sb-site -->
<div id="back-top"><a href="#header"><i class="fa fa-chevron-up"></i></a></div>
<!-- Scripts -->
<script src="{%THEME%}/assets/js/jquery.min.js"></script>
<script src="{%THEME%}/assets/js/jquery.cookie.js"></script>
<script src="{%THEME%}/assets/js/bootstrap.min.js"></script>
<script src="{%THEME%}/assets/js/bootstrap-switch.min.js"></script>
<script src="{%THEME%}/assets/js/wow.min.js"></script>
<script src="{%THEME%}/assets/js/slidebars.js"></script>
<script src="{%THEME%}/assets/js/jquery.bxslider.min.js"></script>
<script src="{%THEME%}/assets/js/holder.js"></script>
<script src="{%THEME%}/assets/js/buttons.js"></script>
<script src="{%THEME%}/assets/js/styleswitcher.js"></script>
<script src="{%THEME%}/assets/js/jquery.mixitup.min.js"></script>
<script src="{%THEME%}/assets/js/circles.min.js"></script>

<!-- Syntaxhighlighter -->
<script src="{%THEME%}/assets/js/syntaxhighlighter/shCore.js"></script>
<script src="{%THEME%}/assets/js/syntaxhighlighter/shBrushXml.js"></script>
<script src="{%THEME%}/assets/js/syntaxhighlighter/shBrushJScript.js"></script>

<script src="{%THEME%}/assets/js/app.js"></script>
<script src="{%THEME%}/assets/js/index.js"></script>

</body>

</html>
