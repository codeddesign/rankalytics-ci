<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends CI_Controller {

	function index() {           #2
        // load language file:
        $this->lang->load('about');
        $this->load->view('home/about.php');
    }
}
?>