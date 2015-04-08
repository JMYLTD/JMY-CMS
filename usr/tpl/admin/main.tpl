<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html class="js canvas csstransforms csstransforms3d csstransitions video audio localstorage sessionstorage svg inlinesvg">
	<head>	
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
		<meta name="theme-color" content="#535a6c">
		{META}
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/main.css">
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/animate.min.css">
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/theme.css">
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/page_theme.css">
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/jquery-jvectormap-1.2.2.css">
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/datepicker.css">		
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/slider.css">
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/dropzone.css">
		<link rel="stylesheet" href="{ADM_THEME}/assets/css/bootstrap-select.css">		
		<link rel="shortcut icon" href="{ADM_THEME}/assets/images/favicon.ico" />
		<link rel="icon" sizes="192x192" href="{ADM_THEME}/assets/images/favicon.ico">		
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		<script src="usr/plugins/js/ajax_class.js" type="text/javascript"></script>
		<script src="usr/plugins/js/adminPanel.js" type="text/javascript"></script>
		<script src="{ADM_THEME}/assets/js/modernizr.js"></script>
		
	</head>
	<body>
		<div class="app" data-sidebar="locked">
			<header class="header header-fixed navbar bg-white">
				<div class="brand bg-success">
					<a href="#" class="fa fa-bars off-left visible-xs" data-toggle="off-canvas" data-move="ltr"></a>
					<a href="administration/" class="navbar-brand text-white">
						<i class="fa fa-check-circle mg-r-xs"></i>
						<span class="h5"> JMY <b>CMS</b></span>
					</a>
				</div>
				<ul class="nav navbar-nav navbar-right off-right">						
					<li class="notifications dropdown hidden-xs">
						<a href="#" data-toggle="dropdown">
							<i class="fa fa-bell"></i>
							<div class="badge badge-top bg-danger animated flash">{NOTIF_NUMB}</div>
						</a>
						<div class="dropdown-menu dropdown-menu-right animated slideInRight">
							<div class="panel bg-white no-border no-margin">
								<div class="panel-heading no-radius">
									<small><b>[alang:_PANEL_SUNMENU_NOTIF]</b></small>
								</div>
								<ul class="list-group">
									{NOTIF}
								</ul>								
							</div>
						</div>
					</li>					
					<li class="quickmenu mg-r-md">
						<a href="#" data-toggle="dropdown">
							<img src="{AVATAR}" class="avatar pull-left img-circle" alt="Administarator" title="Administarator">
							<i class="caret mg-l-xs hidden-xs no-margin"></i>
						</a>
						<ul class="dropdown-menu dropdown-menu-right mg-r-xs">
							<li>
								<a  target="_blank" href="{URL}/profile">
								<div class="pd-t-sm">[alang:_PANEL_SUNMENU_WELCOME], {NAME}!<br><small class="text-muted">[alang:_PANEL_SUNMENU_IP] {IP}</small></div>
								</a>							
							</li>
							<li>
								<a href="administration/user/edit/1">[alang:_PANEL_SUNMENU_PROFILE]</a>
							</li>
							<li>
								<a href="administration/statistic">[alang:_PANEL_SUNMENU_STATS]</a>
							</li>
							<li>
								<a target="_blank" href="{URL}">[alang:_PANEL_SUNMENU_VIEW]</a>
							</li>
							<li>
								<a target="_blank" href="http://extras.jmy.su/cms/">[alang:_PANEL_SUNMENU_HELP]</a>
							</li>
							<li class="divider">
							</li>
							<li>
								<a href="/profile/logout">[alang:_PANEL_SUNMENU_EXIT]</a>
							</li>
						</ul>
					</li>							
				</ul>
			</header>
			<section class="layout">
				<aside class="sidebar canvas-left bg-dark">
					<nav class="main-navigation">
						<ul>
							<li class="active">
								<a href="administration/">
									<i class="fa fa-home"></i><span>[alang:_PANEL_MENU_MAIN]</span>
								</a>
							</li>							
							<li class="dropdown show-on-hover">
								<a href="#" data-toggle="dropdown">
									<i class="fa fa-tasks"></i>
									<span>[alang:_PANEL_MENU_NEWS]</span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="administration/module/news"><span>[alang:_PANEL_MENU_NEWS_MANAGER]</span></a></li>
									<li>
										<a href="administration/cats"><span>[alang:_PANEL_MENU_NEWS_CAT]</span></a>
									</li>
									<li>
										<a href="administration/xfields"><span>[alang:_PANEL_MENU_NEWS_XFIELDS]</span></a>
									</li>
								</ul>
							</li>							
							<li class="dropdown show-on-hover">
								<a href="#" data-toggle="dropdown">									
									<i class="fa fa-user"></i>
									<span>[alang:_PANEL_MENU_USER]</span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="administration/user"><span>[alang:_PANEL_MENU_USER_MANAGER]</span></a>
									</li>
									<li>
										<a href="administration/groups"><span>[alang:_PANEL_MENU_USER_GROUP]</span></a>
									</li>
									<li>
										<a href="administration/comments"><span>[alang:_PANEL_MENU_USER_COMMENT]</span></a>
									</li>
									<li>
										<a href="administration/voting/"><span>[alang:_PANEL_MENU_USER_POLL]</span></a>										
									</li>
								</ul>
							</li>
							<li class="dropdown show-on-hover">
								<a href="#" data-toggle="dropdown">
									<i class="fa fa-file"></i>
									<span>[alang:_PANEL_MENU_COM]</span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="administration/module/board"><span>[alang:_PANEL_MENU_COM_FORUM]</span></a>
									</li>
									<li>
										<a href="administration/module/gallery"><span>[alang:_PANEL_MENU_COM_GALLERY]</span></a>
									</li>
									<li>
										<a href="administration/module/guestbook"><span>[alang:_PANEL_MENU_COM_GUESTBOOK]</span></a>
									</li>								
									<li>
										<a href="administration/module/content"><span>[alang:_PANEL_MENU_COM_STATIC]</span></a>
									</li>
								</ul>
							</li>
							<li class="show-on-hover">
								<a href="administration/config" >
									<i class="fa fa-cog"></i>
									<span>[alang:_PANEL_MENU_CONFIG]</span>
								</a>								
							</li>
							<li class="dropdown show-on-hover">
								<a href="#" data-toggle="dropdown">
									<i class="fa fa-globe"></i>
									<span>[alang:_PANEL_MENU_EXP]</span>
								</a>
								<ul class="dropdown-menu">
									<li>
										<a href="administration/modules"><span>[alang:_PANEL_MENU_EXP_MODULES]</span></a>
									</li>
									<li>
										<a href="administration/blocks"><span>[alang:_PANEL_MENU_EXP_BLOCKS]</span></a>
									</li>									
									<li>
										<a href="administration/templates"><span>[alang:_PANEL_MENU_EXP_TPL]</span></a>
									</li>									
								</ul>
							</li>
							<li class="dropdown show-on-hover">
								<a href="#" data-toggle="dropdown">
									<i class="fa fa-ellipsis-h"></i>
									<span>[alang:_PANEL_MENU_OTHER]</span>
								</a>
								<ul class="dropdown-menu">	
									<li>
										<a href="administration/module/sitemap"><span>[alang:_PANEL_MENU_OTHER_MAP]</span></a>
									</li>									
									<li>
										<a href="administration/smiles"><span>[alang:_PANEL_MENU_OTHER_SMILES]</span></a>
									</li>									
									<li>
										<a href="administration/db"><span>[alang:_PANEL_MENU_OTHER_BD]</span></a>
									</li>
									<li>
										<a href="administration/log"><span>[alang:_PANEL_MENU_OTHER_LOG]</span></a>
									</li>
								</ul>
							</li>
						</ul>
					</nav>
					<footer>						
						<div class="footer-toolbar pull-left">							
							<a href="#" class="toggle-sidebar pull-right hidden-xs">
								<i class="fa fa-angle-left"></i>
							</a>
						</div>
					</footer>
				</aside>
				<section class="main-content">
				<div class="content-wrap">
						{SUBNAV}
						{MODULE}						
				</div>
				</section>
				</section>
				</div>
				<div data-phase="1" class="offline-ui">
				<div class="offline-ui-content" data-retry-in="" data-retry-in-abbr=""></div>
				<a class="offline-ui-retry"></a>
				</div>
