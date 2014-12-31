<?php
require_once('PPBootStrap.php');

class Paypal
{
    private $_dev_mode, $_default_info, $_token, $_configuration, $_paypalService, $_errors, $_transactionId, $redirects;

    function __construct()
    {
        $this->_configuration = Configuration::getAcctAndConfig();
        $this->_dev_mode = FALSE; // make it TRUE for tests / debugging
        $this->_token = NULL;
        $this->_errors = FALSE;
        $this->_transactionId = FALSE;
        $this->redirects = NULL;

        //
        $this->_default_info = array(
            // general sets:
            'currencyCode' => Subscriptions_Lib::$_currency_code,
            'paymentType' => ucfirst('sale'),
            'noShipping' => '0',
            'addressOverride' => '0',
            'reqConfirmShipping' => '0',
            'allowNote' => '0',

            // Billing agreement details:
            'billingType' => '',
            'billingAgreementText' => '',

            // display options:
            'cppheaderimage' => '',
            'cppheaderbordercolor' => '',
            'cppheaderbackcolor' => '',
            'cpppayflowcolor' => '',
            'cppcartbordercolor' => '',
            'cpplogoimage' => 'https://rankalytics.com/assets/paypal_logo.png', // logo image
            'pageStyle' => '',
            'brandName' => '',
        );
    }

    /**
     * @return string
     */
    function getMainURL() {
        return 'https://' . $_SERVER['SERVER_NAME'].'';
    }

    function setDefaultRedirects() {
        $url = $this->getMainURL();

        $this->redirects = array(
            'return' => $url.'/promembership',
            'cancel' => $url.'/promembership?mode=1',
        );
    }

    /**
     * @param array $redirects
     */
    function setRedirects(array $redirects) {
        $url = $this->getMainURL();

        foreach($redirects as $r_key => $r_value) {
            $redirects[$r_key] = $url.''.$r_value;
        }

        $this->redirects = $redirects;
    }

