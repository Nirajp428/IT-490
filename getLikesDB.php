<?php
include 'mysqlconnect.php';
session_start();

$movieId=$_POST["txtMovieId"];
$userId=$_POST["txtUserId"];
$s="select * from rating";
// $mydb=getDB();
$res=mysqli_query($mydb,$s);
if(mysqli_num_rows($res)>0)
{
    while($row=mysqli_fetch_assoc($res))
    {
        print_r($row["movie_id"]." ".$row["isLike"]." ");
    }
        // header("Location: home.php");
}
// else
// {
//     echo "Error : ".$mydb->error;
// }



?>