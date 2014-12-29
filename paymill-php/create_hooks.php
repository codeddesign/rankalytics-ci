<?php 
//print_r($_REQUEST);
error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
require './paymill-php/autoload.php'; //Including Paymill
$apiKey="89c134d9ba995c215a0b0bd01f0e267a";
$request = new Paymill\Request($apiKey);
/*$payment = new Paymill\Models\Request\Payment();
$payment->setToken($_REQUEST['token']); */
try{

//$response = $request->getAll($webhook);
    $webhook = new Paymill\Models\Request\Webhook();
 /*$webhook->setUrl("http://rankalytics.com/webHooks/transactions")
                ->setEventTypes(array(
                    'transaction.succeeded',
                    'transaction.failed',
                ));*/
    /*$webhook->setUrl("http://rankalytics.com/webHooks/subscriptions")
                ->setEventTypes(array(
                    'subscription.succeeded',
                    'subscription.failed',
                    'subscription.updated'
                ));*/
/*    $webhook->setUrl("http://rankalytics.com/webHooks/refunds")
                ->setEventTypes(array(
                    'refund.succeeded',
                    'refund.failed'
                    
                ));*/
    $webhook->setUrl("http://rankalytics.com/webHooks/subscriptions")
                ->setEventTypes(array(
                    'subscription.deleted'
                ));
//     subscription created - subscription updated - subscription deleted - subscription failed
        $response = $request->create($webhook);
        
        print_r($response);
        
        
    }catch(PaymillException $e){
        //Do something with the error informations below
        $e->getResponseCode();
        $e->getStatusCode();
        $e->getErrorMessage();
    }


?>
