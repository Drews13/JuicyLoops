<?php

require_once 'vendor/autoload.php';
require_once 'pdo.php';

session_start();

$loader = new Twig_Loader_Filesystem('Templates');
$twig = new Twig_Environment($loader, array(
    'cache'       => 'compilation_cache',
    'auto_reload' => true
));

if (isset($_FILES['loop']))
{
	if ($_FILES['loop']['error'])
	{
		switch ($_FILES['loop']['error']) 
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
		echo $twig->render('upload.html');
		exit();
	}

	if ($_FILES['loop']['type'] != 'audio/wav')
	{
		$err = "Формат файла должен быть WAV";
		$twig->addGlobal('err', $err);
		echo $twig->render('upload.html');
		exit();
	}
	$num = LoadLoopChar($db, $_POST['name'], $_POST['description'], $_POST['category'], $_POST['bpm'], $_POST['keynote'], date("Y-m-d H:i:s"), $_SESSION['user_id']);
	$dir = "loops/" . $num . ".wav";
	move_uploaded_file($_FILES['loop']['tmp_name'], $dir);
}
$twig->addGlobal('err', $err);
$twig->addGlobal('user_id', $_SESSION['user_id']);
$twig->addGlobal('user_name', GetUserName($db, $_SESSION['user_id']));

echo $twig->render('upload.html');