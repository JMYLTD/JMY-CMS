<!DOCTYPE HTML>
<head>
{%META%}
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0 minimal-ui"/>
<meta name="apple-mobile-web-app-capable" content="yes"/>
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{%THEME%}/images/splash/splash-icon.png">
<link rel="apple-touch-startup-image" href="{%THEME%}/images/splash/splash-screen.png" 			media="screen and (max-device-width: 320px)" />  
<link rel="apple-touch-startup-image" href="{%THEME%}/images/splash/splash-screen_402x.png" 		media="(max-device-width: 480px) and (-webkit-min-device-pixel-ratio: 2)" /> 
<link rel="apple-touch-startup-image" sizes="640x1096" href="{%THEME%}/images/splash/splash-screen_403x.png" />
<link rel="apple-touch-startup-image" sizes="1024x748" href="{%THEME%}/images/splash/splash-screen-ipad-landscape" media="screen and (min-device-width : 481px) and (max-device-width : 1024px) and (orientation : landscape)" />
<link rel="apple-touch-startup-image" sizes="768x1004" href="{%THEME%}/images/splash/splash-screen-ipad-portrait.png" media="screen and (min-device-width : 481px) and (max-device-width : 1024px) and (orientation : portrait)" />
<link rel="apple-touch-startup-image" sizes="1536x2008" href="{%THEME%}/images/splash/splash-screen-ipad-portrait-retina.png"   media="(device-width: 768px)	and (orientation: portrait)	and (-webkit-device-pixel-ratio: 2)"/>
<link rel="apple-touch-startup-image" sizes="1496x2048" href="{%THEME%}/images/splash/splash-screen-ipad-landscape-retina.png"   media="(device-width: 768px)	and (orientation: landscape)	and (-webkit-device-pixel-ratio: 2)"/>
<link href="{%THEME%}/styles/style.css"     		 rel="stylesheet" type="text/css">
<link href="{%THEME%}/styles/framework.css" 		 rel="stylesheet" type="text/css">
<link href="{%THEME%}/styles/owl.carousel.css" 	 rel="stylesheet" type="text/css">
<link href="{%THEME%}/styles/owl.theme.css" 		 rel="stylesheet" type="text/css">
<link href="{%THEME%}/styles/swipebox.css"		 rel="stylesheet" type="text/css">
<link href="{%THEME%}/styles/font-awesome.css"	 rel="stylesheet" type="text/css">
<link href="{%THEME%}/styles/animate.css"			 rel="stylesheet" type="text/css">
<link href="{%THEME%}/styles/engine.css"            rel="stylesheet" type="text/css">
<script type="text/javascript" src="{%THEME%}/scripts/jquery.js"></script>
<script type="text/javascript" src="{%THEME%}/scripts/jqueryui.js"></script>
<script type="text/javascript" src="{%THEME%}/scripts/framework.plugins.js"></script>
<script type="text/javascript" src="{%THEME%}/scripts/custom.js"></script>
</head>
<body>
<div id="preloader">
<div id="status">
<p class="center-text">Загрузка контента...<em>Пожалуйста подождите</em>
</p>
</div>
</div>
<div class="all-elements">
<div class="navigation-background"></div>
{%TPL:navigation%}    
<div class="header">
<a href="{%URL%}" class="header-logo"></a>
<a href="#" class="header-menu show-navigation"><i class="fa fa-navicon"></i></a>
{%TPL:login%}
</div>
<div class="header-clear"></div>
[modules:profile:1]{%MODULE%}[/modules]
<div class="content">
[modules:profile:0]
<div class="decoration transparent"></div>
[index:1]
<div class="widget container">
<form role="search" method="post" name="search_form" action="search">
<input class="blog-search" type="text" name="query" value="Поиск...">
<div class="blog-search-btn"><input type="submit" class="button button-green" value="Найти"></div>
</form>
</div>
<div class="decoration"></div>
[/index]
[/modules]
[modules:news,profile:0]{%MODULE%}[/modules]        
[modules:news:1]<div class="blog-posts">{%MODULE%}</div>[/modules] 
<div class="decoration"></div>
{%TPL:footer%}        
</div>
</div>
</body>