<?php

require_once 'vendor/autoload.php';

$loader = new Twig_Loader_Filesystem('Templates');
$twig = new Twig_Environment($loader, array(
    'cache'       => 'compilation_cache',
    'auto_reload' => true
));

$comments = array(array("author"=>"w84scaler","loop"=>"Num_15","musician"=>"Hearty D"),
	array("author"=>"prague15031939","loop"=>"Num_15","musician"=>"Hearty D"),
	array("author"=>"Hearty D","loop"=>"Dream_6","musician"=>"kaminari666"));

echo $twig->render('mainpage.html',array('comments'=>$comments));