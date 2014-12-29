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
$webhook->setFilter(array(
    'count' => 10,
    'offset' => 0
));
        $response = $request->getAll($webhook);
        echo "<pre>";
        print_r($response);
        
        $id = $response['3']['id'];
        //$id="hook_e7e6b167fafade7bfa4b";
        if(isset($_GET['delete']) && $_GET['delete']==1){
            $webhook = new Paymill\Models\Request\Webhook();
            $webhook->setId($id);
            $response = $request->delete($webhook);
            print_r($response );
        }
        
    }catch(PaymillException $e){
        //Do something with the error informations below
        $e->getResponseCode();
        $e->getStatusCode();
        $e->getErrorMessage();
    }


?>
