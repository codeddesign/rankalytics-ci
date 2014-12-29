<?php
error_reporting(E_ALL);

// load requirements:
function __autoload($className) {
    require_once 'classes/'.$className.'.php';
}

// initiate:
$api = new API;
$api->processApi();
?>