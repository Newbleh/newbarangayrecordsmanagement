<?php
header('Content-Type: application/json');
require_once '../includes/config.php';

$total_residents = $conn->query("SELECT COUNT(*) as count FROM residents")->fetch_assoc()['count'];
$total_documents = $conn->query("SELECT COUNT(*) as count FROM documents")->fetch_assoc()['count'];
$total_blotter = $conn->query("SELECT COUNT(*) as count FROM blotter")->fetch_assoc()['count'];
$pending_blotter = $conn->query("SELECT COUNT(*) as count FROM blotter WHERE status = 'Pending'")->fetch_assoc()['count'];

echo json_encode([
    'total_residents' => $total_residents,
    'total_documents' => $total_documents,
    'total_blotter' => $total_blotter,
    'pending_blotter' => $pending_blotter
]);
?>