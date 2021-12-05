<?php require_once(__DIR__ . "/partials/nav.php");?>
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

<?php
$movie = $_GET['request'];
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
?>
<?php require(__DIR__ . "/partials/flash.php");?>

