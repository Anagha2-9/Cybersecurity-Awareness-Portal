/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 14: Node.js and Express Server, Routing and Static Resources
 * Practical 15: REST API & MySQL Database Connectivity
 * ============================================================================
 * 
 * Concepts Demonstrated for Practical 14:
 * 1. Express web server initialization
 * 2. Serving static resources (HTML, CSS, JS, JSON) via express.static()
 * 3. Express Routing (GET, POST, etc.)
 * 4. Custom middleware (Request Logger with next())
 * 5. CORS & Body Parsers (express.json, express.urlencoded)
 * 
 * How to Run (PowerShell):
 *   cd backend-node
 *   node server.js
 * Access in browser: http://localhost:5000
 */

const express = require('express');
const path = require('path');
const cors = require('cors');

// Import REST API routes (Practical 15) and Database connection tester
const apiRoutes = require('./routes/api');
const { testDbConnection } = require('./db');

const app = express();
const PORT = 5000;

// ----------------------------------------------------------------------------
// Middleware 1: Cross-Origin Resource Sharing (CORS)
// ----------------------------------------------------------------------------
app.use(cors());

// ----------------------------------------------------------------------------
// Middleware 2: Built-in Body Parsers
// ----------------------------------------------------------------------------
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// ----------------------------------------------------------------------------
// Middleware 3: Custom Request Logging Middleware (Practical 14)
// ----------------------------------------------------------------------------
app.use((req, res, next) => {
  const timestamp = new Date().toLocaleTimeString();
  console.log(`[CyberLog] [${req.method}] ${req.url} - ${timestamp}`);
  // Transfer control to next handler in the stack
  next();
});

// ----------------------------------------------------------------------------
// Practical 14: Serving Static Resources (HTML, CSS, JS, Media)
// ----------------------------------------------------------------------------
// Serves all files located in the root portal folder as static assets
const staticRoot = path.join(__dirname, '..');
app.use(express.static(staticRoot));

// ----------------------------------------------------------------------------
// Practical 14: Express Routing Examples
// ----------------------------------------------------------------------------
// Custom route providing server routing overview
app.get('/api-routes', (req, res) => {
  res.json({
    practical: 'Practical 14: Express Server & Routing',
    status: 'ONLINE',
    port: PORT,
    routes: [
      { method: 'GET', path: '/', description: 'Serves static index.html portal' },
      { method: 'GET', path: '/api/incidents', description: 'Practical 15: Fetches incidents from MySQL' },
      { method: 'POST', path: '/api/incidents', description: 'Practical 15: Inserts incident into MySQL' },
      { method: 'PUT', path: '/api/incidents/:id', description: 'Practical 15: Updates incident in MySQL' },
      { method: 'DELETE', path: '/api/incidents/:id', description: 'Practical 15: Deletes incident from MySQL' }
    ]
  });
});

// ----------------------------------------------------------------------------
// Practical 15: Mount Database-Connected REST API
// ----------------------------------------------------------------------------
app.use('/api', apiRoutes);

// Error Handling Middleware
app.use((err, req, res, next) => {
  console.error('Server Error:', err.message);
  res.status(500).json({ error: 'Internal Server Error', message: err.message });
});

// Start Express Server & Test Database Connection
app.listen(PORT, async () => {
  console.log('===============================================================');
  console.log(` 🛡️  Cybersecurity Portal Express Server Running!            `);
  console.log(` Web Portal (Static):  http://localhost:${PORT}               `);
  console.log(` Practical 14 Routes:  http://localhost:${PORT}/api-routes    `);
  console.log(` Practical 15 REST API: http://localhost:${PORT}/api/incidents `);
  console.log('===============================================================');

  // Test MySQL Connection for Practical 15
  await testDbConnection();
});
