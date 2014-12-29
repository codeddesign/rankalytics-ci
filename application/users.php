<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * The class used for managing Users
 *
 *
 * @author     Sudhir
 * @link       codeddesign.org
 * @package    Analytics
 */
class Users extends CI_Controller
{
    private $user_id, $_tax, $_service_prices, $_service_limits, $_month_days;

    /**
     * __construct()
     *
     * @desc constructor for Users
     *
     * @author Sudhir
     * @access public
     *
     */
    public function __construct()
    {
        // ..
        parent::__construct();

        // load requirements:
        $this->load->helper('form');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->model('users_model', 'users', true);
        $this->load->model('analytical_model', 'analytical', true);
        $this->load->model('subscriptions_model', 'subscriptions', true);
        $this->pgsql = $this->load->database('pgsql', true);

        /* 'GLOBAL' SETS:
         * * _tax => percentage tax
         * * _month_days => the number of days that equals to a month
         * * _plan_prices => contains array with plans per service, except the free ones.
         * * _plan_limits => number of links/words the user is limited too depending on the subscription plan
         * */
        $this->_tax = 19;
        $this->_month_days = 28;
        $this->_service_prices = array(
            'ranktracker' => array(
                'pro' => 99,
                'enterprise' => 299,
            ),
            'seocrawl' => array(
                'starter' => 99,
                'pro' => 249,
                'enterprise' => 399,
            ),
        );

        $this->_service_limits = array(
            'ranktracker' => array(
                'pro' => array(
                    'number' => '10000',
                    'text' => '10,000',
                ),
                'enterprise' => array(
                    'number' => NULL,
                    'text' => 'unlimited',
                ),
                'starter' => array(
                    'number' => '?',
                    'text' => '?',
                ), // not-paid
            ),
            'seocrawl' => array(
                'starter' => array(
                    'number' => '35000',
                    'text' => '35,000',
                ),
                'pro' => array(
                    'number' => '250000',
                    'text' => '250,000',
                ),
                'enterprise' => array(
                    'number' => '1000000',
                    'text' => '1 Million',
                ),
                'free' => array(
                    'number' => '?',
                    'text' => '?',
                ), // not-paid
            ),
        );

        // ..
    }

    public function logout()
    {
        $this->session->unset_userdata('logged_in');
        redirect('ranktracker');
    }

    public function admin()
    {
        if (!$userId = $this->users->isloggedIn()) {
            redirect("ranktracker");
        }
        if (!$adminUser = $this->users->isAdmin()) {
            $this->load->view('ranktracker/admin', array("error" => "You are not authorised to vew this page", "notAdmin" => 1));
            exit();
        } else {
            $isAjax = $this->input->post("isAjax");
            $this->load->library('pagination');
            $this->config->load('paginationConfig');
            $paginationConfig = $this->config->item('paginationConfig'); // Taking default config values for pagination
            $paginationConfig['base_url'] = base_url() . 'users/admin/';
            $paginationConfig['uri_segment'] = 15;
            $searchString = $this->input->post('searchString');
            $total = $this->users->getUsersCount($searchString);
            $paginationConfig['total_rows'] = $total;
            $startFrom = $this->uri->segment($paginationConfig['uri_segment']);

            if ($startFrom == '') {
                $startFrom = 0;
            }

            $paginationConfig['per_page'] = 10;
            $limit = array($startFrom, $paginationConfig['per_page']);
            $this->data['users'] = $this->users->getUsers($searchString, $limit);
            $this->pagination->initialize($paginationConfig);

            if ($isAjax == 1) {
                $user_html = $this->load->view('ranktracker/admin/userlist', $this->data, true);
                echo json_encode(array("error" => 0, "html" => $user_html, "pagination" => $this->pagination->create_links()));
            } else {
                //$this->data['main_content'] = 'ranktracker/admin';
                $this->load->view('ranktracker/admin', $this->data);
            }
        }
    }

    /* ajax pre-validation for small form - registration */
    public function prevalidation()
    {
        $requestMethod = $this->input->server('REQUEST_METHOD');
        if (strtolower($requestMethod) != 'post') {
            echo json_encode(array('error' => 1, 'msg' => 'invalid request'));
            exit;
        }

        $isValidated = $this->validateUser();
        if ($isValidated['error'] >= 1) {
            echo json_encode($isValidated);
        } else {
            $temp_data = array(
                'firstName' => $this->input->post('firstName'),
                'lastName' => $this->input->post('lastName'),
                'emailAddress' => $this->input->post('emailAddress'),
                'userName' => $this->input->post('userName'),
                'password' => md5($this->input->post('password')),
            );

            $this->session->set_userdata(array('tempData' => $temp_data));

            echo json_encode(array('error' => 0, 'redirect' => 'users/promembership'));
        }
    }

    function validateUser()
    {
        $msg = "";
        $error = 0;
        $error_ids = array();
        if (trim($firstname = $this->input->post('firstName')) == "") {
            $error_ids[] = "firstName";
            $error = 1;
        }
        if (trim($lastname = $this->input->post('lastName')) == "") {
            $error_ids[] = "lastName";
            $error = 1;
        }
        if (trim($email = $this->input->post('emailAddress')) == "") {
            $error_ids[] = "emailAddress";
            $error = 1;
        }
        if ($this->users->isUserExists(array("emailAddress" => $email))) {
            $error_ids[] = "emailAddressExists";
            $error = 1;
        }
        if (trim($userName = $this->input->post('userName')) == "") {
            $error_ids[] = "userName";
            $error = 1;
        }
        if ($this->users->isUserExists(array("userName" => $userName))) {
            $error_ids[] = "userNameExists";
            $error = 1;
        }
        if (trim($name = $this->input->post('password')) == "") {
            $error_ids[] = "password";
            $error = 1;
        }

        return array("msg" => $msg, "ids" => $error_ids, "error" => $error);
    }

