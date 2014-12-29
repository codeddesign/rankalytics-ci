<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
define('SRC_PATH', APPPATH.'/third_party/Adwords/src/');
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


class My_adwords extends AdWordsUser { 
    public function __construct() { 
       parent::__construct();
    }     
    
 function GetCampaigns() {
   // Get the service, which loads the required classes.
   $campaignService = $this->GetService('CampaignService', ADWORDS_VERSION);
 
   // Create selector.
   $selector = new Selector();
   $selector->fields = array('Id', 'Name');
   $selector->ordering[] = new OrderBy('Name', 'ASCENDING');
 
   // Create paging controls.
   $selector->paging = new Paging(0, AdWordsConstants::RECOMMENDED_PAGE_SIZE);
 
   do {
     // Make the get request.
     $page = $campaignService->get($selector);
 
     // Display results.
     if (isset($page->entries)) {
       foreach ($page->entries as $campaign) {
         printf("Campaign with name '%s' and ID '%s' was found.\n",
             $campaign->name, $campaign->id);
       }
     } else {
       print "No campaigns were found.\n";
     }
 
     // Advance the paging index.
     $selector->paging->startIndex += AdWordsConstants::RECOMMENDED_PAGE_SIZE;
   } while ($page->totalNumEntries > $selector->paging->startIndex);
 }  
 
 function GetOAuth2Credential($user) {
 	$redirectUri = NULL;
 	$offline = TRUE;
 	// Get the authorization URL for the OAuth2 token.
 	// No redirect URL is being used since this is an installed application. A web
 	// application would pass in a redirect URL back to the application,
 	// ensuring it's one that has been configured in the API console.
 	// Passing true for the second parameter ($offline) will provide us a refresh
 	// token which can used be refresh the access token when it expires.
 	$OAuth2Handler = $user->GetOAuth2Handler();
 	$authorizationUrl = $OAuth2Handler->GetAuthorizationUrl(
 			$user->GetOAuth2Info(), $redirectUri, $offline);
 
 	// In a web application you would redirect the user to the authorization URL
 	// and after approving the token they would be redirected back to the
 	// redirect URL, with the URL parameter "code" added. For desktop
 	// or server applications, spawn a browser to the URL and then have the user
 	// enter the authorization code that is displayed.
 	printf("Log in to your AdWords account and open the following URL:\n%s\n\n",
 	$authorizationUrl);
 	print "After approving the token enter the authorization code here: ";
 	$stdin = fopen('php://stdin', 'r');
 	$code = trim(fgets($stdin));
 	fclose($stdin);
 	print "\n";
 
 	// Get the access token using the authorization code. Ensure you use the same
 	// redirect URL used when requesting authorization.
 	$user->SetOAuth2Info(
 			$OAuth2Handler->GetAccessToken(
 					$user->GetOAuth2Info(), $code, $redirectUri));
 
 
 	// The access token expires but the refresh token obtained for offline use
 	// doesn't, and should be stored for later use.
 	return $user->GetOAuth2Info();
 }
 
 function get_access_token()
 {
 	$clientId = "410637168219-8jjbillguhrba23r10dn6orc98kgo5t1.apps.googleusercontent.com";
 	$clientSecret = "bepGLVhNz_i7mRxUVkJbXZSD";
 	$callbackUrl = "http://rankalytics.com/adwords";
 	
 	// Create a new user and set the oAuth settings
 	$user = new AdWordsUser();
 	$user->SetOAuth2Info(array(
 			"client_id" => $clientId,
 			"client_secret" => $clientSecret
 	));
 	
 	//$authUrl = $user->GetOAuth2AuthorizationUrl($callbackUrl, true);
 	
 	//header("Location: $authUrl");
 	
 	$authCode = $_REQUEST["code"];
 	$user->GetOAuth2AccessToken($authCode, $callbackUrl);
 	$oauthInfo = $user->GetOAuth2Info();
 	echo '<pre>';print_r($oauthInfo);
 	echo '<pre>'; print_r($user);
 }
 
