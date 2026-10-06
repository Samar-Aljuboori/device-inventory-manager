<?php
// Include database connection
require_once 'db.php';

// Check if ID is provided in the URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Delete the device securely using prepared statements and WHERE
$sql = "DELETE FROM devices WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);

// Redirect back to index.php after successful deletion
header('Location: index.php');
exit();
?>