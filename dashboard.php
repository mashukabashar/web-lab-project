<?php
	include('Database/connect.php');
	include('session.php');		
	include("header.php");
	
	// Get user information from session (now guaranteed to be set by session.php)
	$user_email = $_SESSION['email'];
	$user_name = $_SESSION['full_name'];
	$user_id = $_SESSION['user_id'];
	
	// Get user's booking statistics
	$stats_query = "SELECT 
		COUNT(*) as total_bookings,
		SUM(CASE WHEN booking_status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_bookings,
		SUM(CASE WHEN payment_status = 'paid' THEN 1 ELSE 0 END) as paid_bookings,
		SUM(CASE WHEN advance_paid IS NOT NULL THEN advance_paid ELSE 0 END) as total_paid
		FROM booking WHERE user_id = '" . mysqli_real_escape_string($con, $user_id) . "'";
	
	$stats_result = mysqli_query($con, $stats_query);
	
	// Check if query was successful
	if (!$stats_result) {
		// Fallback query for older database structure
		$fallback_query = "SELECT COUNT(*) as total_bookings, 0 as confirmed_bookings, 0 as paid_bookings, 0 as total_paid FROM booking WHERE user_id = '" . mysqli_real_escape_string($con, $user_id) . "'";
		$stats_result = mysqli_query($con, $fallback_query);
	}
	
	$stats = mysqli_fetch_assoc($stats_result);
	
	// Ensure stats are not null
	$stats['total_bookings'] = $stats['total_bookings'] ?? 0;
	$stats['confirmed_bookings'] = $stats['confirmed_bookings'] ?? 0;
	$stats['paid_bookings'] = $stats['paid_bookings'] ?? 0;
	$stats['total_paid'] = $stats['total_paid'] ?? 0;
	
	// Get recent bookings (last 3)
	$recent_query = "SELECT * FROM booking WHERE user_id = '" . mysqli_real_escape_string($con, $user_id) . "' ORDER BY booking_date DESC LIMIT 3";
	$recent_result = mysqli_query($con, $recent_query);
	
	// Check if query was successful (in case booking_date column doesn't exist)
	if (!$recent_result) {
		$recent_query = "SELECT * FROM booking WHERE user_id = '" . mysqli_real_escape_string($con, $user_id) . "' ORDER BY id DESC LIMIT 3";
		$recent_result = mysqli_query($con, $recent_query);
	}
?>

<div class="banner about-bnr">
	<div class="container">
	</div>
</div>

<!-- Dashboard Content -->
<div class="about">
	<div class="container"> 
		<h3 class="w3ls-title1">User <span>Dashboard</span></h3>
		
		<!-- Welcome Section -->
		<div class="about-agileinfo w3layouts" style="margin-bottom: 30px;">
			<div class="col-md-12 about-wthree-grids">
				<div style="background: linear-gradient(135deg, #b9722d 0%, #d18942 100%); color: white; padding: 25px; border-radius: 8px; margin-bottom: 30px;">
					<div class="row">
						<div class="col-md-8">
							<h4 style="color: white; margin-bottom: 10px; font-size: 1.8em;">
								<i class="fa fa-user-circle" style="margin-right: 10px;"></i>Welcome back, <?php echo $user_name; ?>!
							</h4>
							<p style="color: #f0f0f0; margin-bottom: 0; font-size: 1.1em;">
								Manage your event bookings and account from your personal dashboard
							</p>
						</div>
						<div class="col-md-4 text-right">
							<div style="background: rgba(255,255,255,0.1); padding: 15px; border-radius: 5px;">
								<p style="margin: 0; color: #f0f0f0;"><strong>Email:</strong> <?php echo $user_email; ?></p>
								<p style="margin: 0; color: #f0f0f0;"><strong>User ID:</strong> #<?php echo str_pad($user_id, 4, '0', STR_PAD_LEFT); ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		
		<!-- Statistics Cards -->
		<div class="about-agileinfo w3layouts" style="margin-bottom: 30px;">
			<h4 class="w3ltitle" style="text-align: center; margin-bottom: 30px;">Account Statistics</h4>
			<div class="col-md-3">
				<div class="about-w3imgs" style="text-align: center; background: #17a2b8; color: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
					<i class="fa fa-calendar-check-o" style="font-size: 2.5em; margin-bottom: 15px;"></i>
					<h2 style="margin: 10px 0; color: white; font-size: 2.2em;"><?php echo $stats['total_bookings']; ?></h2>
					<p style="margin: 0; font-size: 1.1em;">Total Bookings</p>
				</div>
			</div>
			<div class="col-md-3">
				<div class="about-w3imgs" style="text-align: center; background: #28a745; color: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
					<i class="fa fa-check-circle" style="font-size: 2.5em; margin-bottom: 15px;"></i>
					<h2 style="margin: 10px 0; color: white; font-size: 2.2em;"><?php echo $stats['confirmed_bookings']; ?></h2>
					<p style="margin: 0; font-size: 1.1em;">Confirmed Events</p>
				</div>
			</div>
			<div class="col-md-3">
				<div class="about-w3imgs" style="text-align: center; background: #ffc107; color: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
					<i class="fa fa-credit-card" style="font-size: 2.5em; margin-bottom: 15px;"></i>
					<h2 style="margin: 10px 0; color: white; font-size: 2.2em;"><?php echo $stats['paid_bookings']; ?></h2>
					<p style="margin: 0; font-size: 1.1em;">Fully Paid</p>
				</div>
			</div>
			<div class="col-md-3">
				<div class="about-w3imgs" style="text-align: center; background: #6c757d; color: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
					<i class="fa fa-credit-card" style="font-size: 2.5em; margin-bottom: 15px;"></i>
					<h2 style="margin: 10px 0; color: white; font-size: 2.2em;">৳<?php echo number_format($stats['total_paid']); ?></h2>
					<p style="margin: 0; font-size: 1.1em;">Total Paid</p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		
		<!-- Quick Actions -->
		<div class="about-agileinfo w3layouts" style="margin-bottom: 30px;">
			<h4 class="w3ltitle" style="text-align: center; margin-bottom: 30px;">Quick Actions</h4>
			<div class="col-md-12">
				<div style="text-align: center; background: #f8f9fa; padding: 30px; border-radius: 8px; border: 2px solid #e9ecef;">
					<div class="row">
						<div class="col-md-3">
							<a href="gallery.php" style="text-decoration: none; color: inherit;">
								<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; cursor: pointer; border: 2px solid #808080;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
									<i class="fa fa-plus-circle" style="font-size: 2.5em; color: #808080; margin-bottom: 10px;"></i>
									<h5 style="color: #333; margin: 10px 0 5px 0;">Book New Event</h5>
									<p style="color: #666; margin: 0; font-size: 0.9em;">Browse our event gallery</p>
								</div>
							</a>
						</div>
						<div class="col-md-3">
							<a href="booking_history.php" style="text-decoration: none; color: inherit;">
								<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; cursor: pointer; border: 2px solid #17a2b8;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
									<i class="fa fa-list-alt" style="font-size: 2.5em; color: #17a2b8; margin-bottom: 10px;"></i>
									<h5 style="color: #333; margin: 10px 0 5px 0;">View All Bookings</h5>
									<p style="color: #666; margin: 0; font-size: 0.9em;">Complete booking history</p>
								</div>
							</a>
						</div>
						<div class="col-md-3">
							<a href="user_profile.php" style="text-decoration: none; color: inherit;">
								<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; cursor: pointer; border: 2px solid #28a745;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
									<i class="fa fa-user-cog" style="font-size: 2.5em; color: #28a745; margin-bottom: 10px;"></i>
									<h5 style="color: #333; margin: 10px 0 5px 0;">Edit Profile</h5>
									<p style="color: #666; margin: 0; font-size: 0.9em;">Update your information</p>
								</div>
							</a>
						</div>
						<div class="col-md-3">
							<a href="contact.php" style="text-decoration: none; color: inherit;">
								<div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); transition: all 0.3s; cursor: pointer; border: 2px solid #ffc107;" onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 4px 8px rgba(0,0,0,0.2)'" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.1)'">
									<i class="fa fa-phone" style="font-size: 2.5em; color: #ffc107; margin-bottom: 10px;"></i>
									<h5 style="color: #333; margin: 10px 0 5px 0;">Contact Support</h5>
									<p style="color: #666; margin: 0; font-size: 0.9em;">Get help & support</p>
								</div>
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		
		<!-- Recent Bookings -->
		<div class="about-agileinfo w3layouts">
			<h4 class="w3ltitle" style="text-align: center; margin-bottom: 30px;">Recent Bookings</h4>
			<div class="col-md-12">
				<?php if(mysqli_num_rows($recent_result) > 0): ?>
					<?php while($booking = mysqli_fetch_assoc($recent_result)): ?>
						<div style="background: white; border: 1px solid #ddd; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
							<div class="row">
								<div class="col-md-2">
									<img src="./images/<?php echo $booking['theme']; ?>" 
										style="width: 100%; height: 80px; object-fit: cover; border-radius: 5px;" 
										alt="Theme Image"/>
								</div>
								<div class="col-md-4">
									<h5 style="color: #b9722d; margin: 0 0 10px 0;">
										<i class="fa fa-calendar"></i> Booking #<?php echo $booking['id']; ?>
									</h5>
									<p style="margin: 5px 0; color: #333;"><strong>Theme:</strong> <?php echo $booking['thm_nm']; ?></p>
									<p style="margin: 5px 0; color: #666;"><strong>Event Date:</strong> <?php echo $booking['date']; ?></p>
								</div>
								<div class="col-md-3">
									<p style="margin: 5px 0; color: #333;"><strong>Total:</strong> ৳<?php echo number_format($booking['price']); ?></p>
									<p style="margin: 5px 0; color: #28a745;"><strong>Paid:</strong> ৳<?php echo number_format($booking['advance_paid']); ?></p>
									<p style="margin: 5px 0; color: #dc3545;"><strong>Remaining:</strong> ৳<?php echo number_format($booking['remaining_amount']); ?></p>
								</div>
								<div class="col-md-3">
									<div style="margin-bottom: 10px;">
										<span style="background-color: <?php echo ($booking['booking_status'] == 'confirmed') ? '#28a745' : '#ffc107'; ?>; color: white; padding: 4px 8px; border-radius: 3px; font-size: 0.8em;">
											<i class="fa fa-<?php echo ($booking['booking_status'] == 'confirmed') ? 'check' : 'clock-o'; ?>"></i>
											<?php echo ucfirst($booking['booking_status']); ?>
										</span><br><br>
										<span style="background-color: <?php echo ($booking['payment_status'] == 'paid') ? '#28a745' : '#dc3545'; ?>; color: white; padding: 4px 8px; border-radius: 3px; font-size: 0.8em;">
											<i class="fa fa-<?php echo ($booking['payment_status'] == 'paid') ? 'check-circle' : 'exclamation-circle'; ?>"></i>
											<?php echo ucfirst($booking['payment_status']); ?>
										</span>
									</div>
									<div>
										<?php if($booking['payment_status'] != 'paid'): ?>
											<a href="payment.php?booking_id=<?php echo $booking['id']; ?>" 
												style="background: #28a745; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 0.8em; margin-right: 5px;">
												<i class="fa fa-credit-card"></i> Pay
											</a>
										<?php endif; ?>
										<a href="download_invoice.php?booking_id=<?php echo $booking['id']; ?>" 
											style="background: #17a2b8; color: white; padding: 5px 10px; border-radius: 3px; text-decoration: none; font-size: 0.8em;" 
											target="_blank">
											<i class="fa fa-download"></i> Invoice
										</a>
									</div>
								</div>
							</div>
						</div>
					<?php endwhile; ?>
					
					<div style="text-align: center; margin-top: 30px;">
						<a href="booking_history.php" class="w3ls-more">
							<span class="effect6"><span><i class="fa fa-list"></i> View All Bookings</span></span>
						</a>
					</div>
				<?php else: ?>
					<div style="text-align: center; padding: 50px; background: #f8f9fa; border-radius: 8px; border: 2px dashed #dee2e6;">
						<i class="fa fa-calendar-times-o" style="font-size: 3em; color: #6c757d; margin-bottom: 20px;"></i>
						<h5 style="color: #6c757d; margin-bottom: 10px;">No bookings yet</h5>
						<p style="color: #999; margin-bottom: 20px;">Start by booking your first event with us!</p>
						<a href="gallery.php" class="w3ls-more">
							<span class="effect6"><span><i class="fa fa-plus"></i> Browse Events</span></span>
						</a>
					</div>
				<?php endif; ?>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>

<?php 
	include_once("footer.php");
?>
