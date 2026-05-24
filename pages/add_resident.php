<?php
session_start();
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_login();

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
        $errors[] = 'Please fill in required fields.';
    } else {
        $result = prepare_and_execute($conn, "INSERT INTO residents (first_name, last_name, middle_name, address, birthdate, contact_number, email, gender, civil_status, occupation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", "ssssssssss", $first_name, $last_name, $middle_name, $address, $birthdate, $contact_number, $email, $gender, $civil_status, $occupation);
        if ($result) {
            $success = 'Resident added successfully.';
        } else {
            $errors[] = 'Failed to add resident.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Resident - Barangay Records Management</title>
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
                    <li class="nav-item"><a class="nav-link active" href="residents.php">Residents</a></li>
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
        <h1>Add Resident</h1>
        
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
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name *</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="last_name" class="form-label">Last Name *</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="middle_name" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name">
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="address" class="form-label">Address *</label>
                <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="birthdate" class="form-label">Birthdate *</label>
                        <input type="date" class="form-control" id="birthdate" name="birthdate" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contact_number" name="contact_number">
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="gender" class="form-label">Gender</label>
                        <select class="form-control" id="gender" name="gender">
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="civil_status" class="form-label">Civil Status</label>
                        <select class="form-control" id="civil_status" name="civil_status">
                            <option value="">Select</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Divorced">Divorced</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="occupation" class="form-label">Occupation</label>
                        <input type="text" class="form-control" id="occupation" name="occupation">
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Resident</button>
            <a href="residents.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>