<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
define('SRC_PATH', APPPATH.'/third_party/adword_api/src/');
define('LIB_PATH', 'Google/Api/Ads/AdWords/Lib');
define('UTIL_PATH', 'Google/Api/Ads/Common/Util');
define('AW_UTIL_PATH', 'Google/Api/Ads/AdWords/Util');

define('ADWORDS_VERSION', 'v201402');

// Configure include path
ini_set('include_path', implode(array(
    ini_get('include_path'), PATH_SEPARATOR, SRC_PATH))
    );

// Include the AdWordsUser file
require_once SRC_PATH.LIB_PATH. '/AdWordsUser.php';


class My_adwords_api extends AdWordsUser { 
    public function __construct() { 
       parent::__construct();
    }     
    

}