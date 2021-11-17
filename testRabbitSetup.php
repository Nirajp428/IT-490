#!/usr/bin/php
<?php
$currentUser = get_current_user();
$old_path = getcwd();

// chdir("/home/$currentUser/Desktop/Git/rabbitmqphp_example");
chdir("/home/$currentUser/Desktop/Git/IT-490");
$output = shell_exec('./testRabbitSetup.sh');

chdir($old_path);

?>
