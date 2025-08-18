<?php
	include('Database/connect.php');
	include('session.php');		
	include("header.php");
	
	// Get user's booking history
	if(!isset($_SESSION['email'])) {
		echo '<script type="text/javascript">window.location="login.php";</script>';
		exit;
	}
	
	$user_email = $_SESSION['email'];
	$user_name = isset($_SESSION['full_name']) ? $_SESSION['full_name'] : $_SESSION['email'];
	$user_id = $_SESSION['user_id'];
	
	// Query bookings by user_id (more reliable than email)
	$query = "SELECT * FROM booking WHERE user_id = '" . mysqli_real_escape_string($con, $user_id) . "' ORDER BY booking_date DESC";
	$result = mysqli_query($con, $query);
	
	// If no results by user_id, try by email as fallback
	if(mysqli_num_rows($result) == 0) {
		$query = "SELECT * FROM booking WHERE email = '" . mysqli_real_escape_string($con, $user_email) . "' ORDER BY booking_date DESC";
		$result = mysqli_query($con, $query);
	}
?>

<style>
/* Enhanced Button Styling */
.btn-payment:hover {
    background: linear-gradient(45deg, #20c997, #28a745) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 16px rgba(40, 167, 69, 0.4) !important;
}

.btn-invoice:hover {
    background: linear-gradient(45deg, #8e44ad, #6f42c1) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 8px 16px rgba(111, 66, 193, 0.4) !important;
}

.btn-payment:active, .btn-invoice:active {
    transform: translateY(0) !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
}

/* Responsive button styling */
@media (max-width: 768px) {
    .btn-payment, .btn-invoice {
        display: block !important;
        width: 100% !important;
        margin: 5px 0 !important;
        text-align: center !important;
    }
}

/* Loading animation on click */
.btn-payment:active::before, .btn-invoice:active::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div class="banner about-bnr">
	<div class="container">
	</div>
</div>

<div class="codes">
	<div class="container"> 
		<h2 class="w3ls-hdg" align="center">My Booking History</h2>
		<p align="center" style="color: #666; margin-bottom: 30px;">Welcome <?php echo $user_name; ?>! Here are all your event bookings.</p>
		
		<?php if(mysqli_num_rows($result) > 0): ?>
			<div class="grid_3 grid_4">
				<?php while($booking = mysqli_fetch_assoc($result)): ?>
					<div class="tab-content" style="margin-bottom: 30px; border: 1px solid #ddd; padding: 20px; border-radius: 5px;">
						<div class="tab-pane active">
							<div class="row">
								<!-- Booking Details Column -->
								<div class="col-md-8">
									<h4 style="color: #333; margin-bottom: 15px;">Booking #<?php echo $booking['id']; ?></h4>
									
									<div class="form-group">
										<div class="col-sm-12">
											<p><strong>Theme:</strong> <?php echo $booking['thm_nm']; ?></p>
											<p><strong>Event Date:</strong> <?php echo $booking['date']; ?></p>
											<p><strong>Booking Date:</strong> <?php echo date('d/m/Y H:i', strtotime($booking['booking_date'])); ?></p>
											<p><strong>Total Price:</strong> ৳<?php echo number_format($booking['price']); ?></p>
											<p><strong>Advance Paid:</strong> ৳<?php echo number_format($booking['advance_paid']); ?></p>
											<p><strong>Remaining Amount:</strong> ৳<?php echo number_format($booking['remaining_amount']); ?></p>
										</div>
									</div>
									
									<!-- Status Badges -->
									<div class="form-group">
										<div class="col-sm-12">
											<?php 
											$status_color = '#ffc107'; // Default yellow for pending
											$status_text = ucfirst($booking['booking_status']);
											
											if($booking['booking_status'] == 'approved') {
												$status_color = '#28a745'; // Green for approved
											} else if($booking['booking_status'] == 'rejected') {
												$status_color = '#dc3545'; // Red for rejected
											}
											?>
											<span class="badge" style="background-color: <?php echo $status_color; ?>; padding: 8px 12px; margin-right: 10px;">
												Booking: <?php echo $status_text; ?>
											</span>
											<span class="badge" style="background-color: <?php echo ($booking['payment_status'] == 'paid') ? '#28a745' : '#dc3545'; ?>; padding: 8px 12px;">
												Payment: <?php echo ucfirst($booking['payment_status']); ?>
											</span>
										</div>
									</div>
									
									<!-- Action Buttons -->
									<div class="form-group">
										<div class="col-sm-12" style="margin-top: 20px;">
											<?php if($booking['booking_status'] == 'rejected'): ?>
												<div class="alert" style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
													<strong>Booking Rejected:</strong> This booking has been rejected by the admin. Please contact us for more information.
												</div>
											<?php elseif($booking['booking_status'] == 'pending'): ?>
												<div class="alert" style="background-color: #fff3cd; color: #856404; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
													<strong>Pending Approval:</strong> Your booking is waiting for admin approval. Payment will be available once approved.
												</div>
											<?php endif; ?>
											
											<?php if($booking['payment_status'] != 'paid' && $booking['booking_status'] == 'approved'): ?>
												<a href="payment.php?booking_id=<?php echo $booking['id']; ?>" 
												   class="btn btn-payment" 
												   style="background: linear-gradient(45deg, #28a745, #20c997); 
												          color: white; 
												          padding: 12px 20px; 
												          margin-right: 15px; 
												          border-radius: 8px; 
												          text-decoration: none; 
												          font-weight: 600;
												          box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
												          border: none;
												          transition: all 0.3s ease;
												          display: inline-flex;
												          align-items: center;
												          gap: 8px;"
												   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(40, 167, 69, 0.4)'"
												   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(40, 167, 69, 0.3)'">
													<i class="fa fa-credit-card" style="font-size: 16px;"></i>
													Pay Advance
												</a>
											<?php endif; ?>
											<a href="download_invoice.php?booking_id=<?php echo $booking['id']; ?>" 
											   class="btn btn-invoice" 
											   style="background: linear-gradient(45deg, #6f42c1, #8e44ad); 
											          color: white; 
											          padding: 12px 20px; 
											          border-radius: 8px; 
											          text-decoration: none; 
											          font-weight: 600;
											          box-shadow: 0 4px 8px rgba(111, 66, 193, 0.3);
											          border: none;
											          transition: all 0.3s ease;
											          display: inline-flex;
											          align-items: center;
											          gap: 8px;"
											   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 12px rgba(111, 66, 193, 0.4)'"
											   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 8px rgba(111, 66, 193, 0.3)'">
												<i class="fa fa-download" style="font-size: 16px;"></i>
												Download Invoice
											</a>
										</div>
									</div>
								</div>
								
								<!-- Theme Image Column -->
								<div class="col-md-4">
									<div class="form-group">
										<img src="./images/<?php echo $booking['theme']; ?>" class="img-responsive" style="max-height: 200px; width: 100%; object-fit: cover; border-radius: 5px;" alt="Theme Image"/>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
		<?php else: ?>
			<div class="grid_3 grid_4">
				<div class="tab-content">
					<div class="tab-pane active">
						<div class="text-center" style="padding: 50px;">
							<h4>No bookings found</h4>
							<p>You haven't made any bookings yet.</p>
							<a href="index.php" class="btn" style="background: #808080;">Browse Events</a>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>

<?php 
	include_once("footer.php");
?>
