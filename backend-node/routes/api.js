/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 15: REST API using Node.js, Express & Database Connectivity (MySQL)
 * ============================================================================
 * 
 * Demonstrates Complete Database-Connected RESTful API:
 * 1. GET    /api/incidents       -> Retrieve all records from MySQL
 * 2. GET    /api/incidents/:id   -> Retrieve single record by ID from MySQL
 * 3. POST   /api/incidents       -> Insert new record into MySQL (HTTP 201 Created)
 * 4. PUT    /api/incidents/:id   -> Update existing record in MySQL (HTTP 200 OK)
 * 5. DELETE /api/incidents/:id   -> Remove record from MySQL (HTTP 200 OK)
 * 6. GET    /api/db-status       -> Check live MySQL connection health
 */

const express = require('express');
const router = express.Router();
const { pool } = require('../db');

// In-Memory Reference Threat List (for educational threat lookups)
const threatDatabase = [
  { id: 1, name: 'Phishing', severity: 'High', vector: 'Email/SMS', defense: 'MFA & Domain Verification' },
  { id: 2, name: 'Ransomware', severity: 'Critical', vector: 'Trojans', defense: 'Air-gapped 3-2-1 Backups' },
  { id: 3, name: 'SQL Injection', severity: 'Critical', vector: 'Form Input', defense: 'Prepared Statements' },
  { id: 4, name: 'Cross-Site Scripting', severity: 'High', vector: 'Reflected Script', defense: 'Context Output Encoding' },
  { id: 5, name: 'DDoS', severity: 'Medium', vector: 'Botnet Streams', defense: 'Rate Limiting & Anycast WAF' }
];

// ----------------------------------------------------------------------------
// Health / Status Endpoint: Check MySQL connectivity
// ----------------------------------------------------------------------------
router.get('/db-status', async (req, res) => {
  try {
    const [rows] = await pool.query('SELECT 1 as test');
    res.status(200).json({
      connected: true,
      database: 'cyber_portal_db',
      engine: 'MySQL (mysql2 connection pool)',
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    res.status(503).json({
      connected: false,
      database: 'cyber_portal_db',
      error: error.message,
      advice: 'Start MySQL in XAMPP and import backend-php/db.sql via phpMyAdmin.'
    });
  }
});

// ----------------------------------------------------------------------------
// 1. GET /api/incidents - Retrieve all incidents from MySQL
// ----------------------------------------------------------------------------
router.get('/incidents', async (req, res) => {
  try {
    const [rows] = await pool.query('SELECT * FROM incident_reports ORDER BY id DESC');
    res.status(200).json({
      success: true,
      source: 'MySQL Database (incident_reports table)',
      count: rows.length,
      data: rows
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Failed to query MySQL database: ' + error.message
    });
  }
});

// ----------------------------------------------------------------------------
// 2. GET /api/incidents/:id - Retrieve single incident by ID from MySQL
// ----------------------------------------------------------------------------
router.get('/incidents/:id', async (req, res) => {
  const id = parseInt(req.params.id, 10);
  try {
    const [rows] = await pool.query('SELECT * FROM incident_reports WHERE id = ?', [id]);
    if (rows.length === 0) {
      return res.status(404).json({
        success: false,
        message: `Incident #${id} not found in MySQL database.`
      });
    }
    res.status(200).json({
      success: true,
      data: rows[0]
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Database query error: ' + error.message
    });
  }
});

// ----------------------------------------------------------------------------
// 3. POST /api/incidents - Insert new incident into MySQL (HTTP 201 Created)
// ----------------------------------------------------------------------------
router.post('/incidents', async (req, res) => {
  const { reporterName, email, incidentType, description } = req.body;

  // Validation
  if (!reporterName || !email || !incidentType || !description) {
    return res.status(400).json({
      success: false,
      message: 'Validation Error: reporterName, email, incidentType, and description are required.'
    });
  }

  try {
    const sql = `INSERT INTO incident_reports (reporter_name, email, incident_type, description) 
                 VALUES (?, ?, ?, ?)`;
    const [result] = await pool.query(sql, [
      reporterName.trim(),
      email.trim(),
      incidentType.trim(),
      description.trim()
    ]);

    // Return HTTP 201 Created with the generated MySQL primary key ID
    res.status(201).json({
      success: true,
      message: 'Record successfully inserted into MySQL database table!',
      data: {
        id: result.insertId,
        reporter_name: reporterName,
        email: email,
        incident_type: incidentType,
        description: description,
        reported_at: new Date().toISOString()
      }
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Database insert failed: ' + error.message
    });
  }
});

// ----------------------------------------------------------------------------
// 4. PUT /api/incidents/:id - Update existing incident in MySQL (HTTP 200 OK)
// ----------------------------------------------------------------------------
router.put('/incidents/:id', async (req, res) => {
  const id = parseInt(req.params.id, 10);
  const { reporterName, email, incidentType, description } = req.body;

  if (!reporterName || !email || !incidentType || !description) {
    return res.status(400).json({
      success: false,
      message: 'Validation Error: reporterName, email, incidentType, and description are required for update.'
    });
  }

  try {
    const sql = `UPDATE incident_reports 
                 SET reporter_name = ?, email = ?, incident_type = ?, description = ? 
                 WHERE id = ?`;
    const [result] = await pool.query(sql, [
      reporterName.trim(),
      email.trim(),
      incidentType.trim(),
      description.trim(),
      id
    ]);

    if (result.affectedRows === 0) {
      return res.status(404).json({
        success: false,
        message: `Incident #${id} not found in MySQL database.`
      });
    }

    res.status(200).json({
      success: true,
      message: `Incident #${id} successfully updated in MySQL database.`,
      updatedId: id
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Database update failed: ' + error.message
    });
  }
});

// ----------------------------------------------------------------------------
// 5. DELETE /api/incidents/:id - Remove incident from MySQL (HTTP 200 OK)
// ----------------------------------------------------------------------------
router.delete('/incidents/:id', async (req, res) => {
  const id = parseInt(req.params.id, 10);

  try {
    const [result] = await pool.query('DELETE FROM incident_reports WHERE id = ?', [id]);
    if (result.affectedRows === 0) {
      return res.status(404).json({
        success: false,
        message: `Incident #${id} not found in MySQL database.`
      });
    }

    res.status(200).json({
      success: true,
      message: `Incident #${id} successfully deleted from MySQL database.`,
      deletedId: id
    });
  } catch (error) {
    res.status(500).json({
      success: false,
      message: 'Database delete failed: ' + error.message
    });
  }
});

// ----------------------------------------------------------------------------
// 6. GET /api/threats - Threat Reference List
// ----------------------------------------------------------------------------
router.get('/threats', (req, res) => {
  res.status(200).json({
    success: true,
    count: threatDatabase.length,
    data: threatDatabase
  });
});

module.exports = router;
