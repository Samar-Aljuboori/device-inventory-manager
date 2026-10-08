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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Device - Inventory Manager</title>
    <!-- Link to the external CSS stylesheet (Separated) -->
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
                    <span class="header-title-text"><i class="fa-solid fa-plus-circle"></i> Add New Hardware Asset</span>
                </div>
                <div class="header-right">
                    <span class="date-time"><i class="fa-regular fa-clock"></i> <?php echo date('D, d M Y h:i A'); ?></span>
                </div>
            </header>

            <!-- Form Container Card -->
            <section class="dashboard-grid form-layout-grid">
                <div class="content-card">
                    <div class="card-header-flex form-card-header">
                        <h3>Device Information Form</h3>
                        <a href="index.php" class="back-link"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
                    </div>

                    <!-- Display error message if validation fails -->
                    <?php if (!empty($error_message)): ?>
                        <div class="error-alert-box">
                            <i class="fa-solid fa-triangle-exclamation"></i> <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Add Device Form -->
                    <form action="add.php" method="POST" class="add-form-group">
                        
                        <div class="add-input-box">
                            <label for="device_name">Device Name: *</label>
                            <input type="text" id="device_name" name="device_name" required placeholder="e.g. MacBook Pro M3">
                        </div>
                        
                        <div class="add-input-box">
                            <label for="device_number">Device Number / Serial: *</label>
                            <input type="text" id="device_number" name="device_number" required placeholder="e.g. #984123">
                        </div>
                        
                        <div class="add-input-box">
                            <label for="device_type">Device Type:</label>
                            <input type="text" id="device_type" name="device_type" placeholder="e.g. Laptop, Phone, Printer">
                        </div>
                        
                        <div class="add-input-box">
                            <label for="status">Status:</label>
                            <select id="status" name="status">
                                <option value="Available">Available</option>
                                <option value="In Use">In Use</option>
                                <option value="Repair">Repair</option>
                                <option value="Reserved">Reserved</option>
                                <option value="Retired">Retired</option>
                            </select>
                        </div>
                        
                        <div class="add-input-box">
                            <label for="notes">Notes:</label>
                            <textarea id="notes" name="notes" rows="4" placeholder="Add any additional comments or assigned user details..."></textarea>
                        </div>
                        
                        <div class="add-btn-group">
                            <button type="submit" class="submit-btn"><i class="fa-solid fa-plus"></i> Add Device</button>
                            <a href="index.php" class="add-cancel-link">Cancel</a>
                        </div>
                    </form>
                </div>
            </section>

        </main>
    </div>

</body>
</html>