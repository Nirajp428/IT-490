#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$client = new rabbitMQClient("testRabbitMQ.ini","testServer");

$msg = $_SESSION['message'];
$timestamp = $_SESSION['timestamp'];
$type = $_SESSION['type'];

$request = array();
$request['type'] = $type;
$request['timestamp'] = $timestamp;
$request['username'] = "kevin";
$request['password'] = "password";
$request['message'] = $msg;
$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

#echo $argv[0]." END".PHP_EOL;

