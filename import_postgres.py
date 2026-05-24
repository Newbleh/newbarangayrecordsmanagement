#!/usr/bin/env python3
"""
Import PostgreSQL dump into Render PostgreSQL database
"""
import psycopg2
import sys

# Render PostgreSQL credentials
DB_HOST = 'dpg-d8810bjbc2fs73efl1b0-a.oregon-postgres.render.com'
DB_PORT = 5432
DB_USER = 'charles_f2ae_user'
DB_PASS = 'yPU9HiYUZoTGmnN3jQDAEOEnS6rpeYFM'
DB_NAME = 'charles_f2ae'

# Path to SQL dump file
SQL_FILE = r'C:\Users\Charles\OneDrive\something\OneDrive\Desktop\BRM\import_clean.sql'

try:
    print(f"Connecting to PostgreSQL at {DB_HOST}:{DB_PORT}/{DB_NAME}...")
    conn = psycopg2.connect(
        host=DB_HOST,
        port=DB_PORT,
        user=DB_USER,
        password=DB_PASS,
        database=DB_NAME
    )
    print("✓ Connected successfully")
    
    cursor = conn.cursor()
    
    # Read and execute SQL file
    print(f"Reading SQL dump from {SQL_FILE}...")
    with open(SQL_FILE, 'r', encoding='utf-8') as f:
        sql_content = f.read()
    
    print("Executing SQL import (parsing statements)...")
    
    # Split by semicolon and filter out empty/comment-only statements
    statements = sql_content.split(';')
    stmt_count = 0
    for stmt in statements:
        # Clean up the statement
        lines = []
        for line in stmt.split('\n'):
            # Remove comments
            if '--' in line:
                line = line[:line.index('--')]
            line = line.strip()
            if line:
                lines.append(line)
        
        cleaned_stmt = ' '.join(lines)
        if cleaned_stmt.strip():
            try:
                cursor.execute(cleaned_stmt)
                stmt_count += 1
            except psycopg2.Error as e:
                print(f"Warning: Error in statement: {cleaned_stmt[:100]}... ({e})")
                # continue on error
    
    conn.commit()
    print(f"✓ SQL import completed successfully ({stmt_count} statements executed)")
    
    # Verify tables were created
    cursor.execute("""
        SELECT tablename FROM pg_tables 
        WHERE schemaname = 'public' 
        ORDER BY tablename;
    """)
    tables = cursor.fetchall()
    print(f"\n✓ Created tables: {[t[0] for t in tables]}")
    
    # Check if admin user exists
    cursor.execute("SELECT COUNT(*) FROM users WHERE username = 'admin';")
    admin_count = cursor.fetchone()[0]
    print(f"✓ Admin users in database: {admin_count}")
    
    cursor.close()
    conn.close()
    print("\n✓ Import complete! Database is ready.")
    sys.exit(0)
    
except psycopg2.Error as e:
    print(f"✗ PostgreSQL error: {e}")
    sys.exit(1)
except FileNotFoundError:
    print(f"✗ SQL file not found: {SQL_FILE}")
    sys.exit(1)
except Exception as e:
    print(f"✗ Error: {e}")
    sys.exit(1)
