<?php
class My_Stripe
{
    private $customerId;
    private $sub_external = array();

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
    public function makeSubscription( $token, $subscriptions, $emailAddress )
    {
        $this->createCustomer( $emailAddress, $token );

        $cu = Stripe_Customer::retrieve( $this->customerId );

        foreach ($subscriptions as $s_no => $subscription) {
            $external = $cu->subscriptions->create( array( 'plan' => $this->getPlanId( $subscription['service'], $subscription['plan'] ) ) );

            $this->sub_external[$s_no] = $external->id;
        }
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
    public function getExternalIds()
    {
        return $this->sub_external;
    }
}