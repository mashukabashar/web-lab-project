<?php
include("../Database/connect.php");

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$login_message = "";
$debug_info = [];

if(isset($_POST['submit']))
{
    $debug_info[] = "Form submitted";
    
    // Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
        $debug_info[] = "Session started";
    } else {
        $debug_info[] = "Session already active";
    }
    
    $name = mysqli_real_escape_string($con, $_POST['nm']);
    $pwd = mysqli_real_escape_string($con, $_POST['pwd']);
    
    $debug_info[] = "Credentials received - Username: '$name', Password: '$pwd'";
    
    // Debug: Check if we can connect to database
    if(!$con) {
        $login_message = "Database connection failed: " . mysqli_connect_error();
        $debug_info[] = "❌ " . $login_message;
    } else {
        $debug_info[] = "✅ Database connection successful";
        
        // Check if admin table exists
        $table_check = mysqli_query($con, "SHOW TABLES LIKE 'admin'");
        if(mysqli_num_rows($table_check) == 0) {
            $login_message = "Admin table not found. Please run the database setup first.";
            $debug_info[] = "❌ " . $login_message;
        } else {
            $debug_info[] = "✅ Admin table exists";
            
            // Show available users for debugging
            $all_users = mysqli_query($con, "SELECT nm FROM admin");
            $available_users = [];
            while($user_row = mysqli_fetch_assoc($all_users)) {
                $available_users[] = $user_row['nm'];
            }
            $debug_info[] = "Available users: " . implode(', ', $available_users);
            
            // Check admin credentials
            $qry = mysqli_query($con, "SELECT * FROM admin WHERE nm='$name' AND pswd='$pwd'");
            
            if(!$qry) {
                $login_message = "Database query failed: " . mysqli_error($con);
                $debug_info[] = "❌ " . $login_message;
            } else if(mysqli_num_rows($qry) > 0) {
                $debug_info[] = "✅ Credentials valid";
                $_SESSION['admin'] = $name;
                $debug_info[] = "✅ Session set: admin = " . $_SESSION['admin'];
                
                // Redirect to admin panel
                $debug_info[] = "🔄 Redirecting to index.php";
                header('Location: index.php');	
                exit;
            } else {
                $login_message = "Invalid username or password";
                $debug_info[] = "❌ " . $login_message;
                
                // Test individual conditions for better debugging
                $username_test = mysqli_query($con, "SELECT * FROM admin WHERE nm='$name'");
                $password_test = mysqli_query($con, "SELECT * FROM admin WHERE pswd='$pwd'");
                
                if(mysqli_num_rows($username_test) == 0) {
                    $debug_info[] = "❌ Username '$name' not found";
                } else {
                    $debug_info[] = "✅ Username '$name' found";
                }
                
                if(mysqli_num_rows($password_test) == 0) {
                    $debug_info[] = "❌ Password '$pwd' not found";
                } else {
                    $debug_info[] = "✅ Password '$pwd' found";
                }
            }
        }
    }
}
?>
<!--
Author: EVENTEASE
-->
<!DOCTYPE html>
<html lang="en">
<head>
<title>EventEase - Admin Login (Debug Version)</title>
<!-- meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="EventEase Admin Login" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //meta tags -->
<!-- Custom Theme files -->
<link href="../css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
<link href="../css/style.css" type="text/css" rel="stylesheet" media="all">
<link rel="stylesheet" href="../css/flexslider.css" type="text/css" media="screen" />
<link href="../css/font-awesome.css" rel="stylesheet"> 
<!-- //Custom Theme files -->
<!-- js -->
<script src="js/jquery-1.11.1.min.js"></script> 
<!-- //js --> 
<!-- web fonts -->
<link href="//fonts.googleapis.com/css?family=Abel" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<!-- //web fonts -->
<style>
.debug-panel {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 5px;
    padding: 15px;
    margin: 20px 0;
    font-family: monospace;
}
.error-msg {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 5px;
    padding: 10px;
    margin: 10px 0;
}
.quick-login {
    background: #d1ecf1;
    border: 1px solid #bee5eb;
    border-radius: 5px;
    padding: 15px;
    margin: 20px 0;
}
</style>
</head>
<body>
	<!-- header -->
	<div class="header">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header navbar-left">
					<h1>
					<img src="../images/logo.png"></h1>
				</div>
				<div class="header-right">
					<div class="agileits-topnav">
					</div>
					<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">					
						<div class="clearfix"> </div>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>	
		</nav>		
	</div>	
	<!-- //header -->
	
	<!-- footer -->
	<div class="footer">
		<div class="container">
			<h3 class="w3ltitle"><span>EVENTEASE</span> ADMIN LOGIN</h3>
			
			<?php if($login_message): ?>
			<div class="error-msg">
				<strong>Login Failed:</strong> <?php echo $login_message; ?>
			</div>
			<?php endif; ?>
			
			<?php if(!empty($debug_info)): ?>
			<div class="debug-panel">
				<h4>Debug Information:</h4>
				<?php foreach($debug_info as $info): ?>
				<div><?php echo $info; ?></div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
			
			<div class="quick-login">
				<h4>Quick Login Options:</h4>
				<p><strong>Option 1:</strong> Username: <code>admin</code> | Password: <code>admin123</code></p>
				<p><strong>Option 2:</strong> Username: <code>Drashti</code> | Password: <code>sabhaya</code></p>
			</div>
			
			<div class="footer-agileinfo">
				<div class="col-md-8 contact-grids">
					<div class="contact-w3form">
						<form name="login" action="" method="post"> 
							<input type="text" name="nm" placeholder="Username" required value="<?php echo isset($_POST['nm']) ? htmlspecialchars($_POST['nm']) : 'admin'; ?>"/>
							<br/>
							<input type="password" name="pwd" placeholder="Password" required value="<?php echo isset($_POST['pwd']) ? htmlspecialchars($_POST['pwd']) : 'admin123'; ?>"/>
							<input type="submit" name="submit" value="LOGIN">
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<!-- copy-right -->
		<div class="copy-right w3-agile-text">
			<div class="container">
				<p>© 2025 EventEase Admin Panel</p>	
				<p><a href="../db_test.php">Database Test</a> | <a href="../admin_setup.php">Admin Setup</a></p>
			</div>
		</div>
		<!-- //copy-right -->
	</div>
	<!-- //footer --> 
	
	<!-- Bootstrap core JavaScript -->
    <script src="js/bootstrap.js"></script>
</body>
</html>
