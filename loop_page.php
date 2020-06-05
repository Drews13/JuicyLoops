<?php

require_once 'vendor/autoload.php';
require_once 'pdo.php';

session_start();

$loader = new Twig_Loader_Filesystem('Templates');
$twig = new Twig_Environment($loader, array(
    'cache'       => 'compilation_cache',
    'auto_reload' => true
));

LoadComment($db, $_POST['com_text'], $_SESSION['user_id'], $_GET['loop_id'], date("Y-m-d H:i:s"));

$loop = GetLoop($db, $_GET['loop_id']);
$comments = GetComments($db, $_GET['loop_id']);
$comments_amount = GetCommentsAmount($db, $_GET['loop_id']);

$twig->addGlobal('user_id', $_SESSION['user_id']);
$twig->addGlobal('user_name', GetUserName($db, $_SESSION['user_id']));

echo $twig->render('loop_page.html', array('loop' => $loop, 'comments' => $comments, 'comments_amount' => $comments_amount));
