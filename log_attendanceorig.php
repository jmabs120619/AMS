<?php
include 'admin/db.php';
// include 'admin/loading_screen.php';
// Only include the loading screen if not an AJAX request (i.e., not a JSON request)
if (empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
    include 'admin/loading_screen.php'; // Loading screen is only included for regular page loads, not for AJAX requests
}

if (isset($_POST['rfid_uid'])) {

    $rfid_uid = $_POST['rfid_uid'];
    $current_date = date('Y-m-d');

    // Fetch user details
    $stmt = $pdo->prepare("SELECT id, name FROM employees WHERE rfid_uid = :rfid_uid");
    $stmt->execute(['rfid_uid' => $rfid_uid]);
    $user = $stmt->fetch();

    if ($user) {
        // Fetch attendance logs for today
        $check_attendance_stmt = $pdo->prepare("
            SELECT * FROM attendance 
            WHERE user_id = :user_id 
            AND DATE(log_time) = :current_date
            ORDER BY log_time ASC
        ");
        $check_attendance_stmt->execute([
            'user_id' => $user['id'],
            'current_date' => $current_date
        ]);
        $attendance_records = $check_attendance_stmt->fetchAll();

        // Determine next action based on the number of records
        $next_action = null;
        switch (count($attendance_records)) {
            case 0:
                $next_action = ['log_type' => 'time_in', 'session' => 'morning'];
                break;
            case 1:
                $next_action = ['log_type' => 'time_out', 'session' => 'morning'];
                break;
            case 2:
                $next_action = ['log_type' => 'time_in', 'session' => 'afternoon'];
                break;
            case 3:
                $next_action = ['log_type' => 'time_out', 'session' => 'afternoon'];
                break;
            default:
                echo json_encode(['status' => 'error', 'message' => 'All attendance logs for today are already recorded.']);
                exit;
        }

        // Insert the log into the database
        $insert_stmt = $pdo->prepare("
            INSERT INTO attendance (user_id, log_type, session, log_time) 
            VALUES (:user_id, :log_type, :session, NOW())
        ");
        $insert_stmt->execute([
            'user_id' => $user['id'],
            'log_type' => $next_action['log_type'],
            'session' => $next_action['session']
        ]);

        // Return success message including the user's name
        echo json_encode([
            'status' => 'success',
            'message' => ucfirst($next_action['log_type']) . " logged for " . $user['name'] . " in the " . $next_action['session'] . " session.",
            'user_name' => $user['name'] // Add the user's name to the response
        ]);

        //
       
    } else {
        echo json_encode(['status' => 'error', 'message' => 'RFID not registered']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No RFID provided']);
}
