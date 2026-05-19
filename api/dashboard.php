<?php
header('Content-Type: application/json');
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'get_stats':
        $total_residents = $conn->query("SELECT COUNT(*) as count FROM residents")->fetch_assoc()['count'];
        $total_documents = $conn->query("SELECT COUNT(*) as count FROM documents")->fetch_assoc()['count'];
        $total_blotter = $conn->query("SELECT COUNT(*) as count FROM blotter")->fetch_assoc()['count'];
        $pending_blotter = $conn->query("SELECT COUNT(*) as count FROM blotter WHERE status = 'Pending'")->fetch_assoc()['count'];
        
        echo json_encode([
            'success' => true,
            'data' => [
                'total_residents' => intval($total_residents),
                'total_documents' => intval($total_documents),
                'total_blotter' => intval($total_blotter),
                'pending_blotter' => intval($pending_blotter)
            ]
        ]);
        break;

    case 'get_residents':
        $search = $_GET['search'] ?? '';
        $query = "SELECT id, CONCAT(last_name, ', ', first_name) as name FROM residents";
        if (!empty($search)) {
            $search = $conn->real_escape_string($search);
            $query .= " WHERE first_name LIKE '%$search%' OR last_name LIKE '%$search%'";
        }
        $query .= " LIMIT 10";
        $result = $conn->query($query);
        $residents = [];
        while ($row = $result->fetch_assoc()) {
            $residents[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $residents]);
        break;

    case 'get_blotter':
        $status = $_GET['status'] ?? '';
        $query = "SELECT id, description, incident_date, status FROM blotter";
        if (!empty($status)) {
            $status = $conn->real_escape_string($status);
            $query .= " WHERE status = '$status'";
        }
        $query .= " ORDER BY incident_date DESC LIMIT 10";
        $result = $conn->query($query);
        $blotter = [];
        while ($row = $result->fetch_assoc()) {
            $blotter[] = $row;
        }
        echo json_encode(['success' => true, 'data' => $blotter]);
        break;

    case 'delete_blotter':
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid ID']);
            exit();
        }
        $stmt = $conn->prepare("DELETE FROM blotter WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Blotter record deleted']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete record']);
        }
        $stmt->close();
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>