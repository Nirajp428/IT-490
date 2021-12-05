<?php
include 'mysqlconnect.php';
session_start();

function likesystem($someVariable)
{
    $_SESSION['type']=$like
    $_SESSION['']=
    include_once("testRabbitMQClient.php");
}



$movieId=$_POST["txtMovieId"];
$userId=$_POST["txtUserId"];
$s="select * from rating where movie_id=".$movieId;
$res=mysqli_query($mydb,$s);
if(mysqli_num_rows($res)>0)
{
    $s="update rating set isLike=1 where movie_id=".$movieId;
}
else $s="insert into rating(user_id, movie_id, isLike) values(".$userId.",".$movieId.",1)";
echo $s;
// $mydb=getDB();
if(mysqli_query($mydb,$s))
{
    echo "User added";
    header("Location: home.php");
}
else
{
    echo "Error : ".$mydb->error;
}



?>