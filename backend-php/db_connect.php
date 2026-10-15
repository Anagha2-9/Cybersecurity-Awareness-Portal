<?php
/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 14: MySQL Database Connection via PHP PDO
 * ============================================================================
 * 
 * Why PDO (PHP Data Objects)?
 * 1. Database-agnostic interface (supports MySQL, PostgreSQL, SQLite, etc.)
 * 2. Native support for Prepared Statements to prevent SQL Injection attacks.
 * 3. Exception-based robust error handling.
 */

// Default XAMPP MySQL configuration credentials
$host     = '127.0.0.1';       // MySQL Host (or localhost)
$db_name  = 'cyber_portal_db'; // Database Name created in db.sql
$username = 'root';            // Default XAMPP username
$password = '';                // Default XAMPP password is empty

try {
    // Data Source Name (DSN)
    $dsn = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";
    
    // Create PDO connection instance
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Throw exceptions on errors
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Return associative arrays
        PDO::ATTR_EMULATE_PREPARES   => false                   // Native prepared statements
    ]);

    // Connection successful
} catch (PDOException $e) {
    // Return friendly error message with instructions
    die("<div style='font-family: Segoe UI, sans-serif; background: #0f172a; color: #f8fafc; padding: 2rem;'>
            <div style='background: #1e293b; border-left: 4px solid #ef4444; padding: 1.5rem; max-width: 600px; margin: auto; border-radius: 8px;'>
                <h3 style='color: #ef4444;'>❌ MySQL Database Connection Failed</h3>
                <p>Could not connect to database <code>$db_name</code>.</p>
                <p style='color: #94a3b8;'>Error Details: " . htmlspecialchars($e->getMessage()) . "</p>
                <hr style='border: 0; border-top: 1px solid #334155; margin: 1rem 0;'>
                <p><strong>Troubleshooting for XAMPP:</strong></p>
                <ol style='line-height: 1.8;'>
                    <li>Open <strong>XAMPP Control Panel</strong> and ensure <strong>MySQL</strong> is started (green).</li>
                    <li>Ensure you imported <code>backend-php/db.sql</code> via <a href='http://localhost/phpmyadmin' style='color: #06b6d4;'>phpMyAdmin</a>.</li>
                </ol>
            </div>
         </div>");
}
?>
