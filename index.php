<?php
// Include the database connection file to connect with MySQL
require_once 'db.php';

// Check if a search term was submitted
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search = '%' . trim($_GET['search']) . '%';
    
    // SQL query with WHERE and LIKE for multiple columns
    $sql = "SELECT * FROM devices WHERE device_name LIKE :search OR device_number LIKE :search OR device_type LIKE :search ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':search' => $search]);
} else {
    // Regular query if no search is performed
    $sql = "SELECT * FROM devices ORDER BY id DESC";
    $stmt = $conn->query($sql);
}

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
        
        <!-- Search Form -->
        <form method="GET" action="index.php">
            <input type="text" name="search" placeholder="Search by name, number, or type..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Search</button>
            <a href="index.php">Reset</a>
        </form>
        <br>

        <!-- Devices Table -->
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Device Name</th>
                    <th>Device Number</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Notes</th>
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
                            <td><?php echo htmlspecialchars($device['notes']); ?></td>
                            <td><?php echo htmlspecialchars($device['created_date']); ?></td>
                            <td>
                                <a href="edit.php?id=<?php echo $device['id']; ?>">Edit</a>
                                <a href="delete.php?id=<?php echo $device['id']; ?>" onclick="return confirm('Are you sure you want to delete this device?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <? else: ?>
                    <!-- Show message if table is empty -->
                    <tr>
                        <td colspan="7">No devices found in the database.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>