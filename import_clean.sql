-- Barangay Records Management System - PostgreSQL Schema
-- This file creates all tables for the BRM application

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'staff' CHECK (role IN ('admin', 'staff')),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create residents table
CREATE TABLE IF NOT EXISTS residents (
    id SERIAL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    address TEXT NOT NULL,
    birthdate DATE NOT NULL,
    contact_number VARCHAR(15),
    email VARCHAR(100),
    gender VARCHAR(20) CHECK (gender IN ('Male', 'Female', 'Other')),
    civil_status VARCHAR(20) CHECK (civil_status IN ('Single', 'Married', 'Widowed', 'Divorced')),
    occupation VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create documents table
CREATE TABLE IF NOT EXISTS documents (
    id SERIAL PRIMARY KEY,
    resident_id INT NOT NULL,
    document_type VARCHAR(100) NOT NULL,
    issued_date DATE NOT NULL,
    expiry_date DATE,
    status VARCHAR(20) DEFAULT 'Active' CHECK (status IN ('Active', 'Expired', 'Revoked')),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (resident_id) REFERENCES residents(id) ON DELETE CASCADE
);

-- Create blotter table
CREATE TABLE IF NOT EXISTS blotter (
    id SERIAL PRIMARY KEY,
    incident_date TIMESTAMP NOT NULL,
    description TEXT NOT NULL,
    complainant VARCHAR(100),
    respondent VARCHAR(100),
    location TEXT,
    status VARCHAR(20) DEFAULT 'Pending' CHECK (status IN ('Pending', 'Resolved', 'Dismissed')),
    resolution TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert admin user (password: admin123)
-- Hash generated with: password_hash('admin123', PASSWORD_DEFAULT)
INSERT INTO users (username, password, role) VALUES 
('admin', '$2y$10$C9XTTub4qYMCcxqgjIkSheZaluZ8M.ZQYK7VwGhY/jeSjRCTGg1pm', 'admin')
ON CONFLICT (username) DO NOTHING;

-- Insert sample resident
INSERT INTO residents (first_name, last_name, middle_name, address, birthdate, contact_number, email, gender, civil_status, occupation) VALUES
('Christian', 'Salazar', 'Schenider', 'banonong dapitan city', '2002-11-19', '09676457123', 'christian@example.com', 'Male', 'Single', 'Student')
ON CONFLICT DO NOTHING;

-- Insert sample document
INSERT INTO documents (resident_id, document_type, issued_date, expiry_date, status, notes) VALUES
(1, 'pdf', '2005-04-15', '2026-12-06', 'Active', 'ipatawag kang kapitan')
ON CONFLICT DO NOTHING;

-- Insert sample blotter record
INSERT INTO blotter (incident_date, description, complainant, respondent, location, status, resolution) VALUES
('2026-05-22 13:30:00', 'ipatawag ka kay nangawat kag puthaw', 'charles', 'suspect1', 'street', 'Pending', NULL)
ON CONFLICT DO NOTHING;

COMMIT;
