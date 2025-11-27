<?php
$current_page = basename($_SERVER['PHP_SELF']);

session_start();

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">
        <!-- Branding -->
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <img src="DPWH_Logo.jpg" alt="Logo" width="40" height="40" class="me-2 rounded-circle">
            <span class="fw-bold">DPWH RFID Attendance Monitoring System</span>
        </a>

        <!-- Navbar toggler -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar links -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <!-- Dashboard Link -->
                <li class="nav-item <?= $current_page == 'dashboard.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="dashboard.php">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>

                <!-- Manage Employees Link -->
                <li class="nav-item <?= $current_page == 'employees.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="employees.php">
                        <i class="bi bi-people me-1"></i> Manage Employees
                    </a>
                </li>

                <!-- Settings Link -->
                <!-- <li class="nav-item <?= $current_page == 'settings.php' ? 'active' : ''; ?>">
                    <a class="nav-link" href="settings.php">
                        <i class="bi bi-gear me-1"></i> Settings
                    </a>
                </li> -->

                <!-- Logout Button -->
                <li class="nav-item">
                    <a class="btn btn-danger btn-sm px-3 py-2 text-white" href="logout.php" onclick="return confirm('Are you sure you want to logout?');">
                        <i class="bi bi-box-arrow-right me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>

    </div>
</nav>

<!-- Load Bootstrap and Icon libraries in the head -->

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>

<!-- Load Bootstrap JS at the bottom -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>