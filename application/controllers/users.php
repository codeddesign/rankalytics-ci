<?php if ( ! defined( 'BASEPATH' )) {
    exit( 'No direct script access allowed' );
}

/**
 * Class Users
 */
class Users extends CI_Controller
{
    private $sendMail;
    private $sub_id;
    private $ajaxGeneric;

    public function __construct()
    {
        parent::__construct();
        Subscriptions_Lib::loadConfig();

        $this->ajaxGeneric = array(
            'error' => true,
            'msg'   => 'Not logged in',
        );

        // load requirements:
        $this->load->helper( 'form' );
        $this->load->library( 'session' );
        $this->load->library( 'form_validation' );
        $this->load->model( 'users_model', 'users', true );
        $this->load->model( 'analytical_model', 'analytical', true );
        $this->load->model( 'subscriptions_model', 'subscriptions', true );
        $this->load->model( 'countries_model', 'countries', true );
        $this->pgsql = $this->load->database( 'pgsql', true );
    }

    /**
     *
     */
    public function logout()
    {
        $this->session->unset_userdata( 'logged_in' );
        redirect( 'ranktracker' );
    }

    /**
     *
     */
    public function admin()
    {
        if ( ! $userId = $this->users->isloggedIn()) {
            redirect( "ranktracker" );
        }

        if ( ! $adminUser = $this->users->isAdmin()) {
            $this->load->view( 'ranktracker/admin', array( "error" => "You are not authorised to vew this page", "notAdmin" => 1 ) );
        } else {
            // load requirements:
            $this->load->library( 'pagination' );
            $this->config->load( 'paginationConfig' );

            // post data:
            $searchString = $this->input->post( 'searchString' );
            $isAjax       = $this->input->post( "isAjax" );

            // pagination:
            $paginationConfig                = $this->config->item( 'paginationConfig' ); // Taking default config values for pagination
            $paginationConfig['base_url']    = base_url() . 'users/admin/';
            $paginationConfig['uri_segment'] = 3;
            $paginationConfig['total_rows']  = $this->users->getUsersCount( $searchString );
            $paginationConfig['per_page']    = 10;

            $startFrom = $this->uri->segment( $paginationConfig['uri_segment'] );
            $startFrom = ( $startFrom == '' ) ? 0 : $startFrom;

            $limit               = array( $startFrom, $paginationConfig['per_page'] );
            $this->data['users'] = $this->users->getUsers( $searchString, $limit, 0, array( 'by' => 'createdOn', 'how' => 'DESC' ) );
            $this->pagination->initialize( $paginationConfig );

            if ($isAjax == 1) {
                $user_html = $this->load->view( 'ranktracker/admin/userlist', $this->data, true );
                echo json_encode( array( "error" => 0, "html" => $user_html, "pagination" => $this->pagination->create_links() ) );
            } else {
                $this->load->view( 'ranktracker/admin', $this->data );
            }
        }
    }

    function approvesubscription()
    {
        $out = array(
            'error' => 1,
            'msg'   => 'Nothing to do',
        );

        $this->json_exit( $out );
    }

    /**
     * ajax pre-validation for small form - registration
     */
    public function prevalidation()
    {
        $requestMethod = $this->input->server( 'REQUEST_METHOD' );
        if (strtolower( $requestMethod ) != 'post') {
            echo json_encode( array( 'error' => 1, 'msg' => 'invalid request' ) );
            exit;
        }

        $isValidated = $this->validateUser();
        if ($isValidated['error'] >= 1) {
            echo json_encode( $isValidated );
        } else {
            $temp_data = array(
                'firstName'    => $this->input->post( 'firstName' ),
                'lastName'     => $this->input->post( 'lastName' ),
                'emailAddress' => $this->input->post( 'emailAddress' ),
                'userName'     => $this->input->post( 'userName' ),
                'password'     => md5( $this->input->post( 'password' ) ),
            );

            $this->session->set_userdata( array( 'tempData' => $temp_data ) );

            echo json_encode( array( 'error' => 0, 'redirect' => 'users/promembership' ) );
        }
    }

    /**
     * @return array
     */
    function validateUser()
    {
        $msg       = "";
        $error     = 0;
        $error_ids = array();
        if (trim( $firstname = $this->input->post( 'firstName' ) ) == "") {
            $error_ids[] = "firstName";
            $error       = 1;
        }
        if (trim( $lastname = $this->input->post( 'lastName' ) ) == "") {
            $error_ids[] = "lastName";
            $error       = 1;
        }
        if (trim( $email = $this->input->post( 'emailAddress' ) ) == "") {
            $error_ids[] = "emailAddress";
            $error       = 1;
        }
        if ($this->users->isUserExists( array( "emailAddress" => $email ) )) {
            $error_ids[] = "emailAddressExists";
            $error       = 1;
        }
        if (trim( $userName = $this->input->post( 'userName' ) ) == "") {
            $error_ids[] = "userName";
            $error       = 1;
        }
        if ($this->users->isUserExists( array( "userName" => $userName ) )) {
            $error_ids[] = "userNameExists";
            $error       = 1;
        }
        if (trim( $name = $this->input->post( 'password' ) ) == "") {
            $error_ids[] = "password";
            $error       = 1;
        }

        return array( "msg" => $msg, "ids" => $error_ids, "error" => $error );
    }

    /**
     * rest of registration - after redirect
     */
    public function promembership()
    {
        // get user info from session:
        $user_data = $this->session->all_userdata();

        // load language file:
        $this->lang->load( 'promembership' );

        // 0 check:
        $_disabled = true;
        if ( ! $_disabled and isset( $user_data['paymentStep'] )) {
            $this->load->view( 'ranktracker/promembership', array( 'paymentData' => $user_data ) );
            return false;
        }

        if ($_disabled and isset( $user_data['registered'] )) {
            $this->load->view( 'ranktracker/promembership', array( 'registered' => $user_data['registered'] ) );
            return false;
        }

        // 2 check:
        if ( ! isset( $user_data['tempData'] ) AND ( ! isset( $info_msg ) OR $info_msg == false )) {
            redirect( 'ranktracker' );
        }

        if (isset( $user_data['tempData'] )) {
            $this->data['temp']      = $user_data['tempData'];
            $this->data['countries'] = $this->countries->getAll();
        }

        $this->data['meta_title']   = "RankAlytics Pro-Membership";
        $this->data['main_content'] = 'ranktracker/promembership';

        $this->load->view( 'ranktracker/promembership', $this->data );
    }

