<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

if (!is_logged_in()) {
    header('Location: pages/login.php');
    exit();
}

header('Location: pages/dashboard.php');
exit();
?>