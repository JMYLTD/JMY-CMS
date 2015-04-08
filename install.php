<?php 

/**
* @name        JMY CMS
* @link        http://jmy.su/
* @copyright   Copyright (C) 2012-2015 JMY LTD
* @license     LICENSE.txt (see attached file)
* @version     VERSION.txt (see attached file)
* @author      Komarov Ivan
*/

define('ROOT', dirname(__FILE__) . '/');
define('ACCESS', true);
define('_YES', 'да');
define('_NO', 'нет');
error_reporting(1);
header('Content-type: text/html; charset=utf-8');
require_once ROOT . 'lib/php_funcs.php';
require_once ROOT . 'lib/global.php';
require_once ROOT . 'root/functions.php';

function head() 
{
	ob_start();
}

function foot($n_p) 
{
global $information, $title;
	$content = ob_get_contents();
	ob_end_clean();
	switch(isset($_GET['step']) ? ($_GET['step']) : null)
	{
		default:
			$step = 'WELCOME TO JMY!';
			$nav_bar = 'Соглашение';
			$img_bar = 'install/tpl/images/1.png';
			break;			
			
		case '0':
			$step = 'Шаг 1';
			$nav_bar = 'Настройка подключения к MySql.';
			$img_bar = 'install/tpl/images/2.png';
			break;
			
		case "1":
			$step = 'Шаг 2';
			$nav_bar = 'Внесение таблиц в базу данных.';
			$img_bar = 'install/tpl/images/3.png';
			break;
		
		case "2":
			$step = 'Шаг 3';
			$nav_bar = 'Первичная настройка сайта.';
			$img_bar = 'install/tpl/images/4.png';
			break;		
			
		case "chmod":
			$step = 'Проверка файлов';
			$nav_bar = 'Проверка разрешений';
			$img_bar = 'install/tpl/images/chmod.png';
			break;
		
		case "3":
			$step = 'Шаг 4';
			$nav_bar = 'Сохранение параметров.';
			$img_bar = 'install/tpl/images/4.png';
			break;
		
		case "4":
			$step = 'Финал';
			$nav_bar = 'Финальный шаг.';
			$img_bar = 'install/tpl/images/5.png';	
			$autor='<section class="panel">
						<div class="row">
							<div class="col-md-12">
								<div class="carousel slide" data-ride="carousel" id="quote-carousel">
									<div class="carousel-inner">
										<div class="item active">
											<div class="row">
												<div class="col-sm-3 text-center">
													<img class="img-circle avatar avatar-md" src="install/tpl/images/i.jpg" alt="">
												</div>
												<div class="col-sm-9">
													<p>Спасибо вам за то что выбрали нашу систему, я надеюсь JMY cms понравится вам!</p>
													<small>
														<i>Комаров Иван</i>
													</small>
												</div>
											</div>
										</div>
									</div>											
								</div>
							</div>
						</div>
					</section>';
			break;
	}
	
	$html = file_get_contents('install/tpl/install.html');
	$html = str_replace('{%CONTENT%}', $content, $html);
	$html = str_replace('{%URL%}', $_SERVER['HTTP_HOST'], $html);
	$html = str_replace('{%INFORMATION%}', $information, $html);
	$html = str_replace('{%STEP%}', $step, $html);
	$html = str_replace('{%IMG_BAR%}', $img_bar, $html);
	$html = str_replace('{%TITLE%}', $title, $html);
	$html = str_replace('{%NAV_BAR%}', '<b>' . $nav_bar . '</b>', $html);
	$html = str_replace('{%NO_PADDING%}', $n_p, $html);
	$html = str_replace('{%AUTOR%}', $autor, $html);
	
	echo $html;
}

function license()
{
	$title = 'Добро пожаловать | ';
	head();
	echo 'Добро пожаловать в установку JMY CMS. Мы очень признательны, что Вы выбрали наш продукт!<br /><br />';
	echo '
	<iframe style="height:250px; overflow:auto; width:650px; min-width: 650px; margin:0 auto; border:1px dashed #ccc; padding:5px;"  src="http://cms.jmy.su/inc/gpl.htm" width="655" height="250" scrolling="auto" frameborder="0" allowtransparency="true" allowFullScreen="true" allowScriptAccess="always"></iframe>
	';
	echo '<br /><br /><div align="center"><button type="button" class="btn btn-success" onclick="window.location = \'install.php?step=0\';" />Принимаю, перейти к процессу установке</button></div>';
	foot();
}

