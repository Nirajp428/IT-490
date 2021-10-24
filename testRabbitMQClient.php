#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

#$destination = $_SESSION['destination'];

#$msg = $_SESSION['message'];
#$timestamp = $_SESSION['timestamp'];
#$type = $_SESSION['type'];


$request = array();
$request['type'] = $_SESSION['type'];


switch ($type) {
	case "error":
		$request['timestamp'] = $_SESSION['timestamp'];
		$request['message'] = $_SESSION['message'];
		$client = new rabbitMQClient("testRabbitMQ.ini","logExchangeServer");
		break;
	case "login":
		$request['username'] = $_SESSION['username'];
		$request['password'] = $_SESSION['password'];
		$client = new rabbitMQClient("testRabbitMQ.ini","dbServer");
		break;
	case "register":
		$request['username'] = $_SESSION['username'];
                $request['password'] = $_SESSION['password'];
		$request['email'] = $_SESSION['email'];
		$client = new rabbitMQClient("testRabbitMQ.ini","dbServer");
		break;
	case "validateSession":
		$request['sessionID'] = $_SESSION['sessionID'];
                $client = new rabbitMQClient("testRabbitMQ.ini","dbServer");
                break;
}

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
print_r($response);
echo "\n\n";

#echo $argv[0]." END".PHP_EOL;

