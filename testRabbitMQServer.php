#!/usr/bin/php
<?php
if (!isset($_SESSION)) { session_start(); }
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');


// Logs errors to 4 VM's
function logError($timestamp, $message, $source)
{
	$currentUser = get_current_user();

	#append function paramaters to log file
	$logfile = fopen("/home/$currentUser/Desktop/logfile.txt", "a") or die("Unable to open log file!");
	
	fwrite($logfile, "$timestamp\t$source\t$message\n");
	fclose($logfile);
}

// DMZ function
function APICall($movie)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.omdbapi.com/?t=$movie&apikey=a7bdaf57");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$contents = curl_exec($ch);
	return $contents;
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
	case "APIrequest":
		$response = APICall($request['movie']);
                return $response;
  }
}

# $server only cares about the queue its assigned in the testRabbitMQ.ini file. 
$server = new rabbitMQServer("testRabbitMQ.ini","logExchangeServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;


exit();
?>
