<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_login();

$errors = [];
$success = '';

// Get residents for dropdown
$residents_result = query_helper($conn, "SELECT id, CONCAT(last_name, ', ', first_name) as name FROM residents ORDER BY last_name, first_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $resident_id = $_POST['resident_id'];
    $document_type = sanitize_input($_POST['document_type']);
    $issued_date = $_POST['issued_date'];
    $expiry_date = $_POST['expiry_date'] ?: null;
    $status = sanitize_input($_POST['status']);
    $notes = sanitize_input($_POST['notes']);

    if (empty($resident_id) || empty($document_type) || empty($issued_date)) {
        $errors[] = 'Please fill in required fields.';
    } else {
        $result = prepare_and_execute($conn, "INSERT INTO documents (resident_id, document_type, issued_date, expiry_date, status, notes) VALUES (?, ?, ?, ?, ?, ?)", "isssss", $resident_id, $document_type, $issued_date, $expiry_date, $status, $notes);
        if ($result) {
            $success = 'Document added successfully.';
        } else {
            $errors[] = 'Failed to add document.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Document - Barangay Records Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="#">Barangay RMS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="residents.php">Residents</a></li>
                    <li class="nav-item"><a class="nav-link active" href="documents.php">Documents</a></li>
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
        <h1>Add Document</h1>
        
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
                <label for="resident_id" class="form-label">Resident *</label>
                <select class="form-control" id="resident_id" name="resident_id" required>
                    <option value="">Select Resident</option>
                    <?php while ($resident = $residents_result->fetch_assoc()): ?>
                        <option value="<?php echo $resident['id']; ?>"><?php echo $resident['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="document_type" class="form-label">Document Type *</label>
                <input type="text" class="form-control" id="document_type" name="document_type" required>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="issued_date" class="form-label">Issued Date *</label>
                        <input type="date" class="form-control" id="issued_date" name="issued_date" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status">
                    <option value="Active">Active</option>
                    <option value="Expired">Expired</option>
                    <option value="Revoked">Revoked</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label for="notes" class="form-label">Notes</label>
                <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Document</button>
            <a href="documents.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>