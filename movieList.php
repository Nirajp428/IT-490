<?php require_once(__DIR__ . "/partials/nav.php");?>
<body style = "background-color:DBE9F4;"</body>

<p><b>Movie List:</b></p>

<style>
img{
height: 200px;
width: 150px;
}
</style>

<?php
if (!isset($_SESSION)) { session_start(); }

$response = getAllMovies();
function getAllMovies()
{
	$_SESSION['type'] = "getAll";
	$response = require("testRabbitMQClient.php");

	if($response != NULL){
		$arr = json_decode($response, true);
		
		$newArr = [];
		foreach($arr as $data => $val)
		{
			
			$newArr[] = $val;
			
		}

		foreach($newArr as $key => $val)
		{
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
<?php require(__DIR__ . "/partials/flash.php");
