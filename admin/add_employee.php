<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = $_POST['employee_id'];
    $name = $_POST['name'];
    $job_title = $_POST['job_title'];
    $department = $_POST['department'];
    $rfid_uid = $_POST['rfid_uid'];
    $status = $_POST['status'];

    // Validate input
    if (empty($name) || empty($job_title) || empty($department) || empty($rfid_uid) || empty($status)) {
        echo "All fields are required!";
        exit;
    }

    try {
        // Insert the new employee into the database
        $stmt = $pdo->prepare("
            INSERT INTO employees (employee_id, name, job_title, department, rfid_uid, status) 
            VALUES (:employee_id,:name, :job_title, :department, :rfid_uid, :status)
        ");
        $stmt->execute([
            'employee_id' => $employee_id,
            'name' => $name,
            'job_title' => $job_title,
            'department' => $department,
            'rfid_uid' => $rfid_uid,
            'status' => $status,
        ]);

        // Redirect or return a success message
        echo "Employee added successfully!";
        header('Location: dashboard.php');
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request method!";
}
