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

class Analytics extends CI_Controller
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
        $this->load->model('analytical_model','analytical',true);
        
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
        echo "test";
    }
    
    /**
    * rankings()
    *
    * @desc Get all categories and subcategories for update job
    * @author Ananthakrishnan
    * @access public 
    * @return array
    */
    public function rankings($project_name)
    {
    	// load language file:
        $this->lang->load('rankgraph');
        $project_name = str_replace("-", "+",$project_name);
        $project_name = urldecode($project_name);
        $this->load->model('project_model','project',true);
        $this->data['keywords_array'] = $this->project->getProjectKeywords($project_name);
        $this->data['meta_title'] = "Rankalytics SERP Tracking";
        $this->data['main_content'] = 'analytics/rank_graph';
        $this->load->view('include/template', $this->data); 
    }
    public function dashboard()
    {
    	// load language file:
        $this->lang->load('rankdash');
        $this->load->model('project_model','project',true);
        $this->data['meta_title'] = "Rankalytics SERP Tracking";
        $this->data['project_data'] = $this->project->getProjectData();
        $this->data['main_content'] = 'dashboard/dashboard';
        $this->load->view('include/template', $this->data); 
    }
    public function keywords_analytics()
    {
    	// load language file:
        $this->lang->load('keywordsanalytics');
    	$this->load->model('project_model','project',true);
    	$this->data['keywords_array'] = $this->project->getallKeywords();
    	$this->data['meta_title'] = "Rankalytics SERP Tracking";
    	$this->data['main_content'] = 'analytics/keywords_analytics';
    	$this->load->view('include/template', $this->data);
    }
}