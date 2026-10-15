<?php
/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 13: PHP-MySQL Connectivity & Complete CRUD Operations
 * ============================================================================
 * 
 * Demonstrates Complete CRUD Lifecycle:
 * - CREATE : SQL INSERT via PDO Prepared Statements
 * - READ   : SQL SELECT with associative array fetching
 * - UPDATE : SQL UPDATE with parameterized modification (crud_update.php)
 * - DELETE : SQL DELETE with parameterized removal (crud_delete.php)
 */

require_once 'db_connect.php';

$message = '';
$msgType = 'success';

// Handle CREATE Operation (Form submission)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create') {
    $reporterName = trim($_POST['reporterName'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $incidentType = trim($_POST['incidentType'] ?? '');
    $description  = trim($_POST['description'] ?? '');

    if (empty($reporterName) || empty($email) || empty($incidentType) || empty($description)) {
        $message = "Validation Error: All fields are required.";
        $msgType = 'danger';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Validation Error: Invalid email format.";
        $msgType = 'danger';
    } else {
        try {
            // CREATE: INSERT SQL
            $sql = "INSERT INTO incident_reports (reporter_name, email, incident_type, description) 
                    VALUES (:name, :email, :type, :desc)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'  => $reporterName,
                ':email' => $email,
                ':type'  => $incidentType,
                ':desc'  => $description
            ]);
            $message = "Record successfully created in MySQL table 'incident_reports'!";
            $msgType = 'success';
        } catch (PDOException $e) {
            $message = "Database Insert Error: " . $e->getMessage();
            $msgType = 'danger';
        }
    }
}

// Check URL query parameters for feedback messages
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'updated') {
        $message = "Record successfully updated in MySQL database!";
        $msgType = 'success';
    } elseif ($_GET['msg'] === 'deleted') {
        $message = "Record successfully deleted from MySQL database!";
        $msgType = 'warning';
    }
}

