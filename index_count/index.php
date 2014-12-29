<?php
// first we check:
if ((isset($_GET['page']) && $_GET['page']) || $_POST['page']) {
    if (isset($_GET['page']) || $_GET['page']) {
        $search_string = ($_GET['page']);
    } else if ($_POST['page']) {
        $search_string = ($_POST['page']);
    }
} else {
    exit('parameter error');
}

// load requirements:
function __autoload($className)
{
    require_once $className . '.php';
}

// SETS:
$MAX_ATTEMPTS = 3;
$attempt = 0;
$parts = parse_url($search_string);

// db init:
$dbo = new DbHandle();

// get 100 random proxies from database:
$query = 'SELECT * FROM "proxy" WHERE "google_blocked"=\'0\' order by random() LIMIT 100'. '';
$proxies = $dbo->getProxies($query);
for($i=0;$i<3; $i++) {
    shuffle($proxies);
}

// obj init:
$ic = new IndexCount($parts['domain'], $proxies);
$ic->doGoogleSearch();
while($ic->isBlocked() && $attempt <= $MAX_ATTEMPTS) {
    $ic->doGoogleSearch();
    $attempt++;
}

// out:
$total_result = ($ic->getIndexedCount() === false) ? 'N/A' : $ic->getIndexedCount();
exit(json_encode(array("ic_count" => $total_result)));
?>