<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Analytics
*
* The class used for managing Analytics
*
* 
* @author     Ananthakrishnan
* @link       codeddesign.org
* @package    Analytics
*/

class Dashboard extends CI_Controller
{
    
    /**
    * __construct()
    *
    * @desc constructor for Analytics
    *
    * @author Ananthakrishnan
    * @access public 
    *
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('users_model','users',true);
        // load language file:
        $this->lang->load('home');
    }
    /**
    * index()
    *
    * @desc index function for Analytics
    * call automatically when page loads
    * @author Ananthakrishnan
    * @access public 
    *
    */
    
    public function index()
    {
        
        $this->load->view('home/home');
    } 
    
}