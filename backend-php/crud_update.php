<?php
/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 13: UPDATE Operation in PHP & MySQL (CRUD)
 * ============================================================================
 * 
 * Demonstrates:
 * - Fetching existing record by ID
 * - Executing SQL UPDATE with PDO Prepared Statement (Prevents SQL Injection)
 */

require_once 'db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$message = '';
$error = '';

if ($id <= 0) {
    header("Location: crud_dashboard.php");
    exit();
}

// Handle Form Submission for Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reporterName = trim($_POST['reporterName'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $incidentType = trim($_POST['incidentType'] ?? '');
    $description  = trim($_POST['description'] ?? '');

    if (empty($reporterName) || empty($email) || empty($incidentType) || empty($description)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please provide a valid email address.";
    } else {
        try {
            // Prepared Statement for UPDATE
            $sql = "UPDATE incident_reports 
                    SET reporter_name = :name, email = :email, incident_type = :type, description = :desc 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':name'  => $reporterName,
                ':email' => $email,
                ':type'  => $incidentType,
                ':desc'  => $description,
                ':id'    => $id
            ]);

            header("Location: crud_dashboard.php?msg=updated");
            exit();
        } catch (PDOException $e) {
            $error = "Database Error: " . $e->getMessage();
        }
    }
}

// Fetch existing record
try {
    $stmt = $pdo->prepare("SELECT * FROM incident_reports WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $incident = $stmt->fetch();

    if (!$incident) {
        die("Incident report #$id not found.");
    }
} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Incident #<?= $id ?> | PHP CRUD (Practical 13)</title>
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
                    <li><a href="crud_dashboard.php" class="active">P13: PHP CRUD Dashboard</a></li>
                    <li><a href="../integration.html">P15: Full Integration</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div style="max-width: 600px; margin: auto;">
            <header>
                <span class="badge badge-warning">UPDATE Operation</span>
                <h1 class="page-title" style="margin-top: 0.4rem;">Edit Incident Report #<?= $id ?></h1>
                <p class="page-subtitle">Demonstrates SQL <code>UPDATE</code> using PDO prepared statements.</p>
            </header>

            <?php if (!empty($error)): ?>
                <div class="card" style="border-left: 4px solid var(--accent-red); margin-bottom: 1rem;">
                    <p style="color: var(--accent-red);"><?= htmlspecialchars($error) ?></p>
                </div>
            <?php endif; ?>

            <div class="card">
                <form method="POST" action="crud_update.php?id=<?= $id ?>">
                    <div class="form-group">
                        <label for="reporterName">Reporter Full Name:</label>
                        <input type="text" id="reporterName" name="reporterName" class="form-control" value="<?= htmlspecialchars($incident['reporter_name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Contact Email:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($incident['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="incidentType">Incident Type:</label>
                        <select id="incidentType" name="incidentType" class="form-control" required>
                            <option value="Phishing" <?= $incident['incident_type'] === 'Phishing' ? 'selected' : '' ?>>Phishing Attempt</option>
                            <option value="Malware" <?= $incident['incident_type'] === 'Malware' ? 'selected' : '' ?>>Malware Detection</option>
                            <option value="Ransomware" <?= $incident['incident_type'] === 'Ransomware' ? 'selected' : '' ?>>Ransomware Threat</option>
                            <option value="SQL Injection" <?= $incident['incident_type'] === 'SQL Injection' ? 'selected' : '' ?>>SQL Injection Probe</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="description">Incident Description:</label>
                        <textarea id="description" name="description" rows="4" class="form-control" required><?= htmlspecialchars($incident['description']) ?></textarea>
                    </div>

                    <div class="flex-between">
                        <button type="submit" class="btn btn-primary">Update Record (SQL UPDATE) &rarr;</button>
                        <a href="crud_dashboard.php" class="btn btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Cybersecurity Awareness Portal | Practical 13: Complete PHP-MySQL CRUD</p>
    </footer>

</body>
</html>