    /* rest of registration - after redirect */
    public function promembership()
    {
        //1st check: after payment: rest of payPal workflow - Check status + show message:
        $pp_msg = array(
            'canceled' => 'Your order was canceled. Please contact support for any questions.<br/>Thank you.%s',
            'completed' => 'Your order has been processed.<br/>Current status is <i>%s</i><br/> Thank you.',
            'completedExtra' => '%s',
            'failed' => 'Something went wrong with your payment. Please contact support and provide this information:<br/> <i>%s</i>',
        );

        if (isset($_GET['token'])) {
            // load requirements:
            $this->load->library('paypal');
            $tempToken = $this->input->get('token');

            // check if canceled:
            if (isset($_GET['mode']) AND trim($_GET['mode']) == '1') {
                $tempStatus = 'canceled';
                $extra = '';
            } else {
                //check & get payment details:
                $response = $this->paypal->doExpressCheckout($tempToken);
                if ($response !== false) {
                    $tempStatus = 'completed';
                    $extra = $response;

                    if (is_array($this->paypal->getErrors())) {
                        $tempStatus = 'completedExtra';
                        $extra = implode('<br/>', $this->paypal->getErrors());
                    }
                } else {
                    $tempStatus = 'failed';

                    if (is_array($this->paypal->getErrors())) {
                        $extra = implode('<br/>', $this->paypal->getErrors());
                    } else {
                        $extra = 'Unknown error.';
                    }
                }
            }

            /*do status update if paypal_order_id is null */
            $transactionId = $this->paypal->getTransactionId();
            $subs = $this->subscriptions->getAll(array('paypal_temp' => $tempToken, 'paypal_order_id' => NULL));

            if ($transactionId !== false) {
                $newInfo = array('paypal_order_id' => $transactionId, 'started_on' => date('Y-m-d H:i:s', time()));
                if (isset($response) AND $response !== false) {
                    $newInfo['status'] = strtolower($response);
                }
            } else {
                $newInfo['status'] = 'canceled';
            }

            if (is_array($subs) AND count($subs) > 0) {
                foreach ($subs as $s_no => $sub) {
                    $this->subscriptions->doUpdate(
                        $newInfo,
                        array('id' => $sub['id'])
                    );
                }
            }

            //set info message:
            $replace_this = array('token', 'payment attempts');
            $replace_with = array('session', 'status checks');

            $info_msg = str_ireplace($replace_this, $replace_with, sprintf($pp_msg[$tempStatus], $extra));
        }

        //2nd check:
        $user_data = $this->session->all_userdata();
        if (!isset($user_data['tempData']) AND !isset($info_msg)) {
            redirect('ranktracker');
        }

        if (isset($info_msg)) {
            $this->data['pp_msg'] = $info_msg;
        }

        if (isset($user_data['tempData'])) {
            $this->load->model('countries_model', 'countries', true);

            $this->data['temp'] = $user_data['tempData'];
            $this->data['countries'] = $this->countries->getAll();
        }

        $this->data['meta_title'] = "RankAlytics Pro-Membership";
        $this->data['main_content'] = 'ranktracker/promembership';

        $this->load->view('ranktracker/promembership', $this->data);
    }

    /* validate and save user + subscriptions */
    public function save()
    {
        header('Content-Type: application/json');

        $user_data = $this->session->all_userdata();
        if (strtolower($this->input->server('REQUEST_METHOD')) != 'post' OR !array_key_exists('tempData', $user_data)) {
            $out = array(
                'error' => true,
                'redirect_to' => '/ranktracker',
            );
            exit(json_encode($out));
        }

        // temp:
        $out = array(
            'error' => false,
            'paid' => false,
            'redirect_to' => '/',
        );

        // if not payed, defaults to 'none' into db:
        $pType = false;
        if (isset($_POST['paymentType'])) {
            $pType = $this->input->post('paymentType');
            $out['paid'] = true;
        }

        // sets & session save:
        $temp_info = $user_data['tempData'];
        $userArray = array(
            'firstName' => $this->input->post('firstName'),
            'lastName' => $this->input->post('lastName'),
            'emailAddress' => $temp_info['emailAddress'],
            'userName' => $temp_info['userName'],
            'password' => $temp_info['password'],
            'verificationCode' => substr(md5($temp_info['emailAddress'] . time()), 0, 19),
            'verificationCodeSentDate' => date("Y-m-d"),
            'userRole' => 'mainuser',
            'accountType' => 'none',
            'phoneNumber' => $this->input->post('phoneNumber'),
            'streetAddress' => $this->input->post('streetAddress'),
            'city' => $this->input->post('city'),
            'zipCode' => $this->input->post('zipCode'),
            'vatNumber' => '', /*$this->input->post('vatNumber'),*/
            'country' => $this->input->post('country'),
        );
        $this->session->set_userdata(array('tempData' => $userArray));

        // save user info:
        $userId = $this->users->save($userArray);

        // send email:
        $userArray['userId'] = $userId;
        if (!$this->sendVerficationEmail($userArray)) {
            $out['error_msg'] = 'Mail sending failed!';
            exit(json_encode($out));
        }

        // number of months:
        $months = array(
            'ranktracker' => $this->input->post('monthsRanktracker'),
            'seocrawl' => $this->input->post('monthsSeocrawl'),
        );

        // save subscriptions:
        $subscriptions = array();
        $criteria = 'accountType';
        $sub_id = 'SUB-' . (rand(10000, 99999) . '-' . substr(time(), -6));
        foreach ($_POST as $key_name => $value) {
            $t_value = trim(strtolower($value));

            if (stripos($key_name, $criteria) !== false) {
                $temp_name = strtolower(str_replace($criteria, '', $key_name));
                $subscription = array(
                    'user_id' => $userId,
                    'service' => $temp_name,
                    'plan' => $t_value,
                    'order_id' => $sub_id,
                );
                $subscription['months'] = $months[$subscription['service']];
                $subscription['payment_type'] = isset($this->_service_prices[$subscription['service']][$subscription['plan']]) ? $pType : 'none';

                $this->subscriptions->doSave($subscription);

                //
                $subscriptions[] = $subscription;
            }
        }

        // handle behavior:
        switch ($pType) {
            case 'none':
                // ..
                break;
            case 'manual':
                $out['what'] = 'manual';
                break;
            case 'paypal':
                $this->load->library('paypal');

                //
                $out['what'] = 'paypal';
                $out['redirect_to'] = $this->getPayPalLink($userArray['country'], $subscriptions);

                // update:
                $this->subscriptions->doUpdate(
                    array('paypal_temp' => $this->paypal->getToken(), 'status' => 'pending'),
                    array('order_id' => $sub_id, 'payment_type' => 'paypal')
                );
                break;
        }

        // change true/false to 1/0
        foreach ($out as $o_no => $o_val) {
            if (is_bool($o_val)) {
                $out[$o_no] = ($o_val) ? 1 : 0;
            }
        }

        $this->session->unset_userdata('tempData');
        exit(json_encode($out));
    }

