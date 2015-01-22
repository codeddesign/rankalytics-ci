<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Project
 *
 * The class used for managing Analytics
 *
 *
 * @author     Ananthakrishnan
 * @link       codeddesign.org
 * @package    Rankalytics
 */
class Project extends CI_Controller
{

    /**
     * __construct()
     *
     * @desc constructor for Project
     *
     * @author Ananthakrishnan
     * @access public
     *
     */
    public function __construct()
    {
        parent::__construct();
        Subscriptions_Lib::loadConfig();

        $this->load->helper('form');
        $this->load->helper('url'); // load 'url' helper
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('csvimport');
        $this->load->model('project_model', 'project', true);
        $this->load->model('project_keywords_model', 'project_keywords', true);
        $this->load->model('subscriptions_model', 'subscriptions');
    }

    /**
     * saveProject()
     *
     * @desc saveProject function for new project
     * Save posted data of new project form
     * @author Ananthakrishnan
     * @access public
     *
     */
    public function save()
    {
        //$textAr= array();
        if ($this->input->server('REQUEST_METHOD') === 'POST') {

            $this->load->model("common_model");
            $unique_id = $this->common_model->createUniqueId();
            $this->load->model("users_model", "users");
            if (!$userId = $this->users->isLoggedIn()) {
                redirect("ranktracker");
                return false;
            }
            $data_array = array(
                "id"           => $unique_id,
                'project_name' => $this->input->post( 'project_name' ),
                'domain_url'   => $this->stripHttp( $this->input->post( 'domainurl' ) ),
                'userId'       => $userId,
                'location' => $this->input->post('location'),
            );
            $projectId = $insert_id = $this->project->saveProject($data_array);

            // saving project keywords 
            $keywords = $this->input->post('keywords');

            if ($keywords) {
                $keyword_arr = explode("\n", $keywords);
                $checklimit = $this->check_limit($keyword_arr);
                if ($checklimit == 0) {
                    $uploadData['msg'] = "Please Upgrade Your Keywords Limit. ";
                    $uploadData['isError'] = 1;
                    $this->load->view("users/ajaxIframeMessage", $uploadData);
                    return;
                } else {
                    $result = $this->insert_keywords($projectId, $keyword_arr);
                    $keyword_count = count($keyword_arr);
                }
            } else {
                $config['allowed_types'] = 'csv|txt';
                $config['max_size'] = '1000000';
                $config['upload_path'] = './uploads/temp/';
                $this->load->library('upload', $config);
                if (!$this->upload->do_upload()) {
                    $error = array('error' => $this->upload->display_errors());
                    $uploadData['msg'] = $error['error'] . "<p>Or Enter keywords in textarea</p>";
                    $uploadData['isError'] = 1;
                    $this->load->view("users/ajaxIframeMessage", $uploadData);
                    return;
                } else {
                    $data = array('upload_data' => $this->upload->data());
                    $this->load->helper('file');
                    $string = read_file($data['upload_data']['full_path']);
                    $keys_file_arr = explode("\n", $string);
                    $checklimit = $this->check_limit($keys_file_arr);
                    if ($checklimit == 0) {
                        $uploadData['msg'] = "Please Upgrade Your Keywords Limit. ";
                        $uploadData['isError'] = 1;
                        $this->load->view("users/ajaxIframeMessage", $uploadData);
                        return;
                    } else {
                        $result = $this->insert_keywords($projectId, $keys_file_arr);
                        $keyword_count = count($keys_file_arr);
                    }
                }
            }
            if (count($result['repeat_arr']) == 0) {
                $msg = "<div class='keywordaddsuccess'></div>";

            } else {
                $msg = "Saved total " . count($result['saved_arr']) . " keyword(s) and " . count($result['repeat_arr']) . " keywords(s) repeated";
            }
            $uploadData['msg'] = $msg;
            $uploadData['isError'] = 0;
            if (isset($keyword_count) && $keyword_count >= 1) {
                $uploadData['thirdParameter'] = ',"' . $projectId . '","' . $keyword_count . '"';
            } else {
                $uploadData['thirdParameter'] = ',"0","0"';
            }
            $uploadData['js_function'] = $this->input->post('js_function');
            $this->load->view("users/ajaxIframeMessage", $uploadData);
        }
    }