 function EstimateKeywordTrafficExample(AdWordsUser $user) {
 	// Get the service, which loads the required classes.
 	$trafficEstimatorService =
 	$user->GetService('TrafficEstimatorService', ADWORDS_VERSION);
 
 	// Create keywords. Up to 2000 keywords can be passed in a single request.
 	$keywords = array();
 	$keywords[] = new Keyword('mars cruise', 'BROAD');
 	$keywords[] = new Keyword('cheap cruise', 'PHRASE');
 	$keywords[] = new Keyword('cruise', 'EXACT');
 
 	// Negative keywords don't return estimates, but adjust the estimates of the
 	// other keywords in the hypothetical ad group.
 	$negativeKeywords = array();
 	$negativeKeywords[] = new Keyword('moon walk', 'BROAD');
 
 	// Create a keyword estimate request for each keyword.
 	$keywordEstimateRequests = array();
 	foreach ($keywords as $keyword) {
 		$keywordEstimateRequest = new KeywordEstimateRequest();
 		$keywordEstimateRequest->keyword = $keyword;
 		$keywordEstimateRequests[] = $keywordEstimateRequest;
 	}
 
 	// Create a keyword estimate request for each negative keyword.
 	foreach ($negativeKeywords as $negativeKeyword) {
 		$keywordEstimateRequest = new KeywordEstimateRequest();
 		$keywordEstimateRequest->keyword = $negativeKeyword;
 		$keywordEstimateRequest->isNegative = TRUE;
 		$keywordEstimateRequests[] = $keywordEstimateRequest;
 	}
 
 	// Create ad group estimate requests.
 	$adGroupEstimateRequest = new AdGroupEstimateRequest();
 	$adGroupEstimateRequest->keywordEstimateRequests = $keywordEstimateRequests;
 	$adGroupEstimateRequest->maxCpc = new Money(1000000);
 
 	// Create campaign estimate requests.
 	$campaignEstimateRequest = new CampaignEstimateRequest();
 	$campaignEstimateRequest->adGroupEstimateRequests[] = $adGroupEstimateRequest;
 
 	// Set targeting criteria. Only locations and languages are supported.
 	$unitedStates = new Location();
 	$unitedStates->id = 2840;
 	$campaignEstimateRequest->criteria[] = $unitedStates;
 
 	$english = new Language();
 	$english->id = 1000;
 	$campaignEstimateRequest->criteria[] = $english;
 
 	// Create selector.
 	$selector = new TrafficEstimatorSelector();
 	$selector->campaignEstimateRequests[] = $campaignEstimateRequest;
 
 	// Make the get request.
 	$result = $trafficEstimatorService->get($selector);
 
 	// Display results.
 	$keywordEstimates =
 	$result->campaignEstimates[0]->adGroupEstimates[0]->keywordEstimates;
 	for ($i = 0; $i < sizeof($keywordEstimates); $i++) {
 		$keywordEstimateRequest = $keywordEstimateRequests[$i];
 		// Skip negative keywords, since they don't return estimates.
 		if (!$keywordEstimateRequest->isNegative) {
 			$keyword = $keywordEstimateRequest->keyword;
 			$keywordEstimate = $keywordEstimates[$i];
 
 			// Find the mean of the min and max values.
 			$meanAverageCpc = ($keywordEstimate->min->averageCpc->microAmount
 					+ $keywordEstimate->max->averageCpc->microAmount) / 2;
 			$meanAveragePosition = ($keywordEstimate->min->averagePosition
 					+ $keywordEstimate->max->averagePosition) / 2;
 			$meanClicks = ($keywordEstimate->min->clicksPerDay
 					+ $keywordEstimate->max->clicksPerDay) / 2;
 			$meanTotalCost = ($keywordEstimate->min->totalCost->microAmount
 					+ $keywordEstimate->max->totalCost->microAmount) / 2;
 
 			printf("Results for the keyword with text '%s' and match type '%s':\n",
 			$keyword->text, $keyword->matchType);
 			printf("  Estimated average CPC in micros: %.0f\n", $meanAverageCpc);
 			printf("  Estimated ad position: %.2f \n", $meanAveragePosition);
 			printf("  Estimated daily clicks: %d\n", $meanClicks);
 			printf("  Estimated daily cost in micros: %.0f\n\n", $meanTotalCost);
 		}
 	}
 }
}