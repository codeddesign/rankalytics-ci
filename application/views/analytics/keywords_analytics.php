<?php 
// AdWordsUser.php directly via require_once.
// $path = '/path/to/pda_api_php_lib/src';
$path = '/var/www/assets/googleads-php-lib/src';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

//require_once 'Google/Api/Ads/AdWordsUser/Lib/AdWordsUser.php';
require_once '/var/www/assets/googleads-php-lib/src/Google/Api/Ads/AdWords/Lib/AdWordsUser.php';

$user = new AdWordsUser(null, 'thomas.stehle@booming.de', 'My6Celeb', 'VQZgOLzIPPFrEcuc_DPIcA');
$user->SetDefaultServer("https://adwords.google.com/");
$user->SetClientId('3815361686');
define('ADWORDS_VERSION','v201402');
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

			echo lang('keywordanalytics.keywordanalytics');
			$keyword->text, $keyword->matchType);
			echo lang('keywordanalytics.estmicros'); printf(" %.0f\n", $meanAverageCpc);
			echo lang('keywordanalytics.estad'); printf(" %.2f \n", $meanAveragePosition);
			echo lang('keywordanalytics.estnum'); printf(" %d\n", $meanClicks);
			echo lang('keywordanalytics.estcost'); printf(" %.0f\n\n", $meanTotalCost);
		}
	}
}



try {
	// Get AdWordsUser from credentials in "../auth.ini"
	// relative to the AdWordsUser.php file's directory.
	$user = new AdWordsUser(null, 'thomas.stehle@booming.de', 'My6Celeb', 'VQZgOLzIPPFrEcuc_DPIcA');
	$user->SetDefaultServer("https://adwords.google.com/");
	$user->SetClientId('3815361686');

	// Log every SOAP XML request and response.
	$user->LogAll();

	// Run the example.
	EstimateKeywordTrafficExample($user);
} catch (Exception $e) {
	echo lang('keywordanalytics.error'); printf(" %s\n", $e->getMessage());
}

?>