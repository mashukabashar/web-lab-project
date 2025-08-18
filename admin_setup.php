<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Setup - EventEase</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .success { color: #2e7d32; background: #e8f5e8; padding: 10px; border-radius: 5px; }
        .error { color: #d32f2f; background: #ffebee; padding: 10px; border-radius: 5px; }
        .warning { color: #f57c00; background: #fff8e1; padding: 10px; border-radius: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer; margin: 5px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>EventEase Admin Setup</h1>
    <p>This page will help you set up the admin account for the EventEase system.</p>
    
    <?php
    include_once("Database/connect.php");
    
    $action = $_GET['action'] ?? 'check';
    
    if (!$con) {
        echo "<div class='error'>❌ Database connection failed: " . mysqli_connect_error() . "</div>";
        exit;
    }
    
    echo "<div class='success'>✅ Database connection successful</div>";
    
    function checkAdminTable($con) {
        $check = mysqli_query($con, "SHOW TABLES LIKE 'admin'");
        return mysqli_num_rows($check) > 0;
    }
    
    function getAdminUsers($con) {
        $result = mysqli_query($con, "SELECT * FROM admin");
        $users = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $users[] = $row;
            }
        }
        return $users;
    }
    
    if ($action == 'setup') {
        echo "<h2>Setting up Admin Table...</h2>";
        
        // Create admin table if it doesn't exist
        if (!checkAdminTable($con)) {
            $create_sql = "CREATE TABLE `admin` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nm` varchar(50) NOT NULL,
                `pswd` varchar(50) NOT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=latin1";
            
            if (mysqli_query($con, $create_sql)) {
                echo "<div class='success'>✅ Admin table created successfully</div>";
            } else {
                echo "<div class='error'>❌ Error creating admin table: " . mysqli_error($con) . "</div>";
                exit;
            }
        }
        
        // Insert default admin users
        $admins = [
            ['admin', 'admin123'],
            ['Drashti', 'sabhaya']
        ];
        
        foreach ($admins as $admin) {
            $check_existing = mysqli_query($con, "SELECT * FROM admin WHERE nm='" . $admin[0] . "'");
            if (mysqli_num_rows($check_existing) == 0) {
                $insert_sql = "INSERT INTO admin (nm, pswd) VALUES ('" . $admin[0] . "', '" . $admin[1] . "')";
                if (mysqli_query($con, $insert_sql)) {
                    echo "<div class='success'>✅ Added admin user: " . $admin[0] . "</div>";
                } else {
                    echo "<div class='error'>❌ Error adding admin " . $admin[0] . ": " . mysqli_error($con) . "</div>";
                }
            } else {
                echo "<div class='warning'>⚠️ Admin user '" . $admin[0] . "' already exists</div>";
            }
        }
    }
    
    // Always show current status
    echo "<h2>Current Admin Status</h2>";
    
    if (checkAdminTable($con)) {
        echo "<div class='success'>✅ Admin table exists</div>";
        
        $users = getAdminUsers($con);
        if (empty($users)) {
            echo "<div class='warning'>⚠️ No admin users found</div>";
            echo "<p><a href='?action=setup' class='btn'>Setup Admin Users</a></p>";
        } else {
            echo "<div class='success'>✅ Admin users found: " . count($users) . "</div>";
            echo "<h3>Available Admin Users:</h3>";
            echo "<table>";
            echo "<tr><th>Username</th><th>Password</th></tr>";
            foreach ($users as $user) {
                echo "<tr><td>" . htmlspecialchars($user['nm']) . "</td><td>" . htmlspecialchars($user['pswd']) . "</td></tr>";
            }
            echo "</table>";
            
            echo "<div style='background: #e3f2fd; padding: 15px; border-radius: 5px; margin: 20px 0;'>";
            echo "<h3>🎉 Ready to Login!</h3>";
            echo "<p>You can now access the admin panel with any of the credentials above.</p>";
            echo "<p><a href='Admin/login.php' class='btn' target='_blank'>Go to Admin Login</a></p>";
            echo "</div>";
        }
    } else {
        echo "<div class='error'>❌ Admin table does not exist</div>";
        echo "<p><a href='?action=setup' class='btn'>Create Admin Table & Users</a></p>";
    }
    
    mysqli_close($con);
    ?>
    
    <hr>
    <h3>Troubleshooting</h3>
    <ul>
        <li>If you still can't login after setup, make sure your database name in <code>Database/connect.php</code> is correct</li>
        <li>Check that your MySQL/MariaDB server is running</li>
        <li>Verify the database credentials in the connection file</li>
    </ul>
    
    <p><small>If you continue to have issues, check the browser console for errors or contact your system administrator.</small></p>
</body>
</html>
