<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class webHooks extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
            $content = "Here is the response";
            $body = @file_get_contents('php://input');
            $event_json = json_decode($body, true);
            ob_start();
            //print_r($_REQUEST);
            print_r($event_json);
            $contents=$content.ob_get_clean();
            $this->load->model('email_model');
            $email=array("support"=>"support@rankalytics.com");
            $subject="Webhook response";
            $email_result = $this->email_model->send($email,$subject,$contents);
            
        }
        function refunds(){
            $body = @file_get_contents('php://input');
            $event_json = json_decode($body, true);
            ob_start();
            //print_r($_REQUEST);
            echo "<pre>";
            print_r($event_json);
            $refund['refund_type'] = $event_json['event']['event_type'];
            $event_resource =$event_json['event']['event_resource'];
            $refund['refund_id'] = $event_resource['id'];
            $refund['amount'] = $event_resource['amount'];
            $refund['status'] = $event_resource['status'];
            $refund['description'] = $event_resource['description'];
            $refund['created_at'] = $event_resource['created_at'];
            $refund['updated_at'] = $event_resource['updated_at'];
            $refund['response_code'] = $event_resource['response_code'];
            $refund['transaction_id'] = $event_resource['transaction']['id'];
            $refund['client_id'] = $event_resource['transaction']['client']['id'];
            print_r($refund);
            $contents=$content.ob_get_clean();
            $this->load->model('email_model');
            $email=array("support"=>"support@rankalytics.com");
            $subject="Webhook response refund";
            $email_result = $this->email_model->send($email,$subject,$contents);
            
                        $email=array("support"=>$event_resource['transaction']['client']['email']);
                        if($refund['refund_type']=='refund.succeeded'){
                            $subject="Refund Succeeded";
                        }
                        if($refund['refund_type']=='refund.failed'){
                            $subject="Refunded Failed";
                        }
                        $contents = $this->load->view("general_email/refund",array(),TRUE);
                        $email_result = $this->email_model->send($email,$subject,$contents);
                        /*$refund['refund_type'] = 'refund.succeeded';
    $refund['refund_id'] = 'refund_64d9f568c6cd12bd5b39';
    $refund['amount'] = '3500';
    $refund['status'] = 'refunded';
    $refund['description'] = 'test refund OK 4';
    $refund['created_at'] = '1398161722';
    $refund['updated_at'] = '1398161722';
    $refund['response_code'] = '20000';
    $refund['transaction_id'] = 'tran_3bd278a351710e1475951a2a17be';*/
                        $this->load->model("webhook_refunds_model","webhook_refunds",true);
                        $this->webhook_refunds->save($refund);
                        
                                    // saving in user_account_logs
            $this->load->model("user_account_logs_model","user_account_logs");
            $user_account_logs['action_taken']=$refund['refund_type'];
            $user_account_logs['action_by']="webhook";
            $user_account_logs['description']="Refund succeded/Failed";
            $this->load_model("users_model","usres",true);
            $user=$this->users->getUserWhere(array("paymillClientId"=>$refund['client_id'])); // need to track if the user doent come up from the database
            $user_account_logs['user_id']="{$user['0']['id']}";
            $this->user_account_logs->save($user_account_logs);
        }
        function subscriptions(){ // This function will execute automatically when any subscription scceeded,failed,updated
            $body = @file_get_contents('php://input');
            $event_json = json_decode($body, true);
            
            $subscription['subscription_type'] = $event_json['event']['event_type'];
            $event_resource =$event_json['event']['event_resource'];
            $subscription['subscription_id'] = $event_resource['subscription']['id'];
            $subscription['offer_id'] = $event_resource['subscription']['offer']['id'];
            $subscription['next_capture_at'] = $event_resource['subscription']['next_capture_at'];
            $subscription['created_at'] = $event_resource['subscription']['created_at'];
            $subscription['updated_at'] = $event_resource['subscription']['updated_at'];
            $subscription['canceled_at'] = $event_resource['subscription']['canceled_at'];
            $subscription['payment_id'] = $event_resource['subscription']['payment']['id'];
            $subscription['client_id'] = $event_resource['subscription']['client']['id'];
            $subscription['transaction_id']=$event_resource['transaction']['id'];
            
            $this->load->model("webhook_subscriptions_model","webhook_subscriptions",true);
            
            $this->webhook_subscriptions->save($subscription);
            $this->load->model('email_model');
            if(trim($subscription['subscription_type'])=='subscription.updated'){
                        $email=array("support"=>$event_resource['subscription']['client']['email']);
                        $subject="Pro account susbscription updated";
                        $contents = $this->load->view("general_email/subscription_update",array(),TRUE);//"Contact request message ";
                        $email_result = $this->email_model->send($email,$subject,$contents);
            }
            $this->load->model("paymilltransactions_model","paymilltransactions",true);
            $paymilltransactions['next_capture_at']=$subscription['next_capture_at'];
            $paymilltransactions['updated_at']=$subscription['updated_at'];
            $paymilltransactions['canceled_at']=$subscription['canceled_at'];
            $cond['subscription_id']=$subscription['subscription_id'];
            
            $this->paymilltransactions->updateTable($paymilltransactions,$cond);
            
            // saving in user_account_logs
            $this->load->model("user_account_logs_model","user_account_logs");
            $user_account_logs['action_taken']=$subscription['subscription_type'];
            $user_account_logs['action_by']="webhook";
            $user_account_logs['description']="Subscription Created/Updated";
            $this->load_model("users_model","usres",true);
            $user=$this->users->getUserWhere(array("paymillClientId"=>$subscription['client_id'])); // need to track if the user doent come up from the database
            $user_account_logs['user_id']="{$user['0']['id']}";
            $this->user_account_logs->save($user_account_logs);
        }

        public function transactions(){ // this function will execute automatically when any transaction succeeded or failed
            //$content = "Here is the response";
            $body = @file_get_contents('php://input');
            $event_json = json_decode($body, true);
            
            /*$this->load->model('email_model');
            $email=array("support"=>"sudhirpur123@gmail.com");
            $subject="Webhook response";
            
            $email_result = $this->email_model->send($email,$subject,$contents);*/
             //ob_start();
            $transaction_resource = $event_json['event']['event_resource'];
            $transaction['transaction_type']=$event_json['event']['event_type'];
            $transaction['transaction_id'] = $transaction_resource['id'];
            $transaction['amount']= $transaction_resource['amount'];
            $transaction['origin_amount']= $transaction_resource['origin_amount'];
            $transaction['status']= $transaction_resource['status'];
            $transaction['description']= $transaction_resource['description'];
            $sub_arr1 = explode("Subscription#",$transaction_resource['description']);
            $sub_arr2 = explode(" " ,$sub_arr1['1']);
            $transaction['subscription_id']= $sub_arr2['0'];
            $transaction['created_at']= $transaction_resource['created_at'];
            $transaction['updated_at']= $transaction_resource['updated_at'];
            $transaction['response_code']= $transaction_resource['response_code'];
            $transaction['short_id']= $transaction_resource['short_id'];
            $transaction['is_fraud']= $transaction_resource['is_fraud'];
            $transaction['payment_id']= $transaction_resource['payment']['id'];
            $transaction['client_id']= $transaction_resource['client']['id'];
            
            
            $payment['payment_id'] = $transaction_resource['payment']['id'];
            $payment['type'] = $transaction_resource['payment']['type'];
            
            
            if(isset($transaction_resource['payment']['card_type']))
            $payment['cardType'] = $transaction_resource['payment']['card_type'];
            
            if(isset($transaction_resource['payment']['country']))
            $payment['country'] = $transaction_resource['payment']['country'];
            
            if(isset($transaction_resource['payment']['bin']))
            $payment['bin'] = $transaction_resource['payment']['bin'];
            
            if(isset($transaction_resource['payment']['card_holder']))
            $payment['cardHolder'] = $transaction_resource['payment']['card_holder'];
            
            if(isset($transaction_resource['payment']['code']))
            $payment['code'] = $transaction_resource['payment']['code'];
            
            if(isset($transaction_resource['payment']['holder']))
            $payment['accountHolder'] = $transaction_resource['payment']['holder'];
            
            if(isset($transaction_resource['payment']['account']))
            $payment['account'] = $transaction_resource['payment']['account'];
            
            if(isset($transaction_resource['payment']['iban']))
            $payment['iban'] = $transaction_resource['payment']['iban'];
            
            if(isset($transaction_resource['payment']['bic']))
            $payment['bic'] = $transaction_resource['payment']['bic'];
            
            $payment['client_id'] = $transaction_resource['payment']['client'];
            $payment['created_at'] = $transaction_resource['payment']['created_at'];
            $payment['updated_at'] = $transaction_resource['payment']['updated_at'];
            
            /*print_r($transaction);
            print_r($payment);
            $contents="other response ".ob_get_clean();
            $this->load->model('email_model','email_model2');
            $email=array("support"=>"sudhirpur123@gmail.com");
            $subject="Webhook response";
            
            $email_result = $this->email_model2->send($email,$subject,$contents);
            ob_start();*/
            /*$transaction['transaction_type'] = 'transaction.succeeded';
            $transaction['transaction_id'] = 'tran_9da4d44a7d3ec6977597a96e9358';
                    $transaction['amount'] = 3500 ;$transaction['origin_amount'] = 3500; 
                    $transaction['status'] = 'closed'; $transaction['description'] = 'Subscription#sub_e57e0145376d4b3d4d1c Rank Tracker Subscription ';
                    $transaction['subscription_id'] = 'sub_e57e0145376d4b3d4d1c';
                    $transaction['created_at'] = '1398075553'; $transaction['updated_at'] = '1398075553';
                    $transaction['response_code'] = '20000'; $transaction['short_id'] = '7357.7357.7357';
                    $transaction['is_fraud'] = '';
                    $transaction['payment_id'] = 'pay_c1e2b0d7f995e27d8b2715a0';*/
            try{
                $this->load->model("webhook_transactions_model","webhook_transaction",true);
            $this->webhook_transaction->save($transaction);
            $this->load->model("webhook_payments_model","webhook_payment");
            $this->webhook_payment->save($payment);
                        

            }catch(Exception $e){
               // print_r($e);
    }
            
            /*$contents="other response2 ".ob_get_clean();
            $this->load->model('email_model','email_model3');
            $email=array("support"=>"sudhirpur123@gmail.com");
            $subject="Webhook response33";
            $email_result = $this->email_model3->send($email,$subject,$contents);*/
            
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */