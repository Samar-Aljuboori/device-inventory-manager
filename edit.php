<?php
require_once 'db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$id = $_GET['id'];

$sql = "SELECT * FROM devices WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([':id' => $id]);
$device = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$device) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $device_name = $_POST['device_name'];
    $status = $_POST['status'];

    $update_sql = "UPDATE devices SET device_name = :device_name, status = :status WHERE id = :id";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->execute([
        ':device_name' => $device_name,
        ':status' => $status,
        ':id' => $id
    ]);

    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Device</title>
</head>
<body>
    <h2>Edit Device</h2>
<form action="edit.php?id=<?php echo $device['id']; ?>" method="POST">
    <div>
        <label for="device_name">Device Name:</label><br>
        <input type="text" id="device_name" name="device_name" value="<?php echo htmlspecialchars($device['device_name']); ?>" required>
    </div>
    <br>
    <div>
        <label for="device_number">Device Number:</label><br>
        <input type="text" id="device_number" name="device_number" value="<?php echo htmlspecialchars($device['device_number']); ?>" required>
    </div>
    <br>
    <div>
        <label for="device_type">Device Type:</label><br>
        <input type="text" id="device_type" name="device_type" value="<?php echo htmlspecialchars($device['device_type']); ?>">
    </div>
    <br>
    <div>
        <label for="status">Status:</label><br>
        <select name="status">
            <option value="Available" <?php if($device['status'] == 'Available') echo 'selected'; ?>>Available</option>
            <option value="In Use" <?php if($device['status'] == 'In Use') echo 'selected'; ?>>In Use</option>
            <option value="Repair" <?php if($device['status'] == 'Repair') echo 'selected'; ?>>Repair</option>
            <option value="Reserved" <?php if($device['status'] == 'Reserved') echo 'selected'; ?>>Reserved</option>
            <option value="Retired" <?php if($device['status'] == 'Retired') echo 'selected'; ?>>Retired</option>
        </select>
    </div>
    <br>
    <div>
        <label for="notes">Notes:</label><br>
        <textarea id="notes" name="notes"><?php echo htmlspecialchars($device['notes']); ?></textarea>
    </div>
    <br>
    <div>
        <button type="submit">Update Device</button>
        <a href="index.php">Cancel</a>
    </div>
</form>
</body>
</html>