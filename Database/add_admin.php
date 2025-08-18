<?php
include("connect.php");

// Add standard admin user
$result = mysqli_query($con, "INSERT INTO admin (nm, pswd) VALUES ('admin', 'admin123')");
if($result) {
    echo "✓ Added admin/admin123 user\n";
} else {
    echo "User might already exist or error: " . mysqli_error($con) . "\n";
}

// Show all admin users
$all = mysqli_query($con, "SELECT * FROM admin");
echo "\nAll admin users:\n";
while($row = mysqli_fetch_assoc($all)) {
    echo "- " . $row['nm'] . " / " . $row['pswd'] . "\n";
}
?>