function step0() {
global $information, $title;
	$information = 'Настройка подключения к базе данных MySql. Если вы не знаете предназначение того или иного поля, обратитесь к вашему хостеру для разъяснений или на наш форум :).';
	$title = 'Начало | ';
	head();
	echo (!is_writable('./etc/db.config.php') ? ' <font color="red">Система не смогла получить доступ к файлу "etc/db.config.php", пожалуйста установите права <b>666</b> на этот файл для продолжения установки!</font>' : '');
	echo '
	<form action="install.php?step=1" method="post">
	<div class="form-group">
	<label class="col-sm-2 control-label">MySql сервер:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="dbhost"  data-parsley-required="true" data-parsley-trigger="change" value="localhost" data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Имя пользователя:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="dbuser" data-parsley-required="true" data-parsley-trigger="change" placeholder="Ведите пользователя базы данных." data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Имя базы данных:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="dbname" data-parsley-required="true" data-parsley-trigger="change" placeholder="Имя базы данных, где будут созданы таблицы." data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Пароль:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="dbpass" data-parsley-required="true" data-parsley-trigger="change" placeholder="Введите пароль от базы данных, которую ввели выше." data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Префикс:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="prefix" data-parsley-required="true" data-parsley-trigger="change" value="JMY_" data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>';
	if(is_writable('./etc/db.config.php')) 
	{
		echo '<br /><div align="center"><input type="submit" value="Далее" class="btn btn-success" /></div>';
	}
	else
	{
		echo '<b>Необходимо устранить все ошибки перед продолжением установки.</b>';
	}
	echo '</td>
	</tr>
	</table>
	</form>
	';
	foot();
}