    public function subscriptions()
    {
        if ( ! $userId = $this->users->isLoggedIn()) {
            redirect( "ranktracker" );
        }

        $user_array = $this->users->getUserById( $userId );
        $userData   = $user_array[0];

        # check if from paypal and do some work
        $this->handlePaypal( $this->session->all_userdata() );

        // fetch subscriptions information:
        $i                       = 0;
        $data['current_options'] = array();
        foreach (Subscriptions_Lib::$_service_prices as $service => $null) {
            // 'internal' info:
            $tempInfo = $this->subscriptions->getSubscriptionInfo( $userId, $service );

            // if tempInfo is not array => there's no subscription. Apply default information:
            if ( ! is_array( $tempInfo )) {
                $tempInfo = ( $userData['userRole'] == 'admin' ) ? Subscriptions_Lib::getDefaultForAdmin( $service ) : Subscriptions_Lib::getDefaultNotSubscribed( $service );
            }

            // rest of workflow:
            if (is_array( $tempInfo )) {
                $tempInfo['limit'] = Subscriptions_Lib::$_service_limits[$service][$tempInfo['plan']]['text'];

                // 'JavaScript' info:
                $data['current_options'][$i] = array(
                    'service' => ucfirst( $service ),
                    'plan'    => ucfirst( $tempInfo['plan'] ),
                    'pType'   => ucfirst( $tempInfo['payment_type'] ),
                    'isPaid'  => Subscriptions_Lib::isPaid( $service, $tempInfo['plan'] ),
                    'amount'  => Subscriptions_Lib::$_service_prices[$service][$tempInfo['plan']],
                );
                $i ++;

                //save stuff:
                $data[$service] = $tempInfo;

                // fetch current stats of services:
                if ($service == 'seocrawl') {
                    $this->load->model( 'seocrawl_campaign_model', 'seocrawl_campaign', true );
                    $data[$service]['usage_number'] = $this->seocrawl_campaign->total( $userId );
                }

                if ($service == 'ranktracker') {
                    $this->load->model( 'project_keywords_model', 'project_keywords', true );
                    $data[$service]['usage_number'] = $this->project_keywords->getNumberOfKeywordsByUser( $userId );
                }
            }
        }

        // ..
        $data['current_options'] = json_encode( $data['current_options'] );

        $data['current']       = 'subscriptions';
        $data['user_database'] = $userData;

        $this->load->view( 'dashboard/subscriptions', $data );
    }

    /**
     * @param $tempData
     *
     * @return mixed
     */
    private function saveUserAndSession( $tempData )
    {
        $userArray = array(
            'firstName'                => $this->input->post( 'firstName' ),
            'lastName'                 => $this->input->post( 'lastName' ),
            'emailAddress'             => $tempData['emailAddress'],
            'userName'                 => $tempData['userName'],
            'password'                 => $tempData['password'],
            'verificationCode'         => substr( md5( $tempData['emailAddress'] . time() ), 0, 19 ),
            'verificationCodeSentDate' => date( "Y-m-d" ),
            'userRole'                 => 'mainuser',
            'accountType'              => 'none',
            'phoneNumber'              => $this->input->post( 'phoneNumber' ),
            'streetAddress'            => $this->input->post( 'streetAddress' ),
            'city'                     => $this->input->post( 'city' ),
            'zipCode'                  => $this->input->post( 'zipCode' ),
            'vatNumber'                => '', /*$this->input->post('vatNumber'),*/
            'country'                  => $this->input->post( 'country' ),
        );

        // save user info:
        if ( ! isset( $tempData['userId'] )) {
            $this->sendMail = true;
            $userId         = $this->users->save( $userArray );
        } else {
            $this->sendMail = false;
            $userId         = $tempData['userId'];
        }

        $userArray['userId'] = $userId;
        $this->session->set_userdata( array( 'tempData' => $userArray ) );

        return $userArray;
    }

    /**
     * validate and save user + subscriptions
     */
    public function save()
    {
        header( 'Content-Type: application/json' );

        $user_data = $this->session->all_userdata();
        if (strtolower( $this->input->server( 'REQUEST_METHOD' ) ) != 'post' OR ! array_key_exists( 'tempData', $user_data )) {
            $this->json_exit(
                array(
                    'error'       => true,
                    'redirect_to' => '/ranktracker',
                )
            );
        }

        // sets & session save:
        $userArray = $this->saveUserAndSession( $user_data['tempData'] );
        $userId    = $userArray['userId'];

        // send email:
        $skip = true;
        if ($skip and $this->sendMail) {
            if ( ! $this->sendVerficationEmail( $userArray )) {
                # internal error:
                // $this->json_exit( array('error'   => true, 'message' => 'Failed sending email.') );
            }
        }

        # next step:
        $this->session->set_userdata(
            array(
                'registered' => $userArray['emailAddress']
            )
        );

        $this->session->unset_userdata( 'tempData' );

        # render next view:
        $this->json_exit( array(
            'error'       => false,
            'redirect_to' => '/users/promembership'
        ) );
    }

    /**
     * helper function to ease our work : )
     *
     * @param array $out
     */
    protected function json_exit( array $out )
    {
        header( 'Content-Type: application/json' );

        // change true/false to 1/0
        foreach ($out as $o_no => $o_val) {
            if (is_bool( $o_val )) {
                $out[$o_no] = ( $o_val ) ? 1 : 0;
            }
        }

        exit( json_encode( $out ) );
    }

    /**
     * @param $userId
     *
     * @return array|bool
     */
    private function createNewSubscription( $userId )
    {
        $services = Subscriptions_Lib::$_service_prices;

        $serviceName = trim( $this->input->post( 'serviceName' ) );
        $planName    = trim( $this->input->post( 'servicePlan' ) );
        $paymentType = trim( $this->input->post( 'paymentType' ) );

        if ( ! isset( $services[$serviceName] ) OR ! isset( $services[$serviceName][$planName] )) {
            $this->json_exit( array(
                'error' => true,
                'msg'   => 'Unknown plan/service selected',
            ) );
        }

        $this->sub_id    = 'SUB-' . ( rand( 10000, 99999 ) . '-' . substr( time(), - 6 ) );
        $subscriptions[] = array(
            'user_id'      => $userId,
            'service'      => $serviceName,
            'plan'         => $planName,
            'order_id'     => $this->sub_id,
            'status'       => 'pending',
            'payment_type' => $paymentType,
        );

        $this->session->set_userdata(
            array(
                'subscriptionId' => $this->sub_id
            )
        );

        $this->subscriptions->doSave( $subscriptions );

        if ( ! count( $subscriptions )) {
            return false;
        }

        return $subscriptions;
    }

