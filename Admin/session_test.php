<?php
/**
 * Session Test for Admin Login
 */

// Test session functionality
session_start();

echo "<h2>Session Test</h2>";

// Test 1: Can we start sessions?
echo "<p>✅ Session started successfully</p>";
echo "<p>Session ID: " . session_id() . "</p>";

// Test 2: Can we set session variables?
$_SESSION['test'] = 'working';
if(isset($_SESSION['test']) && $_SESSION['test'] === 'working') {
    echo "<p>✅ Session variables work correctly</p>";
} else {
    echo "<p>❌ Session variables not working</p>";
}

// Test 3: Simulate admin login
if(isset($_GET['test_login'])) {
    $_SESSION['admin'] = 'test_admin';
    echo "<p>✅ Admin session set: " . $_SESSION['admin'] . "</p>";
    echo "<p><a href='index.php'>Try accessing admin panel</a></p>";
} else {
    echo "<p><a href='?test_login=1'>Test admin session</a></p>";
}

// Test 4: Check if we can include database
include("../Database/connect.php");
if($con) {
    echo "<p>✅ Database connection works</p>";
    
    // Quick admin check
    $admin_count = mysqli_query($con, "SELECT COUNT(*) as count FROM admin");
    $count = mysqli_fetch_assoc($admin_count);
    echo "<p>✅ Admin table has " . $count['count'] . " users</p>";
} else {
    echo "<p>❌ Database connection failed</p>";
}

// Test 5: Show all session data
echo "<h3>Current Session Data:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h3>PHP Configuration:</h3>";
echo "<p>Session Save Path: " . session_save_path() . "</p>";
echo "<p>Session Cookie Params: " . print_r(session_get_cookie_params(), true) . "</p>";

?>

<h3>Navigation:</h3>
<p>
    <a href="login_fixed.php">Fixed Login Page</a> | 
    <a href="login.php">Original Login</a> | 
    <a href="index.php">Admin Panel</a>
</p>
