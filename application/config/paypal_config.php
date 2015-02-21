<?php
$config['paypal_config'] = array(
    'sandbox' => array(
        'client' => array(
            'id' => 'AfV0AWcmP57Rh17YA-7cgL-gr17RsEDjJprUT8bs2XZaHDH2H7tTnJ-wmlxnXsB-Qdzlk0EcCvmSiZKS',
            'secret' => 'EHTefL4FAwvNN13-qMY3iwj1nkRJa_qGyYDlbQ3xcq9qsX3w8DHF7bc27q2rm_bV1P9zng-plQeKsGZ3',
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