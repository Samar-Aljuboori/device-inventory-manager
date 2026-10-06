<?php
// Include the database connection file
require_once 'db.php';

// Check if ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Fetch the existing device data securely using prepared statements
$sql = "SELECT * FROM devices WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$device = $stmt->fetch(PDO::FETCH_ASSOC);

// If the device does not exist, redirect back to index.php
if (!$device) {
    header('Location: index.php');
    exit();
}
?>