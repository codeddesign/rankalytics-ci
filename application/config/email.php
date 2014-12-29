<?php
/*$mail->Timeout 		 = 60;
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
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
        */
$config['email']['Timeout']=60;
$config['email']['SMTPSecure']='ssl';
$config['email']['SMTPAuth']=TRUE;
$config['email']['Host']='smtp.zoho.com';
$config['email']['SMTPDebug']=0;
$config['email']['Port']=465;
$config['email']['charset'] = 'utf-8';
$config['email']['Username']="support@rankalytics.com";
$config['email']['Password']="My6Celeb";
$config['email']['SetFrom']="support@rankalytics.com";
$config['email']['AddReplyTo']="support@rankalytics.com";

?>
