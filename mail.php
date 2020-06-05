<?php

require_once 'PHPMailer/PHPMailer.php';
require_once 'PHPMailer/SMTP.php';
require_once 'PHPMailer/Exception.php';

require_once 'pdo.php';

function send_message($user_mail, $msg)
{
	$mail = new PHPMailer\PHPMailer\PHPMailer();
	try 
	{
		$mail->isSMTP();
		$mail->CharSet = "UTF-8";
		$mail->SMTPAuth   = true;
		$mail->Host       = 'ssl://smtp.mail.ru';
		$mail->Username   = 'juicyloops@mail.ru';
		$mail->Password   = 'mailer4web';
		$mail->SMTPSecure = 'ssl';
		$mail->Port       = 465;
		$mail->setFrom('juicyloops@mail.ru', 'Hearty D');
		$mail->addAddress($user_mail);
		$mail->isHTML(true);
		$mail->Subject = "Juicy Loops last 10 comments";
		$mail->Body = $msg;
		if ($mail->send()) 
		{
			return 1;
		}
	}
	catch (Exception $e) 
	{
		return 0;
	}
}

function send_mail($db, $email, $user_name) 
{
	$comments = Get10Comments($db);
  	$msg = '<b>Dear ' . $user_name . '!</b><br>Welcome back to JuicyLoops.<br>You are proposed to take a look at the last 10 comments today!<br><br>';
  	foreach ($comments as $comment) 
  	{
    	$msg .= '&#9675; <a href="juicyloops/profile.php?account_id=' . $comment['author_id'] . '"> ' . $comment['author_name'] . '</a> commented on <a href="juicyloops/loop_page.php?loop_id=' . $comment['sample_id'] . '"> ' . $comment['sample_name'] . '</a> by <a href="juicyloops/profile.php?account_id=' . $comment['musician_id'] . '"> ' . $comment['musician_name'] . '</a> : &quot;' . $comment['text'] . '&quot;<br>';
  	}
  	$msg .= '<b>Sincerly yours, Hearty D</b>';
  	send_message($email, $msg);
}