// READ: Fetch all incidents
try {
    $stmt = $pdo->query("SELECT * FROM incident_reports ORDER BY id DESC");
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
    <title>Practical 13: Complete PHP-MySQL CRUD | CyberAware Portal</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/responsive.css">
</head>
<body>

    <header class="site-header">
        <div class="nav-container">
            <div class="logo">
                <a href="../index.html">
                    🛡️ CyberAware <span class="logo-badge">Portal</span>
                </a>
            </div>
            <nav aria-label="Main Navigation">
                <ul class="nav-links">
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../threats.html">Cyber Threats</a></li>
                    <li><a href="../learn.html">Learn</a></li>
                    <li><a href="../safety-tips.html">Safety Tips</a></li>
                    <li><a href="../resources.html">Resources</a></li>
                    <li><a href="../quiz.html">Quiz</a></li>
                    <li><a href="crud_dashboard.php" class="active highlight-btn">P13: PHP CRUD</a></li>
                    <li><a href="../practicals.html">Examiner Hub</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <header>
            <span class="badge badge-info">Practical 13</span>
            <h1 class="page-title">PHP-MySQL Connectivity &amp; Complete CRUD Operations</h1>
            <p class="page-subtitle">
                Demonstrates full lifecycle Database Operations: 
                <span class="badge badge-success">CREATE</span>, 
                <span class="badge badge-info">READ</span>, 
                <span class="badge badge-warning">UPDATE</span>, and 
                <span class="badge badge-danger">DELETE</span> using PHP PDO Prepared Statements.
            </p>
        </header>

        <?php if (!empty($message)): ?>
            <div class="card" style="border-left: 4px solid <?= $msgType === 'success' ? 'var(--accent-green)' : ($msgType === 'warning' ? 'var(--accent-yellow)' : 'var(--accent-red)') ?>; margin-bottom: 1.5rem;">
                <p style="color: <?= $msgType === 'success' ? 'var(--accent-green)' : ($msgType === 'warning' ? 'var(--accent-yellow)' : 'var(--accent-red)') ?>; font-weight: 600;">
                    <?= htmlspecialchars($message) ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- CREATE OPERATION: Insert New Record -->
        <section class="card">
            <h3>1. CREATE Operation (SQL INSERT)</h3>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                Adds a new security incident report into MySQL table <code>incident_reports</code>:
            </p>

            <form method="POST" action="crud_dashboard.php">
                <input type="hidden" name="action" value="create">
                
                <div class="two-col-grid" style="margin-top: 0;">
                    <div class="form-group">
                        <label for="reporterName">Reporter Name:</label>
                        <input type="text" id="reporterName" name="reporterName" class="form-control" placeholder="e.g. Alex Morgan" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Contact Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="e.g. alex@college.edu" required>
                    </div>
                </div>

                <div class="two-col-grid" style="margin-top: 0;">
                    <div class="form-group">
                        <label for="incidentType">Incident Type:</label>
                        <select id="incidentType" name="incidentType" class="form-control" required>
                            <option value="">-- Select Incident Classification --</option>
                            <option value="Phishing">Phishing Attempt</option>
                            <option value="Malware">Malware Infection</option>
                            <option value="Ransomware">Ransomware Warning</option>
                            <option value="SQL Injection">SQL Injection Probe</option>
                            <option value="DDoS">DDoS Spike</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Summary Description:</label>
                        <input type="text" id="description" name="description" class="form-control" placeholder="Brief summary of threat observed..." required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    + Insert Record (SQL INSERT) &rarr;
                </button>
            </form>
        </section>

        <!-- READ, UPDATE & DELETE OPERATIONS: Record Table -->
        <section class="card" style="margin-top: 1.5rem;">
            <div class="flex-between">
                <h3>2. READ, UPDATE &amp; DELETE Operations (SQL SELECT, UPDATE, DELETE)</h3>
                <span class="badge badge-info">Total Records: <?= count($incidents) ?></span>
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem; margin-top: 0.3rem;">
                Table rows fetched via PDO <code>SELECT</code>. Click <strong>Edit</strong> to UPDATE or <strong>Delete</strong> to DELETE:
            </p>

            <?php if (empty($incidents)): ?>
                <p style="color: var(--text-muted); margin-top: 1rem;">No incident records found. Add one above.</p>
            <?php else: ?>
                <div style="overflow-x: auto; margin-top: 1rem;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Reporter</th>
                                <th>Email</th>
                                <th>Threat Category</th>
                                <th>Description</th>
                                <th>Date/Time</th>
                                <th style="text-align: center;">CRUD Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($incidents as $row): ?>
                                <tr>
                                    <td><strong>#<?= (int)$row['id'] ?></strong></td>
                                    <td><?= htmlspecialchars($row['reporter_name']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <td>
                                        <span class="badge <?= in_array($row['incident_type'], ['Phishing', 'Ransomware', 'SQL Injection']) ? 'badge-danger' : 'badge-warning' ?>">
                                            <?= htmlspecialchars($row['incident_type']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td style="font-size: 0.8rem; color: var(--text-muted);"><?= htmlspecialchars($row['reported_at']) ?></td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        <!-- UPDATE Action -->
                                        <a href="crud_update.php?id=<?= (int)$row['id'] ?>" class="btn btn-outline" style="padding: 2px 8px; font-size: 0.8rem; margin-right: 4px;">
                                            ✏️ Edit
                                        </a>
                                        <!-- DELETE Action -->
                                        <a href="crud_delete.php?id=<?= (int)$row['id'] ?>" onclick="return confirm('Are you sure you want to delete incident #<?= (int)$row['id'] ?> from MySQL?');" class="btn btn-outline" style="padding: 2px 8px; font-size: 0.8rem; border-color: var(--accent-red); color: var(--accent-red);">
                                            🗑️ Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

    </main>

    <footer class="site-footer">
        <div class="footer-bottom" style="border: 0; padding: 0;">
            <p>&copy; 2026 Cybersecurity Awareness and Learning Portal | Practical 13: Complete PHP-MySQL CRUD</p>
            <div>
                <a href="../practicals.html" class="footer-wtl-link">🧪 WTL Practicals</a>
                <a href="../index.html" class="footer-wtl-link" style="margin-left: 0.5rem;">&larr; Public Portal</a>
            </div>
        </div>
    </footer>

</body>
</html>
