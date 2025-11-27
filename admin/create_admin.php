<?php
// Include your database connection file
include 'db.php';

// Set admin username and password
$username = 'jeremy';
$password = password_hash('jeremy123', PASSWORD_BCRYPT);  // Securely hash the password

// Check if the admin already exists in the database
$stmt = $pdo->prepare("SELECT * FROM admins WHERE username = :username");
$stmt->execute(['username' => $username]);
$admin = $stmt->fetch();

if (!$admin) {
    // Insert the admin account if it doesn't already exist
    $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (:username, :password)");
    $stmt->execute(['username' => $username, 'password' => $password]);

    echo "Admin account created successfully!";
} else {
    echo "Admin account already exists.";
}
?>
