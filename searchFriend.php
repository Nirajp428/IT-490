<?php require_once(__DIR__ . "/partials/nav.php");?>

<form method="POST">
        <label for="search">Add Friend:</label>
        <input type="search" id="search"  name="search" required/>
        <input type="submit" name="submit"  value="Search"/>
</form>

<?php
if (!isset($_SESSION)) { session_start(); }

function friendRequest($friend){
	$_SESSION['type'] = "friendRequest";
	$_SESSION['username'] = $_SESSION['user']['username'];
	$_SESSION['friend'] = $friend;
	$_SESSION['status'] = "p";
        $response = require("testRabbitMQClient.php");

	if($response == "requestSent"){
		echo "Request Sent";
	}		

	if($response == "alreadySent"){
		echo "Request already sent";
	}
}
if (isset($_POST["submit"])) {
     $search = null;
     if (isset($_POST["search"])){
        $search = $_POST["search"];
     }
     $response = friendRequest($search);
}
echo "<br>";
echo '<a href="showFriendReq.php" >Show Pending Requests</a>';

/*
$returned = friendDisplay();
function friendDisplay(){
        $_SESSION['type'] = "friendDisplay";
	$_SESSION['status']='p';
	$_SESSION['username'] = $_SESSION['user']['username'];
        $returned = require("testRabbitMQClient.php");

	echo "<strong>";
	echo "Pending Friend Requests:";
        echo "</strong>";
        echo "<br>";
	
	if($returned != NULL){
		$arr = json_decode($returned, true);
                $newArr = [];
                foreach($arr as $data => $val)
                {
                       $newArr[] = $val;
		}
                foreach($newArr as $key => $val)
                {
                	$friendReq = $val['username'];
			echo ($friendReq. ":  ");
			echo '<a href="acceptFriend.php?link='.$friendReq.'"target="searchFriend.php">Accept</a>';
			echo "  ";       
			echo '<a href="rejectFriend.php?link='.$friendReq.'">Reject</a>';

			echo "<br>";
		}
	}
	else {
		echo "No Pending Friend Requests";
	}
}*/
?>
<?php require(__DIR__ . "/partials/flash.php");?>
