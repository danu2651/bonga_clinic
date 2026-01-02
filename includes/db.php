<?php
// Database Configuration
$host = 'localhost';
$db   = 'bonga_clinic'; // Change this to 'bonga_clinic_db' if that is your DB name
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// Data Source Name
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

// Connection Options for Security and Performance
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Shows errors clearly
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Fetches data as an array
    PDO::ATTR_EMULATE_PREPARES   => false,                  // Uses real prepared statements
];

try {
    // Creating the PDO Connection
    $conn = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // If connection fails, it stops and shows the error
    die("Database Connection Failed: " . $e->getMessage());
}
?>