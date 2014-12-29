<?php

class Configuration
{
    // For a full list of configuration parameters refer in wiki page (https://github.com/paypal/sdk-core-php/wiki/Configuring-the-SDK)
    public static function getConfig()
    {
        $config = array(
            // values: 'sandbox' for testing
            //       'live' for production
            "mode" => "sandbox"
            // These values are defaulted in SDK. If you want to override default values, uncomment it and add your value.
            // "http.ConnectionTimeOut" => "5000",
            // "http.Retry" => "2",

        );
        return $config;
    }

    // Creates a configuration array containing credentials and other required configuration parameters.
    public static function getAcctAndConfig()
    {
        $config = array(
            // Signature Credential
            "acct1.UserName" => "thomas.stehle-facilitator_api1.tomste.de",
            "acct1.Password" => "4VTGWVAQUQXFN887",
            "acct1.Signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31ARsdAwCGSx211-oFcDUIcpc8.DgI",
            // Subject is optional and is required only in case of third party authorization
            //"acct1.Subject" => "",
        );

        return array_merge($config, self::getConfig());
    }

}