    /**
     * Cancels a user's subscription - if active.
     *
     */
    public function cancelSubscription()
    {
        $tempInfo = $this->session->userdata( 'logged_in' );
        if ( ! $tempInfo) {
            $this->json_exit( $this->ajaxGeneric );
        }
        $tempInfo = $tempInfo[0];

        # check current subscription:
        $current    = $this->subscriptions->getSubscriptionInfo( $tempInfo['id'], $this->input->post( 'serviceName' ) );
        $externalId = $current['external_id'];
        if ( ! is_array( $current ) OR $current['status'] !== 'active') {
            $this->json_exit( array(
                'error' => true,
                'msg'   => 'You don\'t have an active subscription',
            ) );
        }

        switch ($current['payment_type']) {
            case 'stripe':
                $fromDb     = $this->users->getUserById( $tempInfo['id'] );
                $customerId = $fromDb[0]['stripe_id'];

                $this->load->library( 'stripe' );
                try {
                    $this->stripe->cancelSubscription( $customerId, $externalId );
                } catch ( Exception $e ) {
                    $this->json_exit( array(
                        'error' => true,
                        'msg'   => 'Something went wrong while canceling subscription', // for dev.: $e->getMessage()
                    ) );
                }
                break;
            case 'paypal':
                $this->load->library( 'pp' );
                $response = $this->pp->cancelAgreement( $externalId );
                if ($response['error']) {
                    $this->json_exit( $response );
                }
                break;
        }

        # update status:
        $this->subscriptions->doUpdate(
            array( 'status' => 'canceled' ),
            array( 'external_id' => $externalId )
        );

        $this->json_exit( array(
            'error' => false,
            'msg'   => 'Your subscription has been successfully canceled',
        ) );
    }

    /**
     * Creates a link for user that sends him to paypal's page
     */
    public function paypalLink()
    {
        # check if logged in:
        $tempInfo = $this->session->all_userdata();
        if ( ! isset( $tempInfo['logged_in'][0] )) {
            $this->json_exit( $this->ajaxGeneric );
        }

        $serviceAction = $this->input->post( 'serviceAction' );
        $planName      = $this->input->post( 'servicePlan' );

        # load requirements and make settings:
        $this->load->library( 'pp' );
        $this->load->model( 'ppplans_model', 'existing_plans' );

        if ($serviceAction == 'update') {
            $current = $this->subscriptions->getSubscriptionInfo( $tempInfo['logged_in'][0]['id'], $this->input->post( 'serviceName' ) );
            $externalId = $current['external_id'];

            # check if they are the same plans:
            if ($current['plan'] == $planName) {
                $this->json_exit( array(
                    'error' => true,
                    'msg'   => 'You are already subscribed to this plan',
                ) );
            }

            $response = $this->pp->cancelAgreement( $externalId );
            if ($response['error']) {
                $this->json_exit( $response );
            }

            $this->subscriptions->doUpdate(
                array( 'status' => 'canceled' ),
                array( 'external_id' => $externalId )
            );
        }

        # prepare data and save subscription to db:
        $userData      = $tempInfo['logged_in'][0];
        $subscriptions = $this->createNewSubscription( $userData['id'] );
        $subscription  = $subscriptions[key( $subscriptions )];

        # associate with paypal:
        $this->pp->setSubscription( $subscription );

        # check for existing plan:
        $planShort = My_Pp::generatePlanId( $subscription );
        $planLong  = My_pp::generatePlanName( $subscription );

        $savedPlan = $this->existing_plans->getFirstWhere( array( 'plan_short' => $planShort ) );
        if ($savedPlan !== false) {
            $this->pp->usePlanWithId( $savedPlan['id'] );
        }

        # step 1 - create billing agreement:
        $response = $this->pp->createBillingWithAccount();
        if (is_array( $response ) and $response['error']) {
            $this->subscriptions->removeBySubId( $this->sub_id );
            $this->json_exit( $response );
        }

        # step 2 - save plan if not yet saved:
        if ($savedPlan == false) {
            $this->existing_plans->doSave( array(
                'id'         => $this->pp->getSavedPlanId(),
                'plan_short' => $planShort,
                'plan_long'  => $planLong,
            ) );
        }

        # step 3 - response:
        $this->json_exit( $response );
    }

    /**
     * @param $tempInfo
     **/
    private function handlePaypal( $tempInfo )
    {
        if ( ! $this->session->userdata( 'subscriptionId' )) {
            return false;
        }

        # remove subscriptionId:
        $this->session->unset_userdata( 'subscriptionId' );

        # handle paypal's callback
        if ( ! $this->input->get( 'success' )) {
            return false;
        }

        if (strtolower( $this->input->get( 'success' ) ) !== 'true' OR ! $this->input->get( 'token' )) {
            # canceled:
            $this->subscriptions->doUpdate(
                array( 'status' => 'canceled' ),
                array( 'order_id' => $tempInfo['subscriptionId'] )
            );

            $this->session->set_userdata( array(
                'paypal_flash' => 'Your subscription has been canceled.'
            ) );

            redirect( '/users/subscriptions' );
            return false;
        }

        # step ok:
        $this->load->library( 'pp' );

        $response = $this->pp->handleAgreement( $this->input->get( 'token' ) );
        if (is_array( $response ) and $response['error']) {
            $this->session->set_userdata( array(
                    'paypal_flash' => 'Failed: ' . $response['msg'] . ' (contact support)'
                )
            );

            redirect( '/users/subscriptions' );
            return false;
        }

        # save info to db:
        $agreement = $response['agreement'];
        $this->subscriptions->doUpdate(
            array( 'status' => 'active', 'external_id' => $agreement->id ),
            array( 'order_id' => $tempInfo['subscriptionId'] )
        );

        $this->session->set_userdata( array(
                'paypal_flash' => 'Your subscription has been completed!'
            )
        );

        redirect( '/users/subscriptions' );
        return true;
    }

