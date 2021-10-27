#!/usr/bin/php
<?php
session_start();
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function logError($timestamp, $message, $source)
{
	$currentUser = get_current_user();

	#append function paramaters to log file
	$logfile = fopen("/home/$currentUser/Desktop/logfile.txt", "a") or die("Unable to open file!");
	
	fwrite($logfile, "$timestamp\t$source\t$message\n");
	fclose($logfile);
}

#DB function
function validateSession($sessionID)
{
	// everytime a user accesses a page
	// check DB table if sessionID is still valid
	// return true if valid
	// return false if not, redirect user to login page
}

# DB function
function doLogin($username,$password)
{
    // lookup username in database
    // check if password hash matches username
	return True;
    // return true;
    //else return false if not valid
}

# DB function
function register($username, $password, $email)
{
	// check if user already exists
	// if false, add user information to db table
	// tell user their account has been successfully created
	// prompt log in page
}

# DB function
function getMovie($movie)
{
	//code to check if $movie is already in DB
	//If true, return the movie details to the front end
	//If false, send the $movie to the dmz server

	// $result = SELECT * FROM table WHERE movieTitle == $movie;
	$result == "True";
	if ($result == "True") {
		return "True";
	} elseif ($result == "False") {
		$_SESSION['type'] = 'APIrequest';
		$_SESSION['movie'] = "$movie";
        	$response = require("testRabbitMQClient.php");
       	 	return $response;
	
	} else {
		logError(date('m-d-Y--h:i:s a'), "Boolean error in getMovie() in testRabbitMQServer.php", php_uname('n'));
	}
}



function requestProcessor($request)
{
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
	case "error":
		return logError($request['timestamp'], $request['message'], $request['source']);
	case "login":
		$response = doLogin($request['username'],$request['password']);
		break;
	case "register":
		return register($request['username'],$request['password'],$request['email']);
	case "request":
		$response = getMovie($request['movie']);
		break;
	case "validate_session":
		return validateSession($request['sessionId']);
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

# $server only cares about the queue its assigned in the testRabbitMQ.ini file. 
$server = new rabbitMQServer("testRabbitMQ.ini","logExchangeServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>
