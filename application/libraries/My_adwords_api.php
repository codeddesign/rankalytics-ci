<?php
if ( ! defined( 'BASEPATH' )) {
    exit( 'No direct script access allowed' );
}

$lib_dir = realpath( __DIR__ . '/../third_party/google-api/vendor' );

require_once $lib_dir . '/autoload.php';

// init:
define( 'SRC_PATH', $lib_dir . '/googleads/googleads-php-lib/src/' );
define( 'LIB_PATH', 'Google/Api/Ads/AdWords/Lib' );
define( 'UTIL_PATH', 'Google/Api/Ads/Common/Util' );
define( 'ADWORDS_UTIL_PATH', 'Google/Api/Ads/AdWords/Util' );

define( 'ADWORDS_VERSION', 'v201409' );

// Configure include path
ini_set( 'include_path', implode( array(
    ini_get( 'include_path' ),
    PATH_SEPARATOR,
    SRC_PATH
) ) );

class My_adwords_api extends AdWordsUser
{
    public function __construct()
    {
        parent::__construct();
    }


}