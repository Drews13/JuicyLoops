<?php


require_once 'pdo.php';
require_once 'mail.php';

session_start();

if ($_POST['password'] == GetUserPassword($db,$_POST['email']))
{
	$_SESSION['user_id'] = GetUserID($db,$_POST['email']);
	$user_name = GetUserName($db, $_SESSION['user_id']);
	send_mail($db, $_POST['email'], $user_name);
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/profile.php?account_id='.$_SESSION['user_id']);
}
else
{
	header('Location: http://'.$_SERVER['HTTP_HOST'].'/myaccount.php');
}