function step1() 
{
global $information, $title;
	$information = 'Информация';
	$title = 'Шаг 2 | ';
	head();
	$dbhost = $_POST['dbhost'];
	$dbuser = $_POST['dbuser'];
	$dbpass = $_POST['dbpass'];
	$dbname = $_POST['dbname'];
	$prefix = !empty($_POST['prefix']) ? $_POST['prefix'] : 'toogle_';
	
    $resource = mysql_pconnect($dbhost, $dbuser, $dbpass);
    if ($resource) 
	{
        if (!mysql_select_db($dbname)) 
		{
			echo '<br />
		<br /><div class="alert alert-warning alert-dismissable">
                                      
                                        <strong>Внимание!</strong><br />Соединение с сервером прошло успешно, но мы не смогли найти базу данных: <i>' . $dbname . '</i>. <br />Проверьте введенные данные!
                                    </div>
		<br /><div align="center"> <a href="install.php?step=0" class="btn btn-danger"><< Назад </a></div>';
			
			$stop = 1;
		}
		else
		{

			if(isset($_POST['goCreate']) && isset($_POST['do']))
			{
				@mysql_query('SET NAMES utf8');
				if($_POST['do'] == 'install')
				{
					
					$sql_create = file_get_contents('install/sql/sql_create.sql');
					$sql_create_massiv = split(";", $sql_create);
					$n_p='no-padding';
					echo ' <table class="table no-margin">
                                        <thead>
                                            <tr>
                                                <th class="col-md-5 pd-l-lg"><span class="pd-l-sm"></span>Действие</th>
                                                <th class="col-md-2">Таблица</th>
                                               
                                                <th class="col-md-2">Статус</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           

                                      
                              ';
					foreach($sql_create_massiv as $query)
					{
						if(preg_match('#CREATE#i', $query)) 
						{
							preg_match('#`\[prefix\](.*)`#i', $query, $name);
							if(@mysql_query(str_replace('[prefix]', $prefix, $query) . ";", $resource))
							{
								echo '<tr>
								
								<td><span class="pd-l-sm"></span>Создание таблицы</td>
								<td><b>' . str_replace('[prefix]', $prefix, $name[0]) . '</b></td>
								<td><button type="button" class="btn btn-success btn-sm">V</button></td>
								</tr>';
								
								$success = 1;
							}
							else
							{
								echo '<tr>
								
								<td><span class="pd-l-sm"></span>Ошибка при создании таблицы </td>
								<td><b>' . str_replace('[prefix]', $prefix, $name[0]) . '</b></td>
								<td><button type="button" class="btn btn-danger btn-sm">X</button></td>
								</tr>';
								$error = 1;
							}
						}
					}
					echo '  </tbody>
                                    </table>';
					if (!empty($error))
					{
					echo '<br /><div align="center"> <a href="install.php?step=0" class="btn btn-danger"><< Назад </a></div><br />';
					}
					else
					{
					echo '<br /><div align="center"> <a href="install.php?step=chmod" class="btn btn-success">Далее </a></div><br />';
					
					}
					
					$sql_insert = file_get_contents('install/sql/sql_insert.sql');
					$sql_insert_massiv = split(";", $sql_insert);
					
					foreach($sql_insert_massiv as $query)
					{
						if(preg_match('#INSERT#i', $query)) 
						{
							@mysql_query(str_replace('[prefix]', $prefix, $query), $resource);
						}
					}
					
					if(isset($_POST['test_content']) && $_POST['test_content'] == 1)
					{
						$sql_content = file_get_contents('install/sql/sql_content.sql');
						$sql_content_massiv = split(";", $sql_content);
						
						foreach($sql_content_massiv as $query)
						{
							if(preg_match('#INSERT#i', $query)) 
							{
								@mysql_query(str_replace('[prefix]', $prefix, $query), $resource);
							}
						}
					}
					
					$all_count = count($sql_insert_massiv)+count($sql_create_massiv);
				}
				
				$content .= '$dbhost = \'' . $dbhost . '\';' . "\n";
				$content .= '$dbuser = \'' . $dbuser . '\';' . "\n";
				$content .= '$dbpass = \'' . $dbpass . '\';' . "\n";
				$content .= '$dbname = \'' . $dbname . '\';' . "\n". "\n";
				$content .= '$prefix = \'' . (mb_substr($prefix, -1) == '_' ? mb_substr($prefix, 0, -1) : $prefix) . '\';' . "\n";
				$content .= '$user_prefix = \'' . (mb_substr($prefix, -1) == '_' ? mb_substr($prefix, 0, -1) : $prefix) . '\';' . "\n";
				$content .= '$user_db = \'' . $dbname . '\';' . "\n";

				save_conf('etc/db.config.php', $content);

				

			}
			else
			{
				echo '
				
				<form action="install.php?step=1" method="post">
				
				<br />
		<br /><div class="panel-heading alert alert-success alert-dismissable">
                                      
                                        <strong>Информация!</strong><br />Связь с mysql сервером установлена, получен доступ к базе данных. Нажмите далее для старта работ с базой данных.
                                    </div>
		<br />
				
				
				<input type="hidden" name="dbhost" value="' . $dbhost . '" />
				<input type="hidden" name="dbuser" value="' . $dbuser . '" />
				<input type="hidden" name="dbpass" value="' . $dbpass . '" />
				<input type="hidden" name="prefix" value="' . $prefix . '" />
				<input type="hidden" name="dbname" value="' . $dbname . '" />
				<input type="hidden" name="goCreate" value="1" />
				<input type="hidden" name="do" value="install" />
				<input type="hidden" name="test_content" value="0" />	
				
				
				
				<div align="center"><input type="submit" value="Далее" class="btn btn-success" /></div>
				
				</form>';
			}
		}

	} 
	else 
	{
		echo '<br />
		<br /><div class="alert alert-warning alert-dismissable">
                                      
                                        <strong>Внимание!</strong><br />К сожалению мы не смогли установить соединение с базой данных. Проверьте введенные данные!
                                    </div>
		<br /><div align="center"> <a href="install.php?step=0" class="btn btn-danger"><< Назад </a></div>';
		$stop = 1;
    }

	
	if (!empty($n_p))
	{
	foot($n_p);
	}
	else
	{
	foot();
}
	
}




