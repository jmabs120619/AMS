<?php
include 'navbar.php';
include 'db.php';

if (isset($_GET['id'])) {
    $attendance_id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM attendance WHERE id = :id");
    $stmt->execute(['id' => $attendance_id]);
    $attendance = $stmt->fetch();
}

// Handle form submission to update attendance
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $time_in_morning = $_POST['time_in_morning'];
    $time_out_morning = $_POST['time_out_morning'];
    $time_in_afternoon = $_POST['time_in_afternoon'];
    $time_out_afternoon = $_POST['time_out_afternoon'];

    $update_stmt = $pdo->prepare("
        UPDATE attendance 
        SET time_in_morning = :time_in_morning, time_out_morning = :time_out_morning, 
            time_in_afternoon = :time_in_afternoon, time_out_afternoon = :time_out_afternoon
        WHERE id = :id
    ");
    $update_stmt->execute([
        'time_in_morning' => $time_in_morning,
        'time_out_morning' => $time_out_morning,
        'time_in_afternoon' => $time_in_afternoon,
        'time_out_afternoon' => $time_out_afternoon,
        'id' => $attendance_id
    ]);
    
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attendance</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h1>Edit Attendance Record</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="time_in_morning" class="form-label">Time In Morning</label>
            <input type="time" name="time_in_morning" id="time_in_morning" class="form-control" value="<?php echo htmlspecialchars($attendance['time_in_morning']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="time_out_morning" class="form-label">Time Out Morning</label>
            <input type="time" name="time_out_morning" id="time_out_morning" class="form-control" value="<?php echo htmlspecialchars($attendance['time_out_morning']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="time_in_afternoon" class="form-label">Time In Afternoon</label>
            <input type="time" name="time_in_afternoon" id="time_in_afternoon" class="form-control" value="<?php echo htmlspecialchars($attendance['time_in_afternoon']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="time_out_afternoon" class="form-label">Time Out Afternoon</label>
            <input type="time" name="time_out_afternoon" id="time_out_afternoon" class="form-control" value="<?php echo htmlspecialchars($attendance['time_out_afternoon']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Record</button>
    </form>
</div>
</body>
</html>
