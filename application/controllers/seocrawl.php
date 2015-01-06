<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Seocrawl extends CI_Controller
{

    protected $user_id;

    public function __construct()
    {
        parent::__construct();
        Subscriptions_Lib::loadConfig();

        $this->load->library('session');
        $this->load->library('email');

        $this->load->model('analytical_model', 'analytical', true);
        $this->load->model('users_model', 'users', true);
        $this->load->model('seocrawl_campaign_model', 'seocrawl_campaign', true);
        $this->load->model('subscriptions_model', 'subscriptions', true);

        $this->user_id = $this->users->isLoggedIn();
    }

    public function index()
    {
        $this->load->view('seocrawl/index.php');
    }

    /* dashboard view and methods: */
    public function dashboard()
    {
        $this->checkAuth();

        // grab user's info:
        $temp = $this->session->userdata('logged_in');
        $userInfo = $temp[0];

        // service sets and checks:
        $service = 'seocrawl';
        $sub_info = Subscriptions_Lib::getServiceSubscription($this->subscriptions, $userInfo, $service);

        // ..
        $this->data = array(
            'main_content' => 'seocrawl/dashboard',
            'campaigns_no' => $this->seocrawl_campaign->total($this->user_id),
            'campaigns' => $this->seocrawl_campaign->getAllByUserId($this->user_id),
            'sub_info' => $sub_info,
        );

        $this->load->view('include/seocrawltemplate', $this->data);
    }

    /*
     * creates a new campaign
     * */
    public function saveCampaign()
    {
        $out = array(
            'error' => 1, // 1 = error | 0 = no-error
        );

        $data = array(
            'user_id' => $this->user_id,
            'campaign_name' => trim($this->input->post('campaignName')),
            'domain_url' => trim($this->input->post('domainURL')),
            'depth_level' => trim($this->input->post('depthLevel')),
            'google_indexed' => trim($this->input->post('googleIndexCheck')),
            'ping_non_indexed' => trim($this->input->post('pingNonIndexed')),
        );

        if (strlen($data['campaign_name']) < 3) {
            $out['msg'] = 'Campaign name needs at least 3 characters';
            return $this->jsonMessage($out);
        }

        //add protocol if missing:
        if (substr($data['domain_url'], 0, 4) !== 'http') {
            $data['domain_url'] = 'http://' . str_replace('://', '', $data['domain_url']);
        }

        $parts = parse_url($data['domain_url']);
        if (!isset($parts['host']) OR stripos($parts['host'], '.') === false) {
            $out['msg'] = 'Invalid domain.';
            return $this->jsonMessage($out);
        }

        if (filter_var($data['depth_level'], FILTER_VALIDATE_INT, array("options" => array("min_range" => 1, "max_range" => 999))) === FALSE) {
            $out['msg'] = 'Invalid depth level (1 to 999).';
            return $this->jsonMessage($out);
        }

        //if we get here => no errors:
        $out['error'] = 0;

        $insert_id = $this->seocrawl_campaign->add($data);
        $out['saved_as'] = $insert_id;
        $out['current_total'] = $this->seocrawl_campaign->total($this->user_id);
        return $this->jsonMessage($out);
    }

    /*
     * forAdmin - if TRUE means we are in admin/ area (user_id=null, new template is being loaded)
     * adminViewed - if TRUE, the field from database is not being updated
     * ^ used when viewed in admin/ area
     * */
    public function viewCampaign($forAdmin = false, $adminViewed = false)
    {
        // defaults:
        $out = array(
            'error' => 1,
        );

        $user_id = $this->user_id;
        $template = 'seocrawl/campaign_progress';

        // checks & sets:
        if (!isset($_GET['id'])) {
            return $this->jsonMessage($out);
        }
        $camp_id = $this->input->get('id');

        if($forAdmin == true) {
            $user_id = null;
            $template = 'seocrawl/admin_right';

            //update view status:
            if(!$adminViewed) {
                $this->seocrawl_campaign->updateById($camp_id, array('admin_viewed' => 1));
            }
        }

        // ..
        $r = $this->seocrawl_campaign->getOne($user_id, $camp_id);
        if (!is_array($r)) {
            return $this->jsonMessage($out);
        }

        $this->data['c_info'] = $r;

        $out['error'] = 0;
        $out['html'] = $this->load->view($template, $this->data, true);

        return $this->jsonMessage($out);
    }

    /* admin view and methods: */
    public function admin()
    {
        $this->checkAuth();
        $this->isAdmin($this->user_id);

        // handle pagination:
        $max_results = 10;
        if(isset($_GET['p'])) {
            $page = $this->input->get('p');

            if(!is_numeric($page)) {
                $page = 1;
            }
        } else {
            $page = 1;
        }

        $offset = ($page-1) * $max_results;

        //continue:
        $this->data['main_content'] = 'seocrawl/admin';
        $this->data['total_campaigns'] = count($this->seocrawl_campaign->getAll());
        $this->data['campaigns'] = $this->seocrawl_campaign->getAll(null, null, $max_results, $offset);
        $this->data['current_page'] = $page;
        $this->load->view('include/seocrawltemplate', $this->data);
    }

    public function viewCampaignA() {
        //defaults:
        $forAdmin = true;
        $viewed = false;

        if(isset($_GET['viewed'])) {
            $viewed = trim(strtolower($this->input->get('viewed')));
            $viewed = ($viewed == 'yes') ? true : false;
        }

        $this->viewCampaign($forAdmin, $viewed);
    }

    /*
     * called by admin when a seocrawl project is completed
     * updates info,status, .. to db
     * */
    public function complete() {
        // default err:
        $out = array(
            'error' => 1,
            'msg' => 'Failed: post data missing (contact dev.)',
        );

        if(!isset($_POST['prj_id'])) {
            return $this->jsonMessage($out);
        }

        // update info set:
        $camp_id = $this->input->post('prj_id');
        $data = array(
            'dropbox' => trim($this->input->post('dropbox')),
            'pages_number' => trim($this->input->post('pages_number')),
            'completed' => 1,
        );

        // checks:
        if(strlen($data['dropbox']) == 0) {
            $out['input_id'] = 'dropbox';
            $out['msg'] = 'No dropbox link';
            return $this->jsonMessage($out);
        }

        if(strlen($data['pages_number']) == 0 || !is_numeric($data['pages_number'])) {
            $out['input_id'] = 'pages_number';
            $out['msg'] = 'Number of links is missing / not numeric';
            return $this->jsonMessage($out);
        }

        // do update:
        $this->seocrawl_campaign->updateById($camp_id, $data);

        $out['error'] = 0;
        $out['msg'] = 'Success';
        return $this->jsonMessage($out);
    }

    /* helper functions */
    function jsonMessage($arr)
    {
        header('Content-Type: application/json');
        echo json_encode($arr);

        return 1;
    }

    protected function checkAuth()
    {
        $user = $this->session->userdata['logged_in'][0]['id'];

        // redirect to rantracker if not logged in
        if (!isset($user) || $user == 0 || $user == '') {
            redirect('/');
            return;
        }
    }

    protected function isAdmin($userId)
    {
        $user = $this->users->getUserById($userId);
        if (strtolower(trim($user[0]['userRole']) !== 'admin')) {
            //get out!
            redirect('/');

            return false;
        } else {
            return true;
        }
    }

    function sendrequest()
    {
        // defaults:
        $out = array(
            'error' => 1,
        );

        if ( ! isset( $_POST['msg_subject'] ) OR !isset( $_POST['msg_content'] )) {
            $out['msg'] = 'Something went wrong with your request.';
            return $this->jsonMessage( $out );
        }

        $msg_subject = trim( $_POST['msg_subject'] );
        $msg_content = trim( $_POST['msg_content'] );

        if ( ! strlen( $msg_content ) or ! strlen( $msg_subject )) {
            $out['msg'] = 'Subject/Message is missing or too short.';
            return $this->jsonMessage( $out );
        }

        if ( ! $this->requestToAdmin( $msg_subject, nl2br( $msg_content ) )) {
            return $this->jsonMessage( array( 'error' => 1, 'msg' => 'Something went wrong while sending your email.' ) );
        }

        return $this->jsonMessage( array( 'error' => 0, 'msg' => 'Your feature request has been sent. Thank you!' ) );
    }

    protected function requestToAdmin( $msg_title, $msg_content )
    {
        // loads:
        $this->load->library( 'mymailer' );
        $this->config->load( 'email' );
        $email = $this->config->item( 'email' );

        // prepare 'from':
        if ( ! $userId = $this->users->isLoggedIn()) {
            $this->jsonMessage( array( 'error' => 1, 'msg' => 'Something went wrong while sending your email.' ) );
            return false;
        }

        $user       = $this->users->getUserById( $userId );
        $userData   = $user['0'];
        $from_name  = $userData['firstName'] . " " . $userData['lastName'];
        $from_email = $userData['emailAddress'];

        // config:
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = $email['Timeout'];
        $mail->SMTPDebug  = $email['SMTPDebug'];
        $mail->SMTPSecure = $email['SMTPSecure'];
        $mail->SMTPAuth   = $email['SMTPAuth'];
        $mail->Host       = $email['Host'];
        $mail->Port       = $email['Port'];
        $mail->Username   = $email['Username'];
        $mail->Password   = $email['Password'];
        $mail->Timeout = 15;

        // dynamic:
        $mail->SetFrom( 'support@rankalytics.com', 'support' );
        $mail->AddReplyTo( $from_email, $from_name );
        $mail->Subject = $msg_title . " - seocrawl feature request";

        $mail->MsgHTML( $msg_content );
        $mail->AddAddress( 'support@rankalytics.com', 'support' );

        if ( ! $mail->Send()) {
            return false;
        }

        return true;
    }
}