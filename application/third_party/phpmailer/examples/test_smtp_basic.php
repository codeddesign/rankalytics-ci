<html>
<head>
<title>PHPMailer - SMTP basic test with authentication</title>
</head>
<body>

<?php

//error_reporting(E_ALL);
error_reporting(E_STRICT);

date_default_timezone_set('America/Toronto');

require_once('../class.phpmailer.php');
//include("class.smtp.php"); // optional, gets called from within class.phpmailer.php if not already loaded

$mail             = new PHPMailer();

$body             = file_get_contents('contents.html');
$body             = eregi_replace("[\]",'',$body);

$mail->IsSMTP(); // telling the class to use SMTP
$mail->Timeout 		 = 60;
//$mail->Host       = "mail.yourdomain.com"; // SMTP server
$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
$mail->SMTPSecure = 'ssl';
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->Host       = "smtp.zoho.com"; // sets the SMTP server
$mail->Port       = 465;                    // set the SMTP port for the GMAIL server
$mail->Username   = "support@rankalytics.com"; // SMTP account username
$mail->Password   = "My6Celeb";        // SMTP account password

$mail->SetFrom('support@rankalytics.com', 'First Last');

$mail->AddReplyTo("support@rankalytics.com","First Last");

$mail->Subject    = "PHPMailer Test Subject via smtp, basic with authentication";

$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

// additional
//$mail->SMTPDebug = true;// for debug true 
//$mail->Debugoutput = 'echo';
$mail->Timeout = 60;
// additional ends 
$mail->MsgHTML("this is test message.");

//$address = "whoto@otherdomain.com";
$mail->AddAddress("sudhirpur123@gmail.com", "John Doe");
/*
$mail->AddAttachment("images/phpmailer.gif");      // attachment
$mail->AddAttachment("images/phpmailer_mini.gif"); // attachment
*/
if(!$mail->Send()) {
  echo "Mailer Error: " . $mail->ErrorInfo;
} else {
  echo "Message sent!";
}

?>

</body>
</html>
