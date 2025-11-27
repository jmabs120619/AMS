<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'navbar.php';
include 'db.php';
include 'loading_screen.php';

// Fetch all attendance records with time-in and time-out data
$stmt = $pdo->query("
    SELECT 
        DATE(log_time) AS date,
        session,
        MAX(CASE WHEN log_type = 'time_in' THEN log_time END) AS time_in,
        MAX(CASE WHEN log_type = 'time_out' THEN log_time END) AS time_out
    FROM attendance
    WHERE user_id = 1
    GROUP BY DATE(log_time), session
    ORDER BY date, session;
");
$attendance_records = $stmt->fetchAll();

$employees = $pdo->query("SELECT * FROM employees");
$emp = $employees->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RFID - Employees</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .header {
            padding: 1rem;
            background-color: #0d6efd;
            color: #fff;
            border-radius: 10px;
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

        .modal-header {
            background-color: #0d6efd;
            color: #fff;
        }

        .btn-primary,
        .btn-success {
            box-shadow: 0 4px 6px rgba(0, 123, 255, 0.3);
        }

        .btn-warning {
            color: #fff;
        }

        .btn-warning:hover {
            background-color: #ff9800;
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
    <!-- Admin Dashboard Header -->
    <div class="container mt-4">
        <div class="header d-flex justify-content-between align-items-center">
            <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h1>
            <div>
                <a href="dashboard.php" class="btn btn-light text-primary me-2">Dashboard</a>
                <button type="button" class="btn btn-light text-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                    Add Employee
                </button>
            </div>
        </div>

        <!-- Employee Records Table -->
        <div class="table-container mt-4">
            <h2 class="mb-4">Employee Records</h2>
            <table class="table table-bordered table-hover text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>z</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($emp as $records): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($records['employee_id'] ?? '-'); ?></td>
                            <td><?php echo htmlspecialchars($records['name']); ?></td>
                            <td><?php echo htmlspecialchars($records['job_title']); ?></td>
                            <td><?php echo htmlspecialchars($records['department']); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $records['status'] === 'Active' ? 'success' : 'secondary'; ?>">
                                    <?php echo htmlspecialchars($records['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="update_employee.php?id=<?php echo htmlspecialchars($records['id']); ?>"
                                    class="btn btn-warning btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editEmployeeModal"
                                    data-id="<?php echo htmlspecialchars($records['id']); ?>"
                                    data-employee_id="<?php echo htmlspecialchars($records['employee_id']); ?>"
                                    data-name="<?php echo htmlspecialchars($records['name']); ?>"
                                    data-job_title="<?php echo htmlspecialchars($records['job_title']); ?>"
                                    data-department="<?php echo htmlspecialchars($records['department']); ?>"
                                    data-rfid_uid="<?php echo htmlspecialchars($records['rfid_uid']); ?>"
                                    data-status="<?php echo htmlspecialchars($records['status']); ?>">Edit</a>
                                <a href="generate_dtr.php?rfid=<?php echo $records['rfid_uid']; ?>" class="btn btn-success btn-sm">Generate DTR</a>
                                <!-- <a href="delete_record.php?id=<?php echo htmlspecialchars($records['id']); ?>"
                                    class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this record?')">Delete</a> -->
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Employee Modal -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="update_employee.php">
                        <input type="hidden" id="id" name="id">
                        <div class="mb-3">
                            <div class="mb-3">
                                <label for="employee_id" class="form-label">Employee ID</label>
                                <input type="text" name="employee_id" id="employee_id" class="form-control" required>
                            </div>
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="job_title" class="form-label">Job Title</label>
                            <input type="text" name="job_title" id="job_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" id="department" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="rfid_uid" class="form-label">RFID UID</label>
                            <input type="text" name="rfid_uid" id="rfid_uid" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Employee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="add_employee.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Employee ID</label>
                            <input type="text" name="employee_id" id="employee_id" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="job_title" class="form-label">Job Title</label>
                            <input type="text" name="job_title" id="job_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="department" class="form-label">Department</label>
                            <input type="text" name="department" id="department" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="rfid_uid" class="form-label">RFID UID</label>
                            <input type="text" name="rfid_uid" id="rfid_uid" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="" disabled selected>Select Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Add Employee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- <button class="btn btn-success" onclick="window.print()">Print DTR</button> -->
    </div>
    </div>
    </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function populateEditEmployeeModal(employee) {
            document.getElementById('id').value = employee.id;
            document.getElementById('employee_id').value = employee.employee_id;
            document.getElementById('name').value = employee.name;
            document.getElementById('job_title').value = employee.job_title;
            document.getElementById('department').value = employee.department;
            document.getElementById('rfid_uid').value = employee.rfid_uid;

            // Set the selected value for the status dropdown
            const statusDropdown = document.getElementById('status');
            statusDropdown.value = employee.status;

            // Make sure the selected option is correctly displayed
            const options = statusDropdown.querySelectorAll('option');
            options.forEach(option => {
                if (option.value === employee.status) {
                    option.selected = true;
                } else {
                    option.selected = false;
                }
            });
        }


        // Open Edit Modal and populate data
        const editButtons = document.querySelectorAll('[data-bs-target="#editEmployeeModal"]');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const employee_id = this.getAttribute('data-employee_id');
                const name = this.getAttribute('data-name');
                const jobTitle = this.getAttribute('data-job_title');
                const department = this.getAttribute('data-department');
                const rfid_uid = this.getAttribute('data-rfid_uid');
                const status = this.getAttribute('data-status');

                // Populate the modal fields
                document.getElementById('id').value = id;
                document.getElementById('employee_id').value = employee_id;
                document.getElementById('name').value = name;
                document.getElementById('job_title').value = jobTitle;
                document.getElementById('department').value = department;
                document.getElementById('rfid_uid').value = rfid_uid;
                document.getElementById('status').value = status;
            });
        });



        document.querySelectorAll('.generate-dtr-btn').forEach(button => {
            button.addEventListener('click', function() {
                const rfid = this.getAttribute('data-rfid'); // Assuming the RFID is stored in a `data-rfid` attribute
                const url = `generate_dtr.php?rfid=${rfid}`;

                // Now you can use fetch to request the URL
                fetch(url)
                    .then(response => response.text())
                    .then(data => {
                        // Handle the response (render it to a modal, or display an error message, etc.)
                    })
                    .catch(error => {
                        console.error('Error fetching DTR:', error);
                    });
            });
        });
    </script>


</body>

</html>