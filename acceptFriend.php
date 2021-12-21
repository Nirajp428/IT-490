<?php require_once(__DIR__ . "/partials/nav.php");?>

<?php
$friend=$_GET['link'];

$response = friendRequest($friend);
function friendRequest($friend){
	$_SESSION['type'] = "friendRequest";
        $_SESSION['username'] = $_SESSION['user']['username'];
	$_SESSION['friend'] = $friend;
        $_SESSION['status'] = "f";
        $response = require("testRabbitMQClient.php");

        if($response== "true"){
                flash("Friend Request Accepted");
        }
}
?>
<?php require(__DIR__ . "/partials/flash.php");?>
