<?php
// Include the database connection file
require_once 'db.php';

// Check if the form was submitted using POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and trim input data from the form
    $device_name   = trim($_POST['device_name']);
    $device_number = trim($_POST['device_number']);
    $device_type   = trim($_POST['device_type']);
    $status        = trim($_POST['status']);
    $notes         = trim($_POST['notes']);

    // Basic validation to ensure required fields are not empty
    if (!empty($device_name) && !empty($device_number)) {
        
        // Prepare the SQL INSERT statement using PDO to prevent SQL injection
        $sql = "INSERT INTO devices (device_name, device_number, device_type, status, notes, created_date) 
                VALUES (:device_name, :device_number, :device_type, :status, :notes, NOW())";
        
        $stmt = $conn->prepare($sql);
        
        // Execute the statement by binding the form values
        $stmt->execute([
            ':device_name'   => $device_name,
            ':device_number' => $device_number,
            ':device_type'   => $device_type,
            ':status'        => $status,
            ':notes'         => $notes
        ]);

        // Redirect back to the main dashboard (index.php) after successful insertion
        header('Location: index.php');
        exit();
    } else {
        $error_message = "Please fill in all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Device</title>
</head>
<body>

    <h2>Add New Device</h2>

    <!-- Display error message if validation fails -->
    <?php if (!empty($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Add Device Form -->
    <form action="add.php" method="POST">
        <div>
            <label for="device_name">Device Name:</label><br>
            <input type="text" id="device_name" name="device_name" required>
        </div>
        <br>
        <div>
            <label for="device_number">Device Number:</label><br>
            <input type="text" id="device_number" name="device_number" required>
        </div>
        <br>
        <div>
            <label for="device_type">Device Type:</label><br>
            <input type="text" id="device_type" name="device_type">
        </div>
        <br>
        <div>
            <label for="status">Status:</label><br>
            <input type="text" id="status" name="status">
        </div>
        <br>
        <div>
            <label for="notes">Notes:</label><br>
            <textarea id="notes" name="notes"></textarea>
        </div>
        <br>
        <div>
            <button type="submit">Add Device</button>
            <a href="index.php">Cancel</a>
        </div>
    </form>

</body>
</html>