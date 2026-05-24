<?php
/**
 * Reset admin password in Render PostgreSQL database
 * This script connects directly to the Render database and updates the admin user password
 */

// Render PostgreSQL credentials
$dbHost = 'dpg-d8810bjbc2fs73efl1b0-a.oregon-postgres.render.com';
$dbPort = '5432';
$dbUser = 'charles_f2ae_user';
$dbPass = 'yPU9HiYUZoTGmnN3jQDAEOEnS6rpeYFM';
$dbName = 'charles_f2ae';

try {
    // Connect to PostgreSQL
    $conn = new PDO(
        "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    
    echo "✓ Connected to Render PostgreSQL\n";
    
    // Generate hash for 'admin123'
    $password = 'admin123';
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    echo "Generated hash: " . $passwordHash . "\n";
    
    // Update admin user password
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
    $result = $stmt->execute([$passwordHash, 'admin']);
    
    if ($result) {
        echo "✓ Admin password updated successfully!\n";
        echo "Username: admin\n";
        echo "Password: admin123\n";
        
        // Verify the update
        $check = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $check->execute(['admin']);
        $user = $check->fetch();
        
        if ($user) {
            echo "\n✓ Verification:\n";
            echo "  ID: " . $user['id'] . "\n";
            echo "  Username: " . $user['username'] . "\n";
            echo "  Password Hash: " . substr($user['password'], 0, 20) . "...\n";
            
            // Test password_verify
            if (password_verify('admin123', $user['password'])) {
                echo "✓ Password verification successful!\n";
            } else {
                echo "✗ Password verification failed!\n";
            }
        }
    } else {
        echo "✗ Failed to update password\n";
    }
    
    $conn = null;
    
} catch (PDOException $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
