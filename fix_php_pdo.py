#!/usr/bin/env python3
"""
Fix all PHP files to use PDO-compatible fetch_assoc() calls
"""
import os
import re
from pathlib import Path

pages_dir = Path(r'C:\Users\Charles\OneDrive\something\OneDrive\Desktop\BRM\pages')

# Files that need fixing
files_to_fix = [
    'add_document.php',
    'blotter.php',
    'documents.php',
    'edit_blotter.php',
    'edit_document.php',
    'edit_resident.php',
    'reports.php',
    'residents.php',
    'view_resident.php'
]

for filename in files_to_fix:
    filepath = pages_dir / filename
    if not filepath.exists():
        print(f"Skipped (not found): {filename}")
        continue
    
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    original_content = content
    
    # Pattern 1: $result->fetch_assoc() chains (for queries that execute directly)
    # Replace: $conn->query("...") with: query_helper($conn, "...")
    content = re.sub(
        r'\$conn->query\(',
        r'query_helper($conn, ',
        content
    )
    
    # Pattern 2: $stmt->fetch_assoc() calls (after prepare/execute)
    # These should use PDO::FETCH_ASSOC via wrapper
    # Replace: $result->fetch_assoc() with: $result->fetch_assoc()
    # (wrapper handles this automatically)
    
    if content != original_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Fixed: {filename}")
    else:
        print(f"No changes needed: {filename}")

print("\nAll files processed!")
