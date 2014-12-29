<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Investors extends CI_Controller {

	function index() {           #2
        $this->load->view('home/investors.php');
    }
}
?>