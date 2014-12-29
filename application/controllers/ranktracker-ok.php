<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Ranktracker
*
* The class used for managing Analytics
*
* 
* @author     Ananthakrishnan
* @link       codeddesign.org
* @package    Analytics
*/

class Ranktracker extends CI_Controller
{
    
    /**
    * __construct()
    *
    * @desc constructor for Ranktracker
    *
    * @author Ananthakrishnan
    * @access public 
    *
    */
    public function __construct()
    {
        parent::__construct();
        

        $this->load->model('analytical_model','analytical',true);
        $this->load->library('session');
        $this->load->model('users_model','users',true);
        $this->load->model("project_keywords_model","project_keywords");
        $this->load->model('Project_Keywords_Adwordinfo_Model','project_keywords_adwordinfo');
        
    }
    
    /**
    * settings()
    *
    * @desc settings function for Ranktracker
    * calls on /ranktracker/settings
    * @author Sudhir
    * @access public 
    *
    */
    public function settings(){
        $user = $this->session->userdata('logged_in');
        if(!isset($user['0']['id']) || $user['0']['id']==0 ||  $user['0']['id']==''){ // redirect to rantracker if not logged in
            redirect('ranktracker');
            return;
        }
        $userid= $user['0']['id'];
        $user_array = $this->users->getUserById($userid);
        $data['user_database']= $user_array['0'];
        $data['user_database']['userType']=$this->users->getUserType($user_array['0']);
        if($data['user_database']['isPaid']=='yes'){
            $this->load->model('paymilltransactions_model','paymilltransactions',true);
            $where=array("user_id"=>$userid,"canceled_at"=>"");
            $subscription = $this->paymilltransactions->getSubscriptionByUserid($userid);
            if(isset($subscription['0']))
            $data['user_database']['subscription'] = $subscription['0'];
        }else{
            $data['user_database']['subscription']='';
        }
        $data['current']='dashboard';
        $data['meta_title']='Ranktracker Settings';
        $this->load->view('dashboard/settings',$data);
    }
    
    /**
    * index()
    *
    * @desc index function for Ranktracker
    * call automatically when page loads
    * @author Ananthakrishnan
    * @access public 
    *
    */
    
