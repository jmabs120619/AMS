<?php
$host = 'localhost';
$db = 'rfid_attendance';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    

    if (!empty($_GET['resetrfid']))
    { 
        session_start(); session_destroy();}

} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
