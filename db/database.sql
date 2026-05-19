-- Barangay Records Management System Database Schema

CREATE DATABASE IF NOT EXISTS barangay_records;
USE barangay_records;

-- Users table for authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'staff') DEFAULT 'staff',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Residents table
CREATE TABLE residents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    address TEXT NOT NULL,
    birthdate DATE NOT NULL,
    contact_number VARCHAR(15),
    email VARCHAR(100),
    gender ENUM('Male', 'Female', 'Other'),
    civil_status ENUM('Single', 'Married', 'Widowed', 'Divorced'),
    occupation VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Documents table for tracking issued documents
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    resident_id INT NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    issued_date DATE NOT NULL,
    expiry_date DATE,
    status ENUM('Active', 'Expired', 'Revoked') DEFAULT 'Active',
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (resident_id) REFERENCES residents(id) ON DELETE CASCADE
);

-- Blotter records table
CREATE TABLE blotter (
    id INT AUTO_INCREMENT PRIMARY KEY,
    incident_date DATETIME NOT NULL,
    description TEXT NOT NULL,
    complainant VARCHAR(100),
    respondent VARCHAR(100),
    location TEXT,
    status ENUM('Pending', 'Resolved', 'Dismissed') DEFAULT 'Pending',
    resolution TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO users (username, password, role) VALUES ('admin', '$2y$10$abcdefghijklmnopqrstuv1234567890ABCDEFGHIJKLMNOP', 'admin');
-- Note: This is a placeholder hash. To generate the actual hash for password '121902lacquio', run: php -r "echo password_hash('121902lacquio', PASSWORD_DEFAULT);"