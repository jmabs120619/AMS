<?php
include 'admin/db.php';

if (isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];

    $stmt = $pdo->prepare("
        SELECT DATE(log_time) AS date, 
               log_type, 
               session, 
               TIME(log_time) AS time 
        FROM attendance 
        WHERE user_id = :user_id 
        ORDER BY log_time ASC
    ");
    $stmt->execute(['user_id' => $user_id]);
    $logs = $stmt->fetchAll();

    echo json_encode(['status' => 'success', 'logs' => $logs]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User ID is missing.']);
}
