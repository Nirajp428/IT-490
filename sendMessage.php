#!/usr/bin/php
<?php
if (!isset($_SESSION)) { session_start(); }

function sendError($message)
{
	$_SESSION['type'] = "error";
	$_SESSION['source'] = php_uname('n');
	$_SESSION['timestamp'] = date('m-d-Y--h:i:s a');
	$_SESSION['message'] = $message;
	include_once 'testRabbitMQClient.php';
}
#sendError("Send test logging message");

function sendLogin($email, $password)
{
	$_SESSION['type'] = "login";
	$_SESSION['email'] = $email;
	$_SESSION['password'] = $password;
	$response = require("testRabbitMQClient.php");
	if ($response == "true")
 	{
        	echo "Returned true\n";
	} elseif ($response == "false") {
		echo "Returned false\n";
	} else {
		sendError("Call to SendLogin (sendMessage.php) did not return a valid response of True or False");
	}
}
$response = sendLogin("niraj", "password");

function sendRequest($movie)
{
        $_SESSION['type'] = 'request';
        $_SESSION['movie'] = "$movie";
        $response = require("testRabbitMQClient.php");
        echo $response['title'] . "\n";
        echo $response['year'] . "\n";
        echo $response['rated'] . "\n";
        echo $response['released'] . "\n";
        echo $response['runtime'] . "\n";
        echo $response['genre'] . "\n";
        echo $response['director'] . "\n";
        echo $response['actors'] . "\n";
        echo $response['plot'] . "\n";
}
#$response = sendRequest("marvel");
$response = sendRequest("belfast");

function friend_request($id, $friend_username)
{
	$_SESSION['type'] = "friend_request";
	$_SESSION['id'] = $id;
	$_SESSION['friendUsername'] = $friend_username;
        $response = require('testRabbitMQClient.php');
        echo $response;
}


function friend_request($id, $friend_username)
{
	$_SESSION['type'] = "friend_request";
	$_SESSION['id'] = $id;
	$_SESSION['friendUsername'] = $friend_username;
        $response = require('testRabbitMQClient.php');
        echo $response;
}


function register($username, $password, $email)
{
	$_SESSION['type'] = "register";
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
	$_SESSION['email'] = $email;
	$response = require('testRabbitMQClient.php');
	echo $response;
}
#register("kevin", "password", "km687@njit.edu");

?>
