<?php 
//print_r($_REQUEST);
error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
require './paymill-php/autoload.php'; //Including Paymill

$apiKey="89c134d9ba995c215a0b0bd01f0e267a";
$request = new Paymill\Request($apiKey);
/*$payment = new Paymill\Models\Request\Payment();
$payment->setToken($_REQUEST['token']); */
try{
    $subscription = new Paymill\Models\Request\Subscription();
$subscription->setClient('client_d517b3492522a60324df')
             ->setOffer('offer_6880bd3fb0950d353a38')
             ->setPayment('pay_4009f4ef85b546482fa1dd00');

$response = $request->create($subscription);   
    //$response  = $request->create($payment);
		print_r($response);
      //  $paymentId = $response->getId();
	//	echo "payment id".$paymentId;
    }catch(PaymillException $e){
        //Do something with the error informations below
        $e->getResponseCode();
        $e->getStatusCode();
        $e->getErrorMessage();
    }

?>