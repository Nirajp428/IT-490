#!/usr/bin/php
<?php
session_start();
function sendError($message)
{
	$_SESSION['type'] = "error";
	$_SESSION['timestamp'] = date('m-d-Y--h:i:s a');
	$_SESSION['message'] = $message;
	include_once "testRabbitMQClient.php";
}

$message="Send full messages";
sendError($message);
?>
