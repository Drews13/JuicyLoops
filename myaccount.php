<?php

require_once 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('Templates');
$twig = new Twig_Environment($loader, array(
    'cache'       => 'compilation_cache',
    'auto_reload' => true
));

echo $twig->render('myaccount.html');