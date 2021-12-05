<?php require_once(__DIR__ . "/partials/nav.php");?>

<form method="POST">
        <label for="search">Search for Movie:</label>
        <input type="search" id="search"  name="search" required/>
        <input type="submit" name="submit"  value="Search"/>
</form>

<style>
img{
height: 200px;
width: 150px;
}
</style>

<?php 
if (!isset($_SESSION)) { session_start(); }

function sendRequest($movie)
{
        $_SESSION['type'] = "request";
        $_SESSION['movie'] = $movie;
        $response = require("testRabbitMQClient.php");

	if(isset($response['title'])){
		$posterImage = $response['poster'];
		$poster = base64_encode(file_get_contents($posterImage));
			if(isset($response['poster'])){
		   		echo '<img src="data:image/jpg;base64,'.$poster.' ">'."<br>";
			}
		echo ($response['title'] . "<br>");
        	echo ($response['year'] . "<br>");
        	echo ($response['rated'] . "<br>");
        	echo ($response['released'] . "<br>");
        	echo ($response['runtime'] . "<br>");
        	echo ($response['genre'] . "<br>");
        	echo ($response['director'] . "<br>");
        	echo ($response['actors'] . "<br>");
		echo ("Description: ". "<br>".$response['plot']);
	}

	else{
		echo "No Results";
	}
}

if (isset($_POST["submit"])) {
     $search = null;
     if (isset($_POST["search"])){
	$search = $_POST["search"];
     }
     $response = sendRequest($search);
}
?>
<?php require(__DIR__ . "/partials/flash.php");?>
