<?php
$host = 'localhost';
$db = 'vision_auth';
$user = 'root';      // or your DB username
$pass = '';          // or your DB password
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,      // throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Database connection successful!";    // TEMPORARY message
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();    // Show exact error
    exit();
}