    public function showprojectByuserid()
    {
        //$id = $this->input->post('id');
        $this->load->model("users_model", "users");
        if (!$userId = $this->users->isLoggedIn()) {
            $msg = "Please login to create a Project";
            $error = 1;
            echo json_encode(array("error" => $error, "msg" => $msg));
            return false;
        }
        $project = $this->project->getProjectByUserid($userId);
        $this->data['project_keyword_details_from_cron_model'] = $this->load->model("project_keyword_details_from_cron_model");
        $html = $this->load->view("dashboard/project_row", array("project_data" => $project), true);


        echo json_encode(array("error" => 0, "msg" => "Succesfully done", 'html' => $html));
    }

    public function newProjectValidate()
    {

        $ret_arr = $this->projectValidate();
        echo json_encode($ret_arr);
    }

    public function projectValidate()
    {
        $msg = "";
        $error = 0;
        $error_ids = array();
        $this->load->model("users_model", "users");
        if (!$userId = $this->users->isLoggedIn()) {
            $msg = "Please login to create a Project";
            $error = 1;
        }
        if (trim($name = $this->input->post('project_name')) == "") {
            $error_ids[] = "project_name";
            $error = 1;
        }
        if (trim($name = $this->input->post('domainurl')) == "") {
            $error_ids[] = "domainurl";
            $error = 1;
        }
        if ($this->project->isProjectExists($this->input->post('project_name'), $userId)) {
            $error_ids[] = "project_name";
            $msg = "The project name already exist<br/>";
            $error = 1;
        }
        if ($this->project->isDomainExists($this->stripHttp($this->input->post('domainurl')), $userId)) {
            $error_ids[] = "domainurl";
            $msg .= "The domain already used for another project";
            $error = 1;
        }
        return array("msg" => $msg, "ids" => $error_ids, "error" => $error);
    }

    public function delete()
    {
        $id = $this->input->post('id');

        if ($this->project->delete($id)) {
            echo json_encode(array("error" => 0, "msg" => "Project and keywords deleted"));
        } else {
            echo json_encode(array("error" => 1, "msg" => "Error in deletion"));
        }
    }

    public function showproject()
    {
        $id = $this->input->post('id');
        $project = $this->project->getProjectById($id);
        $this->data['project_keyword_details_from_cron_model'] = $this->load->model("project_keyword_details_from_cron_model");
        $this->load->view("dashboard/project_row", array("project_data" => $project));
    }

    public function flushMessage($msg)
    {
        sleep(1);
        echo $msg;
        flush();
        ob_flush();
    }

    protected function lookupSemrush($domain_url)
    {
        ob_start();
        ob_implicit_flush(true);
        ob_end_flush();

        //how many words at once:
        $RANGE = 50;

        //query:
        $params = array(
            'action' => 'report',
            'type' => 'url_organic',
            'key' => '2bbc00ee2214773f6d73794b789d6f0f',
            'display_offset' => 0,
            'display_limit' => $RANGE,
            'export' => 'api',
            'export_columns' => 'Ph,Po',
            'url' => 'http://' . $domain_url . '/',
        );

        $finished = $keywords_found = false;
        $full_output = '';
        $total = 0;
        while ((!$keywords_found OR !$finished)) {
            $link = 'http://api.semrush.com/?' . http_build_query($params);

            //curl:
            $ch = curl_init($link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);

            if (stripos($output, ":: NOTHING FOUND")) {
                //if no words from the start just exit;
                if (!$keywords_found) {
                    exit(
                    json_encode(array("error" => 2, "msg" => "No keywords found"))
                    );
                }

                //if we did found some words, exit the loop
                if ($keywords_found) {
                    $finished = true;
                }
            } else {
                $keywords_found = true;

                //remove first line from result:
                $lines = explode("\n", $output);
                unset($lines[0]);

                //save full content;
                $full_output .= implode("\n", $lines) . "\n";

                //count all lines:
                $total = count(explode("\n", $full_output));
                if ($total > $RANGE) {
                    $total = $total - 1;
                }

                //flush the message;
                $this->flushMessage('"c": ' . ($total) . ',"d": 0,');

                //change limit and offset:
                $params['display_offset'] = $params['display_limit'];
                $params['display_limit'] = $params['display_limit'] + $RANGE;
            }
        }

        $this->flushMessage('"c": ' . ($total) . ',"d": 1,');
        $this->flushMessage('');

        return $full_output;
    }