    function setExpressCheckout(array $passed_info)
    {
        $default = $this->_default_info;

        if($this->redirects == null) {
            $this->setDefaultRedirects();
        }

        $currencyCode = $default['currencyCode'];

        // shipping address
        $address = new AddressType();
        $address->CityName = $passed_info['city'];
        $address->Name = $passed_info['name'];
        $address->Street1 = $passed_info['street'];
        $address->StateOrProvince = $passed_info['state'];
        $address->PostalCode = $passed_info['postalCode'];
        $address->Country = $passed_info['countryCode'];
        $address->Phone = $passed_info['phone'];

        // details about payment
        $paymentDetails = new PaymentDetailsType();
        $itemTotalValue = $taxTotalValue = 0;

        /* iterate through each item and add to item details */
        foreach ($passed_info['items'] as $i_no => $item) {
            $itemAmount = new BasicAmountType($currencyCode, $item['amount']);
            $itemTotalValue += $item['amount'] * $item['quantity'];
            $taxTotalValue += $item['tax'] * $item['quantity'];

            $itemDetails = new PaymentDetailsItemType();
            $itemDetails->Name = $item['name'];
            $itemDetails->Amount = $itemAmount;
            $itemDetails->Quantity = $item['quantity'];
            $itemDetails->Description = $item['description'];
            $itemDetails->Tax = new BasicAmountType($currencyCode, $item['tax']);

            /* Indicates whether an item is digital or physical. For digital goods, this field is required and must be set to Digital.
             * It is one of the following values: Digital / Physical
            */
            $itemDetails->ItemCategory = $item['category'];

            $paymentDetails->PaymentDetailsItem[$i_no] = $itemDetails;
        }

        /*
         * The total cost of the transaction to the buyer.
         * If shipping cost and tax charges are known, include them in this value.
         * If not, this value should be the current subtotal of the order.
         * If the transaction includes one or more one-time purchases, this field must be equal to the sum of the purchases.
         * If the transaction does not include a one-time purchase such as when you set up a billing agreement for a recurring payment,
         *  set this field to 0.
         */
        $orderTotalValue = $itemTotalValue + $taxTotalValue;

        //Payment details
        $paymentDetails->ShipToAddress = $address;
        $paymentDetails->ItemTotal = new BasicAmountType($currencyCode, $itemTotalValue);
        $paymentDetails->TaxTotal = new BasicAmountType($currencyCode, $taxTotalValue);
        $paymentDetails->OrderTotal = new BasicAmountType($currencyCode, $orderTotalValue);

        /*
         * How you want to obtain payment.
         * When implementing parallel payments, this field is required and must be set to Order.
         * When implementing digital goods, this field is required and must be set to Sale.
         * If the transaction does not include a one-time purchase, this field is ignored. It is one of the following values:
            Sale -> This is a final sale for which you are requesting payment (default).
            Authorization -> This payment is a basic authorization subject to settlement with PayPal Authorization and Capture.
            Order -> This payment is an order authorization subject to settlement with PayPal Authorization and Capture.

         */
        $paymentDetails->PaymentAction = $default['paymentType'];

        /*
         * Your URL for receiving Instant Payment Notification (IPN) about this transaction.
         * If you do not specify this value in the request, the notification URL from your Merchant Profile is used, if one exists.
         */
        if (isset($passed_info['notifyURL'])) {
            $paymentDetails->NotifyURL = $passed_info['notifyURL'];
        }

        $setECReqDetails = new SetExpressCheckoutRequestDetailsType();
        $setECReqDetails->PaymentDetails[0] = $paymentDetails;

        /* (Required) URL to which the buyer is returned if the buyer does not approve the use of PayPal to pay you.
         * For digital goods, you must add JavaScript to this page to close the in-context experience.
         * */
        $setECReqDetails->CancelURL = $this->redirects['cancel'];

        /*(Required) URL to which the buyer's browser is returned after choosing to pay with PayPal.
         * For digital goods, you must add JavaScript to this page to close the in-context experience.
         * */
        $setECReqDetails->ReturnURL = $this->redirects['return'];

        /* Determines where or not PayPal displays shipping address fields on the PayPal pages. For digital goods, this field is required, and you must set it to 1. It is one of the following values:
            0 -> PayPal displays the shipping address on the PayPal pages.
            1 -> PayPal does not display shipping address fields whatsoever.
            2 -> If you do not pass the shipping address, PayPal obtains it from the buyer's account profile.
         */
        $setECReqDetails->NoShipping = $default['noShipping'];

        /* (Optional) Determines whether or not the PayPal pages should display the shipping address set by you in this SetExpressCheckout request, not the shipping address on file with PayPal for this buyer. Displaying the PayPal street address on file does not allow the buyer to edit that address. It is one of the following values:
            0 -> The PayPal pages should not display the shipping address.
            1 -> The PayPal pages should display the shipping address.
        */
        $setECReqDetails->AddressOverride = $default['addressOverride'];

        /* Indicates whether or not you require the buyer's shipping address on file with PayPal be a confirmed address. For digital goods, this field is required, and you must set it to 0. It is one of the following values:
            0 -> You do not require the buyer's shipping address be a confirmed address.
            1 -> You require the buyer's shipping address be a confirmed address.
        */
        $setECReqDetails->ReqConfirmShipping = $default['reqConfirmShipping'];

        // Billing agreement details
        $billingAgreementDetails = new BillingAgreementDetailsType($default['billingType']);
        $billingAgreementDetails->BillingAgreementDescription = $default['billingAgreementText'];
        $setECReqDetails->BillingAgreementDetails = array($billingAgreementDetails);

        // Display options
        $setECReqDetails->cppheaderimage = $default['cppheaderimage'];
        $setECReqDetails->cppheaderbordercolor = $default['cppheaderbordercolor'];
        $setECReqDetails->cppheaderbackcolor = $default['cppheaderbackcolor'];
        $setECReqDetails->cpppayflowcolor = $default['cpppayflowcolor'];
        $setECReqDetails->cppcartbordercolor = $default['cppcartbordercolor'];
        $setECReqDetails->cpplogoimage = $default['cpplogoimage'];
        $setECReqDetails->PageStyle = $default['pageStyle'];
        $setECReqDetails->BrandName = $default['brandName'];

        // Advanced options
        $setECReqDetails->AllowNote = $default['allowNote'];

        $setECReqType = new SetExpressCheckoutRequestType();
        $setECReqType->SetExpressCheckoutRequestDetails = $setECReqDetails;
        $setECReq = new SetExpressCheckoutReq();
        $setECReq->SetExpressCheckoutRequest = $setECReqType;

        /*
         * Creating service wrapper object
         * Creating service wrapper object to make API call and loading
         * Configuration::getAcctAndConfig() returns array that contains credential and config parameters
        */

        $paypalService = new PayPalAPIInterfaceServiceService($this->_configuration);
        try {
            /* wrap API method calls on the service object with a try catch */
            $setECResponse = $paypalService->SetExpressCheckout($setECReq);
        } catch (Exception $ex) {
            if ($this->_dev_mode) {
                include_once("Error.php");
                exit;
            } else {
                // ..
            }
        }

        if (isset($setECResponse)) {
            if ($this->_dev_mode) {
                echo "<table>";
                echo "<tr><td>Ack :</td><td><div id='Ack'>$setECResponse->Ack</div> </td></tr>";
                echo "<tr><td>Token :</td><td><div id='Token'>$setECResponse->Token</div> </td></tr>";
                echo "</table>";
                echo '<pre>';

                print_r($setECResponse);

                echo '</pre>';
                if ($setECResponse->Ack == 'Success') {
                    $token = $setECResponse->Token;
                    // Redirect to paypal.com here
                    $payPalURL = 'https://www.sandbox.paypal.com/webscr?cmd=_express-checkout&token=' . $token;
                    echo " <a href='" . $payPalURL . "' target=\"_blank\"><b>* Redirect to PayPal to login </b></a><br>";
                }

                require_once 'Response.php';
                exit;
            }

            $this->_token = $setECResponse->Token;
        }
    }

