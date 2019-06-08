<?php 
// error_reporting(E_ERROR | E_PARSE);

require "vendor/autoload.php";
$client = new MongoDB\Client; //connect to mongo db server

$db = $client->demo_db;
$user_collection = $db->users; //colection variable

//define site ur constant 
define("SITE_URL", "http://localhost/demo/");

?>