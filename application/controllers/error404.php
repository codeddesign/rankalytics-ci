<?php 
class error404 extends CI_Controller 
{
    public function __construct() 
    {
        parent::__construct(); 
    } 

    public function index() 
    { 
        // load language file:
        $this->lang->load('pagenotfound');
        $this->output->set_status_header('404'); 
        $data['content'] = 'error404'; // View name 
        $this->load->view('error404',$data);//loading in my template 
    } 
} 
?>