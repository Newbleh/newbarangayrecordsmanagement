<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_login();

// Get stats
$result = query_helper($conn, "SELECT COUNT(*) as count FROM residents");
$total_residents = $result->fetch_assoc()['count'];

$result = query_helper($conn, "SELECT COUNT(*) as count FROM documents");
$total_documents = $result->fetch_assoc()['count'];

$result = query_helper($conn, "SELECT COUNT(*) as count FROM blotter");
$total_blotter = $result->fetch_assoc()['count'];

$result = query_helper($conn, "SELECT COUNT(*) as count FROM blotter WHERE status = 'Pending'");
$pending_blotter = $result->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Barangay Records Management</title>
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
                    <li class="nav-item"><a class="nav-link active" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="residents.php">Residents</a></li>
                    <li class="nav-item"><a class="nav-link" href="documents.php">Documents</a></li>
                    <li class="nav-item"><a class="nav-link" href="blotter.php">Blotter</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-3">Barangay Records Management System</h1>
                <p class="text-center text-muted mb-4">Comprehensive platform for managing resident profiles, documents, blotter records, and generating real-time reports.</p>
                <div class="text-center mb-4">
                    <button id="refreshStats" class="btn btn-outline-primary">Refresh Statistics</button>
                </div>
            </div>
        </div>
        
        <div class="row" id="statsRow">
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Residents</h5>
                        <h2 id="totalResidents"><?php echo $total_residents; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Total Documents</h5>
                        <h2 id="totalDocuments"><?php echo $total_documents; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Total Blotter Records</h5>
                        <h2 id="totalBlotter"><?php echo $total_blotter; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger">
                    <div class="card-body">
                        <h5 class="card-title">Pending Blotter</h5>
                        <h2 id="pendingBlotter"><?php echo $pending_blotter; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-4 mb-4">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">System Status</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Fetch the latest system status message from the API.</p>
                        <button id="apiFetchButton" class="btn btn-primary">Fetch Status Message</button>
                        <h5 id="apiTitle" class="mt-3 mb-2">Barangay Service Center</h5>
                        <p id="apiDescription" class="text-muted small mb-3"></p>
                        <div id="apiResponse" class="mt-2 p-3 bg-light rounded">
                            <p class="mb-0 text-muted">System status will appear here...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <a href="add_resident.php" class="btn btn-success btn-sm me-2 mb-2">Add Resident</a>
                        <a href="add_document.php" class="btn btn-info btn-sm me-2 mb-2">Add Document</a>
                        <a href="add_blotter.php" class="btn btn-warning btn-sm me-2 mb-2">Add Blotter</a>
                        <a href="reports.php" class="btn btn-secondary btn-sm mb-2">View Reports</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mb-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Cloud Announcements</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Use Firebase Firestore to store announcements and demonstrate cloud backend integration with real-time updates.</p>
                        <div id="firebaseStatus" class="alert alert-info">Firebase cloud backend status will appear here.</div>

                        <form id="announcementForm" class="mb-4">
                            <div class="mb-3">
                                <label for="announcementTitle" class="form-label">Announcement Title</label>
                                <input type="text" id="announcementTitle" class="form-control" placeholder="Enter announcement title" required>
                            </div>
                            <div class="mb-3">
                                <label for="announcementMessage" class="form-label">Announcement Message</label>
                                <textarea id="announcementMessage" class="form-control" rows="3" placeholder="Enter announcement message" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Post Announcement</button>
                        </form>

                        <div id="announcementList"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.23.0/firebase-firestore-compat.js"></script>
    <script src="../assets/js/firebase-config.js"></script>
    <script src="../assets/js/firebase-announcements.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/dashboard.js"></script>
    <script>
        // Original stats refresh functionality
        function fetchStats() {
            fetch('../api/dashboard.php?action=get_stats')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('totalResidents').textContent = data.data.total_residents;
                        document.getElementById('totalDocuments').textContent = data.data.total_documents;
                        document.getElementById('totalBlotter').textContent = data.data.total_blotter;
                        document.getElementById('pendingBlotter').textContent = data.data.pending_blotter;
                    }
                })
                .catch(error => console.error('Error fetching stats:', error));
        }

        document.getElementById('refreshStats').addEventListener('click', fetchStats);

        // Auto-refresh every 30 seconds
        setInterval(fetchStats, 30000);
    </script>
</body>
</html>