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
			echo "Invalid Password";
			$response = false;
			echo $response;
			return $response;
			$mydb->close();
		}
	    }
	    else{
		echo "invalid user";
		$response = false;
		return $response;
		$mydb->close();
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
		$mydb->close();
		return "False";
	} else {
		// If user not found, insert into DB
		$query = ("INSERT INTO Users(email, password, username) VALUES ('$email', '$password', '$username')");

		if ($mydb->query($query) == TRUE){	
			echo "Successfully registered";
			$mydb->close();
			return "True";
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

function getAllMovies()
{
	$mydb = new mysqli('127.0.0.1','matt','12345','IT490DB');
	if ($mydb->errno != 0)
        {
                echo "failed to connect to database: ". $mydb->error . PHP_EOL;
                logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php register function", php_uname('n'));
                exit(0);
        }
        echo "successfully connected to database".PHP_EOL;
	
	$query = ("SELECT * FROM Movies ORDER BY CASE WHEN `imdbRating` = 'N/A' THEN 1 ELSE 0 END, `imdbRating` desc");
	$result = $mydb->query($query);

	$arr = array();
	while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$arr[] = $row;
	}

	$all = json_encode($arr);
	return $all;
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
	$query = "SELECT * FROM `Movies` WHERE `title` LIKE '%$movie%' ORDER BY `movieID` DESC LIMIT 1";
	$response = $mydb->query($query);

        if ($response->num_rows == 0) {
		// Movie not in DB, get Movie from dmz server
		echo "$movie not found in database, calling dmz server\n";
		$_SESSION['type'] = 'APIrequest';
                $_SESSION['movie'] = "$movie";

                // call dmz server
		$response = require("testRabbitMQClient.php");
		$array = json_decode($response, true);

		if (!isset($array['Title']))
                {
			echo "API does not have $movie listed";
			$mydb->close();
                        return "API does not have $movie listed";
                }

		$title = $array['Title'];
		$year = $array['Year'];
		$rated = $array['Rated'];
		$released = $array['Released'];
		$runtime = $array['Runtime'];
		$genre = $array['Genre'];
		$directors = $array['Director'];
		$directorsNew = str_replace("'", "''", "$directors");
		$actors = $array['Actors'];
		$actorsNew = str_replace("'", "''", "$actors");
		$plot = $array['Plot'];
		$sqlPlot = str_replace("'", "''", "$plot");
		$poster = $array['Poster'];
		$imdbRating = $array['imdbRating'];
		$type = $array['Type'];
		if(isset($array['totalSeasons'])){
			$totalSeasons = $array['totalSeasons'];
		}
		else{
			$totalSeasoons = NULL;
		}

                // add $response which contains results form API call to DB
                $query = "INSERT INTO Movies (title, year, rated, released, runtime, genre, director, actors, plot, poster, imdbRating, contentType, seasons) VALUES ('$title', '$year', '$rated', '$released', '$runtime', '$genre', '$directorsNew', '$actorsNew', '$sqlPlot', '$poster', '$imdbRating', '$type', '$totalSeasons');";
		
		if ($mydb->query($query) === TRUE) {
			echo "New record created successfully";
		} else {
			echo "Error: " . $query . "<br>" . $mydb->error;
		}
		// return $api info to front end
		$query = "SELECT * FROM  Movies WHERE title='$movie';";
                $result = mysqli_query($mydb, $query);
                $row = mysqli_fetch_assoc($result);

		// return movie info to front end
		$mydb->close();
		return $row;

		//restart server file after API call
	//	$_ = $_SERVER['_'];
	//	global $_;
	//	pcntl_exec($_);

	} else { 
		// movie found in DB
		$query = "SELECT * FROM  Movies WHERE title='$movie';";
		$result = mysqli_query($mydb, $query);
		$row = mysqli_fetch_assoc($result);

                // return movie info to front end
		$mydb->close();
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


#function that handles friend requests
function friendRequest($username, $friend, $status){
	 // connect to DB
        $mydb = new mysqli('127.0.0.1','matt','12345','IT490DB');

        if ($mydb->errno != 0)
        {
                echo "failed to connect to database: ". $mydb->error . PHP_EOL;
                logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php getMovie function", php_uname('n'));
                exit(0);
        }
        echo "successfully connected to database".PHP_EOL;

	//f for friends
	if($status == "f"){
		$newquery="UPDATE friendsTable SET friendStatus = 'f' WHERE `username` = '$username' AND `friendName` = '$friend'";

                if ($mydb->query($newquery) === TRUE) {
                        echo "Friend request accepted";
			$friendsQuery = "SELECT * FROM friendsTable WHERE username = '$username' AND friendStatus = 'f'";
			$result = $mydb->query($friendQuery);
		        $arr = array();
        		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                		$arr[] = $row;
        		}

        		$all = json_encode($arr);
       			echo $all;
        		return $all;

			
		}
	}

	//p for pending
	if($status == "p"){
		$query = "SELECT username FROM Users WHERE username = '$friend'";
		$checkQuery = "SELECT * FROM friendsTable WHERE username = '$username' AND friendName = '$friend' AND friendStatus = 'p'";
		$checkRes = mysqli_query($mydb, $checkQuery);
		if(mysqli_num_rows($checkRes)==0)
		{
			$res = mysqli_query($mydb, $query);
			if (mysqli_num_rows($res)>0)
        		{
				$query2 = "INSERT INTO friendsTable(username, friendName, friendStatus) values ('$username','$friend','p')";
				$res2 = mysqli_query($mydb, $query2);
				return "requestSent";
			}
		}
		else{
			echo "Request already sent";
			return "alreadySent";
		}
	}

	//u for unfriend
	if($status == "u"){

	}	
}

#function to display friend requests 
function friendDisplay($username){
	  // connect to DB
        $mydb = new mysqli('127.0.0.1','matt','12345','IT490DB');

        if ($mydb->errno != 0)
        {
                echo "failed to connect to database: ". $mydb->error . PHP_EOL;
                logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php getMovie function", php_uname('n'));
                exit(0);
        }
        echo "successfully connected to database".PHP_EOL;

	$query = "SELECT username FROM friendsTable  WHERE friendName = '$username' AND friendStatus = 'p'";
	
	$result = $mydb->query($query);
        $arr = array();
        while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                $arr[] = $row;
        }

        $all = json_encode($arr);
	echo $all;
	return $all;
}



#function to handle the movie ratings
function like($userid, $movieid, $isLike){
	 // connect to DB
        $mydb = new mysqli('127.0.0.1','matt','12345','IT490DB');

        if ($mydb->errno != 0)
        {
                echo "failed to connect to database: ". $mydb->error . PHP_EOL;
                logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php getMovie function", php_uname('n'));
                exit(0);
        }
        echo "successfully connected to database".PHP_EOL;
	
	$query = "SELECT * from rating WHERE movie_id = '$movieid' AND user_id ='$userid'";
	$query2 = "SELECT * from rating WHERE movie_id = '$movieid' AND user_id ='$userid' AND isLike = '$isLike'";
	$res = mysqli_query($mydb, $query);
	$res2 = mysqli_query($mydb, $query2);
   if (mysqli_num_rows($res2)>0)
   {
	   $delQuery = "DELETE FROM rating WHERE movie_id = '$movieid' AND user_id = '$userid' AND isLike = '$isLike'";
	if ($mydb->query($delQuery) === TRUE) {
 		echo "Entry removed";
           	return "removed";
        }

	else
	{
              	 echo "Error: " . $newquery . "<br>" . $mydb->error;
              	 return false;
        }
   
   }	  
   else{ 
	if (mysqli_num_rows($res)>0)
	{
		$newquery="UPDATE rating SET isLike = '$isLike' WHERE `movie_id` = '$movieid' AND `user_id` = '$userid'";

		if ($mydb->query($newquery) === TRUE) {
			echo "Rating updated successfully";
                	return $isLike;
        	}
		else 
		{
                        echo "Error: " . $newquery . "<br>" . $mydb->error;
                        return false;
                }

	}

	else{
		$query = "INSERT INTO rating(user_id, movie_id, isLike) values ('$userid','$movieid','$isLike')";
	

		if ($mydb->query($query) === TRUE) {
			 echo "Rating recorded successfully";
			 return $isLike;
	 	}
	 	else {
                        echo "Error: " . $query . "<br>" . $mydb->error;
			return false;	
		}
	}
     }	
}



#add movie to watch list
function addToWatchList($userid, $movieid)
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


	if(isset($userid) AND isset($movieid))
	{
		$query1 = "SELECT * from watchList WHERE movie_id = '$movieid' AND user_id ='$userid'";
	        $res1 = mysqli_query($mydb, $query1);		
		if (mysqli_num_rows($res1)>0)
   		{
           		$delQuery = "DELETE FROM watchList WHERE movie_id = '$movieid' AND user_id = '$userid'";
        		if ($mydb->query($delQuery) === TRUE) {
                		echo "Movie removed from watch list";
                		return "removed";
	        	}

		}

		else{
			$insertQuery  = "INSERT INTO watchList(user_id, movie_id) VALUES ('$userid', '$movieid')";

			if ($mydb->query($insertQuery) === TRUE) {
                	        echo "Movie added to watch list successfully";
                        	return "added";
                	}
		}		
	}	
}


