<?php
/**
 * ============================================================================
 * Cybersecurity Awareness and Learning Portal
 * Practical 13: DELETE Operation in PHP & MySQL (CRUD)
 * ============================================================================
 * 
 * Demonstrates:
 * - Deleting record by ID using PDO Prepared Statement
 */

require_once 'db_connect.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        $stmt = $pdo->prepare("DELETE FROM incident_reports WHERE id = :id");
        $stmt->execute([':id' => $id]);
        header("Location: crud_dashboard.php?msg=deleted");
        exit();
    } catch (PDOException $e) {
        die("Database Delete Error: " . htmlspecialchars($e->getMessage()));
    }
} else {
    header("Location: crud_dashboard.php");
    exit();
}
?>
