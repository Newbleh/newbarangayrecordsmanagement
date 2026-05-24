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
$errors = [];
$success = '';

$result = prepare_and_execute($conn, "SELECT * FROM residents WHERE id = ?", "i", $id);
$resident = $result->fetch_assoc();

if (!$resident) {
    header('Location: residents.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = sanitize_input($_POST['first_name']);
    $last_name = sanitize_input($_POST['last_name']);
    $middle_name = sanitize_input($_POST['middle_name']);
    $address = sanitize_input($_POST['address']);
    $birthdate = $_POST['birthdate'];
    $contact_number = sanitize_input($_POST['contact_number']);
    $email = sanitize_input($_POST['email']);
    $gender = sanitize_input($_POST['gender']);
    $civil_status = sanitize_input($_POST['civil_status']);
    $occupation = sanitize_input($_POST['occupation']);

    if (empty($first_name) || empty($last_name) || empty($address) || empty($birthdate)) {
        $errors[] = 'Required fields cannot be empty.';
    } else {
        $result = prepare_and_execute($conn, "UPDATE residents SET first_name = ?, last_name = ?, middle_name = ?, address = ?, birthdate = ?, contact_number = ?, email = ?, gender = ?, civil_status = ?, occupation = ? WHERE id = ?", "ssssssssssi", $first_name, $last_name, $middle_name, $address, $birthdate, $contact_number, $email, $gender, $civil_status, $occupation, $id);
        if ($result) {
            $success = 'Resident profile updated successfully.';
            $resident = array_merge($resident, [
                'first_name' => $first_name,
                'last_name' => $last_name,
                'middle_name' => $middle_name,
                'address' => $address,
                'birthdate' => $birthdate,
                'contact_number' => $contact_number,
                'email' => $email,
                'gender' => $gender,
                'civil_status' => $civil_status,
                'occupation' => $occupation,
            ]);
        } else {
            $errors[] = 'Unable to update resident profile.';
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
    <title>Edit Resident - Barangay RMS</title>
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
                <h1>Edit Resident</h1>
                <p class="text-muted">Update the resident profile information below.</p>
            </div>
            <a href="view_resident.php?id=<?php echo $resident['id']; ?>" class="btn btn-secondary">View Profile</a>
        </div>

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

        <form method="post">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">First Name *</label>
                    <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($resident['first_name']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Last Name *</label>
                    <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($resident['last_name']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" value="<?php echo htmlspecialchars($resident['middle_name']); ?>">
                </div>
            </div>
            <div class="mb-3 mt-3">
                <label class="form-label">Address *</label>
                <textarea name="address" class="form-control" rows="3" required><?php echo htmlspecialchars($resident['address']); ?></textarea>
            </div>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Birthdate *</label>
                    <input type="date" name="birthdate" class="form-control" value="<?php echo htmlspecialchars($resident['birthdate']); ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact Number</label>
                    <input type="text" name="contact_number" class="form-control" value="<?php echo htmlspecialchars($resident['contact_number']); ?>">
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($resident['email']); ?>">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Gender</label>
                    <select name="gender" class="form-select">
                        <option value="">Select</option>
                        <option value="Male" <?php echo $resident['gender'] === 'Male' ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo $resident['gender'] === 'Female' ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo $resident['gender'] === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-3">
                <div class="col-md-6">
                    <label class="form-label">Civil Status</label>
                    <select name="civil_status" class="form-select">
                        <option value="">Select</option>
                        <option value="Single" <?php echo $resident['civil_status'] === 'Single' ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?php echo $resident['civil_status'] === 'Married' ? 'selected' : ''; ?>>Married</option>
                        <option value="Widowed" <?php echo $resident['civil_status'] === 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                        <option value="Divorced" <?php echo $resident['civil_status'] === 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Occupation</label>
                    <input type="text" name="occupation" class="form-control" value="<?php echo htmlspecialchars($resident['occupation']); ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-4">Save Changes</button>
            <a href="residents.php" class="btn btn-secondary mt-4">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>