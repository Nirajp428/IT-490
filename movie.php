<?php require_once(__DIR__ . "/partials/nav.php");
?>
<body style = "background-color:DBE9F4;"</body>
<style>
img{
height: 350px;
width: 250px;
}

.back {
    background-color: #026CBA;
    border: none;
    color: white;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 18px
}
</style>
<br>
<button type="button" class = "back" onclick="history.go(-1);">Back To Movies </button>
<br>
<br>

<form method="post">
<input type="submit" name = "Like" class = "back"  value = "Like"/>
<input type="submit" name = "Dislike" class = "back" value = "Dislike"/>
</form>


<!-- This will be used for the Watch List Feature -->
<form method="post">
<input type="submit" name = "watchList" class = "back"  value = "watchList"/>
</form>

<?php

//this is used for the liking system
function like($userid, $movieid, $isLike)
{
        $_SESSION['type'] = "like";
        $_SESSION['userid'] = $userid;
        $_SESSION['movieid'] = $movieid;
        $_SESSION['isLike'] = $isLike;
        $response = require('testRabbitMQClient.php');
       /* if ($response == "true"){
                flash( "Movie Liked");
	}
	else{
		flash ("Movie dislike");
	} */
}

function watchList($userid, $title, $movie_id, $store){

	$_SESSION['type'] = "watchList";
	$_SESSION['userid'] = $userid;
	$_SESSION['title'] = $title;
	$_SESSION['movieid'] = $movieid;
	$_SESSION['store'] = $store;
	$response = require('testRabbitMQClient.php');

}


$like = "1";
$dislike = "0";
$movie = $_GET['request'];

$userid = $_SESSION['user']['id'];
$movieID = $movie['movieID'];
$title = $movie['title'];
$year = $movie['year'];
$rated = $movie['rated'];
$released = $movie['released'];
$runtime = $movie['runtime'];
$genre = $movie['genre'];
$director = $movie['director'];
$actors = $movie['actors'];
$plot = $movie['plot'];
$imdbRating = $movie['imdbRating'];
$contentType = $movie['contentType'];
$seasons = $movie['seasons'];
$seasons =str_replace(".",$seasons);

$posterImage = $movie['poster'];
$poster = base64_encode(file_get_contents($posterImage));
if(isset($poster))
{
       echo '<img src="data:image/jpg;base64,'.$poster.' ">'."<br>";
}
echo ($title."<br>");
echo ($year . "<br>");
echo ("Rated: ". $rated . "<br>"."<br>");
echo ("Description: ". $plot . "<br>"."<br>");
echo ($released . "<br>");
echo ("Runtime: ". $runtime . "<br>");
echo ("Genre: ". $genre . "<br>");
echo ("Director: ". $director . "<br>");
echo ("Cast: ". $actors . "<br>");
echo ("Imdb Rating: ". $imdbRating . "<br>");
echo ("Type: ". $contentType . "<br>");
if(isset($seasons)){
	echo ("Seasons: ". $seasons);
}

//this is for the liking system
if(array_key_exists('Like', $_POST)){
	if(!isset($userid)){
		flash("Please Login to Like a movie");
	}
	else{
		$response = like($userid, $movieID, $like);
		if($response === true){
			flash("Liked ". $title);
		}
	}
}
else if (array_key_exists('Dislike', $_POST)){
	 if(!isset($userid)){
                echo "Please Login to Dislike a movie";
        }
 	else{	
		$response = like($userid, $movieID, $dislike);
		if($response === true){
                	flash("Disliked ". $title);
		}
	}
}

//this is for the watchList feature
if(array_key_exists('watchList', $_POST)){
	if(!isset($userid)){
		flash("Please Login to Like a movie");
	}
	else{
		$response = like($userid, $title, $movieID, $store);
		if($response === true){
			flash("Added ". $title);
		}
	}
}



?>
<?php require(__DIR__ . "/partials/flash.php");?>
