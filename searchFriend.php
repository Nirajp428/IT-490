<?php require_once(__DIR__ . "/partials/nav.php");?>

<form method="POST">
        <label for="search">Add Friend:</label>
        <input type="search" id="search"  name="search" required/>
        <input type="submit" name="submit"  value="Add"/>
</form>

<?php 
if (!isset($_SESSION)) { session_start(); }

function friendRequest($username, $friend, $status){
    $_SESSION['type'] = "friendRequest";
    $_SESSION['username'] = $_SESSION['user']['username'];
    $_SESSION['friend'] = $friend;
    $_SESSION['status'] = 'p'
    $response = require('testRabbitMQClient.php');
}
    if($response == "requestSent"){
        echo "Request Sent"
    }
    if($response == "alreadySent"){
        echo "Request already sent";
    }

if (isset($_POST["submit"])) {
    $search = null;
    if (isset($_POST["search"])){
   $search = $_POST["search"];
    }
    $response = friendRequest($search);
}
?>
