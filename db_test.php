<?php
/**
 * Database Connection Test
 * Quick test to verify database connectivity
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Database Test - EventEase</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .success { color: green; background: #f0fff0; padding: 10px; border-radius: 5px; }
        .error { color: red; background: #fff0f0; padding: 10px; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>EventEase Database Connection Test</h1>
    
    <?php
    include_once("Database/connect.php");
    
    if (!$con) {
        echo "<div class='error'>❌ Database connection failed: " . mysqli_connect_error() . "</div>";
        echo "<p>Check your database configuration in Database/connect.php</p>";
        exit;
    }
    
    echo "<div class='success'>✅ Database connection successful!</div>";
    
    // Show database info
    $db_name = mysqli_get_server_info($con);
    echo "<p><strong>Server:</strong> $db_name</p>";
    
    // List tables
    $tables_result = mysqli_query($con, "SHOW TABLES");
    if ($tables_result) {
        echo "<h3>Available Tables:</h3>";
        echo "<table>";
        echo "<tr><th>Table Name</th><th>Records</th></tr>";
        
        while ($table = mysqli_fetch_array($tables_result)) {
            $table_name = $table[0];
            $count_result = mysqli_query($con, "SELECT COUNT(*) as count FROM `$table_name`");
            $count = 0;
            if ($count_result) {
                $count_row = mysqli_fetch_assoc($count_result);
                $count = $count_row['count'];
            }
            echo "<tr><td>$table_name</td><td>$count</td></tr>";
        }
        echo "</table>";
    }
    
    // Check admin table specifically
    $admin_check = mysqli_query($con, "SHOW TABLES LIKE 'admin'");
    if (mysqli_num_rows($admin_check) > 0) {
        echo "<h3>Admin Table Status:</h3>";
        $admin_result = mysqli_query($con, "SELECT * FROM admin");
        if (mysqli_num_rows($admin_result) > 0) {
            echo "<div class='success'>✅ Admin table exists with " . mysqli_num_rows($admin_result) . " users</div>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
            while ($admin = mysqli_fetch_assoc($admin_result)) {
                echo "<tr>";
                echo "<td>" . $admin['id'] . "</td>";
                echo "<td>" . $admin['nm'] . "</td>";
                echo "<td>" . $admin['pswd'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='error'>❌ Admin table exists but is empty</div>";
        }
    } else {
        echo "<div class='error'>❌ Admin table does not exist</div>";
    }
    
    mysqli_close($con);
    ?>
    
    <hr>
    <p><a href="admin_setup.php">Go to Admin Setup</a> | <a href="Admin/login.php">Admin Login</a></p>
</body>
</html>
