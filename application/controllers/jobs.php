<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jobs extends CI_Controller {

	function index() {           #2
        // load language file:
        $this->lang->load('jobs');
        $this->load->view('home/jobs.php');
    }
}
?>