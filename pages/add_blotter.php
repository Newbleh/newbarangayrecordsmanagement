<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_login();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $incident_date = $_POST['incident_date'];
    $description = sanitize_input($_POST['description']);
    $complainant = sanitize_input($_POST['complainant']);
    $respondent = sanitize_input($_POST['respondent']);
    $location = sanitize_input($_POST['location']);
    $status = sanitize_input($_POST['status']);
    $resolution = sanitize_input($_POST['resolution']);

    if (empty($incident_date) || empty($description)) {
        $errors[] = 'Please fill in the required fields.';
    } else {
        $stmt = $conn->prepare("INSERT INTO blotter (incident_date, description, complainant, respondent, location, status, resolution) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $incident_date, $description, $complainant, $respondent, $location, $status, $resolution);
        if ($stmt->execute()) {
            $success = 'Blotter record saved successfully.';
        } else {
            $errors[] = 'Unable to save blotter record.';
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Blotter Record - Barangay RMS</title>
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
                    <li class="nav-item"><a class="nav-link active" href="blotter.php">Blotter</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Add Blotter Record</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="post" class="mt-4">
            <div class="mb-3">
                <label class="form-label">Incident Date</label>
                <input type="datetime-local" class="form-control" name="incident_date" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="4" required></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Complainant</label>
                    <input type="text" class="form-control" name="complainant">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Respondent</label>
                    <input type="text" class="form-control" name="respondent">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Location</label>
                <input type="text" class="form-control" name="location">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status">
                    <option value="Pending">Pending</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Dismissed">Dismissed</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Resolution Notes</label>
                <textarea class="form-control" name="resolution" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Blotter</button>
            <a href="blotter.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>