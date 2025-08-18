<?php
include("../Database/connect.php");

// Initialize variables
$login_status = "";
$debug_info = [];

if(isset($_POST['login'])) {
    // Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    $debug_info[] = "Form submitted with username: '$username'";
    
    if(!$con) {
        $login_status = "error";
        $debug_info[] = "❌ Database connection failed: " . mysqli_connect_error();
    } else {
        $debug_info[] = "✅ Database connected successfully";
        
        // Escape input
        $safe_username = mysqli_real_escape_string($con, $username);
        $safe_password = mysqli_real_escape_string($con, $password);
        
        // Query database
        $query = "SELECT * FROM admin WHERE nm='$safe_username' AND pswd='$safe_password'";
        $debug_info[] = "Query: $query";
        
        $result = mysqli_query($con, $query);
        
        if(!$result) {
            $login_status = "error";
            $debug_info[] = "❌ Query failed: " . mysqli_error($con);
        } else {
            $row_count = mysqli_num_rows($result);
            $debug_info[] = "Query returned $row_count rows";
            
            if($row_count > 0) {
                // Login successful
                $_SESSION['admin'] = $username;
                $login_status = "success";
                $debug_info[] = "✅ Login successful! Session set.";
                
                // Redirect after 2 seconds
                echo "<script>
                    setTimeout(function() {
                        window.location.href = 'index.php';
                    }, 2000);
                </script>";
            } else {
                $login_status = "invalid";
                $debug_info[] = "❌ Invalid credentials";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>EventEase - Admin Login (Working Version)</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/bootstrap.css" rel="stylesheet">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-primary {
            width: 100%;
            padding: 12px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #0056b3;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }
        .credentials {
            background: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .debug-info {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 12px;
            font-family: monospace;
        }
        .quick-login {
            margin-top: 15px;
            text-align: center;
        }
        .quick-login button {
            margin: 5px;
            padding: 8px 15px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align: center; margin-bottom: 30px; color: #333;">
            EventEase Admin Login
        </h2>
        
        <?php if($login_status === "success"): ?>
            <div class="alert alert-success">
                <strong>✅ Login Successful!</strong><br>
                Welcome <?php echo htmlspecialchars($username); ?>!<br>
                Redirecting to admin panel...
            </div>
        <?php elseif($login_status === "error"): ?>
            <div class="alert alert-danger">
                <strong>❌ Login Error</strong><br>
                Database connection or query failed.
            </div>
        <?php elseif($login_status === "invalid"): ?>
            <div class="alert alert-danger">
                <strong>❌ Invalid Credentials</strong><br>
                Username or password is incorrect.
            </div>
        <?php endif; ?>
        
        <div class="credentials">
            <strong>Available Login Credentials:</strong><br>
            <strong>Username:</strong> admin | <strong>Password:</strong> admin123<br>
            <strong>Username:</strong> Drashti | <strong>Password:</strong> sabhaya
        </div>
        
        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" class="form-control" 
                       value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'admin'; ?>" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" class="form-control" 
                       value="admin123" required>
            </div>
            
            <button type="submit" name="login" class="btn-primary">
                LOGIN
            </button>
        </form>
        
        <div class="quick-login">
            <p><strong>Quick Login:</strong></p>
            <button onclick="quickLogin('admin', 'admin123')">Login as admin</button>
            <button onclick="quickLogin('Drashti', 'sabhaya')">Login as Drashti</button>
        </div>
        
        <?php if(!empty($debug_info)): ?>
            <div class="debug-info">
                <strong>Debug Info:</strong><br>
                <?php foreach($debug_info as $info): ?>
                    <?php echo htmlspecialchars($info); ?><br>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 20px; font-size: 12px;">
            <a href="../db_test.php" target="_blank">Database Test</a> | 
            <a href="../admin_setup.php" target="_blank">Admin Setup</a> |
            <a href="check_admin.php" target="_blank">Check Admin</a>
        </div>
    </div>
    
    <script>
        function quickLogin(username, password) {
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            document.forms[0].submit();
        }
    </script>
</body>
</html>
