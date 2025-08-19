<?php
include("../Database/connect.php");

$message = "";
$message_type = "";

if(isset($_POST['submit'])) {
    // Start session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    $username = trim($_POST['nm']);
    $password = trim($_POST['pwd']);
    
    if(!$con) {
        $message = "Database connection failed. Please contact administrator.";
        $message_type = "error";
    } else {
        // Escape input
        $safe_username = mysqli_real_escape_string($con, $username);
        $safe_password = mysqli_real_escape_string($con, $password);
        
        // Check credentials
        $query = "SELECT * FROM admin WHERE nm='$safe_username' AND pswd='$safe_password'";
        $result = mysqli_query($con, $query);
        
        if(!$result) {
            $message = "Database query failed: " . mysqli_error($con);
            $message_type = "error";
        } else if(mysqli_num_rows($result) > 0) {
            // Login successful
            $_SESSION['admin'] = $username;
            
            // Use JavaScript redirect to ensure it works
            echo "<script>
                alert('Login successful! Welcome " . addslashes($username) . "');
                window.location.href = 'index.php';
            </script>";
            exit;
        } else {
            $message = "Invalid username or password. Please try again.";
            $message_type = "error";
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
<title>EventEase</title>
<!-- meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Light Fixture Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
	SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola web design" />
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
</head>
<body>
	<!-- header -->
	<div class="header">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header navbar-left">
										<h1 style="font-family: 'Roboto', sans-serif; font-weight: 700; color: #b9722d; font-size: 2.5em; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
						<i class="fa fa-calendar-check-o" style="margin-right: 10px; color: #17a2b8;"></i>
						EventEase

					</h1>
				</div>
				<!-- navigation--> 
					<span class="sr-only">EventEase </span>
				<div class="header-right">
					<div class="agileits-topnav">
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">					
						<div class="clearfix"> </div>
					</div><!-- //navigation -->
				</div>
				<div class="clearfix"> </div>
			</div>	
		</nav>		
	</div>	
	<!-- //header -->
	<!-- footer -->
	<div class="footer">
		<div class="container">
			<h3 class="w3ltitle"><span>EVENTEASE</span> ADMIN</h3>
			
			<?php if($message): ?>
			<div style="background: <?php echo $message_type === 'error' ? '#f8d7da' : '#d4edda'; ?>; 
			            color: <?php echo $message_type === 'error' ? '#721c24' : '#155724'; ?>; 
			            border: 1px solid <?php echo $message_type === 'error' ? '#f5c6cb' : '#c3e6cb'; ?>; 
			            border-radius: 5px; padding: 15px; margin: 20px 0; text-align: center;">
				<strong><?php echo $message_type === 'error' ? 'Error:' : 'Success:'; ?></strong> 
				<?php echo htmlspecialchars($message); ?>
			</div>
			<?php endif; ?>
			
			<div style="background: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px; padding: 15px; margin: 20px 0; text-align: center;">
				<h4>Login Credentials:</h4>
				<p><strong>Username:</strong> Mashuka | <strong>Password:</strong> 12345678</p>
				g			</div>
			
			<div class="footer-agileinfo">
			<div class="col-md-8 contact-grids">
	<div class="contact-w3form">
	
	<form name="login" action="" method="post"> 
							<input type="text" name="nm" placeholder="Username" required value="<?php echo isset($_POST['nm']) ? htmlspecialchars($_POST['nm']) : 'admin'; ?>"/></br>
							<input type="password" name="pwd" placeholder="Password" required value="admin123"/>
							<input type="submit" name="submit" value="LOGIN">
						</form>
					
					<!-- Quick Login Buttons -->
					<!-- <div style="text-align: center; margin: 20px 0;">
						<h4>Quick Login:</h4>
						<button onclick="quickLogin('admin', 'admin123')" 
						        style="margin: 5px; padding: 10px 15px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer;">
						    Login as admin
						</button>
						<button onclick="quickLogin('Drashti', 'sabhaya')" 
						        style="margin: 5px; padding: 10px 15px; background: #17a2b8; color: white; border: none; border-radius: 5px; cursor: pointer;">
						    Login as Drashti
						</button>
					</div> -->
					
					<script>
					function quickLogin(username, password) {
					    document.querySelector('input[name="nm"]').value = username;
					    document.querySelector('input[name="pwd"]').value = password;
					    document.querySelector('form[name="login"]').submit();
					}
					</script>
</div></div>

		<!-- footer -->
	<div class="footer">
		<div class="container">
			
					</div>
				</div>
				<div class="col-md-6 footer-right">
					<div class="address">
						<div class="col-xs-2 contact-grdl">
						</div>
						<div class="col-xs-10 contact-grdr">
						</div>
						<div class="clearfix"> </div>
					</div>
					
					
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
		<!-- copy-right -->
		<div class="copy-right w3-agile-text">
			<div class="container">
				<div class="social-icons">
					
					<div class="clearfix"> </div>
				</div> 
				<p>© 2026 Demo</p>	
			</div>
		</div>
		<!-- //copy-right -->
	</div>
	<!-- //footer --> 
	<!-- start-smooth-scrolling-->
	<script type="text/javascript" src="js/move-top.js"></script>
	<script type="text/javascript" src="js/easing.js"></script>	
	<script type="text/javascript">
			jQuery(document).ready(function($) {
				$(".scroll").click(function(event){		
					event.preventDefault();
			
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
				});
			});
	</script>
	<!-- //end-smooth-scrolling -->	
	<!-- smooth-scrolling-of-move-up -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
			var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
			};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
		});
	</script>
	<!-- //smooth-scrolling-of-move-up -->
	<!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.js"></script>
</body>
</html>