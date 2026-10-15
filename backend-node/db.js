/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 15: MySQL Database Connection Pool for Node.js & Express
 * ============================================================================
 * 
 * Uses 'mysql2/promise' to provide async/await database queries.
 * Database: cyber_portal_db (Imported via backend-php/db.sql)
 */

const mysql = require('mysql2/promise');

// MySQL Connection Pool Configuration (matches XAMPP defaults)
const pool = mysql.createPool({
  host: '127.0.0.1',
  user: 'root',
  password: '',
  database: 'cyber_portal_db',
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0
});

// Helper function to test database connectivity
async function testDbConnection() {
  try {
    const connection = await pool.getConnection();
    console.log('✅ [Practical 15] Successfully connected to MySQL database: cyber_portal_db');
    connection.release();
    return true;
  } catch (error) {
    console.warn('⚠️  [Practical 15] MySQL is not reachable on 127.0.0.1:3306.');
    console.warn('   Ensure XAMPP MySQL is started and backend-php/db.sql is imported.');
    console.warn('   Error details:', error.message);
    return false;
  }
}

module.exports = { pool, testDbConnection };
