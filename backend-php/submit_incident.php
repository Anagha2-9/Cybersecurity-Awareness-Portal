<?php
/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 14: Inserting Data into MySQL using PHP PDO Prepared Statements
 * ============================================================================
 * 
 * Crucial Cybersecurity / Viva Concept:
 * - We use PREPARED STATEMENTS (PDO::prepare) with bound parameters (:name, :email, etc.)
 * - This separates the SQL query logic from user input data.
 * - This completely PREVENTS SQL INJECTION attacks, even if an attacker enters:
 *   "' OR '1'='1" or "'; DROP TABLE incident_reports; --"
 */

require_once 'db_connect.php';

$message = '';
$isSuccess = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporterName = trim($_POST['reporterName'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $incidentType = trim($_POST['incidentType'] ?? '');
    $description  = trim($_POST['description'] ?? '');

    // Basic validation
    if (empty($reporterName) || empty($email) || empty($incidentType) || empty($description)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please provide a valid email address.";
    } else {
        try {
            // SQL query with named placeholders (:placeholder)
            $sql = "INSERT INTO incident_reports (reporter_name, email, incident_type, description) 
                    VALUES (:reporter_name, :email, :incident_type, :description)";
            
            // Prepare statement
            $stmt = $pdo->prepare($sql);

            // Execute with securely bound parameters (Prevents SQL Injection!)
            $stmt->execute([
                ':reporter_name' => $reporterName,
                ':email'         => $email,
                ':incident_type' => $incidentType,
                ':description'   => $description
            ]);

            $isSuccess = true;
            $message = "Incident report securely inserted into MySQL database table 'incident_reports'!";
        } catch (PDOException $e) {
            $message = "Database Error: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Incident | MySQL Integration (Practical 14)</title>
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
        <header>
            <h1 class="page-title">MySQL Database Insertion (Practical 14)</h1>
            <p class="page-subtitle">Demonstrates connecting PHP to MySQL and using Prepared Statements to prevent SQL Injection.</p>
        </header>

        <div style="max-width: 650px; margin: auto;">

            <?php if (!empty($message)): ?>
                <div class="card" style="border-left: 4px solid <?= $isSuccess ? 'var(--accent-green)' : 'var(--accent-red)' ?>;">
                    <p style="color: <?= $isSuccess ? 'var(--accent-green)' : 'var(--accent-red)' ?>; font-weight: 600;">
                        <?= htmlspecialchars($message) ?>
                    </p>
                    <div style="margin-top: 1rem;">
                        <a href="view_incidents.php" class="btn btn-success">View All Reports in MySQL &rarr;</a>
                        <a href="submit_incident.php" class="btn btn-outline" style="margin-left: 0.5rem;">Submit Another</a>
                    </div>
                </div>
            <?php endif; ?>

            <div class="card">
                <h3>Report a Security Incident (Saves to MySQL)</h3>
                <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.2rem;">
                    Submitting this form executes an <code>INSERT INTO incident_reports</code> statement using PHP PDO.
                </p>

                <form method="POST" action="submit_incident.php">
                    <div class="form-group">
                        <label for="reporterName">Reporter Name:</label>
                        <input type="text" id="reporterName" name="reporterName" class="form-control" placeholder="e.g. Rahul Sharma" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Contact Email:</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="e.g. rahul@college.edu" required>
                    </div>

                    <div class="form-group">
                        <label for="incidentType">Incident Classification:</label>
                        <select id="incidentType" name="incidentType" class="form-control" required>
                            <option value="">-- Select Incident Type --</option>
                            <option value="Phishing">Phishing Attempt</option>
                            <option value="Malware">Malware / Virus Detection</option>
                            <option value="Suspicious Login">Suspicious Login Activity</option>
                            <option value="Data Leak">Accidental Data Exposure</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Incident Description:</label>
                        <textarea id="description" name="description" rows="4" class="form-control" placeholder="Describe what happened, timestamp, and affected accounts..." required></textarea>
                    </div>

                    <div class="flex-between">
                        <button type="submit" class="btn btn-primary">
                            Save to MySQL Database &rarr;
                        </button>
                        <a href="view_incidents.php" class="btn btn-outline">
                            View Saved Reports 📋
                        </a>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Cybersecurity Awareness Portal | Practical 14: PHP & MySQL Database Integration</p>
    </footer>

</body>
</html>
