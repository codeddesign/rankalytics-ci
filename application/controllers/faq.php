<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faq extends CI_Controller {

	function index() {           #2
        $this->load->view('home/faq.php');
    }
}
?>