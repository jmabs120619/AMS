<?php
// generate_dtr.php

include 'db.php'; // Include your database connection
include 'navbar.php';
include 'loading_screen.php';

// Retrieve RFID from the request
$rfidUid = isset($_GET['rfid']) ? $_GET['rfid'] : null;
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('m'); // Default to current month

if (!$rfidUid) {
    echo '<h2>RFID not provided in the request. Please provide a valid RFID to generate the DTR.</h2>';
    exit;
}

// Query to fetch employee details by RFID
$stmtEmployee = $pdo->prepare("SELECT * FROM employees WHERE rfid_uid = :rfid LIMIT 1");
$stmtEmployee->execute(['rfid' => $rfidUid]);
$employee = $stmtEmployee->fetch(PDO::FETCH_ASSOC);

if (!$employee) {
    echo '<h2>Employee not found for the provided RFID.</h2>';
    exit;
}

// Generate all dates for the selected month
$year = date('Y'); // Default to the current year
$totalDays = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $year);
$allDates = [];
for ($day = 1; $day <= $totalDays; $day++) {
    $allDates[] = sprintf('%s-%02d-%02d', $year, $selectedMonth, $day);
}

// Query to fetch attendance records for the employee filtered by month
$stmtAttendance = $pdo->prepare("
    SELECT 
        DATE(log_time) AS date, 
        MAX(CASE WHEN log_type = 'time_in' AND session = 'morning' THEN TIME(log_time) END) AS time_in_am,
        MAX(CASE WHEN log_type = 'time_out' AND session = 'morning' THEN TIME(log_time) END) AS time_out_am,
        MAX(CASE WHEN log_type = 'time_in' AND session = 'afternoon' THEN TIME(log_time) END) AS time_in_pm,
        MAX(CASE WHEN log_type = 'time_out' AND session = 'afternoon' THEN TIME(log_time) END) AS time_out_pm
    FROM attendance 
    WHERE user_id = :user_id 
    AND MONTH(log_time) = :month
    GROUP BY DATE(log_time)
    ORDER BY DATE(log_time) ASC
");
$stmtAttendance->execute(['user_id' => $employee['id'], 'month' => $selectedMonth]);
$attendanceRecords = $stmtAttendance->fetchAll(PDO::FETCH_ASSOC);

// Create a map of attendance records by date for easy lookup
$attendanceMap = [];
foreach ($attendanceRecords as $record) {
    $attendanceMap[$record['date']] = $record;
}

// Merge all dates with attendance records
$mergedRecords = [];
foreach ($allDates as $date) {
    $mergedRecords[] = array_merge(
        ['date' => $date, 'time_in_am' => null, 'time_out_am' => null, 'time_in_pm' => null, 'time_out_pm' => null],
        $attendanceMap[$date] ?? []
    );
}

// Get available months
$months = [
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'July',
    '08' => 'August',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December'
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate DTR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container mt-5">
        <!-- Back Button -->
        <a href="dashboard.php" class="btn btn-primary mb-4">Back to Dashboard</a>

        <!-- Page Title -->
        <h1 class="display-4 text-center mb-4">Daily Time Record (DTR)</h1>

        <!-- Month Filter -->
        <div class="mb-4 text-center">
            <form method="GET" action="">
                <label for="month" class="form-label">Select Month:</label>
                <!-- Hidden RFID Field -->
                <input type="hidden" name="rfid" value="<?= htmlspecialchars($rfidUid) ?>">

                <!-- Month Selector -->
                <select name="month" id="month" class="form-select d-inline-block" style="width: 200px;" onchange="this.form.submit()">
                    <?php foreach ($months as $monthNumber => $monthName): ?>
                        <option value="<?= $monthNumber ?>" <?= $selectedMonth == $monthNumber ? 'selected' : ''; ?>>
                            <?= $monthName ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <!-- Print Button -->
                <button type="button" class="btn btn-success" onclick="printDTR()">Print DTR</button>
            </form>
        </div>

        <!-- Employee Details -->
        <div class="card mb-5 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Employee Details</h3>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li><strong>Name:</strong> <?= htmlspecialchars($employee['name']) ?></li>
                    <li><strong>Job Title:</strong> <?= htmlspecialchars($employee['job_title']) ?></li>
                    <li><strong>Department:</strong> <?= htmlspecialchars($employee['department']) ?></li>
                </ul>
            </div>
        </div>

        <!-- Attendance Records -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">Attendance Records</h3>
            </div>
            <div class="card-body">
                <?php if (!empty($mergedRecords)) : ?>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Day</th> <!-- New column for day names -->
                                <th>Time In AM</th>
                                <th>Time Out AM</th>
                                <th>Time In PM</th>
                                <th>Time Out PM</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($mergedRecords as $record) : ?>
                                <?php
                                $date = new DateTime($record['date']); // Convert date string to DateTime object
                                $dayNumber = $date->format('j'); // Extract the day as a number (1-31)
                                $dayName = $date->format('l'); // Extract the day name (e.g., Monday)
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($dayNumber . ' ' . $dayName . '') ?></td> <!-- Display day as a number with the day name -->

                                    <td><?= $record['time_in_am'] ? htmlspecialchars($record['time_in_am']) : '' ?></td>
                                    <td><?= $record['time_out_am'] ? htmlspecialchars($record['time_out_am']) : '' ?></td>
                                    <td><?= $record['time_in_pm'] ? htmlspecialchars($record['time_in_pm']) : '' ?></td>
                                    <td><?= $record['time_out_pm'] ? htmlspecialchars($record['time_out_pm']) : '' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                <?php else : ?>
                    <p>No attendance records found for this employee in the selected month.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Load Bootstrap JS at the bottom -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function printDTR() {
            const dtrTable = document.querySelector('table');
            if (!dtrTable) {
                alert("No DTR data available to print.");
                return;
            }

            const employeeName = "<?= htmlspecialchars($employee['name'] ?? 'Unknown Employee') ?>";
            const selectedMonth = document.getElementById('month').options[document.getElementById('month').selectedIndex].text;

            const printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Daily Time Record</title>');
            printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">');
            printWindow.document.write('</head><body>');
            printWindow.document.write('<h1 class="text-center">Daily Time Record</h1>');
            printWindow.document.write(`<h3 class="text-center">Employee Name: ${employeeName}</h3>`);
            printWindow.document.write(`<h4 class="text-center">Month: ${selectedMonth}</h4>`);
            printWindow.document.write(dtrTable.outerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>