<?php require_once(__DIR__ . "/partials/nav.php");
require_once("sendMessage.php") ?>

<style>
img{
height: 200px;
width: 150px;
}
</style>

<?php
if (!is_logged_in()) {
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
$username = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
        $username = $_SESSION["user"]["username"];
	echo "<strong>";                
	echo ("Welcome to Your Profile, ".$username);
        echo "</strong>";                

}
echo "<br>";
echo "<br>";
	

if(is_logged_in()){
       	echo "<strong>";
	echo ("Saved Movies:"."<br>");
        echo "</strong>";
	$response = displayWatchList();
	
	echo ("<br>"."<br>");
	echo "<strong>";
        echo ("Friends:"."<br>");
        echo "</strong>";
//	$returned = friendDisplay();
        echo '<a href="showFriends.php">Show Friends</a>';

}
/*
function friendDisplay(){
        $_SESSION['type'] = "friendDisplay";
        $_SESSION['status']='f';
        $_SESSION['username'] = $_SESSION['user']['username'];
        $returned = require("testRabbitMQClient.php");
echo $returned;
	
        if($returned != NULL){
                $arr = json_decode($returned, true);
                $newArr = [];
                foreach($arr as $data => $val)
                {
                       $newArr[] = $val;
                }
                foreach($newArr as $key => $val)
	        {
                        $friend = $val['friendName'];
                        echo ($friend);
                        echo "<br>";
                }
        }
        else {
                echo "No Friends";
	}
} */
?>
<?php require(__DIR__ . "/partials/flash.php");
