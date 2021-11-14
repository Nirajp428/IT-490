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
function doLogin($email, $password)
{
     $mydb = new mysqli('127.0.0.1','matt','12345','IT490DB');

     if ($mydb->errno != 0)
        {
                echo "failed to connect to database: ". $mydb->error . PHP_EOL;
                logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php register function", php_uname('n'));
                exit(0);
     }

       if (isset($mydb)){
	       $query = ("SELECT id, email, username, password from Users WHERE '$email' = email LIMIT 1");
		$result = $mydb->query($query);
	       $row = mysqli_fetch_assoc($result);

	   if ($row && isset($row["password"])){
		$dbPassHash = $row["password"];
		if (password_verify($password, $dbPassHash)){
			unset($row["password"]);
			echo json_encode($row);
			return $row;
			$mydb->close();
		}
		else{
			return "False";
			echo "Invalid Password";
		}
	    }
	    else{
		return "False";
		echo "Invalid User";
            }
	   }
}

# DB function
function register($username, $password, $email)
{
	// connect to DB
	$mydb = new mysqli('127.0.0.1','matt','12345','IT490DB');

	if ($mydb->errno != 0)
	{
		echo "failed to connect to database: ". $mydb->error . PHP_EOL;
		logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php register function", php_uname('n'));
        	exit(0);
	}
	echo "successfully connected to database".PHP_EOL;


	// check if user exists
	$query = "SELECT * FROM Users WHERE email='$email'";
	$response = $mydb->query($query);
	if(!$response->num_rows == 0) {
		echo "Email is already in use, please register with a different email";
		return "False";
	} else {
		// If user not found, insert into DB
		$query = ("INSERT INTO Users(email, password, username) VALUES ('$email', '$password', '$username')");

		if ($mydb->query($query) == TRUE){	
			echo "Successfully registered";
			return "True";
			$mydb->close();
		}			
		else {
			echo "Error";
		}
	}

	if ($mydb->errno != 0)
	{
        	echo "failed to execute query:".PHP_EOL;
		echo __FILE__.':'.__LINE__.":error: ".$mydb->error.PHP_EOL;
		logError(date('m-d-Y--h:i:s a'), "Failed to execute register query in testRabbitMQServer.php", php_uname('n'));
		exit(0);
	}
}

#DB function connection to friend request database 
function dbConnection(){
         $db_host = "localhost";
         $db_name = "frnd_req_system";
         $db_username = "root";
         $db_password = "";
         
         $dsn_db = 'mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8';
         try{
            $site_db = new PDO($dsn_db, $db_username, $db_password);
            $site_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $site_db;

         }catch (PDOException $e){
            echo $e->getMessage();
            exit;
       }
} 

# DB function
function getMovie($movie)
{
	//code to check if $movie is already in DB
	//If true, return the movie details to the front end
	//If false, send the $movie to the dmz server

	// $result = SELECT * FROM table WHERE movieTitle == $movie;
	$result = "False";
	if ($result == "True") {
		return True;
	} elseif ($result == "False") {
		$_SESSION['type'] = 'APIrequest';
		$_SESSION['movie'] = "$movie";
        	$response = require("testRabbitMQClient.php");

		// add $response which contains results form API call to DB
		#INSERT INTO table_name (column1, column2, column3, ...) VALUES (value1, value2, value3, ...);

		// return $response to front end
		return $response;
	} else {
		logError(date('m-d-Y--h:i:s a'), "Boolean error in getMovie() in testRabbitMQServer.php", php_uname('n'));
	}
}

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
$server = new rabbitMQServer("testRabbitMQ.ini","dbServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>


