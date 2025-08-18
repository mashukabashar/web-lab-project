<?php
/**
 * Live Login Test - Tests the exact login process
 */

include("connect.php");

echo "<h2>Live Admin Login Test</h2>";
echo "<p>This will test the exact same process as the login form</p>";

// Test both admin accounts
$test_accounts = [
    ['admin', 'admin123'],
    ['Drashti', 'sabhaya']
];

foreach($test_accounts as $account) {
    $username = $account[0];
    $password = $account[1];
    
    echo "<h3>Testing: $username / $password</h3>";
    
    // Simulate the exact login process
    if(!$con) {
        echo "<p style='color: red;'>❌ Database connection failed</p>";
        continue;
    }
    
    // Use the exact same escaping as login.php
    $name = mysqli_real_escape_string($con, trim($username));
    $pwd = mysqli_real_escape_string($con, trim($password));
    
    echo "<p>Escaped values: '$name' / '$pwd'</p>";
    
    // Check if admin table exists (same as login.php)
    $table_check = mysqli_query($con, "SHOW TABLES LIKE 'admin'");
    if(mysqli_num_rows($table_check) == 0) {
        echo "<p style='color: red;'>❌ Admin table not found</p>";
        continue;
    }
    
    // Run the exact same query as login.php
    $qry = mysqli_query($con, "SELECT * FROM admin WHERE nm='$name' AND pswd='$pwd'");
    
    if(!$qry) {
        echo "<p style='color: red;'>❌ Query failed: " . mysqli_error($con) . "</p>";
    } else {
        $rows = mysqli_num_rows($qry);
        echo "<p>Query returned: $rows rows</p>";
        
        if($rows > 0) {
            echo "<p style='color: green;'>✅ LOGIN WOULD SUCCEED</p>";
            $user_data = mysqli_fetch_assoc($qry);
            echo "<p>Found user: ID=" . $user_data['id'] . ", Username='" . $user_data['nm'] . "'</p>";
        } else {
            echo "<p style='color: red;'>❌ LOGIN WOULD FAIL - No matching records</p>";
            
            // Debug: Check if username exists
            $user_check = mysqli_query($con, "SELECT * FROM admin WHERE nm='$name'");
            if(mysqli_num_rows($user_check) > 0) {
                echo "<p>✓ Username '$name' exists</p>";
                $user = mysqli_fetch_assoc($user_check);
                echo "<p>Stored password: '" . $user['pswd'] . "'</p>";
                echo "<p>Provided password: '$pwd'</p>";
                echo "<p>Password match: " . ($user['pswd'] === $pwd ? "YES" : "NO") . "</p>";
            } else {
                echo "<p>❌ Username '$name' not found</p>";
            }
        }
    }
    echo "<hr>";
}

// Show all admin records for verification
echo "<h3>All Admin Records in Database:</h3>";
$all_admins = mysqli_query($con, "SELECT * FROM admin");
echo "<table border='1' style='border-collapse: collapse;'>";
echo "<tr><th>ID</th><th>Username</th><th>Password</th><th>Length Check</th></tr>";
while($admin = mysqli_fetch_assoc($all_admins)) {
    echo "<tr>";
    echo "<td>" . $admin['id'] . "</td>";
    echo "<td>'" . $admin['nm'] . "'</td>";
    echo "<td>'" . $admin['pswd'] . "'</td>";
    echo "<td>UN:" . strlen($admin['nm']) . " PW:" . strlen($admin['pswd']) . "</td>";
    echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>

<h3>Direct Form Test</h3>
<p>Test the login form directly:</p>
<form action="../Admin/login.php" method="post" target="_blank" style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
    <h4>Test 1: admin/admin123</h4>
    <input type="text" name="nm" value="admin" readonly style="padding: 5px; margin: 5px;">
    <input type="password" name="pwd" value="admin123" readonly style="padding: 5px; margin: 5px;">
    <input type="submit" name="submit" value="Test Login" style="padding: 5px 15px; background: #007bff; color: white; border: none; border-radius: 3px;">
</form>

<form action="../Admin/login.php" method="post" target="_blank" style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 10px;">
    <h4>Test 2: Drashti/sabhaya</h4>
    <input type="text" name="nm" value="Drashti" readonly style="padding: 5px; margin: 5px;">
    <input type="password" name="pwd" value="sabhaya" readonly style="padding: 5px; margin: 5px;">
    <input type="submit" name="submit" value="Test Login" style="padding: 5px 15px; background: #007bff; color: white; border: none; border-radius: 3px;">
</form>

<p><a href="../Admin/login.php" target="_blank">Open Admin Login Page</a></p>