    protected function getPayPalLink($countryCode, array $subscriptions)
    {
        // set paypal information:
        $passed_info = array(
            // buyer:
            'city' => '',
            'name' => '',
            'street' => '',
            'state' => '',
            'postalCode' => '',
            'countryCode' => $countryCode,
            'phone' => '',
        );

        // set items information:
        foreach ($subscriptions as $sub_no => $subscription) {
            if (isset($this->_service_prices[$subscription['service']][$subscription['plan']])) {
                $amount = number_format($this->_service_prices[$subscription['service']][$subscription['plan']], 2);
                $tempTax = number_format($this->_tax / 100 * $amount, 2);

                $passed_info['items'][] = array(
                    'name' => ucfirst($subscription['service']) . ' - ' . ucfirst($subscription['plan']) . ' plan',
                    'amount' => $amount,
                    'quantity' => $subscription['months'],
                    'tax' => $tempTax,
                    'category' => 'digital',
                    'description' => 'Order id: ' . $subscription['order_id'],
                );
            }
        }

        $this->paypal->setExpressCheckout($passed_info);
        return $this->paypal->getPayPalURL();
    }

    /*
     * re-sends email for account validation:
     * */
    function resendVerification()
    {
        if (!$userId = $this->users->isLoggedIn()) {
            redirect("ranktracker");
            return false;
        }

        if (!isset($_GET['testMe101'])) {
            if ($this->users->isVerified($userId)) {
                echo "You are already verified";
                return;
            }
        }

        $user = $this->users->getUserById($userId);

        $userArray = $user['0'];
        $userArray['verificationCode'] = substr(md5($userArray['emailAddress'] . time()), 0, 19);
        $userArray['verificationCodeSentDate'] = date("Y-m-d");
        $userArray['userId'] = $userId;
        $userArrayUpdate['verificationCode'] = substr(md5($userArray['emailAddress'] . time()), 0, 19);
        $userArrayUpdate['verificationCodeSentDate'] = date("Y-m-d");


        if (!$this->sendVerficationEmail($userArray)) {
            echo json_encode(array('error' => 0, 'msg' => 'Mail sending failed!'));
        } else {
            echo json_encode(array('error' => 1, 'msg' => 'Mail sent!'));
        }

        $where = array("id" => $userId);

        $this->users->updateTable($userArrayUpdate, $where, 1);
        $this->session->unset_userdata('logged_in'); //unset login session
        return true;
    }

    function verification()
    {
        $this->load->view("users/emailVerification");
    }

    public function regSuccess()
    {
        $this->load->view('users/regSuccess');
    }

    public function sendVerficationEmail($userData)
    { // send email address verification email
        $content = $this->load->view('users/validationEmail', $userData, TRUE);
        $this->load->library('mymailer'); // this lilbrary includes a third party phpmailer class
        $this->config->load('email');
        $email = $this->config->item('email');
        $mail = new PHPMailer();
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = $email['Timeout'];
        $mail->SMTPDebug = $email['SMTPDebug']; // enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
        $mail->SMTPSecure = $email['SMTPSecure'];
        $mail->SMTPAuth = $email['SMTPAuth']; // enable SMTP authentication
        $mail->Host = $email['Host']; // sets the SMTP server
        $mail->Port = $email['Port']; // set the SMTP port for the GMAIL server
        $mail->Username = $email['Username']; // SMTP account username
        $mail->Password = $email['Password']; // SMTP account password
        $mail->SetFrom('support@rankalytics.com', 'Support');
        $mail->AddReplyTo("support@rankalytics.com", "Support");
        $mail->Subject = "Email Address verification on rankalytics.com";
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->Timeout = 60;
        // additional ends
        $mail->MsgHTML($content);
        $address = $userData['emailAddress'];

        $name = $userData['firstName'] . " " . $userData['lastName'];
        $mail->AddAddress($address, $name);
        if (!$mail->Send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            //echo "Message sent!";
            return true;
        }
        return;
    }

    public function verifyEmail($userId, $code)
    {
        $update = $this->users->verifyUser($userId, $code);
        if ($update) {
            //echo "verified";
            header("Location: http://rankalytics.com/verifysuccess");
            die();
            // needs to be chjanged with the proper view file showing appropriate message
        } else {
            header("Location: http://rankalytics.com/verifyfail");
            die();
            //echo "error in verificiation";
            // needs to be chjanged with the proper view file showing appropriate message
        }
    }

    /* Registration related end */

    /* login related */
    public function login()
    {
        $requestMethod = $this->input->server('REQUEST_METHOD');
        if (strtolower($requestMethod) != 'post') {
            redirect('ranktracker');
        }

        $login_data = $this->validateLoginFields();
        if ($login_data['error'] == 1) {
            exit(json_encode($login_data));
        }

        //defaults:
        $out = array(
            "error" => 1,
            'message' => '',
            'redirect_to' => '/',
        );

        // check credentials:
        trim($login_array['emailAddress'] = $this->input->post('loginemail'));
        trim($login_array['password'] = $this->input->post('loginpassword'));
        $login_array['remember'] = $this->input->post('remember');
        $isLoggedin = $this->users->doLogin($login_array);
        if ($isLoggedin !== 'valid') {
            $out['message'] = 'Error! Your login credentials were invalid!';
            exit(json_encode($out));
        }

        //info from session:
        $tempHolder = $this->session->userdata('logged_in');
        $user = $tempHolder[0];

        // check if verified:
        if (is_null($user['status'])) {
            $out['message'] = 'Your account is not yet verified with us.<br/>Please verify your account or <a href="javascript:;" onclick="resendVerification();">click here</a> to resend verification link.';
            exit(json_encode($out));
        }

        // check subscription/s:
        $out['redirect_to'] = '/modules';
        $out['error'] = 0;
        $acc_type = $this->subscriptions->getAccPlan($user['id']);
        if ($acc_type != false AND $acc_type !== 'free') {
            //..
        }

        exit( json_encode($out) );
    }

    public function validateLoginFields()
    {
        $msg = "";
        $error = 0;
        $error_ids = array();
        if (trim($email = $this->input->post('loginemail')) == "") {
            $error_ids[] = "loginemail";
            $error = 1;
        }
        if (trim($name = $this->input->post('loginpassword')) == "") {
            $error_ids[] = "loginpassword";
            $error = 1;
        }

        return array("msg" => $msg, "ids" => $error_ids, "error" => $error);
    }

