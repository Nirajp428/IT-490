<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//we use this to safely get the email to display
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
	$email = $_SESSION["user"]["username"];
	echo ("Welcome, ". $email);
}
?>

<p><b>Welcome to our movie website</b></p>

<?php require(__DIR__ . "/partials/flash.php");
