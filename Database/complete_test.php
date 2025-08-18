<?php
/**
 * Complete Admin Login Test
 * This simulates the exact login process
 */

echo "<h2>Complete Admin Login Process Test</h2>";

// Test 1: Database connection
include_once("connect.php");
if(!$con) {
    echo "<p style='color: red;'>❌ Database connection failed</p>";
    exit;
}
echo "<p style='color: green;'>✅ Database connection successful</p>";

// Test 2: Admin table and users
$admin_check = mysqli_query($con, "SELECT * FROM admin");
echo "<p>Available admin users:</p>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
while($row = mysqli_fetch_assoc($admin_check)) {
    echo "<tr><td>" . $row['id'] . "</td><td>" . $row['nm'] . "</td><td>" . $row['pswd'] . "</td></tr>";
}
echo "</table>";

// Test 3: Session handling
echo "<h3>Session Test</h3>";
if (session_status() == PHP_SESSION_NONE) {
    session_start();
    echo "<p style='color: green;'>✅ Session started successfully</p>";
} else {
    echo "<p style='color: blue;'>ℹ Session already active</p>";
}

// Test 4: Simulate login process
echo "<h3>Login Process Simulation</h3>";

$test_credentials = [
    ['admin', 'admin123'],
    ['Drashti', 'sabhaya']
];

foreach($test_credentials as $creds) {
    $username = mysqli_real_escape_string($con, $creds[0]);
    $password = mysqli_real_escape_string($con, $creds[1]);
    
    echo "<h4>Testing: $username / $password</h4>";
    
    $qry = mysqli_query($con, "SELECT * FROM admin WHERE nm='$username' AND pswd='$password'");
    
    if(!$qry) {
        echo "<p style='color: red;'>❌ Query failed: " . mysqli_error($con) . "</p>";
    } else if(mysqli_num_rows($qry) > 0) {
        echo "<p style='color: green;'>✅ Login successful</p>";
        
        // Simulate session setting
        $_SESSION['admin'] = $username;
        echo "<p>Session set: admin = " . $_SESSION['admin'] . "</p>";
        
        // Check if session is properly set
        if(isset($_SESSION['admin'])) {
            echo "<p style='color: green;'>✅ Session variable set correctly</p>";
        } else {
            echo "<p style='color: red;'>❌ Session variable not set</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Login failed - invalid credentials</p>";
    }
    echo "<hr>";
}

// Test 5: Check session persistence
echo "<h3>Session Persistence Test</h3>";
if(isset($_SESSION['admin'])) {
    echo "<p style='color: green;'>✅ Admin session exists: " . $_SESSION['admin'] . "</p>";
    echo "<p>This user would be able to access admin pages</p>";
} else {
    echo "<p style='color: red;'>❌ No admin session found</p>";
}

// Clear session for clean testing
session_destroy();
echo "<p>Session cleared for next test</p>";

mysqli_close($con);
?>

<h3>Manual Login Test</h3>
<p>Try logging in with these credentials:</p>
<form action="../Admin/login.php" method="post" target="_blank">
    <p>
        Username: <input type="text" name="nm" value="admin" required>
        Password: <input type="password" name="pwd" value="admin123" required>
        <input type="submit" name="submit" value="Test Login">
    </p>
</form>

<form action="../Admin/login.php" method="post" target="_blank">
    <p>
        Username: <input type="text" name="nm" value="Drashti" required>
        Password: <input type="password" name="pwd" value="sabhaya" required>
        <input type="submit" name="submit" value="Test Login">
    </p>
</form>

<p><a href="../Admin/login.php" target="_blank">Open Admin Login Page</a></p>
