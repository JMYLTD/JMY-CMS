<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

define('ACCESS', true);
define('VERSION_ID', '1.6'); 
define('TIMER', microtime(1));
define('ROOT', dirname(__FILE__) . '/');
define('PLUGINS', dirname(__FILE__) . '/usr/plugins/');
define('COOKIE_AUTH', 'auth_jmy');
define('COOKIE_PAUSE', 'pause_jmy');
define('AJAX', true);
define('PAUSE_TIME', 120);
define('COOKIE_TIME', 2592000);
define('ADMIN', 'administration');
define('HACK_SQL', '/SELECT|INSERT|ALTER|DROP|UNION|OUTFILE|WHERE/i');
define('DENIED_HTML', '/<.*?(script|meta|body|object|iframe|frame|applet|style|form|img|onmouseover).*?>/i');

define('INDEX', isset($_GET['url']) ? false : true);



session_start();
require ROOT . 'boot/sub_classes/socialauther/autoload.php';
require ROOT . 'etc/social.config.php';	
require ROOT . 'etc/db.config.php';	


$db = mysql_connect($dbhost, $dbuser, $dbpass);
mysql_select_db($dbname);
mysql_query("SET NAMES utf8");

$adapters = array();
foreach ($adapterConfigs as $adapter => $settings) {
    $class = 'SocialAuther\Adapter\\' . ucfirst($adapter);
    $adapters[$adapter] = new $class($settings);
}

if (isset($_GET['provider']) && array_key_exists($_GET['provider'], $adapters) && !isset($_SESSION['user'])) {
    $auther = new SocialAuther\SocialAuther($adapters[$_GET['provider']]);

    if ($auther->authenticate()) {
        $result = mysql_query("SELECT *  FROM `".$user_db."_users` WHERE `provider` = '{$auther->getProvider()}' AND `social_id` = '{$auther->getSocialId()}' LIMIT 1");
        $record = mysql_fetch_array($result);
        if (!$record) {
		
			if ($auther->getSex()=='male')
			{
				$sex='0';
			}
			else 
			{
				$sex='1';
			}
			
            $values = array(
                $auther->getProvider(),
                $auther->getSocialId(),
                $auther->getName(),
                $auther->getEmail(),
				$sex,
                date('Y-m-d', strtotime($auther->getBirthday()))
            );
			echo '<br>';
			echo $auther->getProvider();
			echo '<br>';
			echo $auther->getSocialId();
			echo '<br>';
			echo  $auther->getName();
			echo '<br>';
			echo $auther->getEmail();
			echo '<br>';
			echo  $sex;
			echo '<br>';
			echo date('Y-m-d', strtotime($auther->getBirthday()));
			echo '<br>';
            $query = "INSERT INTO `".$user_db."_users` (`provider`, `social_id`, `name`, `email`, `sex`, `birthday`) VALUES ('";
            $query .= implode("', '", $values) . "')";
            $db = mysql_query($query);
			echo mysql_errno($result) . ": " . mysql_error($result) . "\n";
			echo 'gh';
			
        }
		else 		
		{
			echo 'gh2';
            $userFromDb = new stdClass();
            $userFromDb->provider   = $record['provider'];
            $userFromDb->socialId   = $record['social_id'];
            $userFromDb->name       = $record['name'];
            $userFromDb->email      = $record['email'];
            $userFromDb->sex        = $record['sex'];
            $userFromDb->birthday   = date('m.d.Y', strtotime($record['birthday']));          
        }

        $user = new stdClass();
        $user->provider   = $auther->getProvider();
        $user->socialId   = $auther->getSocialId();
        $user->name       = $auther->getName();
        $user->email      = $auther->getEmail();
        $user->sex        = $auther->getSex();
        $user->birthday   = $auther->getBirthday();

        if (isset($userFromDb) && $userFromDb != $user) {
            $idToUpdate = $record['id'];
            $birthday = date('Y-m-d', strtotime($user->birthday));

            mysql_query(
                "UPDATE `users` SET " .
                "`social_id` = '{$user->socialId}', `name` = '{$user->name}', `email` = '{$user->email}', " .               
                "`birthday` = '{$birthday}'" .
                "WHERE `id`='{$idToUpdate}'"
            );
        }

        $_SESSION['user'] = $user;
    }

  
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <title></title>
</head>
<body>

<?php
if (isset($_SESSION['user'])) {

	   header('Location: /profile/social_auth');
    echo '<p><a href="info.php">Скрытый контент</a></p>';
} else if (!isset($_GET['code']) && !isset($_SESSION['user'])) {
    foreach ($adapters as $title => $adapter) {
        echo '<p><a href="' . $adapter->getAuthUrl() . '">Аутентификация через ' . ucfirst($title) . '</a></p>';
    }
}
?>

</body>
</html>