#!/usr/bin/php
<?php
session_start();
function sendError($message)
{
	$_SESSION['type'] = "error";
	$_SESSION['source'] = php_uname('n');
	$_SESSION['timestamp'] = date('m-d-Y--h:i:s a');
	$_SESSION['message'] = $message;
	include_once "testRabbitMQClient.php";
}

sendError("Send test logging message");

function sendLogin($username, $password)
{
	$_SESSION['type'] = "login";
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
        include_once "testRabbitMQClient.php";
}

#sendLogin("kevin", "password");
?>
