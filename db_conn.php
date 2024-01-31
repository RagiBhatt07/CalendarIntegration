<?php
// Database configuration settings
$host = 'localhost';      // or your database host
$db   = 'calendar';  // replace with your database name
$user = 'root';  // replace with your database username
$pass = '';  // replace with your database password
$charset = 'utf8mb4';

// Set up DSN (Data Source Name)
$mysqli = "mysql:host=$host;dbname=$db;charset=$charset";

// Set up options for PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Create a PDO instance (connect to the database)
    $pdo = new PDO($mysqli, $user, $pass, $options);
} catch (\PDOException $e) {
    // Handle any errors
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
