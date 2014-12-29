<?php 
//print_r($_REQUEST);
error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
require './paymill-php/autoload.php'; //Including Paymill
$apiKey="89c134d9ba995c215a0b0bd01f0e267a";
$request = new Paymill\Request($apiKey);
/*$payment = new Paymill\Models\Request\Payment();
$payment->setToken($_REQUEST['token']); */
try{
$webhook = new Paymill\Models\Request\Webhook();
        $webhook->setUrl("http://rankalytics.com/callProgram")
                ->setEventTypes(array(
                    'transaction.succeeded',
                    'subscription.created'
                ));

        /*array(
                    'transaction.succeeded',
                    'subscription.created'
                )
         * *
         */
        $response = $request->create($webhook);
        print_r($response);        
        
    }catch(PaymillException $e){
        //Do something with the error informations below
        $e->getResponseCode();
        $e->getStatusCode();
        $e->getErrorMessage();
    }

?>