<link rel="stylesheet" href="static/css/styles.css">
<?php

require_once(__DIR__ . "/../lib/helpers.php");
?>
<nav>
<ul class="nav">
    <li><a href="home.php">Home</a></li>
    <li><a href="movieList.php">Movies</a></li>
    <li><a href="searchMovie.php">Search</a></li>
    <?php if (!is_logged_in()): ?>
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php">Register</a></li>
   <?php endif; ?>


    <?php if (is_logged_in()): ?>
        <li><a href="searchFriend.php">Friends</a></li>
        <li><a href="profile.php">Profile</a></li>
        <li><a href="logout.php">Logout</a></li>
        <li><a href="https://watchparty.me">WatchParty</a></li>
    <?php endif; ?>
</ul>
</nav>
