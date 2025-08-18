<?php
	session_start();
	include("header.php");
?>

<div class="banner about-bnr">
	<div class="container">
	</div>
</div>

<div class="codes">
	<div class="container"> 
		<h2 class="w3ls-hdg" align="center">Session Debug Information</h2>
		
		<div class="grid_3 grid_4">
			<div class="tab-content">
				<div class="tab-pane active" style="padding: 25px;">
					<h4 style="color: #333; margin-bottom: 20px;">Current Session Data:</h4>
					
					<?php if(isset($_SESSION['uname'])): ?>
						<div style="background: #d4edda; border: 1px solid #c3e6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
							<h5 style="color: #155724; margin-bottom: 10px;">✅ User is logged in</h5>
							<p><strong>Username:</strong> <?php echo $_SESSION['uname'] ?? 'Not set'; ?></p>
							<p><strong>Email:</strong> <?php echo $_SESSION['email'] ?? 'Not set'; ?></p>
							<p><strong>User ID:</strong> <?php echo $_SESSION['user_id'] ?? 'Not set'; ?></p>
							<p><strong>Full Name:</strong> <?php echo $_SESSION['full_name'] ?? 'Not set'; ?></p>
						</div>
						
						<div class="text-center">
							<a href="dashboard.php" class="btn success">Go to Dashboard</a>
							<a href="booking_history.php" class="btn info">View Bookings</a>
							<a href="logout.php" class="btn warning">Logout</a>
						</div>
					<?php else: ?>
						<div style="background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
							<h5 style="color: #721c24; margin-bottom: 10px;">❌ User is not logged in</h5>
							<p>Please login to access user features.</p>
						</div>
						
						<div class="text-center">
							<a href="login.php" class="btn warning">Login</a>
							<a href="registration.php" class="btn default">Register</a>
						</div>
					<?php endif; ?>
					
					<hr style="margin: 30px 0;">
					
					<h5 style="color: #333;">All Session Variables:</h5>
					<pre style="background: #f8f9fa; padding: 15px; border-radius: 5px; border: 1px solid #dee2e6;">
<?php print_r($_SESSION); ?>
					</pre>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	include_once("footer.php");
?>
