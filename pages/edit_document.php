<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_login();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: documents.php');
    exit();
}

$id = $_GET['id'];
$errors = [];
$success = '';

$stmt = $conn->prepare("SELECT * FROM documents WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$document = $result->fetch_assoc();
$stmt->close();

if (!$document) {
    header('Location: documents.php');
    exit();
}

$residents_result = query_helper($conn, "SELECT id, CONCAT(last_name, ', ', first_name) as name FROM residents ORDER BY last_name, first_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resident_id = $_POST['resident_id'];
    $document_type = sanitize_input($_POST['document_type']);
    $issued_date = $_POST['issued_date'];
    $expiry_date = $_POST['expiry_date'] ?: null;
    $status = sanitize_input($_POST['status']);
    $notes = sanitize_input($_POST['notes']);

    if (empty($resident_id) || empty($document_type) || empty($issued_date)) {
        $errors[] = 'Please fill in required fields.';
    } else {
        $stmt = $conn->prepare("UPDATE documents SET resident_id = ?, document_type = ?, issued_date = ?, expiry_date = ?, status = ?, notes = ? WHERE id = ?");
        $stmt->bind_param("isssssi", $resident_id, $document_type, $issued_date, $expiry_date, $status, $notes, $id);
        if ($stmt->execute()) {
            $success = 'Document updated successfully.';
            $document = array_merge($document, [
                'resident_id' => $resident_id,
                'document_type' => $document_type,
                'issued_date' => $issued_date,
                'expiry_date' => $expiry_date,
                'status' => $status,
                'notes' => $notes,
            ]);
        } else {
            $errors[] = 'Unable to update document.';
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
    <title>Edit Document - Barangay RMS</title>
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
        <h1>Edit Document</h1>

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
                <label class="form-label">Resident</label>
                <select name="resident_id" class="form-select" required>
                    <option value="">Select Resident</option>
                    <?php while ($resident = $residents_result->fetch_assoc()): ?>
                        <option value="<?php echo $resident['id']; ?>" <?php echo $resident['id'] == $document['resident_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($resident['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Document Type</label>
                <input type="text" name="document_type" class="form-control" value="<?php echo htmlspecialchars($document['document_type']); ?>" required>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Issued Date</label>
                    <input type="date" name="issued_date" class="form-control" value="<?php echo htmlspecialchars($document['issued_date']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Expiry Date</label>
                    <input type="date" name="expiry_date" class="form-control" value="<?php echo htmlspecialchars($document['expiry_date']); ?>">
                </div>
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="Active" <?php echo $document['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                    <option value="Expired" <?php echo $document['status'] === 'Expired' ? 'selected' : ''; ?>>Expired</option>
                    <option value="Revoked" <?php echo $document['status'] === 'Revoked' ? 'selected' : ''; ?>>Revoked</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3"><?php echo htmlspecialchars($document['notes']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="documents.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>