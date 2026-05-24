#!/usr/bin/env python3
"""
Fix prepared statements to be PDO-compatible
"""
import os
import re
from pathlib import Path

pages_dir = Path(r'C:\Users\Charles\OneDrive\something\OneDrive\Desktop\BRM\pages')

# Files that use prepared statements
files_to_fix_prepared = {
    'edit_blotter.php': [
        (r'\$stmt = \$conn->prepare\("SELECT \* FROM blotter WHERE id = \?"\);\s*\$stmt->bind_param\("i", \$id\);\s*\$stmt->execute\(\);\s*\$result = \$stmt->get_result\(\);\s*\$record = \$result->fetch_assoc\(\);',
         '''// Use PDO-compatible prepared statement
if ($conn instanceof mysqli) {
    $stmt = $conn->prepare("SELECT * FROM blotter WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $record = $result->fetch_assoc();
} else {
    $stmt = $conn->prepare("SELECT * FROM blotter WHERE id = ?");
    $record = $stmt->execute([$id]) ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
}'''),
    ],
    'edit_resident.php': [
        (r'\$stmt = \$conn->prepare\("SELECT \* FROM residents WHERE id = \?"\);\s*\$stmt->bind_param\("i", \$id\);\s*\$stmt->execute\(\);\s*\$result = \$stmt->get_result\(\);\s*\$resident = \$result->fetch_assoc\(\);',
         '''// Use PDO-compatible prepared statement
if ($conn instanceof mysqli) {
    $stmt = $conn->prepare("SELECT * FROM residents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resident = $result->fetch_assoc();
} else {
    $stmt = $conn->prepare("SELECT * FROM residents WHERE id = ?");
    $resident = $stmt->execute([$id]) ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
}'''),
    ],
    'view_resident.php': [
        (r'\$stmt = \$conn->prepare\("SELECT \* FROM residents WHERE id = \?"\);\s*\$stmt->bind_param\("i", \$id\);\s*\$stmt->execute\(\);\s*\$result = \$stmt->get_result\(\);\s*\$resident = \$result->fetch_assoc\(\);',
         '''// Use PDO-compatible prepared statement
if ($conn instanceof mysqli) {
    $stmt = $conn->prepare("SELECT * FROM residents WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $resident = $result->fetch_assoc();
} else {
    $stmt = $conn->prepare("SELECT * FROM residents WHERE id = ?");
    $resident = $stmt->execute([$id]) ? $stmt->fetch(PDO::FETCH_ASSOC) : null;
}'''),
    ],
}

print("Prepared statement fixes require manual implementation due to complexity.")
print("Will need to handle each file's prepare/bind_param/execute pattern individually.")