    /* validate settings page forms */
    public function saveSection()
    {
        $this->load->library('form_validation');
        $error = 0;
        $error_msg = array();
        if ($this->input->post('section') == 'emailPassword') {
            if ($this->input->post('password') != '') {
                $this->form_validation->set_rules('password', 'Password', 'matches[confirmPassword]');
                $this->form_validation->set_rules('confirmPassword', 'Password Confirmation');
            }

            $this->form_validation->set_rules('emailAddress', 'Email', 'required|valid_email');
            if ($this->form_validation->run() == FALSE) {

                if ($this->input->post('password') != '') {
                    if (form_error('password') != '') {
                        $error_msg['password'] = form_error('password');
                        $error = 1;
                    }
                    if (form_error('confirmPassword') != '') {
                        $error_msg['confirmPassword'] = form_error('confirmPassword');
                        $error = 1;
                    }
                }
                if (form_error('emailAddress') != '') {
                    $error_msg['emailAddress'] = form_error('emailAddress');
                    $error = 1;
                }
                echo json_encode(array('error' => 1, 'msg' => $error_msg));
            } else {
                $user = $this->session->userdata('logged_in');
                $userid = $user['0']['id'];
                $user_arr['emailAddress'] = $this->input->post('emailAddress');
                if ($this->input->post('password') != '') {
                    $user_arr['password'] = md5($this->input->post('password'));
                }
                $this->users->updateTable($user_arr, $where = array('id' => $userid), 1);
                echo json_encode(array('error' => 0, 'msg' => 'Changes Saved'));
            }
        } elseif ($this->input->post('section') == 'companyInfo') {
            $this->form_validation->set_rules($this->users->_validation_companyInfo);
            if ($this->form_validation->run() == FALSE) {
                if (form_error('companyName') != '') {
                    $error_msg['companyName'] = form_error('companyName');
                    $error = 1;
                }
                echo json_encode(array('error' => 1, 'msg' => $error_msg));
            } else {
                $user = $this->session->userdata('logged_in');
                $userid = $user['0']['id'];

                $user_company = $this->input->post('mainId');
                if ($user_company >= 1) {
                    $userid = $user_company;
                }

                $user_arr['companyName'] = $this->input->post('companyName');
                if ($this->input->post('companyLogo') != '')
                    $user_arr['companyLogo'] = $this->input->post('companyLogo');
                $this->users->updateTable($user_arr, $where = array('id' => $userid), 1);
                echo json_encode(array('error' => 0, 'msg' => 'Changes Saved'));
            }
        } elseif ($this->input->post('section') == 'userInfo') {
            $this->form_validation->set_rules($this->users->_validation_userInfo);
            if ($this->form_validation->run() == FALSE) {
                if (form_error('firstName') != '') {
                    $error_msg['firstName'] = form_error('firstName');
                    $error = 1;
                }
                if (form_error('lastName') != '') {
                    $error_msg['lastName'] = form_error('lastName');
                    $error = 1;
                }
                if (form_error('phoneNumber') != '') {
                    $error_msg['phoneNumber'] = form_error('phoneNumber');
                    $error = 1;
                }
                if (form_error('streetAddress') != '') {
                    $error_msg['streetAddress'] = form_error('streetAddress');
                    $error = 1;
                }
                if (form_error('city') != '') {
                    $error_msg['city'] = form_error('city');
                    $error = 1;
                }
                if (form_error('zipCode') != '') {
                    $error_msg['zipCode'] = form_error('zipCode');
                    $error = 1;
                }
                if (form_error('vatNumber') != '') {
                    $error_msg['vatNumber'] = form_error('vatNumber');
                    $error = 1;
                }
                if (form_error('country') != '') {
                    $error_msg['country'] = form_error('country');
                    $error = 1;
                }
                echo json_encode(array('error' => 1, 'msg' => $error_msg));
            } else {
                $user = $this->session->userdata('logged_in');
                $userid = $user['0']['id'];
                $user_arr['firstName'] = $this->input->post('firstName');
                $user_arr['lastName'] = $this->input->post('lastName');
                $user_arr['phoneNumber'] = $this->input->post('phoneNumber');
                $user_arr['streetAddress'] = $this->input->post('streetAddress');
                $user_arr['city'] = $this->input->post('city');
                $user_arr['zipCode'] = $this->input->post('zipCode');
                $user_arr['vatNumber'] = $this->input->post('vatNumber');
                $user_arr['country'] = $this->input->post('country');
                $this->users->updateTable($user_arr, $where = array('id' => $userid), 1);
                echo json_encode(array('error' => 0, 'msg' => 'Changes Saved'));
            }
        } else if ($this->input->post('section') == 'billingInfo') {
            $paymentType = $this->input->post("paymentType");
            switch ($paymentType) {
                case 'CC':
                    $this->form_validation->set_rules($this->users->_validation_billingInfo);
                    if ($this->form_validation->run() == FALSE) {
                        if (form_error('cardHolderName') != '') {
                            $error_msg['cardHolderName'] = form_error('cardHolderName');
                            $error = 1;
                        }
                        if (form_error('creditCardNumber') != '') {
                            $error_msg['creditCardNumber'] = form_error('creditCardNumber');
                            $error = 1;
                        }
                        if (form_error('expireMonth') != '') {
                            $error_msg['expireMonth'] = form_error('expireMonth');
                            $error = 1;
                        }
                        if (form_error('expireYear') != '') {
                            $error_msg['expireYear'] = form_error('expireYear');
                            $error = 1;
                        }
                        if (form_error('cvvCvc') != '') {
                            $error_msg['cvvCvc'] = form_error('cvvCvc');
                            $error = 1;
                        }
                        echo json_encode(array('error' => 1, 'msg' => $error_msg));
                    } else {
                        $user = $this->session->userdata('logged_in');
                        $userid = $user['0']['id'];
                        $user_arr['cardHolderName'] = $this->input->post('cardHolderName');
                        $user_arr['creditCardNumber'] = substr($this->input->post('creditCardNumber'), 12, 4); //$this->input->post('creditCardNumber');
                        $user_arr['expireMonth'] = $this->input->post('expireMonth');
                        $user_arr['expireYear'] = $this->input->post('expireYear');
                        $user_arr['paymentType'] = $paymentType;
                        $user_arr['cvvCvc'] = $this->input->post('cvvCvc');
                        $user_arr['accountType'] = $this->input->post('accountType');

                        //check for SeoCrawl:
                        if (!isset($_POST["seocrawl"]) and $this->input->post("seocrawl") !== 'yes') {
                            //not for seocrawl:
                            $this->users->updateTable($user_arr, $where = array('id' => $userid), 1);
                        } else {
                            //for seocrawl:
                            $user_arr['user_id'] = $userid;
                            $user_arr['payment_for'] = 'seocrawl';
                            $this->subscriptions->doSave($user_arr, $userid);
                        }

                        $user_info_arr = $this->users->getUserById($userid);
                        $user_info = $user_info_arr['0'];

                        $this->load->model("paymill_model", "paymill");
                        $result = $this->paymill->new_subscription($userid);
                        if (isset($result['error']) && $result['error'] == 0) {
                            $this->load->model('email_model');
                            $email = array("support" => $user_info['emailAddress']);
                            $subject = $user_arr['accountType'] . " account susbscription successful";
                            $contents = $this->load->view("general_email/subscription_successful", array(), TRUE); //"Contact request message ";
                            $email_result = $this->email_model->send($email, $subject, $contents);
                        }

                        echo json_encode($result);
                    }
                    break;
                case "ELV":
                    $user = $this->session->userdata('logged_in');
                    $userid = $user['0']['id'];
                    $user_arr['ELV_bankIdentificationNumber'] = $this->input->post('ELV_bankIdentificationNumber');
                    $user_arr['ELV_accountNumber'] = $this->input->post('ELV_accountNumber');
                    $user_arr['ELV_accountHolderName'] = $this->input->post('ELV_accountHolderName');
                    $user_arr['paymentType'] = $paymentType;
                    $user_arr['accountType'] = $this->input->post('accountType');

                    //check for SeoCrawl:
                    if (!isset($_POST["seocrawl"]) and $this->input->post("seocrawl") !== 'yes') {
                        //not for seocrawl:
                        $this->users->updateTable($user_arr, $where = array('id' => $userid), 1);
                    } else {
                        //for seocrawl:
                        $user_arr['user_id'] = $userid;
                        $user_arr['payment_for'] = 'seocrawl';
                        $this->subscriptions->doSave($user_arr, $userid);
                    }

                    $user_info_arr = $this->users->getUserById($userid);
                    $user_info = $user_info_arr['0'];

                    $this->load->model("paymill_model", "paymill");
                    $result = $this->paymill->new_subscription($userid);
                    if (isset($result['error']) && $result['error'] == 0) {
                        $this->load->model('email_model');
                        $email = array("support" => $user_info['emailAddress']);
                        $subject = $user_arr['accountType'] . " account susbscription successful";
                        $contents = $this->load->view("general_email/subscription_successful", array(), TRUE); //"Contact request message ";
                        $email_result = $this->email_model->send($email, $subject, $contents);
                    }

                    echo json_encode($result);
                    break;
                default:
                    echo json_encode(array('error' => 1, 'msg' => "Invalid payment type"));
                    break;

            }
            // SWITCH
        }

    }// Function saveSection

