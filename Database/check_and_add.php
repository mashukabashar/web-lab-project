<?php
include("connect.php");

echo "Admin table structure:\n";
$result = mysqli_query($con, "DESCRIBE admin");
while($row = mysqli_fetch_assoc($result)) {
    echo "Field: " . $row['Field'] . " | Type: " . $row['Type'] . " | Key: " . $row['Key'] . " | Extra: " . $row['Extra'] . "\n";
}

echo "\nTrying to add admin user with explicit ID:\n";
$max_id = mysqli_query($con, "SELECT MAX(id) as max_id FROM admin");
$max_row = mysqli_fetch_assoc($max_id);
$next_id = ($max_row['max_id'] ?? 0) + 1;

$result = mysqli_query($con, "INSERT INTO admin (id, nm, pswd) VALUES ($next_id, 'admin', 'admin123')");
if($result) {
    echo "✓ Added admin/admin123 user with ID $next_id\n";
} else {
    echo "Error: " . mysqli_error($con) . "\n";
}

// Show all admin users
echo "\nAll admin users:\n";
$all = mysqli_query($con, "SELECT * FROM admin");
while($row = mysqli_fetch_assoc($all)) {
    echo "ID: " . $row['id'] . " | User: " . $row['nm'] . " | Pass: " . $row['pswd'] . "\n";
}
?>
