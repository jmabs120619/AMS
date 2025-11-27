<?php
include 'admin/db.php';


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
                //echo "Successfully Time in !";
                // set var value = 1;

                session_start();
                $_SESSION['rfid'] =  $user['id'];
                header('location:index.php');
                break;
            case 1:
                $next_action = ['log_type' => 'time_out', 'session' => 'morning'];
                echo "Successfully Time out !";
                // set var value = 2;
                session_start();
                $_SESSION['rfid'] =  $user['id'];
                header('location:index.php');
                break;
            case 2:
                $next_action = ['log_type' => 'time_in', 'session' => 'afternoon'];
                echo "Successfully Time in !";
                // set var value = 3;
                session_start();
                $_SESSION['rfid'] =  $user['id'];
                header('location:index.php');
                break;
            case 3:
                $next_action = ['log_type' => 'time_out', 'session' => 'afternoon'];
                echo "Successfully Time out !";
                session_start();
                $_SESSION['rfid'] =  $user['id'];
                header('location:index.php');
                break;
            default:
                echo "All attendance logs for today are already recorded.";
                // echo json_encode(['status' => 'error', 'message' => 'All attendance logs for today are already recorded.']);
                session_start();
                $_SESSION['rfid'] =  $user['id'];
                header('location:index.php');
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

        // return result 


        header('location:index.php');
    } else {
        // echo json_encode(['status' => 'error', 'message' => 'RFID not registered']);
        echo "RFID not registered";
    }
} else {
    // echo json_encode(['status' => 'error', 'message' => 'No RFID provided']);
    echo "NO RFID provided !";
}
