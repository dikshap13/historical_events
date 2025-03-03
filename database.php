<?php
// Database configuration
$host = "127.0.0.1"; // Change if using a different host
$dbname = "historical_events"; // Replace with your database name
$username = "root"; // Replace with your DB username
$password = ""; // Replace with your DB password

try {
    // Create a new PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Database connection successful!";
} catch (PDOException $e) {
    // If connection fails, show error
    die("Database connection failed: " . $e->getMessage());
}