function step2() 
{
global $information, $title;
	$title = 'Шаг 3 | ';
	head();
	require_once ROOT . 'etc/global.config.php';
	echo '	
	
	<form action="install.php?step=3" method="post">
	<div class="form-group">
	<label class="col-sm-2 control-label">Адрес сайта:</label>
	<div class="col-sm-10">
	<input type="text" id="url" class="form-control" name="url" value="http://'.$_SERVER['HTTP_HOST'].'">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	<script>	
	lnk = document.location.pathname; 
	lnk = lnk.replace("/install.php","");	 	
	document.getElementById(\'url\').value += lnk;	
	</script>
	<div class="form-group">
	<label class="col-sm-2 control-label">Название сайта:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="name"  data-parsley-required="true" data-parsley-trigger="change" value="Мой первый сайт на JMY CMS" data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Кодировка сайта:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="charset" data-parsley-required="true" data-parsley-trigger="change" value="' . $config['charset'] . '"  data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	<div class="form-group">
	<label class="col-sm-2 control-label">Включить g-zip?:</label>
	<div class="col-sm-10">
	'.radio("gzip", 1).'	
	</div></div>
	
	<div class="form-group">
	<label class="col-sm-2 control-label">Включить ЧПУ?:</label>
	<div class="col-sm-10">
	'.radio("mod_rewrite", 1).'
	</div></div>
	
	<div class="form-group">
	<label class="col-sm-2 control-label">E-mail администратора:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="email" data-parsley-required="true" data-parsley-trigger="change" value="" data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	
	<div class="form-group">
	<label class="col-sm-2 control-label">Имя администратора:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="nick" data-parsley-required="true" data-parsley-trigger="change" value="admin" data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	
	<div class="form-group">
	<label class="col-sm-2 control-label">Пароль администратора:</label>
	<div class="col-sm-10">
	<input type="text" class="form-control" name="password" data-parsley-required="true" data-parsley-trigger="change" data-parsley-id="5887">	
	<ul class="parsley-errors-list" id="parsley-id-5887"></ul></div></div>
	
	<br />
	<div align="center"><input type="submit" value="Далее" class="btn btn-success" /></div>	
	</form>
	';
	foot();
}


function genmycode($lenght)
{
    $symbols = array('a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','2','3','4','5','6','7','8','9');
    for($i=0;$i<$lenght;$i++)
    {
        $code[] = $symbols[rand(0,sizeof($symbols)-1)];
    }
	$code = array_unique($code);
	
    return implode('', $code);
}
function step3() 
{
global $information, $title;
	$title = 'Шаг 4 | ';
	head();
	if(!empty($_POST['name']) && !empty($_POST['charset']) && !empty($_POST['gzip']) && !empty($_POST['nick']) && !empty($_POST['password']))
	{
		require_once ROOT . 'etc/global.config.php';
		$content = "\$config = array();\n";
		foreach($config as $k => $val) 
		{
			if($k !== 'name' && $k !== 'charset' && $k !== 'gzip' && $k !== 'url' && $k !== 'uniqKey' && $k !== 'mod_rewrite') 
			{
				if(!is_array($val)) 
				{
					$content .= "\$config['".$k."'] = \"".$val."\";\n";
				} 
				else 
				{
					foreach($val as $karr => $varr) 
					{
						$content .= "\$config['".$k."']['".$karr."'] = \"".$varr."\";\n";
					}
				}
			}
		}
		$content .= "\$config['name'] = \"".$_POST['name']."\";\n";
		$content .= "\$config['charset'] = \"".$_POST['charset']."\";\n";
		$content .= "\$config['gzip'] = \"".$_POST['gzip']."\";\n";
		$content .= "\$config['mod_rewrite'] = \"".$_POST['mod_rewrite']."\";\n";
		$content .= "\$config['url'] = \"".$_POST['url']."\";\n";
		$content .= "\$config['uniqKey'] = \"" . genmycode(10) . "\";\n";
		$content .= "\$config['support_mail'] = \"" . $_POST['email'] . "\";\n";
		
		save_conf('etc/global.config.php', $content);
		
		require_once ROOT . 'etc/db.config.php';
		
		$resource = mysql_pconnect($dbhost, $dbuser, $dbpass);
		
		
	    if ($resource) 
		{
	        if (mysql_select_db($dbname)) 
			{
				@mysql_query('SET NAMES utf8');
				$tail = gencode(10);
				list($news) = mysql_fetch_array(mysql_query("SELECT COUNT(*) FROM " . $prefix . "_news", $resource));
				@mysql_query("INSERT INTO " . $prefix . "_users (`nick` , `password` , `tail` , `group` , `user_news` , `active` ) VALUES ('" . $_POST['nick'] . "', '" . md5(mb_substr(md5(md5($_POST['password'])), 0, -mb_strlen($tail)) . $tail) . "', '" . $tail . "', '1', '" . $news . "', '1');", $resource);
				list($uid) = mysql_fetch_array(mysql_query("SELECT id FROM " . $prefix . "_users WHERE nick='" . $_POST['nick'] . "' LIMIT 1", $resource));
				@mysql_query("INSERT INTO `" . $prefix . "_board_users` (`uid`) VALUES ('" . $uid . "');", $resource);
	        }
		}
		echo '
		<br /><div class="alert alert-success alert-dismissable">
                                      
                                        <strong>Настройки сохранены!</strong><br />Имя пользователя: <strong>' . $_POST['nick'] . '</strong><br /> Пароль: <strong>' . $_POST['password'].'</strong>
                                    </div>
		<br /><div align="center"> <a href="install.php?step=4" class="btn btn-success">Далее</a></div>';
		
		
	}
	else
	{
		echo '
		<br /><div class="alert alert-warning alert-dismissable">
                                      
                                        <strong>Внимание!</strong><br />Обязательные поля не заполнены!                               </div>
		<br /><div align="center"> <a href="install.php?step=2" class="btn btn-danger"><<Назад</a></div>';
	
	}
	foot();
}

function checkChmod()
{
	$dirs = array(
	'./tmp/',
	'./tmp/archives/',
	'./tmp/cache/',
	'./tmp/mysql/',
	'./files/avatars/',
	'./files/blog/',
	'./files/board/',
	'./files/gallery/',
	'./files/news/',
	'./files/thumb/',
	'./files/user/',
	'./files/avatars/users/',
	'./files/',
	'./etc/',
	'./usr/tpl/',
	'./usr/modules/',
	'./usr/blocks/',
	'./usr/plugins/',
	);
	$title = 'Проверка прав доступа | ';
	head();
	echo '
	<div align="right">
	<button type="button" class="btn btn-success btn-sm"  onClick="window.location = \'install.php?step=chmod\';">
                                        <i class="fa fa-refresh fa-spin mg-r-xs"></i>Обновить</button>
	<button class="btn btn-sm  btn-info" data-toggle="popover" data-placement="top" title="" data-content="Необходимо установить права на необходимые папки, иначе корректная работа системы не гарантируется!" data-original-title="Внимание!" aria-describedby="popover684207">Что это такое?</button><span class="pd-l-sm"></span><span class="pd-l-sm"></span><span class="pd-l-sm"></span>
	</div>
	<b></b> <hr />';
	
	
	$n_p='no-padding';
					echo ' <table class="table no-margin">
                                        <thead>
                                            <tr>
                                                <th class="col-md-5 pd-l-lg"><span class="pd-l-sm"></span>Действие</th>
                                                <th class="col-md-2">Таблица</th>
                                               
                                                <th class="col-md-2">Статус</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
	
	
	
	
	foreach($dirs as $dir)
	{
		@chmod($dir, 0777);
		$chm = @decoct(@fileperms($dir)) % 1000;
		if(is_writable($dir))
		{
			$status = '<font color="green">разрешено</font>';
		}
		else
		{
			$status = '<font color="red">запрещено</font>';
		}
		echo '<tr><td><span class="pd-l-sm"></span>'.$dir.'</td><td>'.$status. '</td><td> [' . $chm . ']</td></tr>';
	}
	
	foreach(scandir('./etc/') as $file)
	{
		if(preg_match('#.config.php#i', $file))
		{
			$file = './etc/'.$file;
			@chmod($file, 0666);
			$chm = @decoct(@fileperms($file)) % 1000;
			if(is_writable($file))
			{
				$status = '<font color="green">разрешено</font>';
			}
			else
			{
				$status = '<font color="red">запрещено</font>';
			}
			echo '<tr><td><span class="pd-l-sm"></span>'.$file.'</td><td>'.$status. '</td><td> [' . $chm . ']</td></tr>';
		}
	}

	echo '</tbody></table>';
		echo '<br /><div align="center"> <a href="install.php?step=2" class="btn btn-success">Перейти к основным настройкам сайта</a></div><br />';
	if (!empty($n_p))
	{
	foot($n_p);
	}
	else
	{
	foot();
	}
}

if(!file_exists('install/lock.install'))
{
	switch(isset($_GET['step']) ? ($_GET['step']) : null) 
	{
		default:
			license();
			break;
			
		case '0':
			step0();
			break;
		
		case "1":
			step1();
			break;
		
		case "2":
			step2();
			break;
			
		case "chmod":
			checkChmod();
			break;		

		case "3":
			step3();
			break;		

		case "4":
			$title = 'Шаг 4 | ';
			$n_p='no-padding';
			head();
			
			echo '<center><h4>Установка JMY CMS завершена!</h4><br /><br />
			<div style="padding:20px;" class="btn-group btn-group-justified">
			
                                        <a  href="news" class="btn btn-info btn-rounded" role="button">Сайт</a>
                                        <a  href="http://jmy.su" class="btn btn-success" role="button">JMY LTD</a>
                                        <a  href="administration/" class="btn btn-info btn-rounded" role="button">Панель</a>
                                    </div>
									<hr />
			<b>Удалите файл install.php и папку install из корня!</b>
			<hr />
									<div class="mg-t-lg" style="margin-left:50px;">

                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="pd-md">
                                                <a  class="pull-left mg-r-md">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                                                        <i class="fa fa-star fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                                <p class="text-left">
                                                    <span class="text-success">Современный дизайн</span>
                                                    <small class="pull-left">удобство, красота, стиль</small>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="pd-md">
                                                <a class="pull-left mg-r-md">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x text-color"></i>
                                                        <i class="fa fa-rocket fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                                <div class="text-left">
                                                    <span class="text-success">Отличная скорость работы</span>
                                                    <small class="center-block">скорость превыше всего!</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="pd-md">
                                                <a  class="pull-left mg-r-md">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x text-warning"></i>
                                                        <i class="fa fa-cubes fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                                <div class="text-left">
                                                    <span class="text-success">Модульный подход</span>
                                                    <small class="center-block">JMY cms работает по принципу модульной системы</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="pd-md">
                                                <a  class="pull-left mg-r-md">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x text-info"></i>
                                                        <i class="fa fa-check fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                                <div class="text-left">
                                                    <span class="text-success">Простота в использовании</span>
                                                    <small class="center-block">совершенно новый подход к организации работы</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-6">
                                            <div class="pd-md">
                                                <a class="pull-left mg-r-md">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x text-danger"></i>
                                                        <i class="fa fa-heart fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                                <div class="text-left">
                                                    <span class="text-success">Шаблоны</span>
                                                    <small class="center-block">Создайте свой индивидуальный дизайн</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-6">
                                            <div class="pd-md">
                                                <a  class="pull-left mg-r-md">
                                                    <span class="fa-stack fa-lg">
                                                        <i class="fa fa-circle fa-stack-2x text-success"></i>
                                                        <i class="fa fa-comments fa-stack-1x fa-inverse"></i>
                                                    </span>
                                                </a>
                                                <div class="text-left">
                                                    <span class="text-success">Помощь и поддержка</span>
                                                    <small class="center-block">мы всегда протянем руку помощи</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
			</center><br />	
			
			';
			delcache('plugins');		
			@fopen(ROOT . 'install/lock.install', 'w');	
			if (!empty($n_p))
	{
	foot($n_p);
	}
	else
	{
	foot();
	}
			break;
	}
}
else
{
	Header('Location: /');
}