    /* return token */
    public function getToken() {
        return $this->_token;
    }

    // returns link for paypal redirect. If no token, it gets home.
    public function getPayPalURL()
    {
        if ($this->_token !== null) {
            $link = 'https://www.' . ($this->_configuration['mode'] == 'sandbox' ? 'sandbox.' : '') . 'paypal.com/webscr?cmd=_express-checkout&token=';
            return $link . $this->_token;
        } else {
            return '/';
        }
    }

    /* gets the errors if any from getExpressCheckout and DoExpressCheckout */
    protected function collectErrors($response)
    {
        if (isset($response->Errors) AND count($response->Errors) > 0) {
            foreach ($response->Errors as $no => $details) {
                if (isset($details->LongMessage)) {
                    $err_msg[] = $details->LongMessage;
                }
            }
        }

        if (isset($err_msg)) {
            $this->_errors = $err_msg;
        }
    }

    /* returns BOOL if not errors or ARRAY with them */
    public function getErrors()
    {
        return $this->_errors;
    }

    protected function getExpressCheckout()
    {
        $response = false;

        // get express checkout first:
        $getExpressCheckoutDetailsRequest = new GetExpressCheckoutDetailsRequestType($this->_token);
        $getExpressCheckoutReq = new GetExpressCheckoutDetailsReq();
        $getExpressCheckoutReq->GetExpressCheckoutDetailsRequest = $getExpressCheckoutDetailsRequest;

        $paypalService = new PayPalAPIInterfaceServiceService($this->_configuration);
        try {
            /* wrap API method calls on the service object with a try catch */
            $response = $paypalService->GetExpressCheckoutDetails($getExpressCheckoutReq);
        } catch (Exception $ex) {
            $this->collectErrors($response);
            return false;
        }

        if (!is_object($response) OR !property_exists($response, 'GetExpressCheckoutDetailsResponseDetails')) {
            $this->collectErrors($response);
            return false;
        }

        $details = $response->GetExpressCheckoutDetailsResponseDetails;
        if (!property_exists($details->PayerInfo, 'PayerID')) {
            $this->collectErrors($response);
            return false;
        }

        if ($details->PayerInfo->PayerID == '') {
            $this->collectErrors($response);
            return false;
        }

        //sets:
        $this->_paypalService = $paypalService;

        // debug:
        if ($this->_dev_mode) {
            echo 'get:' . "<br/>";
            print_r($response);
        }

        // ..
        return array(
            'PayerID' => $details->PayerInfo->PayerID,
            'Token' => $details->Token,
            'OrderTotalValue' => $details->PaymentDetails[0]->OrderTotal->value,
            'PaymentAction' => $this->_default_info['paymentType'],
            'currencyID' => $this->_default_info['currencyCode'],
        );
    }

    public function doExpressCheckout($token)
    {
        $this->_token = $token;
        $response = false;

        //
        $temp = $this->getExpressCheckout($token);
        if (!is_array($temp)) {
            return false;
        }

        /* doCheckout sets: */

        // order total
        $orderTotal = new BasicAmountType();
        $orderTotal->currencyID = $temp['currencyID'];
        $orderTotal->value = $temp['OrderTotalValue'];

        // set payment details:
        $paymentDetails = new PaymentDetailsType();
        $paymentDetails->OrderTotal = $orderTotal;

        // other info:
        $DoECRequestDetails = new DoExpressCheckoutPaymentRequestDetailsType();
        $DoECRequestDetails->PayerID = $temp['PayerID'];
        $DoECRequestDetails->Token = $temp['Token'];
        $DoECRequestDetails->PaymentAction = $temp['PaymentAction'];
        $DoECRequestDetails->PaymentDetails[0] = $paymentDetails;

        $DoECRequest = new DoExpressCheckoutPaymentRequestType();
        $DoECRequest->DoExpressCheckoutPaymentRequestDetails = $DoECRequestDetails;


        $DoECReq = new DoExpressCheckoutPaymentReq();
        $DoECReq->DoExpressCheckoutPaymentRequest = $DoECRequest;

        try {
            /* wrap API method calls on the service object with a try catch */
            $response = $this->_paypalService->DoExpressCheckoutPayment($DoECReq);
        } catch (Exception $ex) {
            $this->collectErrors($response);
            return false;
        }

        if (!is_object($response) OR !property_exists($response, 'DoExpressCheckoutPaymentResponseDetails')) {
            $this->collectErrors($response);
            return false;
        }

        $details = $response->DoExpressCheckoutPaymentResponseDetails;
        if (!isset($details->PaymentInfo[0]->PaymentStatus)) {
            $this->collectErrors($response);
            return false;
        } else {
            $this->_transactionId = $details->PaymentInfo[0]->TransactionID;
            $this->collectErrors($response);
        }

        // debug:
        if ($this->_dev_mode) {
            echo 'do:' . "<br/>";
            print_r($response);
        }

        // ..
        return $details->PaymentInfo[0]->PaymentStatus;
    }

    // returns false / string
    public function getTransactionId() {
        return $this->_transactionId;
    }
} 