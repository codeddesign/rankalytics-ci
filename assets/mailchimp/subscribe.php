<?php	
	require_once 'inc/MCAPI.class.php';
	$api = new MCAPI('8ed1740c591362f7535151377d0026b4-us8');	
	$merge_vars = array();
	
	// Submit subscriber data to MailChimp
	// For parameters doc, refer to: http://apidocs.mailchimp.com/api/1.3/listsubscribe.func.php
	$retval = $api->listSubscribe( 'd7e9431a46', $_POST["email"], $merge_vars, 'html', false, true );
	
	if ($api->errorCode){
		echo "<h4>Something went wrong, please try again.</h4>";
	} else {
		echo "<h4>Thank you, you have just been added to our Beta waitlist!<br>You will be notified once your spot becomes available.</h4>";
	}
?>
