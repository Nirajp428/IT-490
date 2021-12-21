<?php require_once(__DIR__ . "/partials/nav.php");
?>
<?php
echo "Friends:";
echo "<br>";
$returned = friendDisplay();

function friendDisplay(){
        $_SESSION['type'] = "friendDisplay";
        $_SESSION['status']='f';
        $_SESSION['username'] = $_SESSION['user']['username'];
        $returned = require("testRabbitMQClient.php");
        
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
} 
?>

