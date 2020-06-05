<?php

require_once 'vendor/autoload.php';
require_once 'pdo.php';

session_start();

$loader = new Twig_Loader_Filesystem('Templates');
$twig = new Twig_Environment($loader, array(
    'cache'       => 'compilation_cache',
    'auto_reload' => true
));


$twig->addGlobal('user_id', $_SESSION['user_id']);
$twig->addGlobal('user_name', GetUserName($db, $_SESSION['user_id']));
echo $twig->render('mysettings.html');