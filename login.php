<?php
require_once(__DIR__ . "/partials/nav.php");
require_once('sendMessage.php');
?>
    <form method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required/>
        <label for="p1">Password:</label>
        <input type="password" id="p1" name="password" required/>
        <input type="submit" name="login" value="Login"/>
    </form>

<?php
if (isset($_POST["login"])) {
    $email = null;
    $password = null;
    if (isset($_POST["email"])) {
        $email = $_POST["email"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }
    $isValid = true;
    if (!isset($email) || !isset($password)) {
        $isValid = false;
        flash("Email or password missing");
    }
    if (!strpos($email, "@")) {
        $isValid = false;
        //echo "<br>Invalid email<br>";
        flash("Invalid email");
    }
    if ($isValid) {
        $response = sendLogin($email, $password);
    }
}
?>
<?php require(__DIR__ . "/partials/flash.php");
