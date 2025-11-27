<?php
include 'db.php';

if (isset($_GET['id'])) {
    $attendance_id = $_GET['id'];

    // Delete the attendance record
    $stmt = $pdo->prepare("DELETE FROM employees WHERE id = :id");
    $stmt->execute(['id' => $attendance_id]);

    header("Location: dashboard.php");
    exit();
}
?>
