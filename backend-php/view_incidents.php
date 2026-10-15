<?php
/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 14: Fetching and Displaying Data from MySQL Database
 * ============================================================================
 * 
 * Concepts Demonstrated:
 * - SQL SELECT query execution via PDO
 * - Fetching rows as associative arrays
 * - Safe rendering with htmlspecialchars() to prevent Stored XSS
 * - Empty state handling
 */

require_once 'db_connect.php';

try {
    // Retrieve all incident reports ordered by most recent first
    $stmt = $pdo->query("SELECT * FROM incident_reports ORDER BY reported_at DESC");
    $incidents = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Database Query Error: " . htmlspecialchars($e->getMessage()));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incident Reports | MySQL Database (Practical 14)</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>

    <header class="site-header">
        <div class="nav-container">
            <div class="logo">
                <a href="../index.html">🛡️ CyberAware Portal</a>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../threats.html">Threats</a></li>
                    <li><a href="../resources.html">Tools & Tips</a></li>
                    <li><a href="../pledge.html">Pledge</a></li>
                    <li><a href="../quiz.html">Quiz</a></li>
                    <li><a href="../react-app/index.html">React App</a></li>
                    <li><a href="../integration.html" class="highlight-btn">Full Integration</a></li>
                    <li><a href="../practicals.html">Lab Index</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="flex-between" style="margin-bottom: 1.5rem;">
            <div>
                <h1 class="page-title">Logged Incidents from MySQL Database</h1>
                <p class="page-subtitle">Demonstrates querying records with PHP PDO <code>SELECT</code> and rendering safe HTML.</p>
            </div>
            <div>
                <a href="submit_incident.php" class="btn btn-primary">+ Report New Incident</a>
            </div>
        </div>

        <div class="card">
            <h3>Registered Incidents (Table: <code>incident_reports</code>)</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-top: 0.3rem;">
                Total Records Retrieved: <strong><?= count($incidents) ?></strong>
            </p>

            <?php if (empty($incidents)): ?>
                <p style="color: var(--text-muted); margin-top: 1rem;">No incidents found in the database. <a href="submit_incident.php" style="color: var(--accent-cyan);">Click here to add one</a>.</p>
            <?php else: ?>
                <div style="overflow-x: auto; margin-top: 1rem;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reporter Name</th>
                                <th>Contact Email</th>
                                <th>Incident Type</th>
                                <th>Description</th>
                                <th>Reported At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidents as $row): ?>
                                <tr>
                                    <td><strong>#<?= htmlspecialchars($row['id']) ?></strong></td>
                                    <td><?= htmlspecialchars($row['reporter_name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td>
                                        <span class="badge <?= $row['incident_type'] === 'Phishing' ? 'badge-danger' : 'badge-warning' ?>">
                                            <?= htmlspecialchars($row['incident_type']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($row['reported_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <div style="margin-top: 1.5rem;">
            <a href="../integration.html" class="btn btn-outline">&larr; Return to Practical 15 Integration Hub</a>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Cybersecurity Awareness Portal | Practical 14: MySQL Data Retrieval</p>
    </footer>

</body>
</html>
