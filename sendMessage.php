#!/usr/bin/php
<?php
session_start();

function sendError($message)
{
	$_SESSION['type'] = "error";
	$_SESSION['source'] = php_uname('n');
	$_SESSION['timestamp'] = date('m-d-Y--h:i:s a');
	$_SESSION['message'] = $message;
	include_once 'testRabbitMQClient.php';
}
#sendError("Send test logging message");

function sendLogin($username, $password)
{
	$_SESSION['type'] = "login";
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
	$response = require("testRabbitMQClient.php");
	return $response;
}
#$response = sendLogin("kevin", "password");
if ($response == "True")
{
	echo "Returned True\n";
} elseif ($response == "False") {
	echo "Returned False\n";
} else {
	sendError("Call to SendLogin (sendMessage.php) did not return a valid response of True or False");
}

function sendRequest($movie)
{
	$_SESSION['type'] = 'request';
	$_SESSION['movie'] = "$movie";
	$response = require("testRabbitMQClient.php");
	return $response
}
#$response = sendRequest("marvel");
#echo $response
?>
