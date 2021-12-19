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

# DB function

function getMovie($movie)
{
	// connect to DB
        $mydb = new mysqli('127.0.0.1','matt','12345','IT490DB');

        if ($mydb->errno != 0)
        {
                echo "failed to connect to database: ". $mydb->error . PHP_EOL;
                logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php getMovie function", php_uname('n'));
                exit(0);
        }
        echo "successfully connected to database".PHP_EOL;

	// query to check if $movie is already in DB
	$query = "SELECT * FROM Movies WHERE title='$movie';";
	$response = $mydb->query($query);

        if ($response->num_rows == 0) {
		// Movie not in DB, get Movie from dmz server
		echo "$movie not found in database, calling dmz server\n";
		$_SESSION['type'] = 'APIrequest';
                $_SESSION['movie'] = "$movie";

                // call dmz server
		$response = require("testRabbitMQClient.php");
		$array = json_decode($response, true);

		if ($array['Title'] == NULL)
                {
                        echo "API does not have $movie listed";
                        return "API does not have $movie listed";
                }

		$title = $array['Title'];
		$year = $array['Year'];
		$rated = $array['Rated'];
		$released = $array['Released'];
		$runtime = $array['Runtime'];
		$genre = $array['Genre'];
		$directors = $array['Director'];
		$actors = $array['Actors'];
		$plot = $array['Plot'];
		$sqlPlot = str_replace("'", "''", "$plot");
		$poster = $array['Poster'];
		$imdbRating = $array['imdbRating'];
		$type = $array['Type'];
		$totalSeasons = $array['totalSeasons'];

                // add $response which contains results form API call to DB
                $query = "INSERT INTO Movies (title, year, rated, released, runtime, genre, director, actors, plot, poster, imdbRating, contentType, seasons) VALUES ('$title', '$year', '$rated', '$released', '$runtime', '$genre', '$directors', '$actors', '$sqlPlot', '$poster', '$imdbRating', '$type', '$totalSeasons');";
		
		if ($mydb->query($query) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $query . "<br>" . $mydb->error;
		}
		// return $api info to db
		$query = "SELECT * FROM  Movies WHERE title='$movie';";
                $result = mysqli_query($mydb, $query);
                $row = mysqli_fetch_assoc($result);

                // return movie info to front end
                return $row;

	} else { 
		// movie found in DB
		$query = "SELECT * FROM  Movies WHERE title='$movie';";
		$result = mysqli_query($mydb, $query);
		$row = mysqli_fetch_assoc($result);

                // return movie info to front end
		return $row;
	}
}
 
// DMZ function
function APICall($movie)
{
	$nmovie = preg_replace('/\s+/', '+', $movie);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.omdbapi.com/?t=$nmovie&apikey=a7bdaf57");
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
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}

# $server only cares about the queue its assigned in the testRabbitMQ.ini file. 
$server = new rabbitMQServer("testRabbitMQ.ini","dmzServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;


exit();
