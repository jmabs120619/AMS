<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
    // Retrieve and sanitize input
    $id = trim($_POST['id']);  // Corrected the syntax
    $employee_id = trim($_POST['employee_id']);
    $name = trim($_POST['name']);
    $job_title = trim($_POST['job_title']);
    $department = trim($_POST['department']);
    $rfid_uid = trim($_POST['rfid_uid']);
    $status = trim($_POST['status']);

    // Validate input
    if (empty($employee_id) || empty($name) || empty($job_title) || empty($department) || empty($rfid_uid) || empty($status)) {
        echo "All fields are required!";
        exit;
    }

    try {
        // Update employee record
        $stmt = $pdo->prepare("
            UPDATE employees 
            SET name = :name, job_title = :job_title, department = :department, rfid_uid = :rfid_uid,
                status = :status, employee_id = :employee_id
            WHERE id = :id
        ");
        $stmt->execute([
            'name' => $name,
            'job_title' => $job_title,
            'department' => $department,
            'rfid_uid' => $rfid_uid,
            'status' => $status,
            'employee_id' => $employee_id,
            'id' => $id  // Make sure to bind the 'id' parameter
        ]);

        // Redirect or display a success message
        header("Location: dashboard.php?update=success");
        exit();
    } catch (PDOException $e) {
        // Handle database errors
        echo "Error updating record: " . $e->getMessage();
        exit();
    }
} else {
    // Redirect or handle invalid access
    header("Location: dashboard.php?error=invalid_request");
    exit();
}
