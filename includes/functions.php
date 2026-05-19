<?php
// Common functions

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit();
    }
}

function get_user_role() {
    return $_SESSION['role'] ?? null;
}

function format_date($date) {
    return date('M d, Y', strtotime($date));
}
?>