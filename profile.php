<?php require_once(__DIR__ . "/partials/nav.php"); ?>
<?php
//Note: we have this up here, so our update happens before our get/fetch
//that way we'll fetch the updated data and have it correctly reflect on the form below
//As an exercise swap these two and see how things change
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

?>
  <body>
   <br />
    <form method="POST">
        <label for="email">Email</label>
        <input type="email" name="email" value="<?php safer_echo(get_email()); ?>"/>
        <label for="username">Username</label>
        <input type="text" maxlength="60" name="username" value="<?php safer_echo(get_username()); ?>"/>
        <!-- DO NOT PRELOAD PASSWORD-->
        <label for="pw">Password</label>
        <input type="password" name="password"/>
        <label for="cpw">Confirm Password</label>
        <input type="password" name="confirm"/>
        <input type="submit" name="saved" value="Save Profile"/>
 <br /> 
  </body>
<?php require(__DIR__ . "/partials/flash.php");
