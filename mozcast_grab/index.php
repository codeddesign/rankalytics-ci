<?php
if (!isset($_SESSION)) {
    session_start();
}

// load requirements:
function __autoload($className) {
    require_once $className.'.php';
}

// init db:
$dbo = new DbHandle();

// ..
$html = file_get_contents("http://mozcast.com/");

preg_match('/var\s*graphData\s*\=\s*(\{[.\s\S]*\}\;)/', $html, $matches, PREG_OFFSET_CAPTURE);

preg_match('/temps:\s\[([.\s\S]*)\],/', $matches[0][0], $temps, PREG_OFFSET_CAPTURE);
$temps_array = array();

$temps_string = str_replace("temps: [", "", $temps[0][0]);
$temps_string = trim(str_replace("],", "", $temps_string));

$temps_string = rtrim($temps_string, ",");
$temps_array = explode(",", $temps_string);


preg_match('/dates:\s\[([.\s\S]*)\]/', $matches[0][0], $date, PREG_OFFSET_CAPTURE);
$date_array = array();

$date_string = str_replace("dates: [", "", $date[0][0]);
$date_string = trim(str_replace("]", "", $date_string));
$date_string = trim(str_replace("'", "", $date_string));

$date_string = rtrim($date_string, ",");
$date_array = explode(",", $date_string);
$sql_array = array();
$date = date("Y-m-d H:i:s");
foreach ($date_array as $key => $value) {
    //$celsius = @(trim($temps_array[$key]) - 32) * 5 / 9;
    $fahrenheit = @(trim($temps_array[$key]));
    $sql_array[] = "('" . round($fahrenheit) . "', '" . trim($date_array[$key]) . "', '" . $date . "')";
}

// ..
$query = "TRUNCATE table tbl_mozcast";
$dbo->runQuery($query);

// ..
$query = "INSERT INTO tbl_mozcast(temperature, date, date_crawled) VALUES " . implode(',', $sql_array);
$dbo->runQuery($query);
echo "inserted";
?>