    public function saveGlobalProject()
    {

        $ret_arr = $this->projectValidate();

        if ($ret_arr['error'] == 1) {
            exit(json_encode($ret_arr));
        }

        $userId = $this->users->isLoggedIn();

        $domainurl = $this->input->post('domainurl');
        $project_name = $this->input->post('project_name');
        $location = $this->input->post('global_location');

        $output = $this->lookupSemrush($domainurl);
        $output_arr = explode("\n", $output);

        $this->load->model('common_model');
        $unique_id = $this->common_model->createUniqueId();
        $data_array = array(
            "id" => $unique_id,
            'project_name' => $project_name,
            'domain_url' => $this->stripHttp($domainurl),
            'userId' => $userId,
            'location' => $location,
        );
        $projectId = $insert_id = $this->project->saveProject($data_array);

        //$this->load->model("project_keyword_list_model");
        $key = 0;
        $kcount = 0;
        $keywordIdSmall = $keywordId_projectId_arr = $key_arr = array();
        $keywordIds = array();

        $temp_date = date("Y-m-d H:i:s");
        foreach ($output_arr as $key => $line) {
            if (strlen(trim($line)) > 0) {
                list($out['keyword'], $out['position']) = explode(";", $line);
                $keywordID = $this->common_model->createUniqueId();

                //boovad: added $kcount:
                $key_arr[$kcount]['project_id'] = $out_arr['project_id'] = $projectId;
                $key_arr[$kcount]['unique_id'] = $keywordID;
                $key_arr[$kcount]['location'] = $location;

                $out_arr[$kcount]['location'] = $location;
                $key_arr[$kcount]['keyword'] = $out_arr['keyword'] = $out['keyword'];

                //defaults:
                $key_arr[$kcount]['crawled_status'] = '0';
                $key_arr[$kcount]['total_records'] = '0';
                $key_arr[$kcount]['total_search'] = '0';
                $key_arr[$kcount]['uid'] = $userId;
                $key_arr[$kcount]['crawled_date'] = $temp_date;
                $key_arr[$kcount]['uploadedOn'] = $temp_date;

                // keyword project reln
                $keywordId_projectId_arr[$kcount]['keyword_id'] = $keywordID; //$keyword_tbl_arr['unique_id'];
                $keywordId_projectId_arr[$kcount]['project_id'] = $projectId; //$keyword_tbl_arr['project_id'];
                $keywordId_projectId_arr[$kcount]['id'] = md5(rand(1, 10000) . microtime() . rand(1, 10000));
                $keywordId_projectId_arr[$kcount]['created_on'] = $temp_date;

                //
                $kcount++;
            }
        }

        if ($kcount > 0) {
            #1
            $this->load->model('project_keywords_model');
            $this->project_keywords_model->saveBulk($key_arr);

            #2
            $this->load->model('project_keyword_relation_model');
            $this->project_keyword_relation_model->saveBulk($keywordId_projectId_arr);

            #3 boovad
            $this->common_model->saveNewKeywordAdword($projectId);
        }

        echo "\"error\":0,\"msg\":\"" . $key . " keywords saved\", \"project_id\": \"" . $projectId . "\",\"keyword_count\":" . count($keywordIds);
    }