    public function handleStripe()
    {
        if ( ! ( $token = $this->input->post( 'stripeToken' ) )) {
            $this->json_exit( array( 'msg' => 'Nothing to do here' ) );
        }

        // sets and loads:
        $this->load->library( 'stripe' );
        $serviceAction = $this->input->post( 'serviceAction' );

        // prepare data:
        $tempInfo = $this->session->all_userdata();
        if ( ! isset( $tempInfo['logged_in'][0] )) {
            $this->json_exit( $this->ajaxGeneric );
        }
        $userData      = $tempInfo['logged_in'][0];
        $subscriptions = $this->createNewSubscription( $userData['id'] );
        $subscription  = $subscriptions[0];

        $emailAddress = $userData['emailAddress'];
        try {
            switch ($serviceAction) {
                case 'update':
                    # check current subscription:
                    $current    = $this->subscriptions->getSubscriptionInfo( $userData['id'], $this->input->post( 'serviceName' ) );
                    $externalId = $current['external_id'];
                    if ( ! is_array( $current ) OR $current['status'] !== 'active') {
                        $this->json_exit( array(
                            'error' => true,
                            'msg'   => 'You don\'t have an active subscription',
                        ) );
                    }

                    # check if they are the same plans:
                    if ($current['plan'] == $subscription['plan']) {
                        $this->subscriptions->removeBySubId( $subscription['order_id'] );

                        $this->json_exit( array(
                            'error' => true,
                            'msg'   => 'You are already subscribed to this plan',
                        ) );
                    }

                    $fromDb     = $this->users->getUserById( $userData['id'] );
                    $customerId = $fromDb[0]['stripe_id'];

                    $this->stripe->updateSubscription( $subscription, $externalId, $customerId, $token );

                    # change status for current one:
                    $this->subscriptions->doUpdate(
                        array( 'status' => 'active', 'external_id' => $externalId ),
                        array( 'order_id' => $subscription['order_id'] )
                    );

                    # change status for old one:
                    $this->subscriptions->doUpdate(
                        array( 'status' => 'updated' ),
                        array( 'order_id' => $current['order_id'] )
                    );
                    break;
                default:
                    $this->stripe->makeSubscription( $token, $subscription, $emailAddress );
                    break;
            }

            $out = array(
                'error' => false,
                'msg'   => 'Your payment has been processed. Thank you!'
            );
        } catch ( Exception $e ) {
            $out = array(
                'error' => true,
                'msg'   => $this->stripe->getExceptionNicerMessage( $e ),
            );

            $this->subscriptions->doUpdate(
                array( 'status' => 'failed', 'payment_type' => 'stripe' ),
                array( 'order_id' => $subscription['order_id'] )
            );

            $this->json_exit( $out );
        }

        if ($serviceAction !== 'update') {
            # save client's id:
            $stripe_id = $this->stripe->getCustomerId();
            $this->users->updateTable( compact( 'stripe_id' ), array( 'emailAddress' => $emailAddress ), 1 );

            # update subscription:
            $this->subscriptions->doUpdate(
                array( 'status' => 'active', 'external_id' => $this->stripe->getExternalId(), 'payment_type' => 'stripe' ),
                array( 'order_id' => $subscription['order_id'], 'service' => $subscription['service'], 'plan' => $subscription['plan'] )
            );
        }

        $this->session->unset_userdata( 'subscriptionId' );

        $this->json_exit( $out );
    }

    /**
     * re-sends email for account validation:
     * @return bool
     */
    function resendVerification()
    {
        if ( ! $userId = $this->users->isLoggedIn()) {
            redirect( "ranktracker" );
            return false;
        }

        if ( ! isset( $_GET['testMe101'] )) {
            if ($this->users->isVerified( $userId )) {
                echo "You are already verified";
                return;
            }
        }

        $user = $this->users->getUserById( $userId );

        $userArray                                   = $user['0'];
        $userArray['verificationCode']               = substr( md5( $userArray['emailAddress'] . time() ), 0, 19 );
        $userArray['verificationCodeSentDate']       = date( "Y-m-d" );
        $userArray['userId']                         = $userId;
        $userArrayUpdate['verificationCode']         = substr( md5( $userArray['emailAddress'] . time() ), 0, 19 );
        $userArrayUpdate['verificationCodeSentDate'] = date( "Y-m-d" );


        if ( ! $this->sendVerficationEmail( $userArray )) {
            echo json_encode( array( 'error' => 0, 'msg' => 'Mail sending failed!' ) );
        } else {
            echo json_encode( array( 'error' => 1, 'msg' => 'Mail sent!' ) );
        }

        $where = array( "id" => $userId );

        $this->users->updateTable( $userArrayUpdate, $where, 1 );
        $this->session->unset_userdata( 'logged_in' ); //unset login session
        return true;
    }

    /**
     *
     */
    function verification()
    {
        $this->load->view( "users/emailVerification" );
    }

    /**
     *
     */
    public function regSuccess()
    {
        $this->load->view( 'users/regSuccess' );
    }

    /**
     * @param $userData
     *
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     */
    public function sendVerficationEmail( $userData )
    { // send email address verification email
        $content = $this->load->view( 'users/validationEmail', $userData, true );
        $this->load->library( 'mymailer' ); // this lilbrary includes a third party phpmailer class
        $this->config->load( 'email' );
        $email = $this->config->item( 'email' );
        $mail  = new PHPMailer();
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->CharSet    = 'UTF-8';
        $mail->Timeout    = $email['Timeout'];
        $mail->SMTPDebug  = $email['SMTPDebug']; // enables SMTP debug information (for testing) // 1 = errors and messages // 2 = messages only
        $mail->SMTPSecure = $email['SMTPSecure'];
        $mail->SMTPAuth   = $email['SMTPAuth']; // enable SMTP authentication
        $mail->Host       = $email['Host']; // sets the SMTP server
        $mail->Port       = $email['Port']; // set the SMTP port for the GMAIL server
        $mail->Username   = $email['Username']; // SMTP account username
        $mail->Password   = $email['Password']; // SMTP account password
        $mail->SetFrom( 'support@rankalytics.com', 'Support' );
        $mail->AddReplyTo( "support@rankalytics.com", "Support" );
        $mail->Subject = "Email Address verification on rankalytics.com";
        $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
        $mail->Timeout = 60;
        // additional ends
        $mail->MsgHTML( $content );
        $address = $userData['emailAddress'];

        $name = $userData['firstName'] . " " . $userData['lastName'];
        $mail->AddAddress( $address, $name );
        if ( ! $mail->Send()) {
            //echo "Mailer Error: " . $mail->ErrorInfo;
            return false;
        } else {
            //echo "Message sent!";
            return true;
        }
        return;
    }

