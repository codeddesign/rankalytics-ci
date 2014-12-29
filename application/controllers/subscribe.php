<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Subscribe extends CI_Controller {

	public function index() {           
        $this->load->view('features/subscribe.php');
    }
}
?>