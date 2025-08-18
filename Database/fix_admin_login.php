<?php
/**
 * Fix Admin Login Issue
 * This script ensures the admin table exists and contains the proper admin credentials
 */

include_once("connect.php");

echo "<h2>Admin Login Fix Script</h2>";

// Check if database connection works
if (!$con) {
    die("<p style='color: red;'>ERROR: Database connection failed: " . mysqli_connect_error() . "</p>");
}
echo "<p style='color: green;'>✓ Database connection successful</p>";

// Check if admin table exists
$check_table = mysqli_query($con, "SHOW TABLES LIKE 'admin'");
if (mysqli_num_rows($check_table) == 0) {
    // Create admin table
    $create_table = "CREATE TABLE `admin` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nm` varchar(50) NOT NULL,
        `pswd` varchar(50) NOT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
    
    if (mysqli_query($con, $create_table)) {
        echo "<p style='color: green;'>✓ Admin table created successfully</p>";
    } else {
        die("<p style='color: red;'>ERROR: Could not create admin table: " . mysqli_error($con) . "</p>");
    }
} else {
    echo "<p style='color: green;'>✓ Admin table already exists</p>";
}

// Check current admin records
$check_admin = mysqli_query($con, "SELECT * FROM admin");
echo "<p><strong>Current admin records:</strong></p>";
if (mysqli_num_rows($check_admin) > 0) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
    while ($row = mysqli_fetch_assoc($check_admin)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nm'] . "</td>";
        echo "<td>" . $row['pswd'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: orange;'>⚠ No admin records found</p>";
}

// Insert default admin users if none exist
$admin_count = mysqli_num_rows($check_admin);
if ($admin_count == 0) {
    $insert_admins = [
        ['admin', 'admin123'],
        ['Drashti', 'sabhaya']
    ];
    
    foreach ($insert_admins as $admin) {
        $username = $admin[0];
        $password = $admin[1];
        
        $insert_query = "INSERT INTO admin (nm, pswd) VALUES ('$username', '$password')";
        if (mysqli_query($con, $insert_query)) {
            echo "<p style='color: green;'>✓ Added admin user: $username</p>";
        } else {
            echo "<p style='color: red;'>ERROR: Could not add admin user $username: " . mysqli_error($con) . "</p>";
        }
    }
} else {
    echo "<p style='color: blue;'>ℹ Admin users already exist, skipping insertion</p>";
}

// Verify final admin records
echo "<h3>Final Admin Records:</h3>";
$final_check = mysqli_query($con, "SELECT * FROM admin");
if (mysqli_num_rows($final_check) > 0) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
    while ($row = mysqli_fetch_assoc($final_check)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['nm'] . "</td>";
        echo "<td>" . $row['pswd'] . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<div style='background: #e8f5e8; padding: 15px; border: 1px solid #4caf50; border-radius: 5px; margin: 20px 0;'>";
    echo "<h3 style='color: #2e7d32; margin-top: 0;'>🎉 Admin Login Fix Complete!</h3>";
    echo "<p><strong>You can now login with these credentials:</strong></p>";
    echo "<ul>";
    $final_check = mysqli_query($con, "SELECT * FROM admin");
    while ($row = mysqli_fetch_assoc($final_check)) {
        echo "<li><strong>Username:</strong> " . $row['nm'] . " | <strong>Password:</strong> " . $row['pswd'] . "</li>";
    }
    echo "</ul>";
    echo "<p>Go to: <a href='../Admin/login.php' target='_blank'>Admin Login Page</a></p>";
    echo "</div>";
    
} else {
    echo "<p style='color: red;'>ERROR: No admin records found after fix attempt</p>";
}

mysqli_close($con);
?>