    /**
     * @param $userId
     * @param $code
     */
    public function verifyEmail( $userId, $code )
    {
        $update = $this->users->verifyUser( $userId, $code );
        if ($update) {
            //echo "verified";
            header( "Location: http://rankalytics.com/verifysuccess" );
            die();
            // needs to be chjanged with the proper view file showing appropriate message
        } else {
            header( "Location: http://rankalytics.com/verifyfail" );
            die();
            //echo "error in verificiation";
            // needs to be chjanged with the proper view file showing appropriate message
        }
    }

    /**
     *
     */
    public function login()
    {
        $requestMethod = $this->input->server( 'REQUEST_METHOD' );
        if (strtolower( $requestMethod ) != 'post') {
            redirect( 'ranktracker' );
        }

        $login_data = $this->validateLoginFields();
        if ($login_data['error'] == 1) {
            exit( json_encode( $login_data ) );
        }

        //defaults:
        $out = array(
            "error"       => 1,
            'message'     => '',
            'redirect_to' => '/',
        );

        // check credentials:
        trim( $login_array['emailAddress'] = $this->input->post( 'loginemail' ) );
        trim( $login_array['password'] = $this->input->post( 'loginpassword' ) );
        $login_array['remember'] = $this->input->post( 'remember' );
        $isLoggedin              = $this->users->doLogin( $login_array );
        if ($isLoggedin !== 'valid') {
            $out['message'] = 'Error! Your login credentials were invalid!';
            exit( json_encode( $out ) );
        }

        //info from session:
        $tempHolder = $this->session->userdata( 'logged_in' );
        $user       = $tempHolder[0];

        // check if verified:
        if (is_null( $user['status'] )) {
            $out['message'] = 'Your account is not yet verified with us.<br/>Please verify your account or <a href="javascript:;" onclick="resendVerification();">click here</a> to resend verification link.';
            exit( json_encode( $out ) );
        }

        // check subscription/s:
        $out['redirect_to'] = '/modules';
        $out['error']       = 0;

        exit( json_encode( $out ) );
    }

    /**
     * @return array
     */
    public function validateLoginFields()
    {
        $msg       = "";
        $error     = 0;
        $error_ids = array();
        if (trim( $email = $this->input->post( 'loginemail' ) ) == "") {
            $error_ids[] = "loginemail";
            $error       = 1;
        }
        if (trim( $name = $this->input->post( 'loginpassword' ) ) == "") {
            $error_ids[] = "loginpassword";
            $error       = 1;
        }

        return array( "msg" => $msg, "ids" => $error_ids, "error" => $error );
    }

    /**
     *
     */
    public function saveSection()
    {
        $this->load->library( 'form_validation' );
        $error     = 0;
        $error_msg = array();
        if ($this->input->post( 'section' ) == 'emailPassword') {
            if ($this->input->post( 'password' ) != '') {
                $this->form_validation->set_rules( 'password', 'Password', 'matches[confirmPassword]' );
                $this->form_validation->set_rules( 'confirmPassword', 'Password Confirmation' );
            }

            $this->form_validation->set_rules( 'emailAddress', 'Email', 'required|valid_email' );
            if ($this->form_validation->run() == false) {

                if ($this->input->post( 'password' ) != '') {
                    if (form_error( 'password' ) != '') {
                        $error_msg['password'] = form_error( 'password' );
                        $error                 = 1;
                    }
                    if (form_error( 'confirmPassword' ) != '') {
                        $error_msg['confirmPassword'] = form_error( 'confirmPassword' );
                        $error                        = 1;
                    }
                }
                if (form_error( 'emailAddress' ) != '') {
                    $error_msg['emailAddress'] = form_error( 'emailAddress' );
                    $error                     = 1;
                }
                echo json_encode( array( 'error' => 1, 'msg' => $error_msg ) );
            } else {
                $user                     = $this->session->userdata( 'logged_in' );
                $userid                   = $user['0']['id'];
                $user_arr['emailAddress'] = $this->input->post( 'emailAddress' );
                if ($this->input->post( 'password' ) != '') {
                    $user_arr['password'] = md5( $this->input->post( 'password' ) );
                }
                $this->users->updateTable( $user_arr, $where = array( 'id' => $userid ), 1 );
                echo json_encode( array( 'error' => 0, 'msg' => 'Changes Saved' ) );
            }
        } elseif ($this->input->post( 'section' ) == 'companyInfo') {
            $this->form_validation->set_rules( $this->users->_validation_companyInfo );
            if ($this->form_validation->run() == false) {
                if (form_error( 'companyName' ) != '') {
                    $error_msg['companyName'] = form_error( 'companyName' );
                    $error                    = 1;
                }
                echo json_encode( array( 'error' => 1, 'msg' => $error_msg ) );
            } else {
                $user   = $this->session->userdata( 'logged_in' );
                $userid = $user['0']['id'];

                $user_company = $this->input->post( 'mainId' );
                if ($user_company >= 1) {
                    $userid = $user_company;
                }

                $user_arr['companyName'] = $this->input->post( 'companyName' );
                if ($this->input->post( 'companyLogo' ) != '') {
                    $user_arr['companyLogo'] = $this->input->post( 'companyLogo' );
                }
                $this->users->updateTable( $user_arr, $where = array( 'id' => $userid ), 1 );
                echo json_encode( array( 'error' => 0, 'msg' => 'Changes Saved' ) );
            }
        } elseif ($this->input->post( 'section' ) == 'userInfo') {
            $this->form_validation->set_rules( $this->users->_validation_userInfo );
            if ($this->form_validation->run() == false) {
                if (form_error( 'firstName' ) != '') {
                    $error_msg['firstName'] = form_error( 'firstName' );
                }

                if (form_error( 'lastName' ) != '') {
                    $error_msg['lastName'] = form_error( 'lastName' );
                }

                if (form_error( 'phoneNumber' ) != '') {
                    $error_msg['phoneNumber'] = form_error( 'phoneNumber' );
                }

                if (form_error( 'streetAddress' ) != '') {
                    $error_msg['streetAddress'] = form_error( 'streetAddress' );
                }

                if (form_error( 'city' ) != '') {
                    $error_msg['city'] = form_error( 'city' );
                }

                if (form_error( 'zipCode' ) != '') {
                    $error_msg['zipCode'] = form_error( 'zipCode' );
                }

                if (form_error( 'country' ) != '') {
                    $error_msg['country'] = form_error( 'country' );
                }
                echo json_encode( array( 'error' => 1, 'msg' => $error_msg ) );
            } else {
                $user                      = $this->session->userdata( 'logged_in' );
                $userid                    = $user['0']['id'];
                $user_arr['firstName']     = $this->input->post( 'firstName' );
                $user_arr['lastName']      = $this->input->post( 'lastName' );
                $user_arr['phoneNumber']   = $this->input->post( 'phoneNumber' );
                $user_arr['streetAddress'] = $this->input->post( 'streetAddress' );
                $user_arr['city']          = $this->input->post( 'city' );
                $user_arr['zipCode']       = $this->input->post( 'zipCode' );
                $user_arr['vatNumber']     = ''; /*$this->input->post('vatNumber')*/;
                $user_arr['country'] = $this->input->post( 'country' );

                $this->users->updateTable( $user_arr, $where = array( 'id' => $userid ), 1 );
                echo json_encode( array( 'error' => 0, 'msg' => 'Changes Saved' ) );
            }
        }
    }

