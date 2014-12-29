<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class callProgram extends CI_Controller {

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
            print_r($_REQUEST);
            print_r($event_json);
            $contents=$content.ob_get_clean();
            $this->load->model('email_model');
            $email=array("support"=>"support@rankalytics.com");
            $subject="Webhook response";
            
            $email_result = $this->email_model->send($email,$subject,$contents);

        }
        public function create_hook(){
            error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
            $this->load->library('Paymil_lib',"Paymil_lib");
           // $hookResponse=$this->Paymil_lib->new_webhook(base_url()."callProgram",array('subscription.created'));
           // print_r($hookResponse);
            
        }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */