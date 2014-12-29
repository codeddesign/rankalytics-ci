<?php

//error_reporting(E_STRICT | E_ALL); ini_set('display_errors', 1);
if (!isset($_SESSION)) {
    session_start();
}
// Mysql Settings for proxy servers start

require_once dirname(dirname(__FILE__)) . '/init.php';
require_once UTIL_PATH . '/MapUtils.php';

$host = "5.101.106.4:3306";
    $database_name = "serp";
    $database_user = "phoenixdb";
    $database_password = "My6Celeb";

    $con = mysql_connect($host, $database_user, $database_password);
     mysql_select_db($database_name, $con);
/**
 * Runs the example.
 * @param AdWordsUser $user the user to run the example with
 */
function EstimateKeywordTrafficExample(AdWordsUser $user) {
   
  $max_results = 20;
    $start_limit = 0;
    $end_limit = 3;
    // Get the service, which loads the required classes.
    $trafficEstimatorService = $user->GetService('TrafficEstimatorService', ADWORDS_VERSION);

    // Create keywords. Up to 2000 keywords can be passed in a single request.
    $keywords_query = "SELECT  * FROM tbl_project_keywords LIMIT $start_limit, $end_limit";
    $result_array = array();
    $update_array_array = array();
    //die($keywords_query);
    $keywords_query = mysql_query($keywords_query) or die(mysql_error());

    $keywords = array();
    while ($row = mysql_fetch_array($keywords_query)) {
        $keywords[] = new Keyword(urlencode($row['keyword']), 'EXACT');
       
       
        
        //$keywords_arr[]=$row;
    }
  

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
  $germany = new Location();
    $germany->id = 2276;
    $campaignEstimateRequest->criteria[] = $germany;

    $lang_de = new Language();
    $lang_de->id = 1001;
    $campaignEstimateRequest->criteria[] = $lang_de;

  // Create selector.
  $selector = new TrafficEstimatorSelector();
  $selector->campaignEstimateRequests[] = $campaignEstimateRequest;

  // Make the get request.
  $result = $trafficEstimatorService->get($selector);
  
  //print_r($result);

  // Display results.
  $keywordEstimates =
      $result->campaignEstimates[0]->adGroupEstimates[0]->keywordEstimates;
  for ($i = 0; $i < sizeof($keywordEstimates); $i++) {
    $keywordEstimateRequest = $keywordEstimateRequests[$i];
    // Skip negative keywords, since they don't return estimates.
    if (!$keywordEstimateRequest->isNegative) {
      $keyword = $keywordEstimateRequest->keyword;
      $keywordEstimate = $keywordEstimates[$i];
     // print_r($keywordEstimate);
      // Find the mean of the min and max values.
      $meanAverageCpc = ($keywordEstimate->min->averageCpc->microAmount
          + $keywordEstimate->max->averageCpc->microAmount) / 2;
      $meanAverageimpression = ($keywordEstimate->min->impressionsPerDay
          + $keywordEstimate->max->impressionsPerDay) / 2;
      $meanAveragePosition = ($keywordEstimate->min->averagePosition
          + $keywordEstimate->max->averagePosition) / 2;
      $meanClicks = ($keywordEstimate->min->clicksPerDay
          + $keywordEstimate->max->clicksPerDay) / 2;
      $meanTotalCost = ($keywordEstimate->min->totalCost->microAmount
          + $keywordEstimate->max->totalCost->microAmount) / 2;
      $ctc = ($keywordEstimate->min->clickThroughRate
          + $keywordEstimate->max->clickThroughRate) / 2;

      printf("Results for the keyword with text '%s' and match type '%s':\n",
          $keyword->text, $keyword->matchType);
      printf("  Estimated daily clicks: %d\n", $meanClicks);      
      printf("  Estimated average Impression per clicks: %.0f\n", $meanAverageimpression);        
      printf("  Estimated daily cost in micros: %.0f\n\n", $meanTotalCost);
       printf("  Estimated CTC: %.1f\n\n", $ctc*100);
      printf("  Estimated average CPC in micros: %.0f\n", $meanAverageCpc);
      printf("  Estimated ad position: %.2f \n", $meanAveragePosition);
      echo "<br/>";
    }
  }
}

// Don't run the example if the file is being included.
if (__FILE__ != realpath($_SERVER['PHP_SELF'])) {
  //return;
}

try {
  // Get AdWordsUser from credentials in "../auth.ini"
  // relative to the AdWordsUser.php file's directory.
  $user = new AdWordsUser();

  // Log every SOAP XML request and response.
  $user->LogAll();

  // Run the example.
  EstimateKeywordTrafficExample($user);
} catch (Exception $e) {
  printf("An error has occurred: %s\n", $e->getMessage());
}