    public function projectkeywordsave()
    {
        $projectId = $this->input->post('project_id');
        $keywords = $this->input->post('keywords');

        if ($keywords) {
            $keyword_arr = explode("\n", $keywords);
            $checklimit = $this->check_limit($keyword_arr);
            if ($checklimit == 0) {
                $uploadData['msg'] = "Please Upgrade Your Keywords Limit. ";
                $uploadData['isError'] = 1;
                $this->load->view("users/ajaxIframeMessage", $uploadData);
                return;
            } else {
                $result = $this->insert_keywords($projectId, $keyword_arr);
                $keyword_count = count($keyword_arr);
            }
        } else {
            $config['allowed_types'] = 'csv|txt';
            $config['max_size'] = '1000000';
            $config['upload_path'] = './uploads/temp/';
            $this->load->library('upload', $config);
            if (!$this->upload->do_upload()) {
                $error = array('error' => $this->upload->display_errors());
                $uploadData['msg'] = $error['error'] . "<p>Or Enter keywords in textarea</p>";
                $uploadData['isError'] = 1;
                $this->load->view("users/ajaxIframeMessage", $uploadData);
                return;
            } else {
                $data = array('upload_data' => $this->upload->data());
                $this->load->helper('file');
                $string = read_file($data['upload_data']['full_path']);
                $keys_file_arr = explode("\n", $string);
                $checklimit = $this->check_limit($keys_file_arr);
                if ($checklimit == 0) {
                    $uploadData['msg'] = "Please Upgrade Your Keywords Limit. ";
                    $uploadData['isError'] = 1;
                    $this->load->view("users/ajaxIframeMessage", $uploadData);
                    return;
                } else {

                    $result = $this->insert_keywords($projectId, $keys_file_arr);
                    $keyword_count = count($keys_file_arr);
                }
            }
        }
        if (count($result['repeat_arr']) == 0) {
            $msg = "<div class='keywordaddsuccess'></div>";
            //$msg = "Saved " . count($result['saved_arr']) . " keywords and nothing repeated";
        } else {
            $msg = "Saved total " . count($result['saved_arr']) . " keyword(s) and " . count($result['repeat_arr']) . " keywords(s) repeated";
        }
        $uploadData['msg'] = $msg;
        $uploadData['isError'] = 0;
        if (isset($keyword_count) && $keyword_count >= 1) {
            $uploadData['thirdParameter'] = ',"' . $projectId . '","' . $keyword_count . '"';
        } else {
            $uploadData['thirdParameter'] = ',"0","0"';
        }

        $this->load->view("users/ajaxIframeMessage", $uploadData);
    }

    public function insert_keywords($projectId, $keyword_arr)
    {
        $this->load->model('common_model');
        $this->load->model('project_keyword_relation_model');

        // get project info (it also has user's information):
        $projectInfo = $this->project->getProjectById($projectId);
        $projectLocation = $projectInfo['location'];

        // ..
        $res_array = array("repeat_arr" => array(), "saved_arr" => array());
        $keywordIds = $unique_arr = array();
        $kcount = 0;
        $keyword_unq = $this->project_keywords->KeywordsUnique($keyword_arr);

        foreach ($keyword_unq as $value) {
            $ext = $this->project_keyword_relation_model->checkKeywordId($value['unique_id'], $projectId);
            $unique_arr[] = $value['keyword'];
            if (!$ext) {
                $keywordId_projectId_arr[$kcount]['keyword_id'] = $value['unique_id'];
                $keywordId_projectId_arr[$kcount]['project_id'] = $projectId;
                $keywordId_projectId_arr[$kcount]['id'] = md5(rand(1, 10000) . microtime() . rand(1, 10000));
                $keywordId_projectId_arr[$kcount]['created_on'] = date("Y-m-d");
                $kcount++;
            } else {
                $res_array['repeat_arr'][] = $value['keyword'];
            }
        }

        foreach ($keyword_arr as $key => $keyword) {
            if ($keyword != '' && !in_array($keyword, $unique_arr)) {
                $exists = $this->project_keywords->isKeywordExists($keyword, $projectId);
                if (!$exists) {
                    $unique_id = md5(rand(1, 10000) . microtime());
                    $keyword_tbl_arr['unique_id'] = $unique_id;
                    $keyword_tbl_arr['project_id'] = $projectId;
                    $keyword_tbl_arr['keyword'] = $keyword;

                    if (isset($_POST['location']) && $_POST['location']) {
                        $keyword_tbl_arr['location'] = $_POST['location'];
                    } else {
                        // fallback case:
                        $keyword_tbl_arr['location'] = $projectLocation;
                    }

                    $keyword_tbl_arr['crawled_status'] = '0';
                    $keyword_tbl_arr['total_records'] = '0';
                    $keyword_tbl_arr['total_search'] = '0';

                    $temp_date = date("Y-m-d H:i:s");
                    $keyword_tbl_arr['uid'] = $this->users->isLoggedIn();;
                    $keyword_tbl_arr['uploadedOn'] = $temp_date;
                    $keyword_tbl_arr['crawled_date'] = $temp_date;

                    $keywordIds[] = $this->project_keywords->saveKeyword($keyword_tbl_arr);

                    $keywordID = $this->common_model->createUniqueId(); // create ybu\ique id for small keyword table
                    $out_arr1['keyword'] = $keyword;
                    $out_arr1['project_id'] = $projectId;
                    $out_arr1['unique_id'] = $unique_id;
                    //$this->project_keyword_list_model->save($out_arr1);
                    $keywordIdSmall[] = $keywordID; //

                    $res_array['saved_arr'][] = $keyword;
                    $keywordId_projectId_arr[$kcount]['keyword_id'] = $keyword_tbl_arr['unique_id'];
                    $keywordId_projectId_arr[$kcount]['project_id'] = $keyword_tbl_arr['project_id'];
                    $keywordId_projectId_arr[$kcount]['id'] = md5(rand(1, 10000) . microtime() . rand(1, 10000));
                    $keywordId_projectId_arr[$kcount]['created_on'] = date("Y-m-d");
                    $kcount++;
                } else {
                    $res_array['repeat_arr'][] = $keyword;
                }
            }
        }

        if (isset($keywordId_projectId_arr) && count($keywordId_projectId_arr) >= 1) {
            $this->load->model('project_keyword_relation_model');
            $this->project_keyword_relation_model->saveBulk($keywordId_projectId_arr);
        }

        return $res_array;
    }

