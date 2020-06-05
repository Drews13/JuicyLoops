<?php

require_once 'vendor/autoload.php';
require_once 'pdo.php';

session_start();

$loader = new Twig_Loader_Filesystem('Templates');
$twig = new Twig_Environment($loader, array(
    'cache'       => 'compilation_cache',
    'auto_reload' => true
));



if (isset($_POST['password1']) && isset($_POST['password2']) && isset($_POST['nickname']) && isset($_POST['email']) && isset($_FILES['avatar'])) 
{
	if (preg_match('/^[A-Za-z0-9].*[A-Za-z0-9]@[A-Za-z0-9]*(\.[A-Za-z0-9]+)*\.[A-Za-z]{2,}$/', $_POST['email']))
	{
		if ($_POST['password1'] == $_POST['password2'])
		{	

    		if (CheckForUser($db, $_POST['email'])) 
    		{
    			if ($_FILES['avatar']['error'])
				{
					switch ($_FILES['avatar']['error']) 
					{
						case 1:
						$err = "Ошибка: Размер файла больше допустимого(300МБ)";
						break;

						case 3:
						$err = "Ошибка: Загружена только часть файла";
						break;

						case 4:
						$err = "Ошибка: Файл не был загружен";
						break;

						case 6:
						$err = "Ошибка: Загрузка невозможна: не задан временный каталог";
						break;

						case 7:
						$err = "Ошибка: Загрузка не выполнена: невозможна запись на диск";
						break;
					}
					$twig->addGlobal('err', $err);
					echo $twig->render('register.html');
					exit();
				}
				if ($_FILES['avatar']['type'] != 'image/jpeg')
				{
					$err = "Формат файла должен быть JPG";
					$twig->addGlobal('err', $err);
					echo $twig->render('register.html');
					exit();
				}
				RegisterUser($db, $_POST['nickname'], $_POST['email'], $_POST['password1']);
				$_SESSION['user_id'] = GetUserID($db, $_POST['email']);
				$dir = "avs/" . $_SESSION['user_id'] . ".jpg";
				move_uploaded_file($_FILES['avatar']['tmp_name'], $dir);
      			header('Location: http://'.$_SERVER['HTTP_HOST'].'/profile.php?account_id='.$_SESSION['user_id']);
      			return;
    		}
    		else 
    		{
			    $err = 'User is already registered';
		    	$twig->addGlobal('err', $err);
				echo $twig->render('register.html');
				exit();
	    	}
		}
		else
		{ 
			$err = 'Passwords do not match';
			$twig->addGlobal('err', $err);
			echo $twig->render('register.html');
			exit();
		}
	}
	else
	{
		$err = 'Email Adress is invalid';
		$twig->addGlobal('err', $err);
		echo $twig->render('register.html');
		exit();
	}
}
$twig->addGlobal('user_id', $_SESSION['user_id']);
$twig->addGlobal('user_name', GetUserName($db, $_SESSION['user_id']));
echo $twig->render('register.html');