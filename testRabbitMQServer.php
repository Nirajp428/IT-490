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
    // connect to DB
	$mydb = new mysqli('127.0.0.1','niraj','password','IT490 DB');
	
	// look if email and password hashes are in db
	$query = "SELECT * FROM Users WHERE email='$email' AND password='$password'";	
	$result = mysqli_query($con,$sql);
	$check = mysqli_fetch_array($result);
	if(isset($check)){
		echo 'Welcome';
		return 'true';
	} else {
		echo'Failed to login. Please try again';
		return 'false';
	}
}

# DB function
function register($username, $password, $email)
{
	// connect to DB
	$mydb = new mysqli('127.0.0.1','kevin','password','IT490db');

	if ($mydb->errno != 0)
	{
		echo "failed to connect to database: ". $mydb->error . PHP_EOL;
		logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php register function", php_uname('n'));
        	exit(0);
	}
	echo "successfully connected to database".PHP_EOL;


	// check if user exists
	$query = "SELECT * FROM Users WHERE email='$email';";
	$response = $mydb->query($query);

	if ($response->num_rows == 0) {
		// User not found, insert into DB
                $query = "INSERT INTO Users (email, password) VALUES ('$email', '$password');";
                echo "Successfully registered";
                return "True";

	} else {
		// Email already in DB, try again
		echo "Email is already in use, please register with a different email";
                return "False";
	}

	if ($mydb->errno != 0)
	{
        	echo "failed to execute query:".PHP_EOL;
		echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		logError(date('m-d-Y--h:i:s a'), "Failed to execute register query in testRabbitMQServer.php", php_uname('n'));
		exit(0);
	}
}

# DB function
function getMovie($movie)
{
	// connect to DB
        $mydb = new mysqli('127.0.0.1','kevin','password','IT490db');

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
	case "login":
		$response = doLogin($request['email'],$request['password']);
		return $response;
	case "register":
		return register($request['username'],$request['password'],$request['email']);
	case "request":
		$response = getMovie($request['movie']);
		return $response;
	case "APIrequest":
		$response = APICall($request['movie']);
                return $response;
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
