<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Updates extends CI_Controller {

	public function index() {           
        $this->load->view('features/updates.php');
    }
}
?>