    /* do nothing. */
    public function callCrawler($projectId = '')
    {
        exit(json_encode(array('crawler' => 0, 'msg' => 'disabled call.') ));

        if ($projectId == '') {
            $projectId = $this->input->post('projectId');
        }

        $this->load->model('common_model');
        $result['crawler'] = $this->common_model->crawlNewKeyword($projectId);
        echo json_encode($result);

        /*if(!empty($projectId)){ // IF KEYWORDS EXISTS GET ADWORD info
        
            
        }*/
    }

    public function callAdwordSave($projectId = '')
    {
        if ($projectId == '') {
            $projectId = $this->input->post('projectId');
        }
        $this->load->model('common_model');
        $result['adword'] = $this->common_model->saveNewKeywordAdword($projectId);
        echo json_encode($result);
    }

    public function saved($name)
    {
        $project_name = str_replace("-", "+", $name);
        $project_name = urldecode($project_name);
        $this->session->set_flashdata('message', $project_name . ' successfully created');
        $this->data['message'] = $project_name . ' successfully created';
        $this->data['meta_title'] = "RankAlytics SERP Tracking";
        $this->data['main_content'] = 'dashboard/dashboard';
        $this->data['project_data'] = $this->project->getProjectData();
        $this->load->view('include/template', $this->data);
    }

    public function check_limit($keyword_arr)
    {
        // load requirements:
        $this->load->model("users_model", "users");
        $this->load->model('common_model', 'common', true);
        $this->load->model('project_keywords_model', 'project_keywords');

        if (!$userId = $this->users->isLoggedIn()) {
            return 0;
        }

        // get subscription info:
        $temp = $this->users->getUserById($userId);
        $userInfo = $temp['0'];

        $service = 'ranktracker';
        $subInfo = Subscriptions_Lib::getServiceSubscription($this->subscriptions, $userInfo, $service);
        $total = $this->project_keywords->getNumberOfKeywordsByUser($userId) + count($keyword_arr);

        if ($total <= $subInfo['crawl_limit_no']) {
            return 1;
        } else {
            return 0;
        }
    }

    function delete_project_keyword()
    {
        $keyword_id = $this->input->post('keyword_id');
        $project_id = $this->input->post('project_id');
        $this->load->model("project_Keyword_Relation_Model");
        $where = array("project_id" => $project_id, "keyword_id" => $keyword_id);
        $res = $this->project_Keyword_Relation_Model->delete($where);
        echo $res;
    }

    /**
     * stripHttp()
     * @desc This function is used for remove http:// from url if user entered
     * @link codeddesign.org
     * @author Ananthakrishnan
     * @access public
     *
     */
    function stripHttp($input)
    {
        // in case scheme relative URI is passed, e.g., //www.google.com/
        $input = trim($input, '/');

        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://' . $input;
        }
        $urlParts = parse_url($input);
        // remove www
        //$domain = preg_replace('/^www\./', '', $urlParts['host']);
        $url = preg_replace('#^www\.(.+\.)#i', '$1', $urlParts['host']) . @$urlParts['path'];
        return "www." . $url;
    }

}