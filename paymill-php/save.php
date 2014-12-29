<?php 
print_r($_REQUEST);
require './paymill-php/autoload.php'; //Including Paymill
$apiKey="89c134d9ba995c215a0b0bd01f0e267a";
$request = new Paymill\Request($apiKey);
$payment = new Paymill\Models\Request\Payment();
$payment->setToken($_REQUEST['token']); 
try{
        $response  = $request->create($payment);
		print_r($response);
        $paymentId = $response->getId();
		echo "payment id".$paymentId;
    }catch(PaymillException $e){
        //Do something with the error informations below
        $e->getResponseCode();
        $e->getStatusCode();
        $e->getErrorMessage();
    }

?>