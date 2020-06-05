<?php

require_once 'vendor/autoload.php';
require_once 'pdo.php';

session_start();

$loader = new Twig_Loader_Filesystem('Templates');
$twig = new Twig_Environment($loader, array(
    'cache'       => 'compilation_cache',
    'auto_reload' => true
));
$loops = GetAuthorLoops($db, $_GET['account_id']);
$twig->addGlobal('account_id',$_GET['account_id']);
$twig->addGlobal('user_id', $_SESSION['user_id']);
$twig->addGlobal('user_name', GetUserName($db, $_GET['account_id']));
echo $twig->render('profile.html', array('loops' => $loops));