    /**
     * getUserDetails()
     *
     * @desc for retrieving users details like name,emails and user history etc
     *
     * @paramemters accepts userid(compulsory) from post method. No get parameter
     *
     * @author Sudhir
     * @access public
     *
     */
    function getUserDetails()
    {
        $out = array(
            'details' => 'Something went wrong',
        );
        if ( ! $cUserId = $this->users->isLoggedIn()) {
            $this->json_exit( $out );
        }

        // check if admin:
        $cUser = $this->users->getUserById( $cUserId ); // logged in user's details
        $cUser = $cUser[0];
        if ($cUser['userRole'] !== 'admin') {
            $out['details'] = 'Failed: not admin.';
            $this->json_exit( $out );
        }

        // requested user details:
        $userId  = $this->input->post( 'id' );
        $details = $this->users->getUserDetails( $userId );

        $viewInfo = array(
            "details"       => $details,
            "curuser"       => $cUser,
            'subscriptions' => $this->subscriptions->getSubscriptionInfo( $userId, null, false, true, false ),
        );

        $this->json_exit( array(
            'details' => $this->load->view( 'ranktracker/admin/user_details', $viewInfo, true ),
        ) );
    }

    /**
     *
     */
    function upgradeUser()
    {
        $this->json_exit( array( 'msg' => 'disabled function.' ) );

        $id         = $this->input->post( 'id' );
        $upgradeFor = $this->input->post( 'upgradeFor' );
        ///$date = new DateTime();
        $allowed_upto = date( 'Y-m-d', strtotime( "+{$upgradeFor} days" ) );
        $user_arr     = array( "pro_allowed" => "yes", "allowed_upto" => $allowed_upto );
        $this->users->updateTable( $user_arr, array( "id" => $id ), 1 );
        // saving in user_account_logs
        $this->load->model( "user_account_logs_model", "user_account_logs" );
        $user_account_logs['action_taken'] = "Pro Membership Enabled";
        $user_account_logs['action_by']    = "admin";
        $user_account_logs['description']  = "Pro membership enabled upto {$allowed_upto}";
        $user_account_logs['user_id']      = "{$id}";
        $user_id                           = $this->user_account_logs->save( $user_account_logs );
        if ($user_id >= 1) {
            echo json_encode( array( "error" => 0, "msg" => "Member Upgraded" ) );
        } else {
            echo json_encode( array( "error" => 1, "msg" => "Some Problem upgrading the account" ) );
        }
    }

    /**
     *
     */
    function uploadLogo()
    {
        $requestMethod = $this->input->server( 'REQUEST_METHOD' );
        if (strtolower( $requestMethod ) != 'post') {
            redirect( 'ranktracker' );
        }
        $id                      = $this->input->post( 'userid' );
        $config['upload_path']   = './uploads/logos/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = '1000000';
        $config['max_width']     = '1024';
        $config['max_height']    = '768';
        $this->load->library( 'upload', $config );

        if ( ! $this->upload->do_upload()) {
            $error                 = array( 'error' => $this->upload->display_errors() );
            $uploadData['msg']     = $error['error'];
            $uploadData['isError'] = 1;
            //$this->load->view("users/ajaxIframeMessage",$uploadData);
        } else {
            $data = array( 'upload_data' => $this->upload->data() );
            $this->load->library( 'image_lib' ); // image library for resizing the image for thumbnail
            $config2['image_library']  = 'gd2';
            $config2['source_image']   = $data['upload_data']['full_path'];
            $config2['maintain_ratio'] = true;
            $config2['width']          = 120;
            $config2['height']         = 120;
            $config2['new_image']      = './uploads/logos/thumbnails/' . $data['upload_data']['file_name'];
            $this->image_lib->initialize( $config2 );
            if ( ! $data2 = $this->image_lib->resize()) {
                $error                 = array( 'error' => $this->image_lib->display_errors() );
                $uploadData['msg']     = "Could Not crete Thumbnail" . $error['error'];
                $uploadData['isError'] = 1;
            } else {

                $this->users->updateTable( array( "companyLogo" => $data['upload_data']['file_name'] ), array( "id" => $id ), 1 );
                $uploadData['msg']     = $data['upload_data']['file_name'];
                $uploadData['isError'] = 0;
            }
        }
        $this->load->view( "users/ajaxIframeMessage", $uploadData );
    }

