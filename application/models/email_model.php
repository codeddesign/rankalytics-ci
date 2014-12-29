<?php

class Email_Model extends CI_Model {
	
        var $_defaultConfigValues ;
        var $mail;

	function __construct() 
        {
            $this->load->library('mymailer');
            $this->config->load('email');
            $email = $this->config->item('email');
            $this->mail= new PHPMailer();
            $this->mail->IsSMTP(); // telling the class to use SMTP
            $this->mail->Timeout 		 = $email['Timeout'];
            $this->mail->SMTPDebug  = $email['SMTPDebug'];                     // enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
            $this->mail->SMTPSecure = $email['SMTPSecure'];
            $this->mail->SMTPAuth   = $email['SMTPAuth'];                  // enable SMTP authentication
            $this->mail->Host       = $email['Host']; // sets the SMTP server
            $this->mail->Port       = $email['Port'];                    // set the SMTP port for the GMAIL server
            $this->mail->Username   = $email['Username']; // SMTP account username
            $this->mail->Password   = $email['Password'];        // SMTP account password
            $this->mail->CharSet    = $email['charset']; 
            $this->mail->SetFrom('support@rankalytics.com', 'Support');
            $this->mail->AddReplyTo("support@rankalytics.com","Support");
            //$this->mail->Subject    = "Email Address verification on rankalytics.com";
            
	}
	public function send($email_arr,$subject,$contents,$attachments = "",$custom_config=array()) 
        {
            
            if(empty($email_arr) || !is_array($email_arr)){
                return array("error"=>1,"msg"=>"Email Address not provided");
            }
            if(!empty($custom_config))foreach($custom_config as $key => $value){ // this sets custom config value those are different than the default 
                if($key!="SetFrom" || $key!="AddReplyTo" ){
                    $this->mail->$key($value);
                }else{
                    $this->mail->$key=$value;
                }
            }
            $this->mail->Subject    = $subject;
            $this->mail->MsgHTML($contents);
            foreach($email_arr as $name=>$email){
                $this->mail->AddAddress($email, $name);
            }
           if($attachments)
           $this->mail->AddAttachment($attachments);

            if(!$this->mail->Send()) {
              return array("error"=>1,"msg"=>$this->mail->ErrorInfo);
            }else{
              return array("error"=>0,"msg"=>"Mail Sent");
            }
	}
        
        /*public function update($data,$where,$limit=0){
            $this->db->where($where);
            if($limit!=0){
                $this->db->limit($limit);
            }
            return $this->db->update($this->_tablename, $data); 
        }*/
        
      
}
