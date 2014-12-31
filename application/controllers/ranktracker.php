<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

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
        Subscriptions_Lib::loadConfig();

        $this->load->model('analytical_model', 'analytical', true);
        $this->load->library('session');
        $this->load->library('email');
        $this->load->model('users_model', 'users', true);
        $this->load->model("project_keywords_model", "project_keywords");
        $this->load->model('Project_Keywords_Adwordinfo_Model', 'project_keywords_adwordinfo');
        $this->load->model('subscriptions_model', 'subscriptions');

        $this->pgsql = $this->load->database('pgsql', true);
        $this->lang->load('ranktracker');
        $this->lang->load('rankdash');
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
    public function rankings($username, $project_name, $keyword = null)
    {
        if (!$userId = $this->users->isLoggedIn()) {
            redirect("ranktracker");
            return false;
        }

        error_reporting(E_STRICT | E_ALL);
        ini_set('display_errors', 1);
        $this->load->model('common_model');
        $lastDate = $this->common_model->getFileLastModifiedDate($fileName = 'assets/screen/temp.png');
        //echo "last time is ".$lastDate;
        $today = date("Y-m-d");
        if ($today != $lastDate || 1 == 1) {
            $this->data['js_function'] = "updateWeatherImage();";
        }

        $project_name_raw = $project_name;
        $quicksearch = $this->input->post('quicksearch');
        $isAjax = $this->input->post('isAjax');

        $project_name = str_replace("-", "+", $project_name);
        $project_name = urldecode($project_name);

        $this->load->model('project_model', 'project', true);
        $userId = $this->users->getUseridByUsername($username);
        $projectId = $this->project->get_id_by_projectname($project_name, $userId);

        $this->data['domain'] = $this->project->getProjectDomain($projectId);
        $this->data['domain_url'] = $this->data['domain'];

        $this->data['crawled_sites_model'] = $this->load->model("crawled_sites_model");
        $this->data['meta_title'] = "RankAlytics SERP Tracking";
        $this->data['username'] = $username;
        $this->data['project_name_raw'] = $project_name_raw;

        if ($keyword != null && !is_numeric($keyword)) {
            // individual keyword page
            $keyword = urldecode($keyword);
            $where = array("keyword" => $keyword);
            $this->data['keywords_array'] = $this->project->getProjectKeyword($projectId, array(), $where);

            /*echo "<pre>";
            print_r($this->data['keywords_array']);*/

            $data_array[] = array("Rankings", "ERT", "KEI", "Google Wetter", "Date");

            $date[] = date("Y-m-d");
            $datemdY[] = date("m/d/Y");

            $date[] = date("Y-m-d", strtotime('-1 days'));
            $datemdY[] = date("m/d/Y", strtotime('-1 days'));

            $date[] = date("Y-m-d", strtotime('-2 days'));
            $datemdY[] = date("m/d/Y", strtotime('-2 days'));

            $date[] = date("Y-m-d", strtotime('-3 days'));
            $datemdY[] = date("m/d/Y", strtotime('-3 days'));

            $date[] = date("Y-m-d", strtotime('-4 days'));
            $datemdY[] = date("m/d/Y", strtotime('-4 days'));

            foreach ($date as $key => $dt) {
                $data_array[] = $this->project->get_project_keyword_details_by_keyword($this->data['keywords_array'], $dt, $datemdY[$key]);
            }

            $filename = $this->common_model->createUniqueId() . ".csv";
            $filepath = BASEPATH . "../uploads/temp/" . $filename;
            $fileurl = base_url() . "uploads/temp/" . $filename;


            $this->data['quicksearch'] = '';
            $csv = $this->common_model->array_to_csv($data_array, $filepath);
            $this->data['csvPath'] = $filepath;
            $this->data['csv'] = $fileurl;
            $this->data['common_model'] = $this->common_model;
            $this->data['main_content'] = 'analytics/rank_keyword_graph';
            $this->load->view('include/template', $this->data);
            return;
        }

        if ($quicksearch != '') {
            $like = array("keyword" => $quicksearch);
            $this->data['quicksearch'] = $quicksearch;
        } else {
            $like = array();
            $this->data['quicksearch'] = '';
        }

        /* listing keywords through ajax: */
        if ($isAjax == 1) {
            // load requirements:
            $this->load->library('pagination');
            $this->config->load('paginationConfig');

            // prepare pagination:
            $paginationConfig = $this->config->item('paginationConfig'); // Taking default config values for pagination
            $paginationConfig = array_replace($paginationConfig, array(
                'base_url' => base_url() . 'ranktracker/rankings/' . $username . '/' . $project_name_raw . "/",
                'uri_segment' => 5,
                'per_page' => 10,
                'total_rows' => $this->project->getProjectKeywordsCount($projectId, $like),
            ));

            $limit = array(
                $this->uri->segment($paginationConfig['uri_segment']),
                $paginationConfig['per_page']
            );

            $this->pagination->initialize($paginationConfig);

            // temp sets:
            $collector = false;
            $i = 0;
            $keywords_array = $this->project->getProjectKeywords($projectId, $like, $limit);
            foreach($keywords_array as $key => $value) {
                $tempQ = array('keyword_id' => $value['unique_id'], "host" => $value['domain_url'], 'crawled_date' => date("Y-m-d"));
                $adwordInfo = $this->project_keywords_adwordinfo->get_latest_keywordinfo_by_keywordid($value['unique_id']);

                // determine current info:
                $info = $this->crawled_sites_model->getCrawledInfo($tempQ);
                if(!is_array($info)) {
                    //add a minmum of info:
                    $info = array(
                        'unique_id' => $value['unique_id'],
                        'project_id' => $projectId,
                        'rank' => null,
                        'domain_url' => $value['domain_url'],
                        'keyword' => $value['keyword'],
                    );
                } else {
                    //add some extra info:
                    $info['project_id'] = $projectId;
                    $info['domain_url'] = $value['domain_url'];
                }

                // collect:
                $tempo = array(
                    'days7' => $this->crawled_sites_model->getRank7Days($tempQ),
                    'days28' => $this->crawled_sites_model->getRank28Days($tempQ),
                    'adwordInfo' => $adwordInfo,
                    'ERT' => Crawled_sites_Model::getRankPercentage($info['rank'], $adwordInfo['volume']),
                );

                $collector[$i] = array_merge($tempo, $info);

                // increment:
                $i++;
            }

            // view set:
            $this->data['all_data'] = $collector;

            // out:
            $out = array(
                "error" => 0,
                "html" => $this->load->view('analytics/rank_data', $this->data, true),
                "pagination" => $this->pagination->create_links(),
            );

            exit(json_encode($out));
        } else {
            $this->load->model('common_model');
            $this->load->model("project_keyword_details_from_cron_model", "project_keyword_details_from_cron");
            $data_arr2 = $this->project_keyword_details_from_cron->last_five_days_for_graph($projectId);
            $filename = $this->common_model->createUniqueId() . ".csv";
            $filepath = BASEPATH . "../uploads/temp/" . $filename;
            $fileurl = base_url() . "uploads/temp/" . $filename;

            $csv = $this->common_model->array_to_csv($data_arr2, $filepath);
            $this->data['csvPath'] = $filepath;
            $this->data['csv'] = $fileurl;
            $this->data['common_model'] = $this->common_model;
            $this->data['main_content'] = 'analytics/rank_graph';
            $this->data['google_temps'] = $this->analytical->getGoogleTemperature();

            $this->data['keywords_array'] = array();
            $this->load->view('include/template', $this->data);
        }
    }


    public function dashboard()
    {
        //error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
        if (!$userId = $this->users->isLoggedIn()) {
            redirect("ranktracker");
        }

        if (!$this->users->isVerified($userId)) {
            redirect("users/verification");
        }

        // load requirements:
        $this->load->model('project_model', 'project', true);
        $this->load->model('common_model', 'common', true);
        $this->load->model('project_keywords_model', 'project_keywords');

        // get user info:
        $temp = $this->users->getUserById($userId);
        $userInfo = $temp[0];

        // service subscription info:
        $service = 'ranktracker';
        $subInfo = Subscriptions_Lib::getServiceSubscription($this->subscriptions, $userInfo, $service);

        // ..
        $subSets = Subscriptions_Lib::$_service_limits[$service][$subInfo['plan']];
        $key_used = ($subInfo['plan'] !== "enterprise") ? $subSets['number'] - $this->project_keywords->getNumberOfKeywordsByUser($userId) : $subSets['text'];

        // determine allowed options based on subscription:
        $allowed = array_flip(array('pro', 'enterprise'));
        $allowedOpt = false;
        if (array_key_exists($subInfo['plan'], $allowed)) {
            $allowedOpt = true;
        }

        /*pagination code starts*/
        $this->load->library('pagination');
        $this->config->load('paginationConfig');
        $paginationConfig = $this->config->item('paginationConfig'); // Taking default config values for pagination
        $paginationConfig['base_url'] = base_url() . 'ranktracker/dashboard/';
        $paginationConfig['uri_segment'] = 3;
        $total_result = $this->project->getProjectData($userId, $userInfo['userRole']);
        $paginationConfig['total_rows'] = count($total_result);
        $startFrom = $this->uri->segment($paginationConfig['uri_segment']);
        if ($startFrom == '') {
            $startFrom = 0;
        }

        $paginationConfig['per_page'] = 15;
        $limit = array($startFrom, $paginationConfig['per_page']);
        $this->pagination->initialize($paginationConfig);
        /*pagination code ends*/

        // view sets:
        $projectData = $this->project->getProjectData($userId, $userInfo['userRole'], $limit);
        $this->data = array(
            'meta_title' => "RankAlytics SERP Tracking",
            'main_content' => 'dashboard/dashboard',
            'user' => $userInfo,
            'allowedOpt' => $allowedOpt,
            'key_used' => $key_used,
            'project_data' => $projectData,
            'project_keyword_details_from_cron_model' => $this->load->model("project_keyword_details_from_cron_model"),
            'all_project_data' => $projectData, // this needs to be chanegd later with all projects when the result will be limited to 10 projects
        );

        $this->load->view('include/template', $this->data);
    }

    public function project_keyword_details_save()
    {
        $users = $this->users->get_active_users();
        $this->load->model("crawled_sites_model");
        $this->load->model("project_model", "project");
        $this->load->model("project_keyword_details_from_cron_model", "project_keyword_details");

        if (is_array($users)) {
            foreach ($users as $user) {
                $project_data = $this->project->getProjectDataWithKeywords($user['id'], $user['userRole']);
                $this->project_keyword_details->project_keyword_details_save($project_data);
            }
        }
    }

    public function CompareCompetition()
    {
        $this->load->model('compare_domain');
        $competitors_url = array();
        $user = $this->session->userdata['logged_in'][0]['id'];
        if (!isset($user) || $user == 0 || $user == '') // redirect to rantracker if not logged in
        {
            redirect('ranktracker');
            return;
        }
        $data['domains'] = $this->compare_domain->get_domains($user);
        // $this->load->view('dashboard/compared-domains',$data);


        $user = $this->session->userdata['logged_in'][0]['id'];
        $data['domains'] = $this->compare_domain->get_domains($user);
        if ($_POST) {
            $data['key_words'] = $this->compare_domain->get_keywords();
            $data_keywords = $data['key_words'];
            // echo "<pre>"; print_r($data_keywords); echo "</pre>";
            if ($data_keywords) {
                foreach ($data_keywords as $key => $data_keyword) {
                    // echo 'Key: '.$key.'<br />';
                    $competitors_url = $_POST['competitor_url'];
                    $competitors_url = array_filter($competitors_url);
                    array_unshift($competitors_url, "http://" . $_POST['keywordresearch-keyword_text']);
                    if ($competitors_url) {
                        foreach ($competitors_url as $key2 => $competitor_url) {
                            $trimed = trim($competitor_url);
                            $data['ranking'][$key][] = $this->compare_domain->get_ranking($competitor_url, $data_keyword['keyword']);
                        }
                    }
                }
            }
        }
        $data['competitor_urls'] = $competitors_url;
        // echo "<pre>"; print_r($data['ranking']); echo "</pre>";
        $this->load->view('dashboard/compared-domains', $data);
    }

    public function delete_reports()
    {
        $id = $_POST['id'];
        $file = $_POST['file'];
        $this->db->query('delete from tbl_rt_report where id=\'' . $id . '\'');
        $file1 = $_SERVER['DOCUMENT_ROOT'] . '/csv/' . $file . '.csv';
        $file2 = $_SERVER['DOCUMENT_ROOT'] . '/csv/' . $file . '.pdf';
        unlink($file1);
        unlink($file2);
        echo '1';
    }

    public function reports()
    {
        if (!$userId = $this->users->isLoggedIn()) {
            redirect("ranktracker");
        }

        $tempInfo = $this->users->getUserById($userId);
        $userInfo = $tempInfo[0];

        // ..
        $service = 'ranktracker';
        $this->data = array(
            'user_database' => $userInfo,
            'sub_info' => Subscriptions_Lib::getServiceSubscription($this->subscriptions, $userInfo, $service),
            'current' => 'reports',
            'crawled_sites_model' => $this->load->model("crawled_sites_model"),
            'report_model' => $this->load->model("report_model"),
        );

        $this->load->view("dashboard/reports", $this->data);
    }

    /* contact functions/ops */
    public function contactus()
    {
        $this->load->view("contactus");
    }

    public function contactussave()
    {
        $this->load->library("form_validation");
        $this->load->model("contactus_requests_model", "contactus_request");
        $this->form_validation->set_rules($this->contactus_request->_validation_rules);
        $error = 0;
        $error_msg = array();
        if (!$this->form_validation->run()) {
            $error = 1;
            if (form_error('fullName') != '') {
                $error_msg['fullName'] = form_error('fullName');
            }
            if (form_error('emailAddress') != '') {
                $error_msg['emailAddress1'] = form_error('emailAddress');
            }
            if (form_error('message') != '') {
                $error_msg['message'] = form_error('message');
            }
            echo json_encode(array("error" => $error, "msg" => $error_msg));
            return;
        } else {
            $this->load->model("common_model");
            $contactus_array['id'] = $this->common_model->getNewId("contactus_requests");
            $contactus_array['fullName'] = $this->input->post('fullName');
            $contactus_array['emailAddress'] = $this->input->post('emailAddress');
            $contactus_array['message'] = $this->input->post('message');
            $contactus_array['phoneNumber'] = $this->input->post('phoneNumber');

            if (!$error = $this->contactus_request->save($contactus_array)) {

                echo json_encode(array("error" => $error, "msg" => "Unable to save in database"));
                return;
            }

            $this->load->model('email_model');

            $email = array("support" => $contactus_array['emailAddress']);
            $subject = "Thank you for contact Request";
            //$this->load->view('users/validationEmail', $userData, TRUE);
            $contents = $this->load->view("general_email/contactus_thankyou", array(), TRUE); //"Contact request message ";
            //echo $contents;
            $email_result = $this->email_model->send($email, $subject, $contents);

            $email = array("support" => "accounts@rankalytics.com");
            $subject = "Contact Request ";
            //$contents = "Contact request message ";
            $info_array['info_array'] = $contactus_array;
            $info_array['data'] = array("fullName" => "Full Name", "emailAddress" => "Email", "phoneNumber" => "Phone Number", "message" => "Message");

            $contents = $this->load->view("general_email/contactus_request", $info_array, TRUE); //"Contact request message ";
            //echo $contents;
            $email_result = $this->email_model->send($email, $subject, $contents);

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
    public function keywordsuggestions($keyword = 0, $from_internal = false)
    {
        $keywords = array();
        $search_array = array("+a", "+b", "+c", "+d", "+e", "+f", "+g", "+h", "+i", "+j", "+k", "+l", "+m", "+n", "+o", "+p", "+q", "+r", "+s", "+t", "+u", "+v", "+w", "+x", "+y", "+z", "+1", "+2", "+3", "+4", "+5", "+6", "+7", "+8", "+9");
        $this->load->library('My_adwords_api');
        if (isset($_POST['site_name']) && $_POST['site_name']) {
            $this->data['web'] = $_POST['site_name'];
            $this->session->set_userdata(array("web" => $_POST['site_name']));

        }
        if ($keyword) {
            // For python start ==================================================================================================================================================
            /*  $this->data['web'] =$_POST['site_name'];
              $file_name=  md5($_POST['site_name'].$keyword).".txt";
              $keyword = urlencode($keyword);
              $site_name = $_POST['site_name'];
              shell_exec("python python_find_simil/similarity.py --domain $site_name --keyword $keyword --output similarities/$file_name");
              $this->data['txt_file'] = $file_name; */
            // For python end  ==================================================================================================================================================


            $keyword = urldecode($keyword);
            $proxy_array = $this->analytical->getRandomProxy();
            $keywords[$keyword] = $this->getGoogleSuggestions($keyword, $proxy_array, 0);
            $count = 1;
            $keyword_new = $keyword . "+";
            $keywords[$keyword_new] = $this->getGoogleSuggestions($keyword_new, $proxy_array, $count);
            foreach ($search_array as $value) {
                $count++;
                $keyword_new = $keyword . $value;
                $keywords[$keyword_new] = $this->getGoogleSuggestions($keyword_new, $proxy_array, $count);
            }
            $proxy_ip = $proxy_array[0]['ip'];
            $uname_password = $proxy_array[0]['username'] . ":" . $proxy_array[0]['password'];
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
            curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
            curl_setopt($ch, CURLOPT_PROXYUSERPWD, $uname_password);
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);

            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            //curl_setopt($ch, CURLOPT_URL, 
            //'http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en_US&q='.urlencode($keyword));
            curl_setopt($ch, CURLOPT_URL, 'http://suggestqueries.google.com/complete/search?q=' . urlencode($keyword) . '&client=firefox&hl=de');

            $data = curl_exec($ch);
            $data = (string)$data;
            $data = utf8_encode($data);
            //$data = iconv(mb_detect_encoding($data, mb_detect_order(), true), "utf8", $data);
            if (($data = json_decode($data, true)) !== null) {
                $keywords[$keyword] = $data[1];
            }
            if ($from_internal) {
                return $keywords;
            }
        }
        $this->data['keyword'] = $keyword;
        $this->data['keyword_array'] = $keywords;
        $this->session->set_userdata(array("keyword" => "$keyword"));
        $this->data['meta_title'] = "RankAlytics SERP Tracking";
        $this->data['main_content'] = 'keyword-research/keyword-research';


        $this->load->view('include/template', $this->data);
    }

    public function getGoogleSuggestions($keyword, $proxy_array, $count)
    {

        $child_keywords = array();
        $proxy_ip = $proxy_array[$count]['ip'];
        $uname_password = $proxy_array[$count]['username'] . ":" . $proxy_array[0]['password'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
        curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $uname_password);
        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, 'http://suggestqueries.google.com/complete/search?q=' . urlencode($keyword) . '&client=firefox&hl=de');
        $data = curl_exec($ch);
        $data = (string)$data;
        $data = utf8_encode($data);
        if (($data = json_decode($data, true)) !== null) {
            $child_keywords = $data[1];
        }
        return $child_keywords;
    }


    public function keywordResearchCSVDownloads()
    {
        $count = 0;
        $array_master = $this->keywordsuggestions($this->session->userdata('keyword'), TRUE);
        $send_dom = array();
        $send_dom[0][0] = 'KEYWORD SUGGESTIONS REPORT ' . date("Y-m-d");
        $send_dom[1][0] = 'KEYWORDS';
        $send_dom[1][1] = 'SIMILARITY';
        $send_dom[1][2] = 'MONTHLY SEARCHES';
        $send_dom[1][3] = 'COMPETITION';
        $send_dom[1][5] = 'CPC';
        $j = 2;

        foreach ($array_master as $key => $value) {
            $count++;
            foreach ($value as $value2) {
                $send_dom[$j][0] = $value2;
                $send_dom[$j][1] = $this->find_similarities($value2, $this->session->userdata('web'));
                $j++;
            }
            if ($count >= 10) {
                break;
            }
        }
        $this->array_to_csv_download($send_dom, 'keyword_suggestions_report.csv');
        exit;
    }

    function savekeywords()
    {
        $keyword = $_REQUEST['keyword'];

        $pageurl = $_REQUEST['pageurl'];

        $search_keyworddata = array(
            'keyword' => $keyword,
            'page_url' => $pageurl,
            'user_id' => $this->session->userdata['logged_in'][0]['id'],
            'when_added' => date('Y-m-d')
        );

        $query = $this->db->query('SELECT id   FROM keyword_suggestions where keyword = "' . $keyword . '" and page_url = "' . $pageurl . '"');
        $keywordexist = $query->result_array();
        $total_count = count($keywordexist);
        if ($total_count < 1) {
            $this->db->insert('keyword_suggestions', $search_keyworddata);
            $search_id = $this->db->insert_id();
        } else {
            $search_id = $keywordexist[0]['id'];
        }


    }

    function saveaccesstoken()
    {
        $user_id = $_REQUEST['user_id'];

        $user_email = $_REQUEST['user_email'];


        $acesstoken = strtoupper(md5($user_email . $user_id . time()));

        $this->db->query('update users set access_token ="' . $acesstoken . '" where id = ' . $user_id);

        //$this->users_model->save_access_token($user_id,$acesstoken);

        echo $acesstoken;
        exit;

    }

    public function save_csv_for_keywords()
    {


        $this->load->model('email_model');


        $query = $this->db->query('SELECT * FROM keyword_suggestions where email_status ="N" order by id asc limit 1');


        $search_array = array("+a", "+b", "+c", "+d", "+e", "+f", "+g", "+h", "+i", "+j", "+k", "+l", "+m", "+n", "+o", "+p", "+q", "+r", "+s", "+t", "+u", "+v", "+w", "+x", "+y", "+z", "+1", "+2", "+3", "+4", "+5", "+6", "+7", "+8", "+9");
        $this->load->model('users_model');
        $this->load->library('My_adwords_api');


        if ($query->result()) {
            foreach ($query->result() as $row) {
                $keywords = array();
                $keyword = $row->keyword;
                $pageurl = $row->page_url;
                $user_info = $this->users_model->getUserById($row->user_id);


                $remove_str = array(" ", "'");
                $file_keyword = str_replace($remove_str, "", $row->keyword);
                $file_pageurl = str_replace($remove_str, "", $row->page_url);
                $filename = 'search_keyword_' . $file_keyword . '.csv';
                $searchfile = APPPATH . 'third_party/csv/search_keyword_' . $file_keyword . '.csv';


                if (!file_exists($searchfile)) {

                    if ($pageurl) {
                        $this->data['web'] = $pageurl;

                    }
                    if ($keyword) {
                        $keyword = urldecode($keyword);
                        $proxy_array = $this->analytical->getRandomProxy();
                        $keywords[$keyword] = $this->getGoogleSuggestions($keyword, $proxy_array, 0);
                        $count = 1;
                        $keyword_new = $keyword . "+";
                        $keywords[$keyword_new] = $this->getGoogleSuggestions($keyword_new, $proxy_array, $count);
                        foreach ($search_array as $value) {
                            $count++;
                            $keyword_new = $keyword . $value;
                            $keywords[$keyword_new] = $this->getGoogleSuggestions($keyword_new, $proxy_array, $count);
                        }
                        $proxy_ip = $proxy_array[0]['ip'];
                        $uname_password = $proxy_array[0]['username'] . ":" . $proxy_array[0]['password'];
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
                        // curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64; rv:28.0) Gecko/20100101 Firefox/28.0");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                        curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
                        curl_setopt($ch, CURLOPT_PROXY, $proxy_ip);
                        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $uname_password);
                        curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, TRUE);

                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
                        //curl_setopt($ch, CURLOPT_URL,
                        //'http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en_US&q='.urlencode($keyword));
                        curl_setopt($ch, CURLOPT_URL, 'http://suggestqueries.google.com/complete/search?q=' . urlencode($keyword) . '&client=firefox&hl=de');

                        $data = curl_exec($ch);
                        $data = (string)$data;
                        $data = utf8_encode($data);
                        //$data = iconv(mb_detect_encoding($data, mb_detect_order(), true), "utf8", $data);
                        if (($data = json_decode($data, true)) !== null) {
                            $keywords[$keyword] = $data[1];
                        }
                        if ($from_internal) {
                            $keywords = $keywords;
                        }
                        //close connection
                        curl_close($ch);
                        require('adwords.php');
                        $adwards = new Adwords();

                        $data = "KEYWORD ,SIMILARITY % ,MONTHLY SEARCHES ,COMPETITION ,CPC\r\n";
                        foreach ($keywords as $key => $value):
                            $all_keywords[] = $key;
                            foreach ($value as $key2 => $value2):
                                $all_keywords[] = $value2;
                            endforeach;
                        endforeach;

                        foreach ($all_keywords as $key => $value):
                            $keyword_detials = $adwards->GetKeywordIdeasExample($value);
                            ob_start();
                            //open connection
                            $ch_similir = curl_init();

                            //set the url, number of POST vars, POST data
                            curl_setopt($ch_similir, CURLOPT_URL, "http://www.rankalytics.com/python_find_simil/ignitor.php");
                            curl_setopt($ch_similir, CURLOPT_POST, 1);
                            curl_setopt($ch_similir, CURLOPT_POSTFIELDS,
                                "keyword=" . $value . "&website=" . $pageurl . "");
                            curl_setopt($ch_similir, CURLOPT_ENCODING, 'gzip');
                            //execute post
                            curl_exec($ch_similir);

                            curl_close($ch_similir);
                            $similaty = json_decode(ob_get_contents());
                            ob_clean();
                            if (!empty($keyword_detials)) {
                                $data .= utf8_decode($keyword_detials['keywords']) . "," . $similaty->similarity_score . "," . $keyword_detials['volume'] . "," . $keyword_detials['competition'] . "," . $keyword_detials['cpc'] . "\r\n";
                                //  foreach ($value as $key2 => $value2):
                                // $keyword_detials2= $adwards->GetKeywordIdeasExample($value2);
                                // if(!empty($keyword_detials))
                                //$data .=$keyword_detials2['keywords'].", 87% ,".$keyword_detials2['volume'].",".$keyword_detials2['competition'].",".$keyword_detials2['cpc']."\r\n";
                                // endforeach;
                            }

                        endforeach;

                        chmod($searchfile, 0777);
                        file_put_contents($searchfile, $data);

                    }


                } // end of file exist

                // $user_info[0]['emailAddress']  email_address

                //$email=array("support"=>"php.vikramrawat@gmail.com");
                $subject = "Search Keyword Response";

                $email = array("support" => $user_info[0]['emailAddress']);

                $contents = "please find attachment of csv";
                $attach_file = "";
                $attach_file = FCPATH . $searchfile;
                // $data['download_link'] =site_url('application/third_party/csv/');
                $data = array(
                    'data' => array(
                        'download_link' => site_url('application/third_party/csv/' . $filename),
                        'keyword' => $row->keyword
                    )
                );
                $contents = $this->load->view('general_email/analysisreport', $data, TRUE);
                $email_result = $this->email_model->send($email, $subject, $contents);
                $this->db->query('update keyword_suggestions set email_status ="Y" where id = ' . $row->id);
            }

        }

    }

    /**
     * competitoranalysis()
     * @desc competitor analysis
     * @link codeddesign.org
     * @author Ananthakrishnan
     * @access public
     *
     */
    public function competitoranalysis()
    {
        $user = $this->session->userdata('logged_in');
        if (!isset($user['0']['id']) || $user['0']['id'] == 0 || $user['0']['id'] == '') { // redirect to rantracker if not logged in
            redirect('ranktracker');
            return;
        }
        $top_ten_array = array();
        $urls_array = array();
        $base_urls_array = array();
        $majesticSEOData_array = array();
        $majesticSEODomainArray = array();
        $adword_array = array();
        $this->data['keyword_array'] = array();

        if (isset($_GET['keyword']) && $_GET['keyword']) {
            $keyword = utf8_decode($_GET['keyword']);

            if (isset($_POST['txt_keyword']) && $_POST['txt_keyword']) {
                $this->data['txt_keyword'] = $keyword;
            } else {
                $this->data['drop_keywordresearch'] = $keyword;
            }

            $top_ten_array = $this->analytical->getTopTenCrawledSiteByKeyword($keyword);
            $this->data['keyword_array'] = $this->analytical->getKeywordByKeyword($keyword);
            foreach ($top_ten_array as $key => $value) {
                $urls_array[] = $value['site_url'];
                $base_urls_array[] = "http://" . trim($value['host']);
            }

            if (!empty($urls_array)) {
                $urls_array = implode(", ", $urls_array);
                $base_urls_array = implode(", ", $base_urls_array);
                $majesticSEODomainArray = $this->MajesticSEOData2($base_urls_array);
                $urls_string = $urls_array . ", " . $base_urls_array;
                $majesticSEOData_array = $this->MajesticSEOData($urls_string, "GetIndexItemInfo");
            }

            if (!empty($this->data['keyword_array'])) {
                $adword_array = $this->analytical->getAdWordInfoByID(@$this->data['keyword_array'][0]['unique_id']);
            } else {
                $adword_array = $this->analytical->getAdWordInfoByKeyword($keyword);
            }
        }

        $records = $this->analytical->getKeywords(NULL, 10, $this->users->getRelatedUsers($this->users->isloggedIn()), "dropbox");
        $this->data['keywords'] = $records;
        $this->data['adword_array'] = $adword_array;
        $this->data['majesticSEOData_array'] = $majesticSEOData_array;
        $this->data['majesticSEODomainArray'] = $majesticSEODomainArray;
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
        $result_array = array();
        $records = $this->analytical->getKeywords($category, 5, $this->users->getRelatedUsers($this->users->isloggedIn()), "ajax");
        foreach ($records as $key => $value) {
            $result_array[] = array(
                'label' => utf8_encode($value['keyword']),
                'id' => $value['unique_id']
            );
        }
        echo json_encode($result_array);
    }

    /**
     * MajesticSEOData()
     * @desc MajesticSEOData for finding PA, DA, PBL, DBL,IC
     * @link codeddesign.org
     * @author Ananthakrishnan
     * @access public
     *
     */
    public function MajesticSEOData($urls_array, $action)
    {


        /* $urls_array_new = explode(",",$urls_array);
        $siteurl_array =array();
        $count = 0;
        foreach($urls_array_new as $value) :
        if(!in_array($value,$siteurl_array)):
        if($count > 9)
            break;
            $siteurl_array[] =trim($value);
            $count++;
        endif;
        endforeach;
        //print_r($siteurl_array);
        $urls_array =implode(",",$siteurl_array);
        //print_r($urls_array);*/
        $return_array = array();
        $app_api_key = $this->config->item('majestic_seo_app_id');
        $endpoint = $this->config->item('endpoint');
        require_once BASEPATH . "MajesticSEO/APIService.php";
        $itemsToQuery = $urls_array;
        $items = preg_split("/, /", $itemsToQuery, -1);
        $parameters = array();

        for ($i = 0; $i < count($items); $i++) {
            $parameters["item" . $i] = $items[$i];
        }

        $parameters["items"] = count($items);
        $parameters["datasource"] = "fresh";

        $api_service = new APIService($app_api_key, $endpoint);
        $response = $api_service->executeCommand($action, $parameters);

        if ($response->isOK() == "true") {
            $results = $response->getTableForName('Results');
            foreach ($results->getTableRows() as $row) {
                $return_array[$row['ItemNum']] = $row;
            }
        } else {
            echo "error";
            exit;
        }
        return $return_array;
    }

    /**
     * MajesticSEOData()
     * @desc MajesticSEOData for finding domain age
     * @link codeddesign.org
     * @author Ananthakrishnan
     * @access public
     *
     */
    public function MajesticSEOData2($urls_array)
    {
        // echo "<pre>";
        // print_r($urls_array);
        // echo "</pre>";
        $return_array = array();
        $app_api_key = $this->config->item('majestic_seo_app_id');
        $endpoint = $this->config->item('endpoint');
        require_once BASEPATH . "MajesticSEO/APIService.php";
        $itemsToQuery = $urls_array;
        $items = preg_split("/, /", $itemsToQuery, -1);
        $parameters = array();

        for ($i = 0; $i < count($items); $i++) {
            $parameters["item" . $i] = $items[$i];
        }

        $parameters["items"] = count($items);
        $parameters["datasource"] = "fresh";

        $api_service = new APIService($app_api_key, $endpoint);
        $response = $api_service->executeCommand("GetRefDomainInfo", $parameters);
        // echo "<pre>";
        /// var_dump($response);
        //echo "</pre>";

        if ($response->isOK() == "true") {
            $results = $response->getTableForName('DomainsInfo');
            foreach ($results->getTableRows() as $row) {
                $return_array[$row['RowID']] = $row;
            }
        } else {
            //echo "error";//exit;
        }
        return $return_array;
    }

    /**
     *This function is used to write content into CSV file to download.
     */
    public function array_to_csv_download($array, $filename = "export.csv", $delimiter = ",")
    {
        // open raw memory as file so no temp files needed, you might run out of memory though
        $f = fopen('php://memory', 'w');
        // loop over the input array
        foreach ($array as $line) {
            // generate csv lines from the inner arrays
            fputcsv($f, $line, $delimiter);
        }
        // rewrind the "file" with the csv lines
        fseek($f, 0);
        // tell the browser it's going to be a csv file
        header('Content-Type: application/csv');
        // tell the browser we want to save it instead of displaying it
        header('Content-Disposition: attachement; filename="' . $filename . '"');
        // make php send the generated csv lines to the browser
        fpassthru($f);
    }

    public function find_similarities($keyword, $web)
    {

        $parse = parse_url($web);
        $site_name = $parse['scheme'] . "://" . preg_replace('#^www\.(.+\.)#i', '$1', $parse['host']) . $parse['path'];
        $site_name = (substr($site_name, -1) == '/') ? substr($site_name, 0, -1) : $site_name;

        $keyword = urlencode($keyword);
        $keyword_array = array();
        $keyword_array = explode("+", $keyword);
        $count = 0;
        $sum = 0;
        $file_exists = false;
        foreach ($keyword_array as $value) {
            $value = trim($value);
            $file_name = md5($site_name . $value . date("Y-m-d H:i:s")) . ".txt";
            shell_exec("python python_find_simil/similarity.py --domain $site_name --keyword $value --output similarities/$file_name");
            $file = "similarities/" . $file_name;
            $file_temp = $file;

            if (file_exists($file)) {
                $file_exists = true;
                $file = fopen($file, "r");
                while (!feof($file)) {
                    $string = fgets($file);
                    if (stristr($string, $value) !== false) {
                        $temp_array = explode("+", urlencode($string));
                        if (strcasecmp($temp_array[0], $value) == 0) {
                            $count++;
                            $sum = $sum + $temp_array[2];
                        }
                    }
                }
                @unlink($file_temp);
            }

        }
        if ($count > 0)
            $result = $sum / $count;
        else
            $result = 0;
        return $result;
    }


    /*
    function updateWeatherImage(){// capture google weather image
        error_reporting(E_ERROR | E_PARSE);
        $domain="http://mozcast.com/";

        $output = file_get_contents($domain);
        //echo $output;
        $dom = new DOMDocument();
        $dom->loadHTML($output);
        $domx = new DOMXPath($dom);
        //$entries = $domx->evaluate("//li");
        $entries = $domx->evaluate('//li[@class="row"]');
        $arr = array();
        $res = array();
        $i=1;
        foreach ($entries as $entry) {
            $arr["t".$i] = substr(trim($entry->nodeValue),0,5) ;
            $arr["d".$i] = substr(trim($entry->nodeValue),4) ;
            $res[]=$arr;
            $i++;
        }

        //error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
        include("assets/screen/GrabzItClient.class.php");
        $grabzIt = new GrabzItClient("YTNkNjM0YmE2NDE0NDk0NTg5ODgxYzM5ZjNjODAxNDM=", "GSc/bD8leQlsNgc/LhJOPz90Tj8FLT9qGBohP2NAPz8=");
        $grabzIt->SetImageOptions($domain, null, null, null, 545, 400, "png", null, "highcharts-0" );
        $grabzIt->SaveTo("assets/screen/temp.png");
        //echo "here res ";
        //print_r($res);
        $template = $this->load->view('analytics/weatherTemplate',array('res'=>$res),true);
        echo json_encode(array("error"=>0,"html"=>$template));


    }// capture google weather image*/

    /* PREVIEW FUNCTIONS: */
    function doCurl($search_string, $proxy)
    {
        // do search:
        $max_results = 30;

        $config = array(
            'url' => "https://www.google.com/search?q=" . $search_string . "&hl=en&start=0&num=" . $max_results,
            'header' => 0,
            'timeout' => 3,
            'agent' => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.16) Gecko/20080702 Firefox/2.0.0.16",
        );

        $con = curl_init();
        curl_setopt($con, CURLOPT_TIMEOUT, $config['timeout']);
        curl_setopt($con, CURLOPT_HEADER, $config['header']);
        curl_setopt($con, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($con, CURLOPT_URL, $config['url']);
        curl_setopt($con, CURLOPT_USERAGENT, $config['agent']);
        curl_setopt($con, CURLOPT_FOLLOWLOCATION, false);

        //proxy zone:
        if (isset($proxy['ip'])) {
            curl_setopt($con, CURLOPT_PROXYTYPE, 'HTTP');
            curl_setopt($con, CURLOPT_PROXY, $proxy['ip']);
            curl_setopt($con, CURLOPT_PROXYUSERPWD, trim($proxy['username']) . ':' . trim($proxy['password']));
            curl_setopt($con, CURLOPT_HTTPPROXYTUNNEL, TRUE);
        }

        $data = curl_exec($con);
        curl_close($con);

        $bad = array(
            'Sorry...',
            'ipv4.google.com/sorry',
            ' has moved ',
            'automated queries',
        );

        foreach($bad as $b_no => $b) {
            if(stripos($data, $b) !== false) {
                return null;
            }
        }

        return $data;
    }

    function preview()
    {
        if (!isset($_GET['word']) || !isset($_GET['domain'])) {
            redirect('/ranktracker/dashboard');
        }

        // sets:
        $css_style = 'border: 2px red solid; background-color: #F1F1F1; ';
        $keyword = urlencode(trim($this->input->get('word')));
        $proxies = $this->analytical->getRandomProxy();
        $current = 0;
        $body = null;
        $max_attempts = 5;

        while (($body == '' OR $body == null) AND $current <= $max_attempts) {
            $body = $this->doCurl($keyword, $proxies[$current]);
            $current++;
        }

        // fallback case:
        if ($body == null OR $body == '') {
            $body = $this->doCurl($keyword, array());
            /*echo 'direct'."\n";*/
        } else {
            /*echo 'proxy'."\n";*/
        }

        // prepare selector:
        $replace = array('/', /*'?'*/);
        $with = array('\/', /*'\?'*/);
        $domain = str_replace($replace, $with, trim($this->input->get('domain')));
        $parts = parse_url($domain);
        if(isset($parts['query'])) {
            $queryQ = urlencode($parts['query']);
            $domain = str_ireplace('?'.$parts['query'], '', $domain);
            $domain = $domain .'%3F'.$queryQ;
        }

        /* dom zone */
        $dom = new domDocument;
        $dom->strictErrorChecking = false;
        $dom->preserveWhiteSpace = true;
        $dom->encoding = 'UTF-8';
        @$dom->loadHTML($body);

        $found = false;
        $els = $dom->getElementsByTagName("h3");
        if ($els) {
            foreach ($els as $info) {
                if ($info->getAttribute('class') == 'r' && $found == false) {
                    $sub_els = $info->getElementsByTagName('a');
                    foreach ($sub_els as $info2) {
                        $link = $info2->getAttribute('href');
                        if (preg_match('/q=' . $domain . '&(.*?)/', $link, $matched)) {
                            //apply style:
                            $info->parentNode->setAttribute('style', $css_style);
                            $found = true;
                        }
                    }
                }
            }
        }

        // remove ads:
        $remove_ids = array('rhs_block', 'tads');
        foreach ($remove_ids as $r_no => $r_id) {
            $some_ads = $dom->getElementById($r_id);
            if ($some_ads) {
                $some_ads->parentNode->removeChild($some_ads);
            }
        }

        // disable links / onclick for divs:
        $links = $dom->getElementsByTagName('a');
        if ($links) {
            foreach ($links as $el_link) {
                $el_link->setAttribute('style', 'pointer-events: none; cursor: default;');
            }
        }

        // disable forms:
        $forms = $dom->getElementsByTagName('form');
        if ($forms) {
            foreach ($forms as $el_form) {
                $el_form->setAttribute('onsubmit', 'return false;');
            }
        }

        // disable inputs:
        $inputs = $dom->getElementsByTagName('input');
        if ($inputs) {
            foreach ($inputs as $el_input) {
                $el_input->setAttribute('disabled', 'disabled');
            }
        }

        // disable text select:
        $body_els = $dom->getElementsByTagName('body');
        if ($body_els) {
            foreach ($body_els as $el_body) {
                $el_body->setAttribute('style', '-webkit-touch-callout: none;-webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none;');
            }
        }

        $body = $dom->saveHTML();
        $body = str_ireplace('/images/nav_', 'https://www.google.de/images/nav_', $body);
        echo $body;
    }
}
