<?php
// Include the database connection file to connect with MySQL
require_once 'db.php';

// Check if the device ID is provided in the URL query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

// Fetch the existing device record from the database
$sql = "SELECT * FROM devices WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$device = $stmt->fetch(PDO::FETCH_ASSOC);

// Redirect to index page if the device record does not exist
if (!$device) {
    header('Location: index.php');
    exit();
}

// Check if the form was submitted using the POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and trim input data from the form
    $device_name = trim($_POST['device_name']);
    $device_number = trim($_POST['device_number']);
    $device_type = trim($_POST['device_type']);
    $status = trim($_POST['status']);
    $notes = trim($_POST['notes']);

    // Prepare the SQL UPDATE statement using PDO to prevent SQL injection
    $update_sql = "UPDATE devices SET device_name = :device_name, device_number = :device_number, device_type = :device_type, status = :status, notes = :notes WHERE id = :id";
    $update_stmt = $conn->prepare($update_sql);
    
    // Execute the statement by binding the form values along with the ID
    $update_stmt->execute([
        ':device_name' => $device_name,
        ':device_number' => $device_number,
        ':device_type' => $device_type,
        ':status' => $status,
        ':notes' => $notes,
        ':id' => $id
    ]);

    // Redirect back to the main dashboard after successful update
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Device - Inventory Manager</title>
    <!-- Linking the separate CSS stylesheet -->
    <link rel="stylesheet" href="style.css">
    <!-- FontAwesome Icons for modern UI icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

    <!-- Main Dashboard Application Container -->
    <div class="dashboard-container">
        
        <!-- Sidebar Section -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <h2><i class="fa-solid fa-server"></i> DIMS</h2>
                <p>Data Inventory Manager System</p>
            </div>
            
            <ul class="sidebar-menu">
                <li><a href="index.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li class="active"><a href="index.php"><i class="fa-solid fa-laptop"></i> Devices</a></li>
                <li><a href="#"><i class="fa-solid fa-folder-open"></i> Categories</a></li>
                <li><a href="#"><i class="fa-solid fa-chart-line"></i> Reports</a></li>
                <li><a href="#"><i class="fa-solid fa-users"></i> Users</a></li>
                <li><a href="#"><i class="fa-solid fa-gear"></i> Settings</a></li>
            </ul>

            <div class="sidebar-footer">
                <div class="user-profile">
                    <span class="user-avatar">SA</span>
                    <div class="user-details">
                        <h4>Samar Al-juboori</h4>
                        <p>Administrator</p>
                    </div>
                </div>
                <a href="#" class="logout-link"><i class="fa-solid fa-arrow-right-from-bracket"></i> Log out</a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <main class="main-content">
            
            <!-- Top Header Section -->
            <header class="top-header">
                <div class="search-box">
                    <span class="header-title-text"><i class="fa-solid fa-pen-to-square"></i> Edit Hardware Asset</span>
                </div>
                <div class="header-right">
                    <span class="date-time"><i class="fa-regular fa-clock"></i> <?php echo date('D, d M Y h:i A'); ?></span>
                </div>
            </header>

            <!-- Form Container Card -->
            <section class="dashboard-grid form-layout-grid">
                <div class="content-card">
                    <div class="card-header-flex form-card-header">
                        <h3>Update Device Information</h3>
                        <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
                    </div>

                    <!-- Edit Device Form -->
                    <form action="edit.php?id=<?php echo $device['id']; ?>" method="POST" class="edit-form-group">
                        
                        <div class="edit-input-box">
                            <label for="device_name">Device Name:</label>
                            <input type="text" id="device_name" name="device_name" value="<?php echo htmlspecialchars($device['device_name']); ?>" required placeholder="e.g. MacBook Pro M3">
                        </div>
                        
                        <div class="edit-input-box">
                            <label for="device_number">Device Number:</label>
                            <input type="text" id="device_number" name="device_number" value="<?php echo htmlspecialchars($device['device_number']); ?>" required placeholder="e.g. #984123">
                        </div>
                        
                        <div class="edit-input-box">
                            <label for="device_type">Device Type:</label>
                            <input type="text" id="device_type" name="device_type" value="<?php echo htmlspecialchars($device['device_type']); ?>" placeholder="e.g. Laptop, Phone, Printer">
                        </div>
                        
                        <div class="edit-input-box">
                            <label for="status">Status:</label>
                            <select name="status" id="status">
                                <option value="Available" <?php if($device['status'] == 'Available') echo 'selected'; ?>>Available</option>
                                <option value="In Use" <?php if($device['status'] == 'In Use') echo 'selected'; ?>>In Use</option>
                                <option value="Repair" <?php if($device['status'] == 'Repair') echo 'selected'; ?>>Repair</option>
                                <option value="Reserved" <?php if($device['status'] == 'Reserved') echo 'selected'; ?>>Reserved</option>
                                <option value="Retired" <?php if($device['status'] == 'Retired') echo 'selected'; ?>>Retired</option>
                            </select>
                        </div>
                        
                        <div class="edit-input-box">
                            <label for="notes">Notes:</label>
                            <textarea id="notes" name="notes" rows="4" placeholder="Add any additional comments or assigned user details..."><?php echo htmlspecialchars($device['notes']); ?></textarea>
                        </div>
                        
                        <div class="edit-btn-group">
                            <button type="submit" class="edit-submit-btn"><i class="fa-solid fa-floppy-disk"></i> Update Device</button>
                            <a href="index.php" class="edit-cancel-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </section>

        </main>
    </div>

</body>
</html>