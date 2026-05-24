<?php
// Database configuration - supports both MySQL and PostgreSQL
$dbHost = getenv('DB_HOST') ?: '127.0.0.1';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') ?: '';
$dbName = getenv('DB_NAME') ?: 'barangay_records';
$dbType = getenv('DB_TYPE') ?: 'mysql';

if ($dbHost === 'localhost') {
    $dbHost = '127.0.0.1';
}

// Detect database type from port or explicit setting
if ($dbPort == '5432' || strpos($dbHost, 'postgres') !== false || strpos($dbHost, 'render') !== false) {
    $dbType = 'postgresql';
}

// Create connection based on database type
if ($dbType === 'postgresql') {
    try {
        $conn = new PDO(
            "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName",
            $dbUser,
            $dbPass,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }
} else {
    // MySQL connection using mysqli
    $conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8");
}

/**
 * Helper function to create a wrapper for database results
 * Makes PDO and mysqli results compatible
 */
function wrap_result($result, $is_pdo = false) {
    if ($is_pdo) {
        return new PDOResultWrapper($result);
    }
    return new MysqliResultWrapper($result);
}

/**
 * Wrapper class for mysqli results
 */
class MysqliResultWrapper {
    private $result;
    
    public function __construct($result) {
        $this->result = $result;
    }
    
    public function fetch_assoc() {
        return $this->result ? $this->result->fetch_assoc() : null;
    }
    
    public function num_rows() {
        return $this->result ? $this->result->num_rows : 0;
    }
}

/**
 * Wrapper class for PDO results
 */
class PDOResultWrapper {
    private $stmt;
    
    public function __construct($stmt) {
        $this->stmt = $stmt;
    }
    
    public function fetch_assoc() {
        return $this->stmt ? $this->stmt->fetch(PDO::FETCH_ASSOC) : null;
    }
    
    public function num_rows() {
        if ($this->stmt) {
            return $this->stmt->rowCount();
        }
        return 0;
    }
}

// Helper function to run queries that work with both mysqli and PDO
function query_helper($conn, $sql) {
    if ($conn instanceof PDO) {
        $stmt = $conn->query($sql);
        return new PDOResultWrapper($stmt);
    } else {
        $result = $conn->query($sql);
        return new MysqliResultWrapper($result);
    }
}
?>