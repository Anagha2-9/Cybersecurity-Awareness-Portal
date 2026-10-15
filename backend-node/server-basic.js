/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 10: Node.js Core Architecture & Basic HTTP Server
 * ============================================================================
 * 
 * Concepts Demonstrated:
 * - Built-in 'http' core module (No npm dependencies required)
 * - Request / Response handling (req.url, req.method, res.writeHead, res.end)
 * - Serving JSON and plain HTML responses
 * - Reading operating system information via 'os' module
 * 
 * How to Run (PowerShell):
 *   cd backend-node
 *   node server-basic.js
 * Access in browser: http://localhost:4000
 */

const http = require('http');
const os = require('os');

// Server configuration port
const PORT = 4000;

// Create the HTTP server using Node.js built-in 'http.createServer'
const server = http.createServer((req, res) => {
  // Add CORS headers so frontend can call this server if needed
  res.setHeader('Access-Control-Allow-Origin', '*');
  res.setHeader('Access-Control-Allow-Methods', 'GET, OPTIONS');

  // Handle preflight OPTIONS request
  if (req.method === 'OPTIONS') {
    res.writeHead(204);
    res.end();
    return;
  }

  // Route 1: Home Page / Root Route
  if (req.url === '/' && req.method === 'GET') {
    res.writeHead(200, { 'Content-Type': 'text/html; charset=utf-8' });
    res.end(`
      <!DOCTYPE html>
      <html>
      <head>
        <title>Node.js Basic HTTP Server - Practical 10</title>
        <style>
          body { font-family: Segoe UI, sans-serif; background: #0f172a; color: #f8fafc; padding: 2rem; }
          .card { background: #1e293b; padding: 1.5rem; border-radius: 8px; border-left: 4px solid #06b6d4; max-width: 600px; margin: auto; }
          a { color: #06b6d4; }
          code { background: #334155; padding: 2px 6px; border-radius: 4px; }
        </style>
      </head>
      <body>
        <div class="card">
          <h2>🛡️ Node.js Core HTTP Server (Practical 10)</h2>
          <p>This server was built using only Node.js core modules (<code>http</code>, <code>os</code>) with zero third-party packages.</p>
          <hr style="border: 0; border-top: 1px solid #334155; margin: 1rem 0;">
          <p><strong>Available Endpoints:</strong></p>
          <ul>
            <li><a href="/api/health">/api/health</a> - Server health & system metrics in JSON</li>
          </ul>
        </div>
      </body>
      </html>
    `);
    return;
  }

  // Route 2: System Health and Cyber Server Status (JSON)
  if (req.url === '/api/health' && req.method === 'GET') {
    const healthData = {
      status: 'ONLINE',
      application: 'Cybersecurity Awareness Portal - Node Core Server',
      practical: 'Practical 10: Node.js Core Server',
      timestamp: new Date().toISOString(),
      system: {
        platform: os.platform(),
        architecture: os.arch(),
        uptimeSeconds: Math.floor(os.uptime()),
        freeMemoryMB: Math.round(os.freemem() / (1024 * 1024)),
        totalMemoryMB: Math.round(os.totalmem() / (1024 * 1024))
      },
      securityNotice: 'Node.js runtime sandbox active. All ports monitored.'
    };

    res.writeHead(200, { 'Content-Type': 'application/json' });
    res.end(JSON.stringify(healthData, null, 2));
    return;
  }

  // 404 Route for unhandled paths
  res.writeHead(404, { 'Content-Type': 'application/json' });
  res.end(JSON.stringify({ error: 'Endpoint Not Found', code: 404 }));
});

// Start listening for client connections
server.listen(PORT, () => {
  console.log(`===================================================`);
  console.log(` [Practical 10] Node.js Core HTTP Server Running! `);
  console.log(` Port: http://localhost:${PORT}`);
  console.log(` Health check: http://localhost:${PORT}/api/health`);
  console.log(`===================================================`);
});
