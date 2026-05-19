<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: blotter.php');
    exit();
}

$id = $_GET['id'];
$errors = [];
$success = '';

$stmt = $conn->prepare("SELECT * FROM blotter WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$record = $result->fetch_assoc();
$stmt->close();

if (!$record) {
    header('Location: blotter.php');
    exit();
}

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
        $stmt = $conn->prepare("UPDATE blotter SET incident_date = ?, description = ?, complainant = ?, respondent = ?, location = ?, status = ?, resolution = ? WHERE id = ?");
        $stmt->bind_param("sssssssi", $incident_date, $description, $complainant, $respondent, $location, $status, $resolution, $id);
        if ($stmt->execute()) {
            $success = 'Blotter record updated successfully.';
            $record = array_merge($record, [
                'incident_date' => $incident_date,
                'description' => $description,
                'complainant' => $complainant,
                'respondent' => $respondent,
                'location' => $location,
                'status' => $status,
                'resolution' => $resolution,
            ]);
        } else {
            $errors[] = 'Unable to update record.';
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
    <title>Edit Blotter - Barangay RMS</title>
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
        <h1>Edit Blotter Record</h1>

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
                <input type="datetime-local" name="incident_date" class="form-control" value="<?php echo htmlspecialchars(date('Y-m-d\TH:i', strtotime($record['incident_date']))); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required><?php echo htmlspecialchars($record['description']); ?></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Complainant</label>
                    <input type="text" name="complainant" class="form-control" value="<?php echo htmlspecialchars($record['complainant']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Respondent</label>
                    <input type="text" name="respondent" class="form-control" value="<?php echo htmlspecialchars($record['respondent']); ?>">
                </div>
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($record['location']); ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Pending" <?php echo $record['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Resolved" <?php echo $record['status'] === 'Resolved' ? 'selected' : ''; ?>>Resolved</option>
                    <option value="Dismissed" <?php echo $record['status'] === 'Dismissed' ? 'selected' : ''; ?>>Dismissed</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Resolution Notes</label>
                <textarea name="resolution" class="form-control" rows="3"><?php echo htmlspecialchars($record['resolution']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="blotter.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>