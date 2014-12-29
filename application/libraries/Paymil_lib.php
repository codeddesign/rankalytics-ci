<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


/**
 * CodeIgniter CSV Import Class
 *
 * This library will help import a CSV file into
 * an associative array.
 * 
 * This library treats the first row of a CSV file
 * as a column header row.
 * 
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Sudhir
 */
        require  'lib/Paymill/Request.php';
        
        require  'lib/Paymill/API/CommunicationAbstract.php';
        require  'lib/Paymill/API/Curl.php';
        require  'lib/Paymill/Models/Request/Base.php';
        require  'lib/Paymill/Models/Request/Payment.php';
        
        require  'lib/Paymill/Services/ResponseHandler.php';
        require  'lib/Paymill/Models/Response/Error.php';
            
        require  'lib/Paymill/Services/PaymillException.php';
class Paymil_lib {


   /**
     * Function that calls phpmailer class for mail sending
     * 
     *
     * @access  public
     * @param   filepath        string  Location of the CSV file
     * @param   column_headers  array   Alternate values that will be used for array keys instead of first line of CSV
     * @param   detect_line_endings  boolean  When true sets the php INI settings to allow script to detect line endings. Needed for CSV files created on Macs.
     * @return  array
     */
    
    var $_request;
    public function __construct()
    {
        //require_once APPPATH.'/third_party/phpmailer/class.phpmailer.php';
        /*set_include_path(get_include_path() . PATH_SEPARATOR  . '/var/www/application/libraries/paymill-php');
        set_include_path(get_include_path() . PATH_SEPARATOR  . '/var/www/application/libraries/paymill-php/');
        echo get_include_path();
        */
      
        //require 'autoload.php'; //Including Paymill
        
        $apiKey="89c134d9ba995c215a0b0bd01f0e267a";
        $this->request = new Paymill\Request($apiKey);
        $this->new_webhook(base_url()."callProgram",array('subscription.created'));
        
    }   
    public function new_webhook($url,$event_array=array('subscription.created')){
        
        $webhook = new Paymill\Models\Request\Webhook();
        $webhook->setUrl($url)
                ->setEventTypes($event_array);

        /*array(
                    'transaction.succeeded',
                    'subscription.created'
                )
         * *
         */
        $response = $this->_request->create($webhook);
        print_r($response);
        //return $response;
        
    }
}