<?php
/**
 * Simple Login Test - No redirects, just pure testing
 */

// Don't include header.php to avoid redirect loops
include("../Database/connect.php");

echo "<h2>Simple Admin Login Test</h2>";
echo "<p>This test will not redirect - it will show you exactly what happens</p>";

if(isset($_POST['test_login'])) {
    echo "<h3>Processing Login...</h3>";
    
    // Start session
    session_start();
    
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    echo "<p>Attempting login with: <strong>$username</strong> / <strong>$password</strong></p>";
    
    // Check credentials
    $safe_user = mysqli_real_escape_string($con, $username);
    $safe_pass = mysqli_real_escape_string($con, $password);
    
    $query = "SELECT * FROM admin WHERE nm='$safe_user' AND pswd='$safe_pass'";
    echo "<p>Query: $query</p>";
    
    $result = mysqli_query($con, $query);
    
    if($result && mysqli_num_rows($result) > 0) {
        echo "<p style='color: green;'><strong>✅ LOGIN SUCCESS!</strong></p>";
        
        // Set session
        $_SESSION['admin'] = $username;
        echo "<p>Session set: admin = " . $_SESSION['admin'] . "</p>";
        
        // Verify session
        if(isset($_SESSION['admin'])) {
            echo "<p style='color: green;'>✅ Session variable is set correctly</p>";
            echo "<p><strong>The login process is working!</strong></p>";
            echo "<p><a href='index.php' target='_blank'>Try accessing admin panel</a></p>";
        } else {
            echo "<p style='color: red;'>❌ Session variable not set properly</p>";
        }
        
    } else {
        echo "<p style='color: red;'><strong>❌ LOGIN FAILED</strong></p>";
        echo "<p>No matching records found</p>";
    }
}

// Show available admin users
echo "<h3>Available Admin Users:</h3>";
$admin_query = mysqli_query($con, "SELECT * FROM admin");
echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
while($admin = mysqli_fetch_assoc($admin_query)) {
    echo "<tr>";
    echo "<td>" . $admin['id'] . "</td>";
    echo "<td>" . $admin['nm'] . "</td>";
    echo "<td>" . $admin['pswd'] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Admin Login Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; padding: 20px; }
        form { background: #f8f9fa; padding: 20px; border-radius: 5px; margin: 20px 0; }
        input[type="text"], input[type="password"] { padding: 10px; margin: 5px 0; width: 200px; }
        input[type="submit"] { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 3px; cursor: pointer; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h3>Test Login Form</h3>
<form method="post">
    <p>
        <label>Username:</label><br>
        <input type="text" name="username" value="admin" required>
    </p>
    <p>
        <label>Password:</label><br>
        <input type="password" name="password" value="admin123" required>
    </p>
    <p>
        <input type="submit" name="test_login" value="Test Login">
    </p>
</form>

<form method="post">
    <p>
        <label>Username:</label><br>
        <input type="text" name="username" value="Drashti" required>
    </p>
    <p>
        <label>Password:</label><br>
        <input type="password" name="password" value="sabhaya" required>
    </p>
    <p>
        <input type="submit" name="test_login" value="Test Login">
    </p>
</form>

<h3>Navigation</h3>
<p>
    <a href="login.php">Original Login Page</a> | 
    <a href="login_debug.php">Debug Login Page</a> | 
    <a href="index.php">Admin Panel</a> |
    <a href="../admin_setup.php">Admin Setup</a>
</p>

</body>
</html>
