<?php

class Paymill_Model extends CI_Model {

	function __construct() 
        {
        }

        public function new_subscription($userId,$offerId="offer_f9ed701131fde5441a22"){
            $this->load->library("paymill");
            //$offers = $this->paymill->get_list_offer();
            //$offerId = $offers['0']['id'];
            //$offerId  = 'offer_b62306b4a11cdca80a06';
            
            

            $users_arr=$this->users->getUserById($userId);
            $user = $users_arr['0'];

            if(trim($user['paymillClientId'])=='' || $user['paymillClientId']==null) {
                $email = $user['emailAddress'];
                $client_description = "Name:{$user['firstName']} {$user['lastName']} ";
                $client = $this->paymill->new_client($email,$client_description);
                if(isset($client['error'])){
                    return array("error"=>1,"msg"=>$client['error']);
                }
                $user_info['paymillClientId'] = $client['id'];
                $condition=array("id"=>$user['id']);
                $result = $this->users->updateTable($user_info,$condition, $limit=1);
                $paymillClientId = $user_info['paymillClientId'];
            }else{
                $paymillClientId = $user['paymillClientId'];
            }
            $response = $this->paymill->new_payment_credit_card($this->input->post('token'),$paymillClientId);
            if(isset($response['error'])){
                return array("error"=>1,"msg"=>$response['error']);
            }
            $paymentId=$response['id'];
            $subscription_result = $this->paymill->new_subscription($paymillClientId, $offerId, $paymentId);
            //print_r($subscription_result );
            $this->load->model('paymilltransactions_model','paymilltransactions');
            
            
            //print_r($subscription_result);
            if(isset($subscription_result['error']) ){
                
                if($subscription_result['error']=="Subscription already connected"){
                    $old_subscription = $this->paymilltransactions->getSubscriptionByUserid($userId);
                    $id=$old_subscription['0']['subscription_id'];
                    $subscription_result = $this->paymill->update_subscription($id, $offerId, $paymentId,false);
                    if(isset($subscription_result['error'])){
                        $error=1;
                        $error_msg[]=$subscription_result['error'];
                        return array('error'=>$error,'msg'=>$error_msg);
                        
                    }
                }else{
                    $error=1;
                    $error_msg[]=$subscription_result['error'];
                    return array('error'=>$error,'msg'=>$error_msg);

                }
                
                //$subscription_result = $this->paymill->update_subscription($id, $offerId, $paymentId,false);
                    //$response = $request->update($subscription);

            }
            
                $subscription_result['user_id']=$userId;

                

                $this->paymilltransactions->saveSubscription($subscription_result);
                $this->users->setAsPro($userId,$offerId);
                $error=0;
                $error_msg[]='Subscription Saved.';
                return array('error'=>$error,'msg'=>$error_msg);
            

        }
      
      
}