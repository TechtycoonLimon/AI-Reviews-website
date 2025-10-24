<?php
// db.php - PDO connection
$host = '127.0.0.1';
$db   = 'ai_reviews';
$user = 'root';
$pass = ''; // default XAMPP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // in production, log errors instead of echoing
    exit('Database connection failed: ' . $e->getMessage());
}
