<?php require_once(__DIR__ . "/partials/nav.php");?>
<?php
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
}
?>

