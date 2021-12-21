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

function sendLogin($email, $password)
{
	$_SESSION['type'] = "login";
	$_SESSION['email'] = $email;
	$_SESSION['password'] = $password;
	$response = require("testRabbitMQClient.php");
	if($response == false){
                echo("Invalid Credentials");
                }

          else{
                $_SESSION["user"] = $response;
                echo("Login Successful");
                die(header("Location: home.php"));      
	  } 
}

function sendRequest($movie)
{
        $_SESSION['type'] = "request";
        $_SESSION['movie'] = $movie;
        $response = require("testRabbitMQClient.php");
	if($response != false){	
	echo $response['title'] . "\n";
        echo $response['year'] . "\n";
        echo $response['rated'] . "\n";
       // echo $response['released'] . "\n";
       // echo $response['runtime'] . "\n";
       // echo $response['genre'] . "\n";
       // echo $response['director'] . "\n";
       // echo $response['actors'] . "\n";
       // echo $response['plot'] . "\n";
	}
	else {
		echo "No Results Found";
	}
}
/*
function friend_request($id, $friend_username)
{
	$_SESSION['type'] = "friend_request";
	$_SESSION['id'] = $id;
	$_SESSION['friendUsername'] = $friend_username;
        $response = require('testRabbitMQClient.php');
        echo $response;
}
 */
function register($username, $password, $email)
{
	$_SESSION['type'] = "register";
	$_SESSION['username'] = $username;
	$_SESSION['password'] = $password;
	$_SESSION['email'] = $email;
	$response = require('testRabbitMQClient.php');
	if ($response == "True"){
		echo "Successfully Registered! Please Log in.";
	}
}

#function to rate movies
function like($userid, $movieid, $isLike)
{
        $_SESSION['type'] = "like";
        $_SESSION['userid'] = $userid;
        $_SESSION['movieid'] = $movieid;
        $_SESSION['isLike'] = $isLike;
        $response = require('testRabbitMQClient.php');

        if($response == "0"){
                $msg =("Movie Disliked");
                flash($msg);
        }

        if($response == "1"){
                $msg = ("Movie Liked");
                flash($msg);
        }

        if($response == "removed"){
                $msg = ("Removed rating");
                flash($msg);
        }
}


#function to add to watch list
function watchList($userid, $movieid)
{
        $_SESSION['type'] = "watchlist";
        $_SESSION['userid'] = $userid;
        $_SESSION['movieid'] = $movieid;
        $response = require('testRabbitMQClient.php');

        if($response == "added"){
                flash("Added to watch list");
        }
        if($response == "removed"){
                flash("Removed from watch list");
        }
}

function displayWatchList()
{
	$_SESSION['type'] ="displayWatchList";
//	$userid = $_SESSION["user"]["id"];
//	$_SESSION['userid'] = $userid;

	$response = require('testRabbitMQClient.php');

	if($response != NULL){
                $arr = json_decode($response, true);

                $newArr = [];
                foreach($arr as $data => $val)
                {

                        $newArr[] = $val;

                }

                foreach($newArr as $key => $val)
                {
                $movieID = $val['movieID'];
                $title = $val['title'];
                $year = $val['year'];
                $poster = $val['poster'];
                $rated = $val['rated'];
                $released = $val['released'];
                $runtime = $val['runtime'];
                $genre = $val['genre'];
                $director = $val['director'];
                $actors = $val['actors'];
                $plot = $val['plot'];
                $imdbRating = $val['imdbRating'];
                $contentType = $val['contentType'];
                $seasons = $val['seasons'];

		 $movieClick =Array(
                        'poster'=>$poster,
                        'movieID' =>$movieID,
                        'title'=>$title,
                        'year'=>$year,
                        'rated'=>$rated,
                        'released'=>$released,
                        'runtime'=>$runtime,
                        'genre'=>$genre,
                        'director'=>$director,
                        'actors'=>$actors,
                        'plot'=>$plot,
                        'imdbRating'=>$imdbRating,
                        'contentType'=>$contentType,
                        'seasons'=>$seasons
                );

                $posterImage = $val['poster'];
                $poster = base64_encode(file_get_contents($posterImage));
                        if(isset($val['poster'])){
                                echo ('<a href="movie.php?'.http_build_query(array('request'=>$movieClick)).'."><img src="data:image/jpg;base64,'.$poster.'"name="request"/></a>');
                        }
                        echo "<br>";
                        echo "<strong>";
                        echo ($title."<br>");
                        echo "</strong>";
                        echo ('Year: ' . $year."<br>");
                        echo ('Rated: ' . $imdbRating."<br>");
                        echo "<br>";
                }
        }
else{
        echo "No movies available";
     }
}

?>
