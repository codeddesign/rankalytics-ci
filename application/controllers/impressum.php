<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Impressum extends CI_Controller {

	function index() {           #2
        $this->load->view('home/impressum.php');
    }
}
?>