    /**
     * getUserDetails()
     *
     * @desc for retrieving users details like name,emails and user history etc
     * @paramemters accepts userid(compulsory) from post method. No get parameter
     * @author Sudhir
     * @access public
     *
     */
    function getUserDetails()
    {
        if (!$curuserId = $this->users->isLoggedIn()) {

        }
        $user = $this->users->getUserById($curuserId); // looged in users deetails

        $userid = $this->input->post('id'); // requested user details
        $details = $this->users->getUserDetails($userid);
        $content['details'] = $this->load->view('ranktracker/admin/user_details', array("details" => $details, "curuser" => $user['0']), true);
        $content['history'] = $this->load->view('ranktracker/admin/user_history', array("details" => $details), true);

        echo json_encode($content);
    }

    function upgradeUser()
    {
        $id = $this->input->post('id');
        $upgradeFor = $this->input->post('upgradeFor');
        ///$date = new DateTime();
        $allowed_upto = date('Y-m-d', strtotime("+{$upgradeFor} days"));
        $user_arr = array("pro_allowed" => "yes", "allowed_upto" => $allowed_upto);
        $this->users->updateTable($user_arr, array("id" => $id), 1);
        // saving in user_account_logs
        $this->load->model("user_account_logs_model", "user_account_logs");
        $user_account_logs['action_taken'] = "Pro Membership Enabled";
        $user_account_logs['action_by'] = "admin";
        $user_account_logs['description'] = "Pro membership enabled upto {$allowed_upto}";
        $user_account_logs['user_id'] = "{$id}";
        $user_id = $this->user_account_logs->save($user_account_logs);
        if ($user_id >= 1) {
            echo json_encode(array("error" => 0, "msg" => "Member Upgraded"));
        } else {
            echo json_encode(array("error" => 1, "msg" => "Some Problem upgrading the account"));
        }
    }

    function uploadLogo()
    {
        $requestMethod = $this->input->server('REQUEST_METHOD');
        if (strtolower($requestMethod) != 'post') {
            redirect('ranktracker');
        }
        $id = $this->input->post('userid');
        $config['upload_path'] = './uploads/logos/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload()) {
            $error = array('error' => $this->upload->display_errors());
            $uploadData['msg'] = $error['error'];
            $uploadData['isError'] = 1;
            //$this->load->view("users/ajaxIframeMessage",$uploadData);
        } else {
            $data = array('upload_data' => $this->upload->data());
            $this->load->library('image_lib'); // image library for resizing the image for thumbnail
            $config2['image_library'] = 'gd2';
            $config2['source_image'] = $data['upload_data']['full_path'];
            $config2['maintain_ratio'] = TRUE;
            $config2['width'] = 120;
            $config2['height'] = 120;
            $config2['new_image'] = './uploads/logos/thumbnails/' . $data['upload_data']['file_name'];
            $this->image_lib->initialize($config2);
            if (!$data2 = $this->image_lib->resize()) {
                $error = array('error' => $this->image_lib->display_errors());
                $uploadData['msg'] = "Could Not crete Thumbnail" . $error['error'];
                $uploadData['isError'] = 1;
            } else {

                $this->users->updateTable(array("companyLogo" => $data['upload_data']['file_name']), array("id" => $id), 1);
                $uploadData['msg'] = $data['upload_data']['file_name'];
                $uploadData['isError'] = 0;
            }
        }
        $this->load->view("users/ajaxIframeMessage", $uploadData);
    }

// Upload Logo

    public function deleteLogo()
    {
        $id = $this->input->post('id');
        $ret_array['error'] = 0;
        $ret_array['msg'] = "";
        $userdata = $this->session->userdata('logged_in');
        $userdata = $userdata[0];

        if ($userdata['userRole'] != 'admin' && $userdata['id'] != $id) {
            $ret_array['error'] = 1;
            $ret_array['msg'][] = "You are not authorised to delete this Logo";
            echo json_encode($ret_array);
            return;
        }
        if ($ret_array['error'] == 0) {
            $user = $this->users->getUserById($id);
            $logoPath = 'uploads/logos/';
            $thumbnailPath = 'uploads/logos/thumbnails/';
            $this->load->helper('file');
            $companyLogo = $user[0]['companyLogo'];

            if (!$msgs[] = unlink($logoPath . $companyLogo)) {
                $ret_array['error'] = 1;
                $ret_array['msg'][] = "Could not delete the logo File";
            }
            if (!$msgs[] = unlink($thumbnailPath . $companyLogo)) {
                $ret_array['error'] = 1;
                $ret_array['msg'][] = "Could not delete the Thumbnail File";
            }

            if ($ret_array['error'] == 0) {
                $this->users->updateTable(array("companyLogo" => ''), array("id" => $id), 1);
                $ret_array['msg'][] = "Logo Deleted.";
            }

        }


        echo json_encode($ret_array);
        return;

    }

