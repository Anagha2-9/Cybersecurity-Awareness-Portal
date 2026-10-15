<?php
/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 13: PHP Server-Side Form Handling & Data Sanitization
 * ============================================================================
 * 
 * Concepts Demonstrated:
 * - PHP Superglobals: $_POST, $_SERVER
 * - Server-side data validation & sanitation (htmlspecialchars, filter_var)
 * - Defense against Cross-Site Scripting (XSS)
 * - Server-rendered response page
 */

// Initialize variables
$fullName = '';
$email    = '';
$role     = '';
$submitted = false;
$errors   = [];

// Check if form is submitted via POST method
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // 1. Sanitize Full Name (Trim and strip dangerous characters)
    $fullName = trim($_POST['fullName'] ?? '');
    if (empty($fullName) || strlen($fullName) < 3) {
        $errors[] = "Full name is required and must be at least 3 characters long.";
    }

    // 2. Validate and Sanitize Email address using PHP filter
    $email = trim($_POST['email'] ?? '');
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    // 3. Sanitize User Role
    $role = trim($_POST['role'] ?? '');
    if (empty($role)) {
        $errors[] = "Please select your role.";
    }

    // If no errors, mark submission as successful
    if (empty($errors)) {
        $submitted = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Form Handling | Practical 13</title>
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
            <h1 class="page-title">PHP Server-Side Form Handling (Practical 13)</h1>
            <p class="page-subtitle">Demonstrates handling HTTP POST requests, data sanitization using <code>htmlspecialchars()</code>, and server-side validation.</p>
        </header>

        <div style="max-width: 650px; margin: auto;">
            
            <?php if ($submitted): ?>
                <!-- Submission Confirmation Panel -->
                <div class="card" style="border-left: 4px solid var(--accent-green);">
                    <h3 style="color: var(--accent-green);">✅ Newsletter Subscription Confirmed!</h3>
                    <p style="margin-top: 0.5rem; color: var(--text-muted);">
                        The PHP server successfully received and sanitized your POST data:
                    </p>
                    
                    <table class="data-table" style="margin-top: 1rem;">
                        <tr>
                            <th>Parameter</th>
                            <th>Sanitized Value</th>
                        </tr>
                        <tr>
                            <td><strong>Full Name:</strong></td>
                            <td><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Email:</strong></td>
                            <td><?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Role:</strong></td>
                            <td><?= htmlspecialchars($role, ENT_QUOTES, 'UTF-8') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Server Timestamp:</strong></td>
                            <td><?= date('Y-m-d H:i:s') ?></td>
                        </tr>
                    </table>

                    <div style="margin-top: 1.5rem;">
                        <a href="subscribe.php" class="btn btn-outline">&larr; Submit Another Response</a>
                        <a href="../integration.html" class="btn btn-primary" style="margin-left: 0.5rem;">Go to Full Integration</a>
                    </div>
                </div>

            <?php else: ?>

                <!-- Subscription Form -->
                <div class="card">
                    <h3>Subscribe to Monthly Cyber Threat Bulletins</h3>
                    <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.2rem;">
                        Data submitted here is processed by PHP on the server side using the <code>$_POST</code> superglobal.
                    </p>

                    <?php if (!empty($errors)): ?>
                        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid var(--accent-red); padding: 0.8rem; border-radius: 6px; margin-bottom: 1rem;">
                            <strong style="color: var(--accent-red);">Validation Errors:</strong>
                            <ul style="color: var(--accent-red); margin-left: 1.2rem; margin-top: 0.3rem;">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="subscribe.php">
                        <div class="form-group">
                            <label for="fullName">Full Name:</label>
                            <input type="text" id="fullName" name="fullName" class="form-control" value="<?= htmlspecialchars($fullName) ?>" placeholder="e.g. Alex Morgan" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" placeholder="e.g. alex@college.edu" required>
                        </div>

                        <div class="form-group">
                            <label for="role">User Role:</label>
                            <select id="role" name="role" class="form-control" required>
                                <option value="">-- Select Your Role --</option>
                                <option value="Student" <?= $role === 'Student' ? 'selected' : '' ?>>College Student</option>
                                <option value="Faculty" <?= $role === 'Faculty' ? 'selected' : '' ?>>Faculty Member</option>
                                <option value="Security Enthusiast" <?= $role === 'Security Enthusiast' ? 'selected' : '' ?>>Security Enthusiast</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Submit via PHP POST &rarr;
                        </button>
                    </form>
                </div>

            <?php endif; ?>

        </div>
    </main>

    <footer class="site-footer">
        <p>&copy; 2026 Cybersecurity Awareness Portal | Practical 13: PHP Form Handling</p>
    </footer>

</body>
</html>
