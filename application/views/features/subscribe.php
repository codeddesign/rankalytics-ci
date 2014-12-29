<?php
  $apiKey         = '8ed1740c591362f7535151377d0026b4-us8'; // Edit me
  $listId         = 'd7e9431a46'; // Edit me
  $double_optin   = true;
  $send_welcome   = true;
  $email_type     = 'html';
  $email          = $_POST['email'];
  $fname          = $_POST['fname'];
  $lname          = $_POST['lname'];

  // Replace us8 with your datacentre, usually found at end of api key
  $submit_url     = "http://us8.api.mailchimp.com/1.3/?method=listSubscribe";

  $data = array(
      'email_address'=>$email,
      'merge_vars' => array('FNAME'=>$fname, 'LNAME'=>$lname),
      'apikey'=>$apiKey,
      'id' => $listId,
      'double_optin' => $double_optin,
      'send_welcome' => $send_welcome,
      'email_type' => $email_type
  );
  $payload = json_encode($data);
   
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $submit_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, urlencode($payload));
   
  $result = curl_exec($ch);
  curl_close ($ch);
  $data = json_decode($result);

    echo "<p>Thanks, a confirmation e-mail is on its way!</p>";
?>