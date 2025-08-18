<?php
/**
 * Direct Admin Login Test
 * This script tests the exact login logic used in the admin login
 */

include_once("connect.php");

echo "<h2>Direct Admin Login Test</h2>";

// Test with the known credentials
$test_username = "Drashti";
$test_password = "sabhaya";

echo "<p>Testing login with:</p>";
echo "<ul>";
echo "<li><strong>Username:</strong> $test_username</li>";
echo "<li><strong>Password:</strong> $test_password</li>";
echo "</ul>";

// Check database connection
if(!$con) {
    echo "<p style='color: red;'>❌ Database connection failed: " . mysqli_connect_error() . "</p>";
    exit;
}
echo "<p style='color: green;'>✅ Database connection successful</p>";

// Check if admin table exists
$table_check = mysqli_query($con, "SHOW TABLES LIKE 'admin'");
if(mysqli_num_rows($table_check) == 0) {
    echo "<p style='color: red;'>❌ Admin table not found</p>";
    exit;
}
echo "<p style='color: green;'>✅ Admin table exists</p>";

// Show all admin records first
echo "<h3>All Admin Records:</h3>";
$all_admins = mysqli_query($con, "SELECT * FROM admin");
if($all_admins && mysqli_num_rows($all_admins) > 0) {
    echo "<table border='1' style='border-collapse: collapse;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Length</th></tr>";
    while($admin = mysqli_fetch_assoc($all_admins)) {
        echo "<tr>";
        echo "<td>" . $admin['id'] . "</td>";
        echo "<td>'" . $admin['nm'] . "'</td>";
        echo "<td>'" . $admin['pswd'] . "'</td>";
        echo "<td>nm:" . strlen($admin['nm']) . ", pswd:" . strlen($admin['pswd']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p style='color: red;'>❌ No admin records found</p>";
}

// Test the exact query used in login
$safe_username = mysqli_real_escape_string($con, $test_username);
$safe_password = mysqli_real_escape_string($con, $test_password);

$login_query = "SELECT * FROM admin WHERE nm='$safe_username' AND pswd='$safe_password'";
echo "<h3>Login Query Test:</h3>";
echo "<p><strong>Query:</strong> $login_query</p>";

$result = mysqli_query($con, $login_query);

if(!$result) {
    echo "<p style='color: red;'>❌ Query failed: " . mysqli_error($con) . "</p>";
} else {
    $rows = mysqli_num_rows($result);
    echo "<p><strong>Rows returned:</strong> $rows</p>";
    
    if($rows > 0) {
        echo "<p style='color: green;'>✅ Login would succeed!</p>";
        $user_data = mysqli_fetch_assoc($result);
        echo "<p>Matched user: " . $user_data['nm'] . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Login would fail - no matching records</p>";
        
        // Test individual conditions
        echo "<h4>Testing individual conditions:</h4>";
        
        $username_test = mysqli_query($con, "SELECT * FROM admin WHERE nm='$safe_username'");
        $username_count = mysqli_num_rows($username_test);
        echo "<p>Username match: $username_count records</p>";
        
        $password_test = mysqli_query($con, "SELECT * FROM admin WHERE pswd='$safe_password'");
        $password_count = mysqli_num_rows($password_test);
        echo "<p>Password match: $password_count records</p>";
        
        // Show exact field values
        if($username_count > 0) {
            $user = mysqli_fetch_assoc($username_test);
            echo "<p>Found username: '" . $user['nm'] . "' (actual password: '" . $user['pswd'] . "')</p>";
        }
    }
}

// Test with different variations
echo "<h3>Testing Variations:</h3>";

$variations = [
    ['admin', 'admin123'],
    ['Drashti', 'sabhaya'],
    ['drashti', 'sabhaya'], // lowercase
    ['Drashti', 'Sabhaya'], // different case
];

foreach($variations as $creds) {
    $u = mysqli_real_escape_string($con, $creds[0]);
    $p = mysqli_real_escape_string($con, $creds[1]);
    $test_result = mysqli_query($con, "SELECT * FROM admin WHERE nm='$u' AND pswd='$p'");
    $count = mysqli_num_rows($test_result);
    $status = $count > 0 ? "✅ SUCCESS" : "❌ FAIL";
    echo "<p>$status - $u / $p ($count matches)</p>";
}

mysqli_close($con);
?>

<p><a href="../Admin/login.php">Go to Admin Login</a></p>
