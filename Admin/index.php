<?php
 	include_once("header.php");
	include("../Database/connect.php");
?>

	<!-- banner-bottom -->
	<div class="w3-agile-text">
		<div class="container"> 
			<?php
					@session_start();
					if(isset($_SESSION['admin']))
					{
						$adm=$_SESSION['admin'];
						echo "<h2 align='center'>WELCOME ".$adm."</h2>";
					} 
		?>
		</div>
	</div>
	<!-- //banner-bottom -->

	<!-- Dashboard Statistics -->
	<div class="codes">
		<div class="container">
			<h3 class="w3ls-hdg" align="center">BOOKING DASHBOARD</h3>
			<div class="row">
				<?php
				// Get booking statistics
				$pending_query = "SELECT COUNT(*) as count FROM booking WHERE booking_status = 'pending'";
				$approved_query = "SELECT COUNT(*) as count FROM booking WHERE booking_status = 'approved'";
				$rejected_query = "SELECT COUNT(*) as count FROM booking WHERE booking_status = 'rejected'";
				$total_query = "SELECT COUNT(*) as count FROM booking";
				
				$pending_result = mysqli_query($con, $pending_query);
				$approved_result = mysqli_query($con, $approved_query);
				$rejected_result = mysqli_query($con, $rejected_query);
				$total_result = mysqli_query($con, $total_query);
				
				$pending_count = mysqli_fetch_assoc($pending_result)['count'];
				$approved_count = mysqli_fetch_assoc($approved_result)['count'];
				$rejected_count = mysqli_fetch_assoc($rejected_result)['count'];
				$total_count = mysqli_fetch_assoc($total_result)['count'];
				?>
				
				<div class="col-md-3">
					<div class="panel panel-warning">
						<div class="panel-body" style="text-align: center; padding: 20px;">
							<h2 style="margin: 0; color: orange;"><?php echo $pending_count; ?></h2>
							<p>Pending Requests</p>
							<?php if($pending_count > 0): ?>
								<a href="view_order.php">View Details</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="panel panel-success">
						<div class="panel-body" style="text-align: center; padding: 20px;">
							<h2 style="margin: 0; color: green;"><?php echo $approved_count; ?></h2>
							<p>Approved</p>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="panel panel-danger">
						<div class="panel-body" style="text-align: center; padding: 20px;">
							<h2 style="margin: 0; color: red;"><?php echo $rejected_count; ?></h2>
							<p>Rejected</p>
						</div>
					</div>
				</div>
				
				<div class="col-md-3">
					<div class="panel panel-primary">
						<div class="panel-body" style="text-align: center; padding: 20px;">
							<h2 style="margin: 0; color: blue;"><?php echo $total_count; ?></h2>
							<p>Total Bookings</p>
						</div>
					</div>
				</div>
			</div>
			
			<?php if($pending_count > 0): ?>
			<div class="alert alert-warning" style="text-align: center;">
				<strong>Action Required:</strong> You have <?php echo $pending_count; ?> pending booking request(s) waiting for approval.
				<a href="view_order.php">Review Now</a>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<!-- //Dashboard Statistics -->
						
	<!-- services -->
	<div class="services">
		<div class="container">
			<h3 class="w3ltitle">MANAGE SERVICES</h3>
			<div class="services-agileinfo">
				<div class="servc-icon">
					<a href="add_wed.php" class="agile-shape"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
					<p class="serw3-agiletext">Wedding</p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="add_anni.php" class="agile-shape"><span class="glyphicon glyphicon-glass" aria-hidden="true"></span>
					<p class="serw3-agiletext"> Anniversary </p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="add_birthd.php" class="agile-shape"><span class="glyphicon fa fa-gift" aria-hidden="true"></span>
					<p class="serw3-agiletext">Birthday party</p>
					</a>
				</div>
				<div class="servc-icon">
					<a href="add_other.php" class="agile-shape"><span class="glyphicon glyphicon-music" aria-hidden="true"></span>
					<p class="serw3-agiletext">Enjoyment</p>
					</a>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	<!-- //services -->
	
	<?php
		include_once("footer.php");
	?>