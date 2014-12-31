<?php
$config['Subscriptions_Lib_Config'] = array(
    'currency' => array(
        'code' => 'usd',
        'symbol' => '$',
    ),
    'tax' => 19,
    'month_days' => 28,
    'prices' => array(
        'ranktracker' => array(
            'pro' => 49,
            'enterprise' => 199,
            'starter' => 0,
        ),
        'seocrawl' => array(
            'starter' => 59,
            'pro' => 129,
            'enterprise' => 299,
            'free' => 0,
        ),
    ),
    'limits' => array(
        'ranktracker' => array(
            'pro' => array(
                'number' => '2000',
                'text' => '2,000',
            ),
            'enterprise' => array(
                'number' => '10000',
                'text' => '10,000',
            ),
            'starter' => array(
                'number' => '30',
                'text' => '30',
            ), // not-paid
        ),
        'seocrawl' => array(
            'starter' => array(
                'number' => '35000',
                'text' => '35,000',
            ),
            'pro' => array(
                'number' => '250000',
                'text' => '250,000',
            ),
            'enterprise' => array(
                'number' => '1000000',
                'text' => '1 Million',
            ),
            'free' => array(
                'number' => '30',
                'text' => '30',
            ), // not-paid
        ),
    )
);