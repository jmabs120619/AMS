<?php
include 'admin/loading_screen.php';
include 'admin/db.php';

session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="DPWH_icon.ico">
    <title>RFID Attendance Monitoring System</title>
    <script src="assets/css/sweetalert.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            background: url('assets/background.png') no-repeat center center/cover;
        }

        .header-title {
            color: #0d6efd;
            font-weight: bold;
        }

        .container {
            max-width: 720px;
            /* Adjust to your preferred width */
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        @media (max-width: 576px) {
            .header-title {
                font-size: 1.5rem;
            }

            p.text-muted {
                font-size: 0.9rem;
            }
        }
    </style>
</head>

<body>
    <!-- Main Container -->
    <div class="container mt-5">
        <!-- Header Section -->
        <div class="text-center mb-4">
            <h1 class="header-title">RFID Attendance Monitoring System</h1>
            <p class="text-muted">A convenient and accurate way to track attendance</p>
        </div>

        <!-- RFID Form Section -->
        <div class="card p-4">
            <form method="POST" action="log_attendance.php">
                <div class="mb-3">
                    <label for="rfid_uid" class="form-label">Scan RFID Tag:</label>
                    <input type="text" id="rfid_uid" name="rfid_uid" class="form-control" placeholder="Enter your RFID UID" autofocus>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>

        <!-- Status Message Section -->
        <div id="message" class="mt-3">
            <?php
            if (isset($_SESSION['rfid'])) {
                $getemp = $pdo->prepare("SELECT * FROM employees WHERE id = :uid");
                $getemp->execute(['uid' => $_SESSION['rfid']]);
                $getemp = $getemp->fetch();

                echo "<div class='alert alert-success'>Successfully stamped: " . $getemp['name'] . "</div>";
                echo "<meta http-equiv='refresh' content='5;url=index.php?resetrfid=go'>";

                $currentDate = date('Y-m-d');
                $stmt = $pdo->prepare("
                    SELECT 
                        DATE(log_time) AS date,
                        MAX(CASE WHEN log_type = 'time_in' AND session = 'morning' THEN TIME(log_time) END) AS time_in_am,
                        MAX(CASE WHEN log_type = 'time_out' AND session = 'morning' THEN TIME(log_time) END) AS time_out_am,
                        MAX(CASE WHEN log_type = 'time_in' AND session = 'afternoon' THEN TIME(log_time) END) AS time_in_pm,
                        MAX(CASE WHEN log_type = 'time_out' AND session = 'afternoon' THEN TIME(log_time) END) AS time_out_pm
                    FROM attendance
                    WHERE user_id = :user_id AND DATE(log_time) = :log_date
                    GROUP BY DATE(log_time)
                    ORDER BY DATE(log_time) DESC
                ");
                $stmt->execute([
                    'user_id' => $_SESSION['rfid'],
                    'log_date' => $currentDate
                ]);
                $logs = $stmt->fetchAll();
            } else {
                echo "<div class='alert alert-danger'>No RFID user is currently active.</div>";
            }
            ?>
        </div>

        <!-- Attendance Logs Section -->
        <div class="mt-5">
            <h3>Your Attendance Logs</h3>
            <table class="table table-bordered table-striped text-center">
                <thead class="table-dark">
                    <tr>
                        <th>Date</th>
                        <th>Time In AM</th>
                        <th>Time Out AM</th>
                        <th>Time In PM</th>
                        <th>Time Out PM</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($logs)) : ?>
                        <?php foreach ($logs as $log) : ?>
                            <tr>
                                <td><?php echo $log['date']; ?></td>
                                <td><?php echo $log['time_in_am'] ?: '-'; ?></td>
                                <td><?php echo $log['time_out_am'] ?: '-'; ?></td>
                                <td><?php echo $log['time_in_pm'] ?: '-'; ?></td>
                                <td><?php echo $log['time_out_pm'] ?: '-'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="5" class="text-center">No attendance records found for today.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>