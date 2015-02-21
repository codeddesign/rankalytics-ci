<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;

use PayPal\Api\Plan;
use PayPal\Api\PaymentDefinition;
use PayPal\Api\MerchantPreferences;
use PayPal\Api\Currency;

use PayPal\Api\Patch;
use PayPal\Common\PayPalModel;
use PayPal\Api\PatchRequest;

use PayPal\Api\Agreement;
use PayPal\Api\Payer;
use Carbon\Carbon;

class My_PaypalRest
{
    private $apiContext;
    private $config;
    private $approvalUrl;
    private $paymentDefinitions;
    private $subscriptions;

    public function __construct()
    {
        require_once __DIR__ . '/vendor/autoload.php';

        $config        = config_item( 'paypal_config' );
        $this->config  = $config['sandbox'];
        $this->details = $config;

        $this->approvalUrl = false;

        $this->setApiContext();
    }

    /**
     * Creates API Context. Used with __construct()
     *
     */
    private function setApiContext()
    {
        try {
            $this->apiContext = new ApiContext(
                new OAuthTokenCredential(
                    $this->config['client']['id'],
                    $this->config['client']['secret']
                )
            );
        } catch ( Exception $ex ) {
            exit( $ex->getMessage() );
        }

        $this->apiContext->setConfig( $this->config['config'] );
    }

    /**
     * @param array $subscriptions
     */
    public function setSubscriptions( array $subscriptions )
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @param $subscription
     *
     * @return string
     */
    private function getPlanName( $subscription )
    {
        return ucfirst( $subscription['service'] ) . " " . strtoupper( $subscription['plan'] );
    }

    /**
     * @return string
     */
    private function getPlanNameAll()
    {
        $save = array();
        foreach ($this->subscriptions as $s_no => $subscription) {
            $save[] = $this->getPlanName( $subscription );
        }

        return implode( ' & ', $save );
    }

    /**
     * @return Plan|array
     */
    private function createPlan()
    {
        $baseUrl = $this->getBaseUrl();

        $plan = new Plan();
        $plan->setName( $this->getPlanNameAll() )
             ->setDescription( $this->details['plan']['description'] )
             ->setType( 'fixed' );

        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl( $baseUrl . "?success=true" )
                            ->setCancelUrl( $baseUrl . "?success=false" )
                            ->setAutoBillAmount( "yes" )
                            ->setInitialFailAmountAction( "CONTINUE" )
                            ->setMaxFailAttempts( "0" );

        foreach ($this->subscriptions as $s_no => $subscription) {
            $pd = new PaymentDefinition();
            $pd->setName( $this->getPlanName( $subscription ) )
               ->setType( 'Regular' )
               ->setFrequency( 'Month' )
               ->setFrequencyInterval( "1" )
               ->setCycles( "12" )
               ->setAmount( new Currency( array( 'value' => Subscriptions_Lib::$_service_prices[$subscription['service']][$subscription['plan']], 'currency' => 'USD' ) ) );

            $plan->setPaymentDefinitions( array($pd) );
        }

        $plan->setMerchantPreferences( $merchantPreferences );

        # Create Plan
        try {
            $plan = $plan->create( $this->apiContext );
        } catch ( Exception $ex ) {
            return array(
                'error' => true,
                'msg'   => $ex->getMessage()
            );
        }

        return $plan;
    }

    /**
     * @param Plan $createdPlan
     *
     * @return Plan|array
     */
    private function updatePlan( Plan $createdPlan )
    {
        try {

            $patch = new Patch();
            $patch->setOp( 'replace' )
                  ->setPath( '/' )
                  ->setValue( new PayPalModel(
                      json_encode(
                          array(
                              'state' => 'ACTIVE'
                          )
                      )
                  ) );

            $patchRequest = new PatchRequest();
            $patchRequest->addPatch( $patch );

            $createdPlan->update( $patchRequest, $this->apiContext );

            $plan = Plan::get( $createdPlan->getId(), $this->apiContext );

        } catch ( Exception $ex ) {
            return array(
                'error' => true,
                'msg'   => $ex->getMessage()
            );
        }

        return $plan;
    }

    /**
     * @return string
     */
    private function getStartDate()
    {
        $date = Carbon::now()->addMinutes( 10 );

        return (string) str_replace( '+0000', 'Z', $date->toIso8601String() );
    }

    /**
     *
     */
    public function createBillingWithAccount()
    {
        # step 1:
        $thePlan = $this->createPlan();
        if (is_array( $thePlan ) and $thePlan['error']) {
            return $thePlan;
        }

        # step 2:
        $thePlan = $this->updatePlan( $thePlan );
        if (is_array( $thePlan ) and $thePlan['error']) {
            return $thePlan;
        }

        # step 3:
        $agreement = new Agreement();
        $agreement->setName( $this->details['agreement']['name'] )
                  ->setDescription( $this->details['agreement']['description'] )
                  ->setStartDate( $this->getStartDate() );

        $plan = new Plan();
        $plan->setId( $thePlan->getId() );
        $agreement->setPlan( $plan );

        // Add Payer
        $payer = new Payer();
        $payer->setPaymentMethod( 'paypal' );
        $agreement->setPayer( $payer );

        # create agreement:
        try {
            // Please note that as the agreement has not yet activated, we wont be receiving the ID just yet.
            $agreement = $agreement->create( $this->apiContext );

            $this->approvalUrl = $agreement->getApprovalLink();

            return array(
                'error' => false,
                'link'  => $this->approvalUrl,
            );

        } catch ( Exception $ex ) {
            return array(
                'error' => true,
                'msg'   => $ex->getMessage()
            );
        }
    }

    /**
     * @return bool|string
     */
    public function getApprovalUrl()
    {
        return $this->approvalUrl;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        if (PHP_SAPI == 'cli') {
            $trace        = debug_backtrace();
            $relativePath = substr( dirname( $trace[0]['file'] ), strlen( dirname( dirname( __FILE__ ) ) ) );
            # echo "Warning: This sample may require a server to handle return URL. Cannot execute in command line. Defaulting URL to http://localhost$relativePath \n";
            return "http://localhost" . $relativePath;
        }

        $protocol = 'http';
        if ($_SERVER['SERVER_PORT'] == 443 || ( ! empty( $_SERVER['HTTPS'] ) && strtolower( $_SERVER['HTTPS'] ) == 'on' )) {
            $protocol .= 's';
        }

        $host    = $_SERVER['HTTP_HOST'];
        $request = $_SERVER['PHP_SELF'];

        return dirname( $protocol . '://' . $host . str_replace( 'index.php/', '', $request ) );
    }

    /**
     * @param $token
     *
     * @return array
     */
    public function handleAgreement( $token )
    {
        $agreement = new Agreement();
        try {
            # Execute the agreement by passing in the token
            $agreement->execute( $token, $this->apiContext );
        } catch ( Exception $ex ) {
            return array(
                'error' => true,
                'msg'   => $ex->getMessage()
            );
        }

        # Make a get call to retrieve the executed agreement details
        try {
            $agreement = Agreement::get( $agreement->getId(), $this->apiContext );
        } catch ( Exception $ex ) {
            return array(
                'error' => true,
                'msg'   => $ex->getMessage()
            );
        }

        return array(
            'error'     => false,
            'agreement' => $agreement
        );
    }
}