<?php
/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 12: PHP Form Handling, Validation, String Manipulation & Sessions
 * ============================================================================
 * 
 * Concepts Demonstrated:
 * 1. Session Management: session_start(), $_SESSION, session_destroy()
 * 2. Form Handling & Validation: $_POST, filter_var(), htmlspecialchars()
 * 3. String Manipulation: strlen(), strtoupper(), strtolower(), substr(), str_replace(), trim(), hash()
 */

// Initialize or resume the PHP session
session_start();

// Handle Logout / Clear Session
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_unset();
    session_destroy();
    header("Location: practical12_session_strings.php");
    exit();
}

// Track page visit count in session
if (!isset($_SESSION['visit_count'])) {
    $_SESSION['visit_count'] = 1;
} else {
    $_SESSION['visit_count']++;
}

// Initialize variables for form handling
$username = '';
$email = '';
$secretKey = '';
$stringAnalysis = null;
$errors = [];

// Form submission handler
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rawUsername = $_POST['username'] ?? '';
    $rawEmail    = $_POST['email'] ?? '';
    $rawKey      = $_POST['secretKey'] ?? '';

    // 1. String Manipulation: trim() to remove surrounding whitespace
    $username  = trim($rawUsername);
    $email     = trim($rawEmail);
    $secretKey = trim($rawKey);

    // 2. Validation
    if (empty($username) || strlen($username) < 3) {
        $errors[] = "Username is required and must be at least 3 characters long.";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email address is required.";
    }

    if (empty($secretKey) || strlen($secretKey) < 6) {
        $errors[] = "Security Key must be at least 6 characters long.";
    }

    // If validation passes, store into Session & perform String Manipulation demos
    if (empty($errors)) {
        // Session Management: Store user details into $_SESSION
        $_SESSION['user'] = [
            'username'   => $username,
            'email'      => $email,
            'login_time' => date('Y-m-d H:i:s')
        ];

        // 3. String Manipulation Demonstrations
        $stringAnalysis = [
            'originalUsername'   => $username,
            'uppercaseUsername'  => strtoupper($username),                  // strtoupper()
            'lowercaseEmail'     => strtolower($email),                     // strtolower()
            'keyLength'          => strlen($secretKey),                     // strlen()
            'maskedKey'          => substr($secretKey, 0, 2) . '****' . substr($secretKey, -2), // substr()
            'sanitizedEmail'     => str_replace('@', ' [at] ', $email),     // str_replace()
            'sha256Hash'         => hash('sha256', $secretKey)              // hash()
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 12: PHP Sessions & Strings | CyberAware Portal</title>
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
                    <li><a href="practical12_session_strings.php" class="active highlight-btn">P12: PHP Sessions</a></li>
                    <li><a href="../practicals.html">Examiner Hub</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <header>
            <span class="badge badge-info">Practical 12</span>
            <h1 class="page-title">PHP Form Handling, Validation, Strings &amp; Sessions</h1>
            <p class="page-subtitle">
                Demonstrates server-side form processing, PHP sessions (<code>$_SESSION</code>), 
                and core string functions (<code>strlen</code>, <code>strtoupper</code>, <code>strtolower</code>, <code>substr</code>, <code>str_replace</code>, <code>hash</code>).
            </p>
        </header>

        <!-- Session Status Banner -->
        <div class="card" style="border-left: 4px solid var(--accent-green); background: rgba(16, 185, 129, 0.05);">
            <div class="flex-between">
                <div>
                    <h3 style="color: var(--accent-green);">🔐 PHP Session State</h3>
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-top: 0.2rem;">
                        Session ID: <code><?= session_id() ?></code> | Session Hits: <strong><?= $_SESSION['visit_count'] ?></strong>
                    </p>
                </div>
                <div>
                    <?php if (isset($_SESSION['user'])): ?>
                        <span class="badge badge-success">Session Active: <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                        <a href="practical12_session_strings.php?action=logout" class="btn btn-outline" style="padding: 0.3rem 0.7rem; font-size: 0.8rem; margin-left: 0.5rem; border-color: var(--accent-red); color: var(--accent-red);">Logout</a>
                    <?php else: ?>
                        <span class="badge badge-warning">No Active User Session</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="two-col-grid">
            
            <!-- PHP Form -->
            <section class="card">
                <h3>Submit Credentials for Session &amp; String Analysis</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                    Data submitted via HTTP POST is processed and stored in <code>$_SESSION</code>:
                </p>

                <?php if (!empty($errors)): ?>
                    <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid var(--accent-red); padding: 0.8rem; border-radius: 6px; margin-bottom: 1rem;">
                        <strong style="color: var(--accent-red);">Validation Errors:</strong>
                        <ul style="color: var(--accent-red); margin-left: 1.2rem; margin-top: 0.3rem;">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="practical12_session_strings.php">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($username) ?>" placeholder="e.g. cyber_analyst" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address:</label>
                        <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" placeholder="e.g. analyst@security.org" required>
                    </div>

                    <div class="form-group">
                        <label for="secretKey">Security Secret Key / Passphrase:</label>
                        <input type="password" id="secretKey" name="secretKey" class="form-control" placeholder="e.g. S3cur3P@ssw0rd!" required>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Process Form &amp; Start Session &rarr;
                    </button>
                </form>
            </section>

            <!-- String Manipulation Results Panel -->
            <section class="card">
                <h3>String Manipulation Demonstration</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                    PHP string functions applied to submitted parameters:
                </p>

                <?php if ($stringAnalysis): ?>
                    <table class="data-table">
                        <tr>
                            <th>Function</th>
                            <th>Output</th>
                        </tr>
                        <tr>
                            <td><code>strtoupper($username)</code></td>
                            <td><strong style="color: var(--accent-cyan);"><?= htmlspecialchars($stringAnalysis['uppercaseUsername']) ?></strong></td>
                        </tr>
                        <tr>
                            <td><code>strtolower($email)</code></td>
                            <td><?= htmlspecialchars($stringAnalysis['lowercaseEmail']) ?></td>
                        </tr>
                        <tr>
                            <td><code>strlen($secretKey)</code></td>
                            <td><?= $stringAnalysis['keyLength'] ?> characters</td>
                        </tr>
                        <tr>
                            <td><code>substr()</code> Masking</td>
                            <td><code><?= htmlspecialchars($stringAnalysis['maskedKey']) ?></code></td>
                        </tr>
                        <tr>
                            <td><code>str_replace('@', ' [at] ', ...)</code></td>
                            <td><?= htmlspecialchars($stringAnalysis['sanitizedEmail']) ?></td>
                        </tr>
                        <tr>
                            <td><code>hash('sha256', $key)</code></td>
                            <td style="font-size: 0.75rem; word-break: break-all; color: var(--accent-green);"><?= htmlspecialchars($stringAnalysis['sha256Hash']) ?></td>
                        </tr>
                    </table>
                <?php else: ?>
                    <div style="background: var(--bg-primary); padding: 1.5rem; text-align: center; border-radius: 6px; color: var(--text-muted);">
                        Submit the form to the left to execute PHP string manipulation functions and view live output.
                    </div>
                <?php endif; ?>
            </section>

        </div>
    </main>

    <footer class="site-footer">
        <div class="footer-bottom" style="border: 0; padding: 0;">
            <p>&copy; 2026 Cybersecurity Awareness and Learning Portal | Practical 12: PHP Sessions, Form Handling &amp; Strings</p>
            <div>
                <a href="../practicals.html" class="footer-wtl-link">🧪 WTL Practicals</a>
                <a href="../index.html" class="footer-wtl-link" style="margin-left: 0.5rem;">&larr; Public Portal</a>
            </div>
        </div>
    </footer>

</body>
</html>
