<?php
// Admin credential checker - Run this file to see what admin accounts exist
include("../Database/connect.php");

echo "<h2>Admin Credential Checker</h2>";

if(!$con) {
    echo "<p style='color:red;'>❌ Database connection failed!</p>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
    exit;
}

echo "<p style='color:green;'>✅ Database connected successfully!</p>";

// Check if admin table exists
$result = mysqli_query($con, "SHOW TABLES LIKE 'admin'");
if(mysqli_num_rows($result) == 0) {
    echo "<p style='color:red;'>❌ Admin table does not exist!</p>";
    echo "<p>Please run the fix_email_columns.sql script to create it.</p>";
    exit;
}

echo "<p style='color:green;'>✅ Admin table exists!</p>";

// Show all admin accounts
$qry = mysqli_query($con, "SELECT * FROM admin");
if(!$qry) {
    echo "<p style='color:red;'>❌ Error querying admin table: " . mysqli_error($con) . "</p>";
    exit;
}

$count = mysqli_num_rows($qry);
echo "<p>Found $count admin account(s):</p>";

if($count > 0) {
    echo "<table border='1' style='border-collapse: collapse; margin: 10px;'>";
    echo "<tr><th>ID</th><th>Username</th><th>Password</th></tr>";
    
    while($row = mysqli_fetch_assoc($qry)) {
        echo "<tr>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td><strong>" . $row['nm'] . "</strong></td>";
        echo "<td><strong>" . $row['pswd'] . "</strong></td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<p style='color:blue;'>💡 Use any of the above username/password combinations to login to admin panel.</p>";
} else {
    echo "<p style='color:red;'>❌ No admin accounts found!</p>";
    echo "<p>Please run the fix_email_columns.sql script to create admin accounts.</p>";
}

// Add a quick form to create admin account
echo "<hr>";
echo "<h3>Create New Admin Account</h3>";

if(isset($_POST['create_admin'])) {
    $new_user = $_POST['new_username'];
    $new_pass = $_POST['new_password'];
    
    $insert_qry = mysqli_query($con, "INSERT INTO admin (nm, pswd) VALUES ('$new_user', '$new_pass')");
    if($insert_qry) {
        echo "<p style='color:green;'>✅ Admin account created successfully!</p>";
        echo "<script>window.location.reload();</script>";
    } else {
        echo "<p style='color:red;'>❌ Error creating admin account: " . mysqli_error($con) . "</p>";
    }
}
?>

<form method="post">
    <p>
        <label>Username: </label>
        <input type="text" name="new_username" value="admin" required>
    </p>
    <p>
        <label>Password: </label>
        <input type="text" name="new_password" value="admin123" required>
    </p>
    <p>
        <input type="submit" name="create_admin" value="Create Admin Account">
    </p>
</form>

<hr>
<p><a href="login.php">← Back to Admin Login</a></p>