    public function index()
    {
        $user = $this->session->userdata('logged_in');
        if(isset($user['0']['id']) && $user['0']['id']>=1 ){ // redirect to dashboard if logged in
            redirect('ranktracker/settings');
            return;
        }
        $this->load->view('ranktracker/ranktracker'); 
        
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
        // Search form
        error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
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
        $projectId = $this->project->get_id_by_projectname($project_name);
        
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
                        $this->load->model('common_model');
                        
            
            $this->load->model("project_keyword_details_from_cron_model","project_keyword_details_from_cron");
            
            $data_arr = $this->project_keyword_details_from_cron->last_five_days_for_graph($projectId);
            //print_r($data_arr);
             $data_arr2=array(
             array( 'Keyword Rankings','Combined Estimated Trafic(ERT)','Combined Keyword Effectiveness Index (KEI)','Combined SEO Visibility','Date'),
array('150','20','400','.5','04/01/2014'),
array('101','21','300','.47','04/15/2014'),
array('145','18','401','.43','04/16/2014'),
array('143','11','423','.52','04/17/2014'),
array('113','16','390','.39','04/18/2014'),
array('147','9','398','.44','04/19/2014'),
array('101','21','310','.47','04/20/2014'),
array('121','18','401','.43','04/21/2014'),
array('150','20','400','.5','05/30/2014')
            );
            // print_r($data_arr2);
            /*date('Y-m-d', strtotime(' +1 day'));
            date('Y-m-d', strtotime(' +1 day'));
            date('Y-m-d', strtotime(' +1 day'));
            date('Y-m-d', strtotime(' +1 day'));
            date('Y-m-d', strtotime(' +1 day'));*/
            $filename = $this->common_model->createUniqueId().".csv";
            $filepath = BASEPATH."../uploads/temp/".$filename;
            $fileurl = base_url()."uploads/temp/".$filename;
            $csv = $this->common_model->array_to_csv($data_arr2,$filepath);
            
            $this->data['csvPath']=$filepath;
            $this->data['csv']=$fileurl;
            $this->data['common_model']=$this->common_model;
            $this->data['main_content'] = 'analytics/rank_graph';
            $this->load->view('include/template', $this->data); 
        }
    }

    
    public function dashboard()
    {
        if(!$userId = $this->users->isLoggedIn())
        {
            redirect("ranktracker");
            return false;
        }
        //error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
        $this->load->model('project_model','project',true);
        $this->data['meta_title'] = "RankAlytics SERP Tracking";
        $user=$this->users->getUserById($userId);
        $userRole = $user[0]['userRole'];
        //$this->data['project_data'] = $this->project->getProjectData($userId,$userRole );
        $this->data['project_data'] = $this->project->getProjectDataWithKeywords($userId,$userRole );
        $this->data['crawled_sites_model']=$this->load->model("crawled_sites_model");
        $this->data['project_keyword_details_from_cron_model']=$this->load->model("project_keyword_details_from_cron_model");
        
        $this->data['all_project_data'] = $this->data['project_data'] ; // this needs to be chanegd later with all projects when the result will be limited to 10 projects
        // Getting project keyword
        //$this->data['keywords_array'] = $this->project->getProjectKeywords($project_name,$like,$limit);
        $this->data['main_content'] = 'dashboard/dashboard';
        $this->load->view('include/template', $this->data); 
    }
    
        public function project_keyword_details_save(){
            
        $users = $this->users->get_active_users();
        $this->load->model("crawled_sites_model");
        $this->load->model("project_model","project");
        $this->load->model("project_keyword_details_from_cron_model","project_keyword_details");
        //echo "<pre>";
        //print_r($users);
        if(is_array($users)){
            foreach($users as $user){
                $project_data = $this->project->getProjectDataWithKeywords($user['id'],$user['userRole'] );
                
                $this->project_keyword_details->project_keyword_details_save($project_data);
            }// endfor $users
        }// end if is_array($users)
    }
    
    public function promembership(){
        if(!$userId = $this->users->isLoggedIn())
        {
            redirect("ranktracker");
            return false;
        }
        $this->data['meta_title'] = "RankAlytics Pro-Membership";
        $user=$this->users->getUserById($userId);
        $this->data['user'] = $user['0'];
        $this->data['main_content'] = 'ranktracker/promembership';
        $this->load->view('ranktracker/promembership', $this->data); 
    }
    
    public function contactus(){
        $this->load->view("contactus");
    }
    
    public function invoices()
    {
        
        if(!$userid = $this->users->isLoggedIn())
        {
            redirect("ranktracker");
            return false;
        }
        //error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
        //$userid= $user['0']['id'];
        $user_array = $this->users->getUserById($userid);
        $data['user_database']= $user_array['0'];
        if($data['user_database']['isPaid']=='yes'){
            $this->load->model('paymilltransactions_model','paymilltransactions',true);
            $where=array("user_id"=>$userid,"canceled_at"=>"");
            $subscription = $this->paymilltransactions->getSubscriptionByUserid($userid);
            $data['user_database']['subscription'] = $subscription['0'];
            //print_r($subscription['0']);
            //die();
            $client_id = $data['user_database']['subscription']['paymill_client_id'];
            $subscription_id = $data['user_database']['subscription']['subscription_id'];
            $offer_id = $data['user_database']['subscription']['offerId'];
            $this->load->model('webhook_payments_model','webhook_payments',true);
            $payments = $this->webhook_payments->getPaymentsByClientId($client_id);
            if(is_array($payments) && count($payments)>=1)
            {
                $count  = 0;
                foreach($payments as $payment){
                    $this->load->model('webhook_transactions_model','webhook_transactions',true);
                    $transactions = $this->webhook_transactions->getTransactionByPaymentId($payment['payment_id']);
                    $payment_arr[$count]['last4']=$payment['last4'];
                    $payment_arr[$count]['created_at']=$payment['created_at'];
                    $payment_arr[$count]['amount']=$transactions['0']['amount'];
                    $count++;
                }
            }
            if(isset($payment_arr)  && is_array($payment_arr) && !empty($payment_arr)){
                $data['payment_arr']=$payment_arr;
            }else{
                $data['payment_arr']=array();
            }
        }else{
            $data['user_database']['subscription']='';
            $data['payment_arr']=array();
        }
        $data['current']='dashboard';
        //$this->load->view('dashboard/settings',$data);
        $this->load->view("dashboard/invoices",$data);
    }
    public function downloadInvoicereport(){
        $user = $this->session->userdata('logged_in');
        if(!isset($user['0']['id']) || $user['0']['id']==0 ||  $user['0']['id']==''){ // redirect to rantracker if not logged in
            redirect('ranktracker');
            return;
        }
//        error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
        $userid= $user['0']['id'];
        $user_array = $this->users->getUserById($userid);
        $data['user_database']= $user_array['0'];
        if($data['user_database']['isPaid']=='yes'){
            $this->load->model('paymilltransactions_model','paymilltransactions',true);
            $where=array("user_id"=>$userid,"canceled_at"=>"");
            $subscription = $this->paymilltransactions->getSubscriptionByUserid($userid);
            $data['user_database']['subscription'] = $subscription['0'];
            $client_id = $data['user_database']['subscription']['paymill_client_id'];
            $subscription_id = $data['user_database']['subscription']['subscription_id'];
            $offer_id = $data['user_database']['subscription']['offerId'];
            $this->load->model('webhook_payments_model','webhook_payments',true);
            $payments = $this->webhook_payments->getPaymentsByClientId($client_id);
            if(is_array($payments) && count($payments)>=1)
            {
                $count  = 0;
                foreach($payments as $payment){
                    $this->load->model('webhook_transactions_model','webhook_transactions',true);
                    $transactions = $this->webhook_transactions->getTransactionByPaymentId($payment['payment_id']);
                    $payment_arr[$count]['last4']=$payment['last4'];
                    $payment_arr[$count]['created_at']=$payment['created_at'];
                    $payment_arr[$count]['amount']=$transactions['0']['amount'];
                    $count++;
                }
            }
            $data['payment_arr']=$payment_arr;
        }else{
            $data['user_database']['subscription']='';
            $data['payment_arr']=array();
        }
        $data['current']='dashboard';
        //$this->load->view('dashboard/settings',$data);
        $invoice = $this->load->view("dashboard/invoicereport_forPDF",$data,true);
        $this->load->helper(array("dompdf_helper","file"));
        $this->load->helper(array('dompdf', 'file'));
     // page info here, db calls, etc.     
        //$html = $this->load->view('controller/viewfile', $data, true);
        
        pdf_create($invoice, 'filename');
    
     
     /*$data = pdf_create($html, '', false);
     write_file('name', $data);
     //if you want to write it to disk and/or send it as an attachment    */
        
    }
    
    public function admin(){
        if(!$userId = $this->users->isloggedIn()){
            redirect("ranktracker");
            return false;
        }
        if(!$adminUser = $this->users->isAdmin()){
            $this->load->view('ranktracker/admin',array("error"=>"You are not authorised to vew this page","notAdmin"=>1));
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
                $user_html = $this->load->view('ranktracker/admin/userlist', $this->data,true);
                echo json_encode(array("error"=>0,"html"=>$user_html,"pagination"=>$this->pagination->create_links()));
            }
            else
            {
                //$this->data['main_content'] = 'ranktracker/admin';
                $this->load->view('ranktracker/admin', $this->data); 
            }
        }
        
        
        
        
    }
    
    public function delete_reports(){
        $id=$_POST['id'];
        $file=$_POST['file'];
        $this->db->query('delete from tbl_rt_report where id='.$id);
        $file1='http://rankalytics.com/csv/'.$file.'.csv';
        $file2='http://rankalytics.com/csv/'.$file.'.pdf';
        @unlink($file1);
        @unlink($file2);
        return 1;
    }

    public function reports()
    {   
        if(!$userId = $this->users->isLoggedIn()){
            redirect("ranktracker");
            return false;
        }
        
        $user_array = $this->users->getUserById($userId);
         if($user_array[0]['isPaid']=='yes'){
            $this->load->model('paymilltransactions_model','paymilltransactions',true);
            $where=array("user_id"=>$userId,"canceled_at"=>"");
            $subscription = $this->paymilltransactions->getSubscriptionByUserid($userId);
            ///if(isset($subscription['0']))
            $data['user_subscription']['subscription'] = $subscription['0'];
            
        }else{
            $data['user_subscription']['subscription']='';
        }
        
        $data['user_database']= $user_array;
        $data['current']='reports';
        $this->data['crawled_sites_model']=$this->load->model("crawled_sites_model");
        $this->data['report_model']=$this->load->model("report_model");       
        $this->load->view("dashboard/reports" ,$data);
    }
    
    public function contactussave(){
        $this->load->library("form_validation");
        $this->load->model("contactus_requests_model","contactus_request");
        $this->form_validation->set_rules($this->contactus_request->_validation_rules);
        $error=0;
        $error_msg=array();
        if(!$this->form_validation->run()){
            $error=1;
            if(form_error('fullName')!=''){
                $error_msg['fullName']=form_error('fullName');
            }
            if(form_error('emailAddress')!=''){
                $error_msg['emailAddress']=form_error('emailAddress');
            }
            if(form_error('message')!=''){
                $error_msg['message']=form_error('message');
            }
            echo json_encode(array("error"=>$error,"msg"=>$error_msg));
            return;
        }else{
            $this->load->model("common_model");
            $contactus_array['id']=$this->common_model->getNewId("contactus_requests");
            $contactus_array['fullName']=$this->input->post('fullName');
            $contactus_array['emailAddress']=$this->input->post('emailAddress');
            $contactus_array['message']=$this->input->post('message');
            $contactus_array['phoneNumber']=$this->input->post('phoneNumber');
            
            if(!$error = $this->contactus_request->save($contactus_array)){
                
                echo json_encode(array("error"=>$error,"msg"=>"Unable to save in database"));
                return;
            }
            
            $this->load->model('email_model');
            
            $email=array("support"=>$contactus_array['emailAddress']);
            $subject="Thank you for contact Request";
            //$this->load->view('users/validationEmail', $userData, TRUE);
            $contents = $this->load->view("general_email/contactus_thankyou",array(),TRUE);//"Contact request message ";
            //echo $contents;
            $email_result = $this->email_model->send($email,$subject,$contents);

            $email=array("support"=>"account@rankalytics.com");
            $subject="Contact Request ";
            //$contents = "Contact request message ";
            $info_array['info_array']=$contactus_array;
            $info_array['data']=array("fullName"=>"Full Name","emailAddress"=>"Email","phoneNumber"=>"Phone Number","message"=>"Message");
            
            $contents = $this->load->view("general_email/contactus_request",$info_array,TRUE);//"Contact request message ";
            //echo $contents;
            $email_result = $this->email_model->send($email,$subject,$contents);

            echo json_encode($email_result);
            
            
        }
    }
    
    
    /**
    * keywordsuggestions()
    * @desc settings get matching keywords from google
    * @link codeddesign.org
    * @author Ananthakrishnan
    * @access public 
    *
    */
    public function keywordsuggestions($keyword = 0)
    {
       $keywords = array();
       $master_keywords = array();
       $child_keywords = array();
       if($keyword)
       {
        $proxy_array = $this->analytical->getRandomProxy();
           
        $proxy_ip = $proxy_array[0]['ip'];
        $uname_password = $proxy_array[0]['username'].":".$proxy_array[0]['password'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
        curl_setopt($ch, CURLOPT_PROXY,$proxy_ip);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $uname_password);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);  
        //curl_setopt($ch, CURLOPT_URL, 
        //'http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en_US&q='.urlencode($keyword));
        curl_setopt($ch, CURLOPT_URL, 'http://suggestqueries.google.com/complete/search?q='.urlencode($keyword).'&client=firefox&hl=de');

        $data = curl_exec($ch);
        $data = (string)$data;
        $data = utf8_encode($data);
        //$data = iconv(mb_detect_encoding($data, mb_detect_order(), true), "utf8", $data);
        if (($data = json_decode($data, true)) !== null) 
        {
            $master_keywords = $data[1];
        }  

        $proxy_array =  shuffle($proxy_array);
        $count = 0;
        foreach ($master_keywords as $key => $value) 
        {
            $proxy_ip = $proxy_array[$count]['ip'];
            $uname_password = $proxy_array[$count]['username'].":".$proxy_array[0]['password'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
            curl_setopt($ch, CURLOPT_PROXY,$proxy_ip);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $uname_password);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);  
            curl_setopt($ch, CURLOPT_URL, 'http://suggestqueries.google.com/complete/search?q='.urlencode($value).'&client=firefox&hl=de');
            $data = curl_exec($ch);
            $data = (string)$data;
            $data = utf8_encode($data);
            if (($data = json_decode($data, true)) !== null) 
            {
                $child_keywords = $data[1];
            }
            $keywords[$key]=array("master_keyword" => $value, "child_keywords" =>$child_keywords);
            $count++;
        }
           $this->data['keyword'] = $keyword;
           $this->data['keyword_array'] = $keywords;
       }
       $this->data['keyword_array'] = $keywords;
       $this->data['meta_title'] = "RankAlytics SERP Tracking";
       $this->data['main_content'] = 'keyword-research/keyword-research';
       $this->load->view('include/template', $this->data); 
    }
    
    
    /**
    * competitoranalysis()
    * @desc competitor analysis
    * @link codeddesign.org
    * @author Ananthakrishnan
    * @access public 
    *
    */
    public function competitoranalysis($keyword =0)
    {
        $records_array = array();
        $top_ten_array = array();
        if($keyword)
        {
            if(isset($_POST['txt_keyword']) && $_POST['txt_keyword'])
            {
                $this->data['txt_keyword'] =$keyword;
                $records_array = $this->analytical->getKeywordByKeyword($_POST['txt_keyword']);
                $top_ten_array = $this->analytical->getTopTenCrawledSiteByKeyword($_POST['txt_keyword']);
            }
            else
            {
                $this->data['drop_keywordresearch'] = $keyword;
                //if(isset($_POST['drop_keywordresearch']) && $_POST['drop_keywordresearch'])
                //{
                   // $records_array = $this->analytical->getKeywordByKeyword($keyword);
                //}
                $top_ten_array = $this->analytical->getTopTenCrawledSiteByKeyword($keyword);
            }
            $this->data['keyword_array'] = $this->analytical->getKeywordByKeyword($keyword);
        }
        $records = $this->analytical->getKeywords(NULL, 5, "dropbox");
        $this->data['keywords'] = $records;
        $this->data['meta_title'] = "RankAlytics SERP Tracking";
        $this->data['top_ten_array'] = $top_ten_array;
        $this->data['main_content'] = 'competitor-analysis/competitor-analysis';
        $this->load->view('include/template', $this->data); 
    }
    
    /**
    * competitoranalysisJson()
    * @desc competitoranalysisJson for autocomplete
    * @link codeddesign.org
    * @author Ananthakrishnan
    * @access public 
    *
    */
    public function getKeywordsJson($category)
    {
        $result_array=array();
        $records = $this->analytical->getKeywords($category, 5);
        foreach ($records as $key => $value) 
        {
            $result_array[] = array(
            'label' => $value['keyword'],
            'id' => $value['unique_id']
            ); 
        }
        echo json_encode($result_array);
    }
  
     public function users(){
        if(!$userId = $this->users->isloggedIn()){
            redirect("ranktracker");
            return false;
        }
        $userId = $this->users->isloggedIn();
        $user_array = $this->users->getUserById($userId);
        
        if("yes" != $user_array[0]['isPaid']){
            $this->load->view('dashboard/users',array("error"=>"You are not authorised to vew this page","notpro"=>1));
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
}