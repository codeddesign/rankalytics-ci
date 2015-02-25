<?php
$config['Subscriptions_Lib_Config'] = array(
    'currency'   => array(
        'code'   => 'usd',
        'symbol' => '$',
    ),
    'tax'        => 19,
    'month_days' => 28,
    'prices'     => array(
        'ranktracker' => array(
            'starter'    => 29,
            'pro'        => 99,
            'enterprise' => 449,
            'free'    => 0,
        ),
        'seocrawl'    => array(
            'starter'    => 29,
            'pro'        => 99,
            'enterprise' => 149,
            'free'       => 0,
        ),
    ),
    'names'      => array(
        'ranktracker' => 'Rank Tracker',
        'seocrawl'    => 'SEO Crawl',
    ),
    'limits'     => array(
        'ranktracker' => array(
            'starter'        => array(
                'number' => '200',
                'text'   => '200',
            ),
            'pro'        => array(
                'number' => '1000',
                'text'   => '1,000',
            ),
            'enterprise' => array(
                'number' => '10000',
                'text'   => '10,000',
            ),
            'free'    => array(
                'number' => '30',
                'text'   => '30',
            ), // not-paid
        ),
        'seocrawl'    => array(
            'starter'    => array(
                'number' => '35000',
                'text'   => '35,000',
            ),
            'pro'        => array(
                'number' => '250000',
                'text'   => '250,000',
            ),
            'enterprise' => array(
                'number' => '1000000',
                'text'   => '1 Million',
            ),
            'free'       => array(
                'number' => '30',
                'text'   => '30',
            ), // not-paid
        ),
    )
);