// delete LOGO

    public function closeAccount()
    {
        $id = $this->input->post('id');
        $ret_array['error'] = 0;
        $ret_array['msg'] = "";
        $userdata = $this->session->userdata('logged_in');
        $userdata = $userdata[0];
        //print_r($userdata);
        if ($userdata['userRole'] != 'admin' && $userdata['id'] != $id) {
            $ret_array['error'] = 1;
            $ret_array['msg'][] = "You are not authorised to close this account";
            echo json_encode($ret_array);
            return;
        }
        if ($ret_array['error'] == 0) {
            $this->users->updateTable(array("status" => '5'), array("id" => $id), 1); // status 5 = closed account
            //$ret_array['error']=1;
            // saving in history table
            $by = $this->input->post('by');
            if ($by == '') {
                $by = 'user';
            }

            // saving in history table
            $ret_array['msg'][] = "Account Closed. Redirecting...";
            // saving in user_account_logs
            $this->load->model("user_account_logs_model", "user_account_logs");
            $user_account_logs['action_taken'] = "Account Closed";
            $user_account_logs['action_by'] = "{$by}";
            $user_account_logs['description'] = "Account Closed";
            $user_account_logs['user_id'] = "{$id}";
            $this->user_account_logs->save($user_account_logs);

            echo json_encode($ret_array);
        }
    } // Close account

    public function cancel_subscription($id = 0)
    {
        if ($id == 0)
            $userId = $this->input->post('id');
        else {
            $userId = $id;
        }

        if (!$this->users->isAdmin()) {
            if (!$loggedInUser = $this->users->isLoggedIn() || $loggedInUser != $userId) {
                $error_msg[] = "You are not authorised to cancel this subscription";
                echo json_encode(array("error" => 1, "msg" => $error_msg));
                return;
            }
        }


        $this->load->model('paymilltransactions_model', 'paymilltransactions');
        $subscription_arr = $this->paymilltransactions->getSubscriptionByUserid($userId);
        if (isset($subscription_arr['0'])) {
            $subscription = $subscription_arr[0];
            $subscription_id = $subscription['subscription_id'];
            $this->load->library('paymill');
            $subscription_response = $this->paymill->remove_subscription($subscription_id);
            if (isset($subscription_response['error'])) {
                echo json_encode(array("error" => 1, "msg" => $subscription_response['error']));
            } else {
                $subscription_update['canceled_at'] = $subscription_response['canceled_at'];
                $user_update['isPaid'] = "no";
                $this->users->updateTable($user_update, array("id" => $userId), 1);
                $user_info_arr = $this->users->getUserById($userId);
                $user_info = $user_info_arr['0'];
                $this->paymilltransactions->updateTable($subscription_update, array("subscription_id" => $subscription_id, "user_id" => $userId));
                $this->load->model('user_account_close_history_model', 'user_account_close_history', true);
                $by = $this->input->post('by');
                if ($by == '') {
                    $by = "user";
                }
                // saving in user_account_logs
                $this->load->model("user_account_logs_model", "user_account_logs");
                $user_account_logs['action_taken'] = "Subscription Canceled";
                $user_account_logs['action_by'] = "{$by}";
                $user_account_logs['description'] = "Pro account susbscription canceled";
                $user_account_logs['user_id'] = "{$id}";
                $this->user_account_logs->save($user_account_logs);

                $this->load->model('email_model');

                $email = array("support" => $user_info['emailAddress']);
                $subject = "Pro account susbscription canceled";
                $contents = $this->load->view("general_email/subscription_cancel", array(), TRUE); //"Contact request message ";
                $email_result = $this->email_model->send($email, $subject, $contents);
                echo json_encode(array("error" => 0, "msg" => "Subscription Canceled"));
                return;
            }
        } else {
            echo json_encode(array("error" => 1, "msg" => "Subscription not found"));
            return;
        }

    }

    public function proMembershipSave()
    {
        $requestMethod = $this->input->server('REQUEST_METHOD');
        if (strtolower($requestMethod) != 'post') {
            redirect('ranktracker');
        }
        $error = 0;

        $error_msg = array();
        $id = $this->input->post("id");
        if ($id == 0 || $id == '') {
            $error_msg[] = 'Error Occured! Try again later';
            $error = 1;
            echo json_encode(array('error' => $error, 'msg' => $error_msg));
        }
        if (!$userId = $this->users->isLoggedIn()) {
            $error_msg[] = "User not logged in";
            $error = 1;
            echo json_encode(array('error' => $error, 'msg' => $error_msg));
            return;
        }
        if ($userId != $id) {
            $error_msg[] = 'Error Occured! You are not authorised to make changes in this account';
            $error = 1;
            echo json_encode(array('error' => $error, 'msg' => $error_msg));
            return;
        }

        $paymentType = $this->input->post("paymentType");

        $this->form_validation->set_rules($this->users->_validation_userInfo);

        if ($this->form_validation->run() == FALSE) {
            if (form_error('firstName') != '') {
                $error_msg['firstName'] = form_error('firstName');
                $error = 1;
            }
            if (form_error('lastName') != '') {
                $error_msg['lastName'] = form_error('lastName');
                $error = 1;
            }
            if (form_error('phoneNumber') != '') {
                $error_msg['phoneNumber'] = form_error('phoneNumber');
                $error = 1;
            }
            if (form_error('streetAddress') != '') {
                $error_msg['streetAddress'] = form_error('streetAddress');
                $error = 1;
            }
            if (form_error('city') != '') {
                $error_msg['city'] = form_error('city');
                $error = 1;
            }
            if (form_error('zipCode') != '') {
                $error_msg['zipCode'] = form_error('zipCode');
                $error = 1;
            }
            if (form_error('vatNumber') != '') {
                $error_msg['vatNumber'] = form_error('vatNumber');
                $error = 1;
            }
            if (form_error('country') != '') {
                $error_msg['country'] = form_error('country');
                $error = 1;
            }

        } else {
            $user_array['firstName'] = $this->input->post('firstName');
            $user_array['lastName'] = $this->input->post('lastName');
            $user_array['phoneNumber'] = $this->input->post('phoneNumber');
            $user_array['streetAddress'] = $this->input->post('streetAddress');
            $user_array['city'] = $this->input->post('city');
            $user_array['zipCode'] = $this->input->post('zipCode');
            $user_array['vatNumber'] = $this->input->post('vatNumber');
            $user_array['country'] = $this->input->post('country');

            $user_array['accountType'] = $this->input->post('accountType');
            $user_array['paymentType'] = $paymentType;

            $where = array("id" => $id);
            if (!$this->users->updateTable($user_array, $where)) {
                $error_msg[] = "Error while saving the data. Please try again later";
            } else {
                $this->load->model("paymill_model", "paymill");
                $result = $this->paymill->new_subscription($userId);
                if (isset($result['error']) && $result['error'] == 0) {
                    $user_info_arr = $this->users->getUserById($userId);
                    $user_info = $user_info_arr['0'];
                    $this->load->model('email_model');
                    $email = array("support" => $user_info['emailAddress']);
                    $subject = "Pro account susbscription successful";
                    $contents = $this->load->view("general_email/subscription_successful", array(), TRUE); //"Contact request message ";
                    if ($this->input->server('SERVER_NAME') != 'rankanalytics')
                        $email_result = $this->email_model->send($email, $subject, $contents);
                }
            }
        }
        echo json_encode(array('error' => $error, 'msg' => $error_msg));
    }

    function deleteAccountByAdmin($id = 0)
    {
        if ($id == 0) {
            $id = $this->input->post('id');
        }
        if (!$this->users->isAdmin()) {
            echo json_encode(array("error" => 0, "msg" => "You are not allowed to delete this user"));
            return;
        }
        $user = $this->users->getUserById($id);
        if ($user['0']['isPaid'] == 'yes') {
            $this->cancel_subscription($id);
        }

        if ($this->users->delete(array("id" => $id), 1)) {
            $this->users->delete(array("mainId" => $id)); //  delete all user haveing mainId as deleted user id
            echo json_encode(array("error" => 0, "msg" => "User has been deleted successfully"));
        } else {
            echo json_encode(array("error" => 1, "msg" => "There is some problem in deleting user"));
        }
    }

    function deleteAccountByUser($id = 0)
    {
        if ($id == 0) {
            $id = $this->input->post('id');
        }
        if (!$userId = $this->users->isLoggedIn()) {
            echo json_encode(array("error" => 1, "msg" => "Log in to delete the user."));
        }
        if ($this->users->delete(array("id" => $id, "mainId" => $userId), 1)) {
            echo json_encode(array("error" => 0, "msg" => "User has been deleted successfully"));
        } else {
            echo json_encode(array("error" => 1, "msg" => "There is some problem in deleting user"));
        }
    }

    public function addsubuser()
    {
        $isValidated = $this->validateSubUser();
        if ($isValidated['error'] >= 1) {
            echo json_encode($isValidated);
        } else {
            $mainuser = $this->session->userdata('logged_in');
            $id = $mainuser['0']['id'];
            $userArray['emailAddress'] = $this->input->post('emailAddress');
            $userArray['userName'] = $this->input->post('username');
            $userArray['password'] = md5($this->input->post('password'));

            $query = $this->db->query('SELECT accountType ,isPaid FROM users where id="' . $id . '"');
            $acctyep = $query->result_array();
            $user = $this->users->getUserById($id);
            $userArray['mainId'] = $id;
            $userArray['accountType'] = $user['0']['accountType'];
            /*            $isPaid1=$acctyep['0']['isPaid'];
                        if($isPaid1==0){$isPaid="no";}else{$isPaid="yes";}*/
            $userArray['isPaid'] = 1;
            $userArray['userRole'] = "subuser";
            $userArray['status'] = "1";
            $userId = $this->users->save($userArray);
            $userArray['userId'] = $userId;

            // saving in user_account_logs
            $this->load->model("user_account_logs_model", "user_account_logs");
            $user_account_logs['action_taken'] = "Account Created";
            $user_account_logs['action_by'] = "user";
            //$userType = $isPaid=="no"?"BASIC":"PRO";
            $userType = $userArray['accountType'];
            $user_account_logs['description'] = "Registration as {$userType} by user";
            $user_account_logs['user_id'] = "{$userId}";
            $this->user_account_logs->save($user_account_logs);
            $msg = "";
            $error = 1;
            $error_ids = array();
            $error_ids[] = "success";
            echo json_encode(array("msg" => $msg, "ids" => $error_ids, "error" => $error));


        }
    }

    function validateSubUser()
    {
        $msg = "";
        $error = 0;
        $error_ids = array();


        if (trim($email = $this->input->post('emailAddress')) == "") {
            $error_ids[] = "emailAddress";
            $error = 1;
        }
        if ($this->users->isUserExists(array("emailAddress" => $email))) {
            $error_ids[] = "emailAddressExists";
            $error = 1;
        }
        if (trim($userName = $this->input->post('username')) == "") {
            $error_ids[] = "userName";
            $error = 1;
        }
        if ($this->users->isUserExists(array("userName" => $userName))) {
            $error_ids[] = "userNameExists";
            $error = 1;
        }
        if (trim($name = $this->input->post('password')) == "") {
            $error_ids[] = "password";
            $error = 1;
        }
        return array("msg" => $msg, "ids" => $error_ids, "error" => $error);
    }

    public function index()
    {
        redirect('/users/settings');
    }

    /**
     * settings()
     *
     * @desc settings function for Ranktracker
     * calls on /users/settings
     * @author Sudhir
     * @access public
     *
     */

    public function settings()
    {
        $user = $this->session->userdata('logged_in');
        if ((!isset($user['0']['id']) || $user['0']['id'] == 0 || $user['0']['id'] == '') or !$this->users->isVerified($user['0']['id'])) {
            redirect('/');
        }

        $userId = $user['0']['id'];
        $user_array = $this->users->getUserById($userId);
        $data['user_database'] = $user_array['0'];
        $data['user_database']['userType'] = $this->users->getUserType($user_array['0']);

        if ($data['user_database']['userRole'] == 'subuser') {
            $mainuser = $this->users->getUserById($data['user_database']['mainId']);

            $data['user_database']['companyLogo'] = $mainuser['0']['companyLogo'];
            $data['user_database']['companyName'] = $mainuser['0']['companyName'];
        }

        // fetch subscriptions information:
        $i = 0;
        $data['current_options'] = array();
        foreach($this->_service_prices as $service => $null) {
            // 'internal' info:
            $data[$service] = $this->subscriptions->getInfoByIdNType($userId, $service);
            $data[$service]['limit'] = $this->_service_limits[$service][$data[$service]['plan']]['text'];

            //..
            $started_on = $data[$service]['started_on'];
            $plus_days = $data[$service]['months'] * $this->_month_days;
            $data[$service]['expires_on'] = date('Y-m-d', strtotime($started_on. ' + '.$plus_days.' days'));

            // 'JavaScript' info:
            $data['current_options'][$i] = array(
                'service' => ucfirst($service),
                'plan' => ucfirst($data[$service]['plan']),
                'pType' => ucfirst($data[$service]['payment_type']),
            );
            $i++;
        }

        // fetch current status of services:


        // ..
        $data['current_options'] = json_encode($data['current_options']);
        $data['current'] = 'dashboard';
        $data['meta_title'] = 'Rankalytics Settings';

        $this->load->view('dashboard/settings', $data);
    }

    public function search()
    {
        if (!$userId = $this->users->isloggedIn()) {
            redirect("ranktracker");
            return false;
        }

        $userId = $this->users->isloggedIn();
        $user_array = $this->users->getUserById($userId);

        if ("yes" != $user_array[0]['isPaid'] || $user_array[0]['userRole'] == 'subuser') {
            $this->load->view('dashboard/users', array("error" => "You are not authorised to vew this page", "notpro" => 1));
        } else {
            $isAjax = $this->input->post("isAjax");
            $this->load->library('pagination');
            $this->config->load('paginationConfig');
            $paginationConfig = $this->config->item('paginationConfig'); // Taking default config values for pagination
            $paginationConfig['base_url'] = base_url() . 'ranktracker/users/';
            $paginationConfig['uri_segment'] = 3;
            $searchString = $this->input->post('searchString');

            $total = $this->users->getUsersCount($searchString, $mainUser = $userId);

            $paginationConfig['total_rows'] = $total;
            $startFrom = $this->uri->segment($paginationConfig['uri_segment']);
            if ($startFrom == '') {
                $startFrom = 0;
            }
            $paginationConfig['per_page'] = 10;
            $limit = array($startFrom, $paginationConfig['per_page']);
            $this->data['users'] = $this->users->getUsers($searchString, $limit, $mainUser = $userId);

            $this->pagination->initialize($paginationConfig);
            if ($isAjax == 1) {
                $user_html = $this->load->view('dashboard/userlist', $this->data, true);
                echo json_encode(array("error" => 0, "html" => $user_html, "pagination" => $this->pagination->create_links()));
            } else {
                $this->load->view('dashboard/users', $this->data);
            }
        }
    }

    public function invoices()
    {
        if (!$userid = $this->users->isLoggedIn()) {
            redirect("ranktracker");
            return false;
        }
        //error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
        //$userid= $user['0']['id'];
        $user_array = $this->users->getUserById($userid);
        $data['user_database'] = $user_array['0'];
        $payment_arr = array();
        if ($data['user_database']['isPaid'] == 'yes') {
            $this->load->model('paymilltransactions_model', 'paymilltransactions', true);
            $where = array("user_id" => $userid, "canceled_at" => "");
            $subscription = $this->paymilltransactions->getSubscriptionByUserid($userid);
            $data['user_database']['subscription'] = $subscription['0'];
            //print_r($subscription['0']);
            //die();
            $client_id = $data['user_database']['subscription']['paymill_client_id'];
            $subscription_id = $data['user_database']['subscription']['subscription_id'];
            $offer_id = $data['user_database']['subscription']['offerId'];
            $this->load->model('webhook_payments_model', 'webhook_payments', true);
            $payments = $this->webhook_payments->getPaymentsByClientId($client_id);
            if (is_array($payments) && count($payments) >= 1) {
                $count = 0;
                foreach ($payments as $payment) {
                    $this->load->model('webhook_transactions_model', 'webhook_transactions', true);
                    $transactions = $this->webhook_transactions->getTransactionByPaymentId($payment['payment_id']);
                    $payment_arr[$count]['last4'] = $payment['last4'];
                    $payment_arr[$count]['created_at'] = $payment['created_at'];
                    $payment_arr[$count]['amount'] = $transactions['0']['amount'];
                    $count++;
                }
            }
            $data['payment_arr'] = $payment_arr;
        } else {
            $data['user_database']['subscription'] = '';
            $data['payment_arr'] = array();
        }
        $data['current'] = 'dashboard';
        //$this->load->view('dashboard/settings',$data);
        $this->load->view("dashboard/invoices", $data);
    }

    public function downloadInvoicereport()
    {
        $user = $this->session->userdata('logged_in');
        if (!isset($user['0']['id']) || $user['0']['id'] == 0 || $user['0']['id'] == '') { // redirect to rantracker if not logged in
            redirect('ranktracker');
            return;
        }
        //error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
        $userid = $user['0']['id'];
        $user_array = $this->users->getUserById($userid);
        $data['user_database'] = $user_array['0'];
        if ($data['user_database']['isPaid'] == 'yes') {
            $this->load->model('paymilltransactions_model', 'paymilltransactions', true);
            $where = array("user_id" => $userid, "canceled_at" => "");
            $subscription = $this->paymilltransactions->getSubscriptionByUserid($userid);
            $data['user_database']['subscription'] = $subscription['0'];

            $client_id = $data['user_database']['subscription']['paymill_client_id'];
            $subscription_id = $data['user_database']['subscription']['subscription_id'];
            $offer_id = $data['user_database']['subscription']['offerId'];
            $this->load->model('webhook_payments_model', 'webhook_payments', true);
            $payments = $this->webhook_payments->getPaymentsByClientId($client_id);
            if (is_array($payments) && count($payments) >= 1) {
                $count = 0;
                foreach ($payments as $payment) {
                    $this->load->model('webhook_transactions_model', 'webhook_transactions', true);
                    $transactions = $this->webhook_transactions->getTransactionByPaymentId($payment['payment_id']);
                    $payment_arr[$count]['last4'] = $payment['last4'];
                    $payment_arr[$count]['created_at'] = $payment['created_at'];
                    $payment_arr[$count]['amount'] = $transactions['0']['amount'];
                    $count++;
                }
            }
            $data['payment_arr'] = $payment_arr;
        } else {
            $data['user_database']['subscription'] = '';
            $data['payment_arr'] = array();
        }
        $data['current'] = 'dashboard';
        //$this->load->view('dashboard/settings',$data);
        $invoice = $this->load->view("dashboard/invoicereport_forPDF", $data, true);
        $this->load->helper(array("dompdf_helper", "file"));
        $this->load->helper(array('dompdf', 'file'));
        // page info here, db calls, etc.
        //$html = $this->load->view('controller/viewfile', $data, true);

        pdf_create($invoice, 'filename');


        /*$data = pdf_create($html, '', false);
        write_file('name', $data);
        //if you want to write it to disk and/or send it as an attachment    */

    }
}// class USERS 