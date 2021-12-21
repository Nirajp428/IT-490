<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<?php
$email = "";
if (isset($_SESSION["user"]) && isset($_SESSION["user"]["email"])) {
	$email = $_SESSION["user"]["username"];
	echo ("Welcome, ". $email);
}
?>

<p><b>Welcome to our movie website</b></p>
<p> Create an account to rate movies and add them to your watch list for later!</p>
<iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%234285F4&ctz=America%2FNew_York&title=New%20Calendar&src=Y2hpcmFna3VtYXIzODVAZ21haWwuY29t&src=ZW4udXNhI2hvbGlkYXlAZ3JvdXAudi5jYWxlbmRhci5nb29nbGUuY29t&color=%237986CB&color=%230B8043" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>
<a target="_blank" href="https://calendar.google.com/event?action=TEMPLATE&amp;tmeid=Nmk0NGc1cXYzZ2RsYW0zMTYybnN2N3Bwc2kgY2hpcmFna3VtYXIzODVAbQ&amp;tmsrc=chiragkumar385%40gmail.com"><img border="0" src="https://www.google.com/calendar/images/ext/gc_button1_en.gif"></a>
<?php require(__DIR__ . "/partials/flash.php");
