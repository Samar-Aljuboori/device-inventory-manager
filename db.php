<?php
// Database configuration parameters
$host = 'localhost';
$db_name = 'device_inventory';
$username = 'root';
$password = '';

try {
    // Create a new PDO (PHP Data Objects) connection instance
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8mb4", $username, $password);
    
    // Set the PDO error mode to exception for proper error handling
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch(PDOException $exception) {
    // Stop execution and display a clean error message if connection fails
    echo "Connection error: " . $exception->getMessage();
    exit();
}
?>