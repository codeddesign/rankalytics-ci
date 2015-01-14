<?php

class My_Stripe
{
    private $_key;

    public function __construct()
    {
        require_once __DIR__ . '/autoload.php';

        $this->_key = 'sk_test_H10QOG4J0DYeplUy3NwsFM7s';

        Stripe::setApiKey( $this->_key );
    }

    public function checkTransaction( array $data, array $stripe_session_data )
    {
        try {
            $customer = Stripe_Customer::create( array(
                'email' => $data['email'],
                'card'  => $data['id'],
            ) );

            $charge = Stripe_Charge::create( array(
                'customer' => $customer->id,
                'amount'   => $stripe_session_data['amount'],
                'currency' => 'usd'
            ) );

            $out = array(
                'error' => false,
                'paid' => $charge->paid,
                'msg' => $charge->failure_message,
                'charge_id' => $charge->id,
            );
        } catch ( Exception $e ) {
            $temp = $e->getMessage();

            // nicer message: remove data after last ":"
            if (stripos( $temp, ":" ) !== false) {
                $parts = explode( ":", $temp );
                $temp  = "";
                $extra = ( count( $parts ) > 2 ) ? ": " : "";
                for ($i = 0; $i < count( $parts ) - 1; $i ++) {
                    $temp .= $extra . $parts[$i];
                }
            }

            $out = array(
                'error' => true,
                'msg'   => $temp,
            );
        }

        return $out;
    }
} 