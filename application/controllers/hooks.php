<?php

class Hooks extends CI_Controller
{
    public function index()
    {
        redirect( '/' );
    }

    public function paypal()
    {

    }

    public function stripe()
    {
        $input = @file_get_contents("php://input");
        $event_json = json_decode($input, 1);

        file_put_contents('here.txt', $input);
        print_r($event_json);
    }

    /*public function test( ){
        $this->load->library( 'stripe' );

    }*/
}