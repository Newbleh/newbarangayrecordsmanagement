<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_login();

$total_residents = query_helper($conn, "SELECT COUNT(*) as count FROM residents")->fetch_assoc()['count'];
$total_documents = query_helper($conn, "SELECT COUNT(*) as count FROM documents")->fetch_assoc()['count'];
$total_blotter = query_helper($conn, "SELECT COUNT(*) as count FROM blotter")->fetch_assoc()['count'];
$resolved_blotter = query_helper($conn, "SELECT COUNT(*) as count FROM blotter WHERE status = 'Resolved'")->fetch_assoc()['count'];
$active_documents = query_helper($conn, "SELECT COUNT(*) as count FROM documents WHERE status = 'Active'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Barangay RMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Barangay RMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="residents.php">Residents</a></li>
                    <li class="nav-item"><a class="nav-link" href="documents.php">Documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="blotter.php">Blotter</a></li>
                    <li class="nav-item"><a class="nav-link active" href="reports.php">Reports</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Real-Time Reports</h1>
        <p class="text-muted mb-4">Live summary of resident, document, and blotter activity in your barangay system.</p>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Residents</h5>
                        <p class="display-6 mb-0"><?php echo $total_residents; ?></p>
                        <p class="text-muted">Total registered residents</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Active Documents</h5>
                        <p class="display-6 mb-0"><?php echo $active_documents; ?></p>
                        <p class="text-muted">Currently active tracked documents</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Blotter Entries</h5>
                        <p class="display-6 mb-0"><?php echo $total_blotter; ?></p>
                        <p class="text-muted">Total blotter cases recorded</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Resolved Cases</h5>
                        <p class="display-6 mb-0"><?php echo $resolved_blotter; ?></p>
                        <p class="text-muted">Cases marked as resolved</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Document Compliance</h5>
                        <p class="mb-0">Active / Total</p>
                        <p class="display-6 mb-0"><?php echo $active_documents; ?> / <?php echo $total_documents; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <p class="footer-note mt-4">Report data is generated from the latest database records. Refresh the page to see updated counts.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>