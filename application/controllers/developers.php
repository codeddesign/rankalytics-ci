<?php //if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Developers extends CI_Controller {

	public function index() {           #2
        // load language file:
        $this->lang->load('developershome');
        $this->load->view('developers/index.php');
    }
    
    public function rankalytics_api() {           #2
        // load language file:
        $this->lang->load('developersrankapi');
        $this->load->view('developers/rankalytics-api.php');
    }
    
    public function ranktracker_api() {           #2
        // load language file:
        $this->lang->load('developerstrackapi');
        $this->load->view('developers/ranktracker-api.php');
    }
    
    public function seocrawl_api() {           #2
        // load language file:
        $this->lang->load('developerscrawl');
        $this->load->view('developers/seocrawl-api.php');
    }
}
?>