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

/**
 * Wrapper class for PDO prepared statements to mimic mysqli API
 */
class PDOStatementWrapper {
    private $stmt;
    private $params = [];
    private $types = '';
    
    public function __construct($stmt) {
        $this->stmt = $stmt;
    }
    
    /**
     * Mimic mysqli's bind_param method
     */
    public function bind_param($types, ...$params) {
        $this->types = $types;
        $this->params = $params;
    }
    
    /**
     * Execute the statement
     */
    public function execute() {
        if (!$this->stmt) {
            return false;
        }
        try {
            return $this->stmt->execute($this->params);
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Get the result (for PDO compatibility)
     */
    public function get_result() {
        return new PDOResultWrapper($this->stmt);
    }
    
    /**
     * Fetch a single row as associative array
     */
    public function fetch_assoc() {
        return $this->stmt ? $this->stmt->fetch(PDO::FETCH_ASSOC) : null;
    }
    
    /**
     * Close the statement (no-op for PDO)
     */
    public function close() {
        // PDO statements auto-close, nothing to do
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

// Override PDO's prepare method to return wrapper
// Note: This requires using $conn->prepare() in code
// For PDO, we'll wrap the prepare call in functions that need it

/**
 * Helper function to prepare and execute statements for both mysqli and PDO
 * Usage: $result = prepare_and_execute($conn, $sql, $types, $param1, $param2, ...);
 */
function prepare_and_execute($conn, $sql, $types = '', ...$params) {
    if ($conn instanceof PDO) {
        // PDO path
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return new PDOResultWrapper($stmt);
    } else {
        // mysqli path
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            if ($types) {
                $stmt->bind_param($types, ...$params);
            }
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return new MysqliResultWrapper($result);
        }
        return null;
    }
}
?>