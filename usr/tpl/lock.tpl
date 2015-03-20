<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<!-- META -->
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
		<title>Сайт находиться на реконструкции</title>
		<base href="http://<?=$_SERVER['HTTP_HOST'] ?>">
		<link rel="shortcut icon" href="usr/tpl/admin/assets/images/favicon.ico" />	
		<!-- MAIN CSS -->
		<link rel="stylesheet" href="usr/tpl/admin/assets/css/bootstrap.min.css">
		<link rel="stylesheet" href="usr/tpl/admin/assets/css/main.css">
		<!-- JS LOAD -->
		<script src="usr/plugins/js/modernizr.js"></script>
	</head>
	<body class="bg-dark">
		<div class="app-user">
			<div class="user-container">
				<section class="panel panel-default">
					<header class="panel-heading">Сайт находиться на реконструкции</header>
					<div class="bg-white user pd-lg">
					
						<h6><?=$config['off_text']?><br /><br /><strong>Для продолжения авторизируйтесь!</strong></h6>
						<form role="form" name="form1" method="post" action="administration">
							<input name="nick" type="text" class="form-control mg-b-sm" placeholder="Логин" autofocus>
							<input name="password" type="password" class="form-control" placeholder="Пароль">
							<br>
							<button class="btn btn-info btn-block" type="submit" name="Submit">Войти</button>
						</form>
						<br>
						<center>Powered by <a target="_blank" href="http://cms.jmy.su/">JMY CMS</a></center>
					</div>
				</section>
			</div>
		</div>
	</body>
</html>