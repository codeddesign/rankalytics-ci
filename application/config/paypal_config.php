<?php
$config['paypal_config'] = array(
    'sandbox' => array(
        'client' => array(
            'id' => 'AY3HihB-rbuPggyVBVgoDg7joIUjfmbnuoeRQhCIzuFNOc8RP5H634Yt8JlM',
            'secret' => 'EKgzPBDRiXUxUcur71v6TRceVqpGc4pF687AOo7ZT8zfhWMKtGTP8ZMvuQf6',
        ),
        'config' => array(
            'mode'             => 'sandbox',
            'log.LogEnabled'   => true,
            'log.FileName'     => 'PayPal.log',
            'log.LogLevel'     => 'DEBUG', // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'validation.level' => 'log',
            'cache.enabled'    => true,
        ),
    ),
    'live' => array(
        'client' => array(
            'id' => '', # todo
            'secret' => '', #todo
        ),
        'config' => array(
            'mode' => 'live',
            'cache.enabled' => 'true',
        )
    ),
    'agreement' => array(
        'name' => 'Rankalytics.com Base Agreement',
        'description' => 'Rankalytics.com main agreement',
    ),
    'plan' => array(
        'description' => 'Rankalytics.com subscriptions on chosen modules'
    )
);