<?php require_once(__DIR__ . "/partials/nav.php");?>

<?php
$response = friendRequest($friend);
function friendRequest($friend){
        $_SESSION['type'] = "friendRequest";
        $_SESSION['username'] = $_SESSION['user']['username'];
        $friend = $_GET['link'];
        $_SESSION['friend'] = $friend;
        $_SESSION['status'] = "r";
        $response = require("testRabbitMQClient.php");

        if($response== "removed"){
                flash("Friend Request Rejected");
        }
}
?>
<?php require(__DIR__ . "/partials/flash.php");?>
