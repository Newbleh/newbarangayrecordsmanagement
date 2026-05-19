<?php
$password = '121902lacquio';
$hashed = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password: " . $hashed;
?>