<?php
require_once(__DIR__ . "/partials/nav.php");
require_once('sendMessage.php');
//require_once('path.inc');
//require_once('get_host_info.inc');
//require_once('testRabbitMQClient.php');
//require_once('rabbitMQLib');
?>

<?php
if (isset($_POST["register"])) {
    $email = null;
    $password = null;
    $confirm = null;
    $username = null;
    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    if (isset($_POST["confirm"])) {
        $confirm = $_POST["confirm"];
    }
    if (isset($_POST["username"])) {
        $username = $_POST["username"];
    }
    $isValid = true;
    //check if passwords match on the server side
    if ($password == $confirm) {
    //not necessary to show
    }
    else {
        flash("Passwords don't match");
        $isValid = false;
    }
    if (!isset($email) || !isset($password) || !isset($confirm)) {
        $isValid = false;
    }
    //password hashed and salted with bcrypt
    //no two passwords will ever have the same hash
    if ($isValid) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

	//here we send the registration info through rabbit to db
	register($username, $hash, $email);
    }

    else {
        flash( "There was a validation issue");
    }
}
//safety measure to prevent php warnings
if (!isset($email)) {
    $email = "";
}
if (!isset($username)) {
    $username = "";
}
?>
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required value="<?php safer_echo($email); ?>"/>
        <label for="user">Username:</label>
        <input type="text" id="user" name="username" required minlength="3" maxlength="60" value="<?php safer_echo($username); ?>"/>
        <label for="p1">Password:</label>
        <input type="password" id="p1" name="password" required minlength="4" maxlength="30"/>
        <label for="p2">Confirm Password:</label>
        <input type="password" id="p2" name="confirm" required/>
        <input type="submit" name="register" value="Register"/>
    </form>
<?php require(__DIR__ . "/partials/flash.php");?>
