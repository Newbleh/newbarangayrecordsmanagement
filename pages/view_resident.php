<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: residents.php');
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM residents WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$resident = $result->fetch_assoc();
$stmt->close();

if (!$resident) {
    header('Location: residents.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resident Details - Barangay RMS</title>
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
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1><?php echo htmlspecialchars($resident['first_name'] . ' ' . $resident['middle_name'] . ' ' . $resident['last_name']); ?></h1>
                <p class="text-muted">Resident profile details and personal record history.</p>
            </div>
            <a href="edit_resident.php?id=<?php echo $resident['id']; ?>" class="btn btn-warning">Edit Profile</a>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Personal Information</h5>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($resident['last_name'] . ', ' . $resident['first_name'] . ' ' . $resident['middle_name']); ?></p>
                        <p><strong>Birthdate:</strong> <?php echo format_date($resident['birthdate']); ?></p>
                        <p><strong>Gender:</strong> <?php echo htmlspecialchars($resident['gender']); ?></p>
                        <p><strong>Civil Status:</strong> <?php echo htmlspecialchars($resident['civil_status']); ?></p>
                        <p><strong>Occupation:</strong> <?php echo htmlspecialchars($resident['occupation']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Contact & Address</h5>
                        <p><strong>Address:</strong> <?php echo htmlspecialchars($resident['address']); ?></p>
                        <p><strong>Contact:</strong> <?php echo htmlspecialchars($resident['contact_number']); ?></p>
                        <p><strong>Email:</strong> <?php echo htmlspecialchars($resident['email']); ?></p>
                        <p><strong>Added:</strong> <?php echo format_date($resident['created_at']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <a href="residents.php" class="btn btn-secondary mt-4">Back to Residents</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>