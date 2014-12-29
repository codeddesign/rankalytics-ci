<?php
class Modules extends CI_Controller {
    function index() {
        $this->load->library('session');

        // load language file:
        $this->lang->load('modules');

        $data['current'] = 'modules';
        $data['meta_title'] = 'Modules';
        $this->load->view('dashboard/modules', $data);
    }
}
?>