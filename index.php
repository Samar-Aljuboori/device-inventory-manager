<?php
// Include the database connection file to connect with MySQL
require_once 'db.php';

// Fetch all devices from the database ordered by id descending (newest first)
$stmt = $conn->query("SELECT * FROM devices ORDER BY id DESC");
$devices = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Inventory Manager</title>
</head>
<body>
    <div class="container">
        <!-- Main page title -->
        <h1>Device Inventory Manager</h1>

        <!-- Devices Table -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Device Name</th>
                    <th>Device Number</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Created Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Check if there are devices in the database -->
                <?php if (count($devices) > 0): ?>
                    <!-- Loop through each device and display it in a table row -->
                    <?php foreach ($devices as $device): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($device['device_name']); ?></td>
                            <td><?php echo htmlspecialchars($device['device_number']); ?></td>
                            <td><?php echo htmlspecialchars($device['device_type']); ?></td>
                            <td><?php echo htmlspecialchars($device['status']); ?></td>
                            <td><?php echo htmlspecialchars($device['created_date']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $device['id']; ?>">Edit</a>
                                <a href="delete.php?id=<?php echo $device['id']; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- Show message if table is empty -->
                    <tr>
                        <td colspan="6">No devices found in the database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>