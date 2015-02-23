<?php
$config['paypal_config'] = array(
    'sandbox' => array(
        'client' => array(
            'id' => 'AcGmoTTVuDx_GNVn8jZTP-GuDrT-_Qi0jZ4_l0IpUC8K7qxIyBBQxjY_FMdzYPpHygsuvYBcm-sc4_qR',
            'secret' => 'EEisSAiYRhNxEsaq6q0RraLV4wDy97jeZOVJe6j16Sml67O8P8sc_YvHikYGZGLArhVpwIO1RUmPpu0J',
        ),
        'config' => array(
            'mode'             => 'sandbox',
            /*'log.LogEnabled'   => true,
            'log.FileName'     => 'PayPal.log',
            'log.LogLevel'     => 'DEBUG', // PLEASE USE `FINE` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
            'validation.level' => 'log',
            'cache.enabled'    => true,*/
        ),
    ),
    'live' => array(
        'client' => array(
            'id' => '', # todo
            'secret' => '', #todo
        ),
        'config' => array(
            'mode' => 'live',
            /*'cache.enabled' => 'true',*/
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