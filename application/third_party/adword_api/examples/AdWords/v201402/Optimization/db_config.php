<?php

if (!isset($_SESSION)) {
    session_start();
}
// Mysql Settings for proxy servers start

$host = "5.101.106.4:3306";
$database_name = "serp";
$database_user = "phoenixdb";
$database_password = "My6Celeb!!";

$con = mysql_connect($host, $database_user, $database_password);

if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db($database_name, $con);

