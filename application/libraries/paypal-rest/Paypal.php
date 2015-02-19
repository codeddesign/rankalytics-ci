<?php
require_once __DIR__ . '/vendor/autoload.php';

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

class My_Pay_pal
{
    private $apiContext;
    private $config;
    private $approvalUrl;

    public function __construct()
    {
        $this->config      = config_item( 'paypal_config' );
        $this->approvalUrl = false;

        $this->setApiContext();
    }

    /**
     * Creates API Context. Used with __construct()
     *
     */
    private function setApiContext()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $this->config['client']['id'],
                $this->config['client']['secret']
            )
        );

        $this->apiContext->setConfig( $this->config['config'] );
    }

    /**
     * @return Plan
     */
    private function createPlan()
    {
        $baseUrl = $this->getBaseUrl();

        $plan = new Plan();
        $plan->setName( 'T-Shirt of the Month Club Plan' )
             ->setDescription( 'Template creation.' )
             ->setType( 'fixed' );

        $paymentDefinition = new PaymentDefinition();
        $paymentDefinition->setName( 'Regular Payments' )
                          ->setType( 'REGULAR' )
                          ->setFrequency( 'Month' )
                          ->setFrequencyInterval( "1" )
                          ->setCycles( "12" )
                          ->setAmount( new Currency( array( 'value' => 100, 'currency' => 'USD' ) ) );

        $merchantPreferences = new MerchantPreferences();
        $merchantPreferences->setReturnUrl( $baseUrl . "/check?success=true" )
                            ->setCancelUrl( $baseUrl . "/check?success=false" )
                            ->setAutoBillAmount( "yes" )
                            ->setInitialFailAmountAction( "CONTINUE" )
                            ->setMaxFailAttempts( "0" );

        $plan->setPaymentDefinitions( array( $paymentDefinition ) );
        $plan->setMerchantPreferences( $merchantPreferences );

        # Create Plan
        try {
            $output = $plan->create( $this->apiContext );
        } catch ( Exception $ex ) {
            exit( $ex->getMessage() );
        }

        return $output;

    }

    /**
     * @param Plan $createdPlan
     *
     * @return Plan
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
            exit( $ex->getMessage() );
        }

        return $plan;
    }

    /**
     *
     */
    public function createBillingWithAccount()
    {
        # step 1:
        $createdPlan = $this->createPlan();

        # step 2:
        $updatePlan = $this->updatePlan( $createdPlan );

        # step 3:
        $agreement = new Agreement();

        $agreement->setName( 'Base Agreement' )
                  ->setDescription( 'Basic Agreement' )
                  ->setStartDate( '2015-06-17T9:45:04Z' );

        $plan = new Plan();
        $plan->setId( $updatePlan->getId() );
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

        } catch ( Exception $ex ) {
            exit( $ex->getMessage() );
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

        return dirname( $protocol . '://' . $host . $request );
    }

    public function handleToken()
    {
        if (isset( $_GET['success'] ) && $_GET['success'] == 'true') {

            $token     = $_GET['token'];
            $agreement = new Agreement();
            try {
                # Execute the agreement by passing in the token
                $agreement->execute( $token, $this->apiContext );
            } catch ( Exception $ex ) {
                exit( $ex->getMessage() );
            }

            # Make a get call to retrieve the executed agreement details
            try {
                $agreement = Agreement::get( $agreement->getId(), $this->apiContext );
            } catch ( Exception $ex ) {
                $ex->getMessage();
            }

            print_r( $agreement );

        } else {
            echo( "User Cancelled the Approval" );
        }
    }
}