<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'navbar.php';
include 'db.php';
include 'loading_screen.php';

// Fetch Employee and Department Data
$employees = $pdo->query("SELECT * FROM employees")->fetchAll();
$departments = $pdo->query("SELECT DISTINCT department FROM employees")->fetchAll();

// Count Employees and Departments
$total_employees = count($employees);
$total_departments = count($departments);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="icon" href="DPWH_icon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .header {
            padding: 1rem;
            background-color: #28a745;
            color: #fff;
            border-radius: 10px;
            text-align: center;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-icon {
            font-size: 2.5rem;
        }

        .table-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
        }

        .table th,
        .table td {
            vertical-align: middle;
        }

        @media (max-width: 768px) {
            .table {
                font-size: 0.9rem;
            }

            .header h1 {
                font-size: 1.5rem;
            }

            .table-container {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="container mt-4">
        <div class="header">
            <h1>Welcome to the Admin Dashboard</h1>
        </div>

        <!-- Summary Cards -->
        <div class="row mt-4">
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-primary">
                    <div class="card-body d-flex align-items-center">
                        <div class="card-icon me-3">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <h3>Total Employees</h3>
                            <h4><?php echo $total_employees; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card text-white bg-success">
                    <div class="card-body d-flex align-items-center">
                        <div class="card-icon me-3">
                            <i class="bi bi-building"></i>
                        </div>
                        <div>
                            <h3>Total Departments</h3>
                            <h4><?php echo $total_departments; ?></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="table-container mt-4">
            <h2 class="mb-4">Employee Records</h2>
            <table class="table table-bordered table-hover text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Job Title</th>
                        <th>Department</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($employees as $employee): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($employee['employee_id']); ?></td>
                            <td><?php echo htmlspecialchars($employee['name']); ?></td>
                            <td><?php echo htmlspecialchars($employee['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($employee['department']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $employee['status'] === 'Active' ? 'success' : 'secondary'; ?>">
                                    <?php echo htmlspecialchars($employee['status']); ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Department Table -->
        <div class="table-container mt-4">
            <h2 class="mb-4">Departments</h2>
            <table class="table table-bordered table-hover text-center">
                <thead class="table-success">
                    <tr>
                        <th>Department Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($departments as $department): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($department['department']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
    include 'footer.php';
    ?>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>