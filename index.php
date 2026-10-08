<?php
// Include database connection file
require_once 'db.php';

// Get the search query if it exists
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Prepare the SQL query based on whether there is a search term
if (!empty($search)) {
    // Search across device name, number, type, status, and notes
    $sql = "SELECT * FROM devices WHERE 
            device_name LIKE :search OR 
            device_number LIKE :search OR 
            device_type LIKE :search OR 
            status LIKE :search OR 
            notes LIKE :search 
            ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':search' => "%$search%"]);
} else {
    // Fetch all devices if no search query is provided
    $sql = "SELECT * FROM devices ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$devices = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Fetch counts for statistics cards
$stmtTotal = $conn->query("SELECT COUNT(*) FROM devices");
$totalDevices = $stmtTotal->fetchColumn();

$stmtAvailable = $conn->query("SELECT COUNT(*) FROM devices WHERE status = 'Available'");
$availableDevices = $stmtAvailable->fetchColumn();

$stmtInUse = $conn->query("SELECT COUNT(*) FROM devices WHERE status = 'In Use'");
$inUseDevices = $stmtInUse->fetchColumn();

$stmtRepair = $conn->query("SELECT COUNT(*) FROM devices WHERE status = 'Repair'");
$repairDevices = $stmtRepair->fetchColumn();

$stmtReserved = $conn->query("SELECT COUNT(*) FROM devices WHERE status = 'Reserved'");
$reservedDevices = $stmtReserved->fetchColumn();

$stmtRetired = $conn->query("SELECT COUNT(*) FROM devices WHERE status = 'Retired'");
$retiredDevices = $stmtRetired->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Inventory Manager - Dashboard</title>
    <!-- Link to the external CSS stylesheet -->
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
                <li class="active"><a href="index.php"><i class="fa-solid fa-chart-pie"></i> Dashboard</a></li>
                <li><a href="#"><i class="fa-solid fa-laptop"></i> Devices</a></li>
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
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <input type="text" id="searchInput" placeholder="Search devices, users, or anything..."
                        autocomplete="off">
                    <!-- Clear button (X) inside the search box -->
                    <i class="fa-solid fa-xmark" id="clearSearchBtn"
                        style="display: none; cursor: pointer; color: #94a3b8;"></i>
                </div>
                <div class="header-right">
                    <span class="date-time"><i class="fa-regular fa-clock"></i> <?php echo date('D, d M Y h:i A'); ?>
                    </span>
                </div>
            </header>

            <!-- Welcome Banner Section -->
            <section class="welcome-section">
                <div class="welcome-text">
                    <h1>Welcome back, Samar </h1>
                    <p>Here's what's happening with your devices today.</p>
                </div>
            </section>

            <!-- Statistics Cards Grid Section -->
            <section class="stats-grid">
                <!-- Total Devices Card -->
                <div class="stat-card">
                    <div class="card-icon blue"><i class="fa-solid fa-desktop"></i></div>
                    <div class="card-content">
                        <h3>Total Devices</h3>
                        <h2><?php echo $totalDevices; ?></h2>
                    </div>
                </div>

                <!-- Available Devices Card -->
                <div class="stat-card">
                    <div class="card-icon green"><i class="fa-solid fa-circle-check"></i></div>
                    <div class="card-content">
                        <h3>Available</h3>
                        <h2><?php echo $availableDevices; ?></h2>
                    </div>
                </div>

                <!-- In Use Devices Card -->
                <div class="stat-card">
                    <div class="card-icon orange"><i class="fa-solid fa-user-clock"></i></div>
                    <div class="card-content">
                        <h3>In Use</h3>
                        <h2><?php echo $inUseDevices; ?></h2>
                    </div>
                </div>

                <!-- Repair Devices Card -->
                <div class="stat-card">
                    <div class="card-icon red"><i class="fa-solid fa-screwdriver-wrench"></i></div>
                    <div class="card-content">
                        <h3>In Repair</h3>
                        <h2><?php echo $repairDevices; ?></h2>
                    </div>
                </div>

                <!-- Reserved Devices Card -->
                <div class="stat-card">
                    <div class="card-icon purple"><i class="fa-solid fa-bookmark"></i></div>
                    <div class="card-content">
                        <h3>Reserved</h3>
                        <h2><?php echo $reservedDevices; ?></h2>
                    </div>
                </div>

                <!-- Retired Devices Card -->
                <div class="stat-card">
                    <div class="card-icon gray"><i class="fa-solid fa-trash-can"></i></div>
                    <div class="card-content">
                        <h3>Retired</h3>
                        <h2><?php echo $retiredDevices; ?></h2>
                    </div>
                </div>
            </section>

            <!-- Main Dashboard Content Grid (Table & Quick Actions) -->
            <section class="dashboard-grid">

                <!-- Devices Table Container -->
                <div class="content-card table-card">
                    <div class="card-header-flex">
                        <h3>Latest Devices</h3>
                        <?php if (isset($_GET['search'])): ?>
                            <a href="index.php" class="reset-filter-btn">Reset Search</a>
                        <?php endif; ?>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Device Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($devices) > 0): ?>
                                    <?php foreach ($devices as $device): ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo htmlspecialchars($device['device_name']); ?></strong><br>
                                                <small
                                                    style="color: #888;">#<?php echo htmlspecialchars($device['device_number']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($device['device_type']); ?></td>
                                            <td>
                                                <span
                                                    class="status-badge <?php echo strtolower(str_replace(' ', '-', $device['status'])); ?>">
                                                    <?php echo htmlspecialchars($device['status']); ?>
                                                </span>
                                            </td>
                                            <td><?php echo htmlspecialchars($device['notes']); ?></td>
                                            <td><?php echo date('d-m-Y H:i', strtotime($device['created_date'])); ?></td>
                                            <td>
                                                <a href="edit.php?id=<?php echo $device['id']; ?>" class="action-btn edit"
                                                    title="Edit"><i class="fa-solid fa-pen"></i></a>
                                                <a href="delete.php?id=<?php echo $device['id']; ?>" class="action-btn delete"
                                                    title="Delete"
                                                    onclick="return confirm('Are you sure you want to delete this device?');"><i
                                                        class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 30px; color: #777;">No devices
                                            found in the database.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Actions Card Container -->
                <div class="content-card actions-card">
                    <h3>Quick Actions</h3>
                    <div class="quick-actions-list">
                        <a href="add.php" class="quick-action-item">
                            <span class="action-icon-box green"><i class="fa-solid fa-plus"></i></span>
                            <span>Add New Device</span>
                            <i class="fa-solid fa-chevron-right arrow-icon"></i>
                        </a>
                        <a href="categories.php" class="quick-action-item">
                            <span class="action-icon-box blue"><i class="fa-solid fa-folder-plus"></i></span>
                            <span>Manage Categories</span>
                            <i class="fa-solid fa-chevron-right arrow-icon"></i>
                        </a>
                        <a href="#" class="quick-action-item">
                            <span class="action-icon-box purple"><i class="fa-solid fa-file-lines"></i></span>
                            <span>View Reports</span>
                            <i class="fa-solid fa-chevron-right arrow-icon"></i>
                        </a>
                        <a href="#" class="quick-action-item">
                            <span class="action-icon-box orange"><i class="fa-solid fa-users"></i></span>
                            <span>Manage Users</span>
                            <i class="fa-solid fa-chevron-right arrow-icon"></i>
                        </a>
                    </div>
                </div>

            </section>

        </main>
    </div>
    <script>
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearchBtn');
        const rows = document.querySelectorAll('table tbody tr');

        // Live search functionality
        searchInput.addEventListener('keyup', function () {
            let filter = this.value.toLowerCase();

            // Show or hide the clear (X) button based on input length
            if (filter.length > 0) {
                clearBtn.style.display = "block";
            } else {
                clearBtn.style.display = "none";
            }

            // Filter table rows instantly
            rows.forEach(row => {
                let text = row.textContent.toLowerCase();
                if (text.includes(filter)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });

        // Clear search input and restore table rows when clicking (X)
        clearBtn.addEventListener('click', function () {
            searchInput.value = "";
            clearBtn.style.display = "none";
            rows.forEach(row => row.style.display = "");
            searchInput.focus();
        });
    </script>
</body>

</html>