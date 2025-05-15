<?php
// db.php

$host = 'localhost';
$dbname = 'job_portal';
$user = 'root'; // Change if necessary
$password = ''; // Change if necessary

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>