<script src="{ADM_THEME}/assets/js/jquery-1.11.0.min.js"></script>
<script src="{ADM_THEME}/assets/js/bootstrap.js"></script>
<script src="{ADM_THEME}/assets/js/bootstrap-select.js"></script>
<script src="{ADM_THEME}/assets/js/bootstrap-slider.js"></script>
<script src="{ADM_THEME}/assets/js/bootstrap-datepicker.js"></script>
<script src="{ADM_THEME}/assets/js/toastr.js"></script>
<script src="{ADM_THEME}/assets/js/jquery.blockUI.js"></script>
<script src="{ADM_THEME}/assets/js/off-canvas.js"></script>
<script src="{ADM_THEME}/assets/js/jquery.placeholder.js"></script>
<script src="{ADM_THEME}/assets/js/offline.min.js"></script>
<script src="{ADM_THEME}/assets/js/pace.min.js"></script>
<script src="{ADM_THEME}/assets/js/components.js"></script>
<script src="{ADM_THEME}/assets/js/dropzone.js"></script>
<script src="{ADM_THEME}/assets/js/parsley.min.js"></script>
<script src="{ADM_THEME}/assets/js/jquery.maskedinput.min.js"></script>
<script src="{ADM_THEME}/assets/js/checkbox.js"></script>
<script src="{ADM_THEME}/assets/js/radio.js"></script>
<script src="{ADM_THEME}/assets/js/wizard.js"></script>
<script src="{ADM_THEME}/assets/js/pillbox.js"></script>
<script src="{ADM_THEME}/assets/js/spinner.js"></script>
<script src="{ADM_THEME}/assets/js/jquery.hotkeys.js"></script>
<script src="{ADM_THEME}/assets/js/bootstrap-wysiwyg.js"></script>
<script src="{ADM_THEME}/assets/js/switchery.js"></script>
<script src="{ADM_THEME}/assets/js/moment.js"></script>
<script src="{ADM_THEME}/assets/js/skycons.js"></script>
<script src="{ADM_THEME}/assets/js/morris.js"></script>
<script src="{ADM_THEME}/assets/js/main.js"></script>
<script src="{ADM_THEME}/assets/js/jquery.slimscroll.js"></script>
<script src="{ADM_THEME}/assets/js/dashboard.js"></script>
<script src="{ADM_THEME}/assets/js/forms.js"></script>
</body></html>