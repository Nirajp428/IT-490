#!/usr/bin/php
<?php
if (!isset($_SESSION)) { session_start(); }
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$type = $_SESSION['type'];
$request = array();


switch ($type) {
	case "error":
		$request['type'] = $_SESSION['type'];
		$request['source'] = $_SESSION['source'];
		$request['timestamp'] = $_SESSION['timestamp'];
		$request['message'] = $_SESSION['message'];
		$client = new rabbitMQClient("testRabbitMQ.ini","logExchangeServer");
		break;
	case "login":
		$request['type'] = $_SESSION['type'];
		$request['username'] = $_SESSION['username'];
		$request['password'] = $_SESSION['password'];
		$client = new rabbitMQClient("testRabbitMQ.ini","dbServer");
		break;
	case "register":
		$request['type'] = $_SESSION['type'];
		$request['username'] = $_SESSION['username'];
                $request['password'] = $_SESSION['password'];
		$request['email'] = $_SESSION['email'];
		$client = new rabbitMQClient("testRabbitMQ.ini","dbServer");
		break;
	case "request":
		$request['type'] = $_SESSION['type'];
		$request['movie'] = $_SESSION['movie'];
		$client = new rabbitMQClient("testRabbitMQ.ini","dbServer");
		break;
	case "APIrequest":
		$request['type'] = $_SESSION['type'];
                $request['movie'] = $_SESSION['movie'];
                $client = new rabbitMQClient("testRabbitMQ.ini","dmzServer");
                break;
	case "validateSession":
		$request['type'] = $_SESSION['type'];
		$request['sessionID'] = $_SESSION['sessionID'];
                $client = new rabbitMQClient("testRabbitMQ.ini","dbServer");
                break;
}

$response = $client->send_request($request);
//$response = $client->publish($request);

echo "client received response: ".PHP_EOL;
#print_r($response);
return $response;
echo "\n\n";

#echo $argv[0]." END".PHP_EOL;