    /**
     *
     */
    public function deleteLogo()
    {
        $id                 = $this->input->post( 'id' );
        $ret_array['error'] = 0;
        $ret_array['msg']   = "";
        $userdata           = $this->session->userdata( 'logged_in' );
        $userdata           = $userdata[0];

        if ($userdata['userRole'] != 'admin' && $userdata['id'] != $id) {
            $ret_array['error'] = 1;
            $ret_array['msg'][] = "You are not authorised to delete this Logo";
            echo json_encode( $ret_array );
            return;
        }
        if ($ret_array['error'] == 0) {
            $user          = $this->users->getUserById( $id );
            $logoPath      = 'uploads/logos/';
            $thumbnailPath = 'uploads/logos/thumbnails/';
            $this->load->helper( 'file' );
            $companyLogo = $user[0]['companyLogo'];

            if ( ! $msgs[] = unlink( $logoPath . $companyLogo )) {
                $ret_array['error'] = 1;
                $ret_array['msg'][] = "Could not delete the logo File";
            }
            if ( ! $msgs[] = unlink( $thumbnailPath . $companyLogo )) {
                $ret_array['error'] = 1;
                $ret_array['msg'][] = "Could not delete the Thumbnail File";
            }

            if ($ret_array['error'] == 0) {
                $this->users->updateTable( array( "companyLogo" => '' ), array( "id" => $id ), 1 );
                $ret_array['msg'][] = "Logo Deleted.";
            }

        }


        echo json_encode( $ret_array );
        return;

    }

    /**
     *
     */
    public function closeAccount()
    {
        $id                 = $this->input->post( 'id' );
        $ret_array['error'] = 0;
        $ret_array['msg']   = "";
        $userdata           = $this->session->userdata( 'logged_in' );
        $userdata           = $userdata[0];
        //print_r($userdata);
        if ($userdata['userRole'] != 'admin' && $userdata['id'] != $id) {
            $ret_array['error'] = 1;
            $ret_array['msg'][] = "You are not authorised to close this account";
            echo json_encode( $ret_array );
            return;
        }
        if ($ret_array['error'] == 0) {
            $this->users->updateTable( array( "status" => '5' ), array( "id" => $id ), 1 ); // status 5 = closed account
            //$ret_array['error']=1;
            // saving in history table
            $by = $this->input->post( 'by' );
            if ($by == '') {
                $by = 'user';
            }

            // saving in history table
            $ret_array['msg'][] = "Account Closed. Redirecting...";
            // saving in user_account_logs
            $this->load->model( "user_account_logs_model", "user_account_logs" );
            $user_account_logs['action_taken'] = "Account Closed";
            $user_account_logs['action_by']    = "{$by}";
            $user_account_logs['description']  = "Account Closed";
            $user_account_logs['user_id']      = "{$id}";
            $this->user_account_logs->save( $user_account_logs );

            echo json_encode( $ret_array );
        }
    } // Close account

    /**
     * @param int $id
     */
    function deleteAccountByAdmin( $id = 0 )
    {
        if ($id == 0) {
            $id = $this->input->post( 'id' );
        }
        if ( ! $this->users->isAdmin()) {
            echo json_encode( array( "error" => 0, "msg" => "You are not allowed to delete this user" ) );
            return;
        }
        $user = $this->users->getUserById( $id );
        if ($user['0']['isPaid'] == 'yes') {
            $this->cancel_subscription( $id );
        }

        if ($this->users->delete( array( "id" => $id ), 1 )) {
            $this->users->delete( array( "mainId" => $id ) ); //  delete all user haveing mainId as deleted user id
            echo json_encode( array( "error" => 0, "msg" => "User has been deleted successfully" ) );
        } else {
            echo json_encode( array( "error" => 1, "msg" => "There is some problem in deleting user" ) );
        }
    }

    /**
     * @param int $id
     */
    function deleteAccountByUser( $id = 0 )
    {
        if ($id == 0) {
            $id = $this->input->post( 'id' );
        }
        if ( ! $userId = $this->users->isLoggedIn()) {
            echo json_encode( array( "error" => 1, "msg" => "Log in to delete the user." ) );
        }
        if ($this->users->delete( array( "id" => $id, "mainId" => $userId ), 1 )) {
            echo json_encode( array( "error" => 0, "msg" => "User has been deleted successfully" ) );
        } else {
            echo json_encode( array( "error" => 1, "msg" => "There is some problem in deleting user" ) );
        }
    }

    /**
     *
     */
    public function addsubuser()
    {
        $isValidated = $this->validateSubUser();
        if ($isValidated['error'] >= 1) {
            echo json_encode( $isValidated );
        } else {
            $mainuser                  = $this->session->userdata( 'logged_in' );
            $id                        = $mainuser['0']['id'];
            $userArray['emailAddress'] = $this->input->post( 'emailAddress' );
            $userArray['userName']     = $this->input->post( 'username' );
            $userArray['password']     = md5( $this->input->post( 'password' ) );

            $query                    = $this->db->query( 'SELECT accountType ,isPaid FROM users where id="' . $id . '"' );
            $acctyep                  = $query->result_array();
            $user                     = $this->users->getUserById( $id );
            $userArray['mainId']      = $id;
            $userArray['accountType'] = $user['0']['accountType'];
            /*            $isPaid1=$acctyep['0']['isPaid'];
                        if($isPaid1==0){$isPaid="no";}else{$isPaid="yes";}*/
            $userArray['isPaid']   = 1;
            $userArray['userRole'] = "subuser";
            $userArray['status']   = "1";
            $userId                = $this->users->save( $userArray );
            $userArray['userId']   = $userId;

            // saving in user_account_logs
            $this->load->model( "user_account_logs_model", "user_account_logs" );
            $user_account_logs['action_taken'] = "Account Created";
            $user_account_logs['action_by']    = "user";
            //$userType = $isPaid=="no"?"BASIC":"PRO";
            $userType                         = $userArray['accountType'];
            $user_account_logs['description'] = "Registration as {$userType} by user";
            $user_account_logs['user_id']     = "{$userId}";
            $this->user_account_logs->save( $user_account_logs );
            $msg         = "";
            $error       = 1;
            $error_ids   = array();
            $error_ids[] = "success";
            echo json_encode( array( "msg" => $msg, "ids" => $error_ids, "error" => $error ) );


        }
    }

