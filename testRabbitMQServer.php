#!/usr/bin/php
<?php
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
function updateDBFromAPI()
{
	// data recieved from dmz server
	// figure out how to parse json data to update db
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
	case "updatedb":
		return updateDBFromAPI();
	case "login":
		$response = doLogin($request['username'],$request['password']);
		break;
	case "register":
		return register($request['username'],$request['password'],$request['email']);
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
