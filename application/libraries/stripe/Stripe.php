<?php

class My_Stripe
{
    private $customerId;
    private $subExternalId = null;

    public function __construct()
    {
        require_once __DIR__ . '/autoload.php';

        $this->customerId = false;

        $config = config_item( 'stripe_config' );

        Stripe::setApiKey( $config['private_key'] );
    }

    /**
     * @param $emailAddress
     * @param $token
     *
     */
    protected function createCustomer( $emailAddress, $token )
    {
        $customer = Stripe_Customer::create( array(
            'card'  => $token,
            'email' => $emailAddress,
        ) );

        $this->customerId = $customer->id;
    }

    /**
     * @param $serviceName
     * @param $planName
     *
     * @return string
     */
    protected function getPlanId( $serviceName, $planName )
    {
        return substr( $serviceName, 0, 3 ) . '_' . $planName;
    }

    /**
     * @param $token
     * @param $emailAddress
     * @param $subscriptions
     */
    public function makeSubscription( $token, $subscription, $emailAddress )
    {
        $this->createCustomer( $emailAddress, $token );

        $cu = Stripe_Customer::retrieve( $this->customerId );

        $external = $cu->subscriptions->create( array( 'plan' => $this->getPlanId( $subscription['service'], $subscription['plan'] ) ) );

        $this->subExternalId = $external->id;
    }

    /**
     * @param Exception $e
     *
     * @return string
     */
    public function getExceptionNicerMessage( Exception $e )
    {
        $msg = $e->getMessage();

        // nicer message: remove data after last ":"
        if (stripos( $msg, ":" ) !== false) {
            $parts = explode( ":", $msg );
            $msg   = "";
            $extra = ( count( $parts ) > 2 ) ? ": " : "";
            for ($i = 0; $i < count( $parts ) - 1; $i ++) {
                $msg .= $extra . $parts[$i];
            }
        }

        return $msg;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @return array
     */
    public function getExternalId()
    {
        return $this->subExternalId;
    }

    /**
     * @param $customerId
     *
     * @return Stripe_Customer
     */
    private function getCustomerById( $customerId )
    {
        return Stripe_Customer::retrieve( $customerId );
    }

    /**
     * @param array $newSubscription
     * @param $subscriptionId
     * @param $customerId
     * @param $token
     */
    public function updateSubscription( array $newSubscription, $subscriptionId, $customerId, $token )
    {
        $customer     = $this->getCustomerById( $customerId );
        $subscription = $customer->subscriptions
            ->retrieve( $subscriptionId );

        $subscription->source = $token;
        $subscription->plan   = $this->getPlanId( $newSubscription['service'], $newSubscription['plan'] );
        $subscription->save();
    }

    /**
     * @param $customerId
     * @param $subscriptionId
     */
    public function cancelSubscription( $customerId, $subscriptionId )
    {
        $customer = $this->getCustomerById( $customerId );
        $customer->subscriptions
            ->retrieve( $subscriptionId )
            ->cancel();
    }
}