function displayWatchList($userid){
	// connect to DB
        $mydb = new mysqli('127.0.0.1','matt','12345','IT490DB');

        if ($mydb->errno != 0)
        {
                echo "failed to connect to database: ". $mydb->error . PHP_EOL;
                logError(date('m-d-Y--h:i:s a'), "Failed to connect to database in testRabbitMQServer.php getMovie function", php_uname('n'));
                exit(0);
        }
        echo "successfully connected to database".PHP_EOL;

	if(isset($userid)){
		$query = "SELECT Movies.*, watchList.user_id FROM Movies  LEFT JOIN watchList ON watchList.movie_id = Movies.movieID WHERE watchList.user_id = '$userid'";
		$result = $mydb->query($query);

	        $arr = array();
       		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
               		$arr[] = $row;
        	}

      	 	$all = json_encode($arr);
		return $all;
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
	case "getAll":
		$response = getAllMovies();
		return $response;
	case "friendRequest":
		$response = friendRequest($request['username'], $request['friend'], $request['status']);
		return $response;
	case "friendDisplay":
		$response = friendDisplay($request['username']);
		return $response;

	case "like":
		$response = like($request['userid'], $request['movieid'], $request['isLike']);
		return $response;
	case "watchlist":
		$response = addToWatchList($request['userid'], $request['movieid']);
		return $response;
	
	case "displayWatchList":
		$response = displayWatchList($request['userid']);
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
