<?php

require_once 'vendor/autoload.php';
require_once 'pdo.php';

session_start();


$loader = new Twig_Loader_Filesystem('Templates');
$twig = new Twig_Environment($loader, array(
    'cache'       => 'compilation_cache',
    'auto_reload' => true
));

$info = Get10Loops($db, $_GET['page_id']);
$prevpage = $_GET['page_id'] - 1;
$currpage = $_GET['page_id'];
$nextpage = $_GET['page_id'] + 1;
$loopnum1 = ($_GET['page_id'] - 1) * 10 +1;
$loopnum2 = $loopnum1 + 9;

if ($_GET['search'] != '') 
{
	$loops = GetAllLoops($db);
	$search = array();
	foreach ($loops as $loop) 
	{
		if (strripos($loop['loopname'], $_GET['search']) !== FALSE) 
		{
			$search[] = $loop;
		}
	}
	$loops = $search;
}
else
{
	$loops = $info[2];
}

$twig->addGlobal('pages_amount', $info[1]); 
$twig->addGlobal('user_id', $_SESSION['user_id']);
$twig->addGlobal('user_name', GetUserName($db, $_SESSION['user_id']));

echo $twig->render('loops_samples.html', array('loops' => $loops, 'prevpage' => $prevpage, 'currpage' => $currpage, 'nextpage' => $nextpage, 'loopnum1' => $loopnum1, 'loopnum2' => $loopnum2));