<?php
// Connect via TCP to avoid socket/pipe issues
$dbHost = '127.0.0.1';
$dbUser = 'root';
$dbPass = '';
$dbName = 'barangay_records';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = 'admin';
$password = 'admin123';
$role = 'admin';
$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('SELECT id FROM users WHERE username = ?');
$stmt->bind_param('s', $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "EXISTS\n";
    exit(0);
}

$stmt2 = $conn->prepare('INSERT INTO users (username, password, role) VALUES (?, ?, ?)');
$stmt2->bind_param('sss', $username, $hash, $role);
if ($stmt2->execute()) {
    echo "CREATED\n";
} else {
    echo "FAILED: " . $stmt2->error . "\n";
}
