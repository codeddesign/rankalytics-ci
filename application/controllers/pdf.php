<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Ranktracker
*
* The class used for managing Analytics
*
* 
* @author     bhaiyyalal
* @link       codeddesign.org
* @package    Analytics
*/

class Pdf extends CI_Controller
{
   public function __construct()
    {
        parent::__construct();
        

        $this->load->model('analytical_model','analytical',true);
        $this->load->library('session');
        $this->load->model('users_model','users',true);
        $this->load->model("project_keywords_model","project_keywords");
        $this->load->model('Project_Keywords_Adwordinfo_Model','project_keywords_adwordinfo');
        
    }
    
    
    function get_graph($domain,$start_date, $end_date){
        $domain_id= $domain;
        $date   =  date("Y-m-d" ,strtotime($start_date));       
       $end_date= date("Y-m-d" ,strtotime($end_date));
        $data['domain_id']=$domain_id;
        $data['date']=$date;
        $data['end_date']=$end_date;
        echo $this->load->view("dashboard/graph_report" ,$data);
    
    }
    
    public function rankings($project_name)
    {
        // Search form
        $project_name_raw = $project_name;
        $quicksearch = $this->input->post('quicksearch');
        $isAjax= $this->input->post('isAjax');
        if($quicksearch!='')
        {
            $like = array("keyword"=>$quicksearch);
            $this->data['quicksearch']=$quicksearch;
        }
        else
        {
            $like = array();
            $this->data['quicksearch']='';
        }

        // Pagination 
        $this->load->library('pagination');
        $this->config->load('paginationConfig');
        $paginationConfig = $this->config->item('paginationConfig');// Taking default config values for pagination
        $paginationConfig['base_url'] = base_url().'ranktracker/rankings/'.$project_name_raw."/";
        $paginationConfig['uri_segment'] = 4;
        $project_name = str_replace("-", "+",$project_name);
        $project_name = urldecode($project_name);
        $this->load->model('project_model','project',true);

        $total = $this->project->getProjectKeywordsCount($project_name,$like);
        $paginationConfig['total_rows'] = $total;
        $limit = array($this->uri->segment($paginationConfig['uri_segment']),$paginationConfig['per_page']);
        $this->data['keywords_array'] = $this->project->getProjectKeywords($project_name,$like,$limit);
        $this->pagination->initialize($paginationConfig);
        $this->data['domain'] = $this->project->getProjectDomain($project_name);
        $this->data['crawled_sites_model']=$this->load->model("crawled_sites_model");
        $this->data['meta_title'] = "RankAlytics SERP Tracking";
        $this->data['project_name_raw']  = $project_name_raw;

        if($isAjax==1)
        {
            $rank_data_html = $this->load->view('analytics/rank_data', $this->data,true);
            echo json_encode(array("error"=>0,"html"=>$rank_data_html,"pagination"=>$this->pagination->create_links()));
        }
        else
        {
           //$this->data['main_content'] = 'analytics/rank_graph';
             $this->data['main_content'] = 'analytics/rank_new_graph';
            // $this->data['main_content'] = 'analytics/graph/index';
            $this->load->view('include/template', $this->data); 
        }
    }
    
   public function users(){
        if(!$userId = $this->users->isloggedIn()){
            redirect("ranktracker");
            return false;
        }
        $userId = $this->users->isloggedIn();
        $user_array = $this->users->getUserById($userId);
        
        if("yes" != $user_array[0]['isPaid']){
            $this->load->view('dashboard/users',array("error"=>"You are not authorised to view this page","notpro"=>1));
            return;
        }else{
            $isAjax = $this->input->post("isAjax");
            $this->load->library('pagination');
            $this->config->load('paginationConfig');
            $paginationConfig = $this->config->item('paginationConfig');// Taking default config values for pagination
            $paginationConfig['base_url'] = base_url().'ranktracker/admin/';
            $paginationConfig['uri_segment'] = 3;
            $searchString = $this->input->post('searchString');
            $total = $this->users->getUsersCount($searchString);
            $paginationConfig['total_rows'] = $total;
            $startFrom = $this->uri->segment($paginationConfig['uri_segment']);
            if($startFrom ==''){
                $startFrom =0;
            }
            $paginationConfig['per_page']=10;
            $limit = array($startFrom ,$paginationConfig['per_page']);
            $this->data['users'] = $this->users->getUsers($searchString,$limit);
            $this->pagination->initialize($paginationConfig);
            if($isAjax==1)
            {
                $user_html = $this->load->view('dashboard/userlist', $this->data,true);
                echo json_encode(array("error"=>0,"html"=>$user_html,"pagination"=>$this->pagination->create_links()));
            }
            else
            {
               $this->load->view('dashboard/users', $this->data); 
            }
        }
        
        
        
        
    }
function     get_suggestion($json,$limit ,$key ){
    
    $data['key']=$key;
    $data['limit']=$limit;
    $data['json']=$json;
    echo $this->load->view('keyword-research/suggestion-list', $data);
            
}
    
    
}