    /**
     * @return array
     */
    function validateSubUser()
    {
        $msg       = "";
        $error     = 0;
        $error_ids = array();


        if (trim( $email = $this->input->post( 'emailAddress' ) ) == "") {
            $error_ids[] = "emailAddress";
            $error       = 1;
        }
        if ($this->users->isUserExists( array( "emailAddress" => $email ) )) {
            $error_ids[] = "emailAddressExists";
            $error       = 1;
        }
        if (trim( $userName = $this->input->post( 'username' ) ) == "") {
            $error_ids[] = "userName";
            $error       = 1;
        }
        if ($this->users->isUserExists( array( "userName" => $userName ) )) {
            $error_ids[] = "userNameExists";
            $error       = 1;
        }
        if (trim( $name = $this->input->post( 'password' ) ) == "") {
            $error_ids[] = "password";
            $error       = 1;
        }
        return array( "msg" => $msg, "ids" => $error_ids, "error" => $error );
    }

    /**
     *
     */
    public function index()
    {
        redirect( '/users/settings' );
    }

    /**
     * shows the settings page:
     */
    public function settings()
    {
        $user = $this->session->userdata( 'logged_in' );
        if (( ! isset( $user['0']['id'] ) || $user['0']['id'] == 0 || $user['0']['id'] == '' ) or ! $this->users->isVerified( $user['0']['id'] )) {
            redirect( '/' );
        }

        $userId                            = $user['0']['id'];
        $user_array                        = $this->users->getUserById( $userId );
        $data['user_database']             = $user_array['0'];
        $data['user_database']['userType'] = $this->users->getUserType( $user_array['0'] );

        if ($data['user_database']['userRole'] == 'subuser') {
            $mainuser = $this->users->getUserById( $data['user_database']['mainId'] );

            $data['user_database']['companyLogo'] = $mainuser['0']['companyLogo'];
            $data['user_database']['companyName'] = $mainuser['0']['companyName'];
        }

        $data['current']    = 'dashboard';
        $data['meta_title'] = 'Rankalytics Settings';
        $data['countries']  = $this->countries->getAll();

        $this->load->view( 'dashboard/settings', $data );
    }

    /**
     *
     */
    public function search()
    {
        if ( ! $userId = $this->users->isloggedIn()) {
            redirect( "ranktracker" );
        }

        $userId     = $this->users->isloggedIn();
        $user_array = $this->users->getUserById( $userId );

        if ("yes" != $user_array[0]['isPaid'] || $user_array[0]['userRole'] == 'subuser') {
            $this->load->view( 'dashboard/users', array( "error" => "You are not authorised to vew this page", "notpro" => 1 ) );
        } else {
            $isAjax = $this->input->post( "isAjax" );
            $this->load->library( 'pagination' );
            $this->config->load( 'paginationConfig' );
            $paginationConfig                = $this->config->item( 'paginationConfig' ); // Taking default config values for pagination
            $paginationConfig['base_url']    = base_url() . 'ranktracker/users/';
            $paginationConfig['uri_segment'] = 3;
            $searchString                    = $this->input->post( 'searchString' );

            $total = $this->users->getUsersCount( $searchString, $mainUser = $userId );

            $paginationConfig['total_rows'] = $total;
            $startFrom                      = $this->uri->segment( $paginationConfig['uri_segment'] );
            if ($startFrom == '') {
                $startFrom = 0;
            }
            $paginationConfig['per_page'] = 10;
            $limit                        = array( $startFrom, $paginationConfig['per_page'] );
            $this->data['users']          = $this->users->getUsers( $searchString, $limit, $mainUser = $userId );

            $this->pagination->initialize( $paginationConfig );
            if ($isAjax == 1) {
                $user_html = $this->load->view( 'dashboard/userlist', $this->data, true );
                echo json_encode( array( "error" => 0, "html" => $user_html, "pagination" => $this->pagination->create_links() ) );
            } else {
                $this->load->view( 'dashboard/users', $this->data );
            }
        }
    }

    /**
     *
     */
    public function invoices()
    {
        if ( ! $userId = $this->users->isLoggedIn()) {
            redirect( "ranktracker" );
        }

        // sets:
        $user_array = $this->users->getUserById( $userId );

        // defaults
        $i             = 0;
        $subscriptions = array();

        $tempInfo = $this->subscriptions->getSubscriptionInfo( $userId, null, false, true, false );
        if (is_array( $tempInfo )) {
            foreach ($tempInfo as $t_no => $tInfo) {
                $service = $tInfo['service'];
                $plan    = $tInfo['plan'];

                // check if paid by plan amount:
                if (Subscriptions_Lib::$_service_prices[$service][$plan] > 0) {
                    $subscriptions[$i] = $tInfo;

                    // determine amount:
                    $subscriptions[$i]['paid'] = Subscriptions_Lib::$_service_prices[$service][$plan];
                    $i ++;
                }
            }
        }

        $this->data = array(
            'current'       => 'dashboard',
            'user_database' => $user_array['0'],
            'subscriptions' => $subscriptions,
        );

        $this->load->view( "dashboard/invoices", $this->data );
    }

    /**
     *
     */
    public function downloadInvoicereport()
    {
        if ( ! $userId = $this->users->isLoggedIn()) {
            redirect( "ranktracker" );
        }

        // sets:
        $user_array = $this->users->getUserById( $userId );

        // defaults
        $i             = 0;
        $subscriptions = array();

        $tempInfo = $this->subscriptions->getSubscriptionInfo( $userId, null, false, true, false );
        foreach ($tempInfo as $t_no => $tInfo) {
            $service = $tInfo['service'];
            $plan    = $tInfo['plan'];

            // check if paid by plan amount:
            if (Subscriptions_Lib::$_service_prices[$service][$plan] > 0) {
                $subscriptions[$i] = $tInfo;

                // determine amount:
                $subscriptions[$i]['paid'] = Subscriptions_Lib::$_service_prices[$service][$plan];
                $i ++;
            }
        }

        // load requirements:
        $this->load->helper( array( "dompdf_helper", "file" ) );
        $this->load->helper( array( 'dompdf', 'file' ) );

        //
        $this->data = array(
            'current'       => 'dashboard',
            'user_database' => $user_array['0'],
            'subscriptions' => $subscriptions,
        );

        $invoice = $this->load->view( "dashboard/invoicereport_forPDF", $this->data, true );

        // ..
        pdf_create( $invoice, 'invoicereport' );

        /*$data = pdf_create($html, '', false);
        write_file('name', $data);*/
    }
}