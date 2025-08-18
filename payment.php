<?php
	include('Database/connect.php');
	include('session.php');		
	include("header.php");
	
	$booking_id = $_GET['booking_id'];
	$user_email = $_SESSION['email'];
	$user_id = $_SESSION['user_id'];
	
	// Get booking details and verify it belongs to current user
	$query = "SELECT * FROM booking WHERE id = '$booking_id' AND email = '$user_email'";
	$result = mysqli_query($con, $query);
	$booking = mysqli_fetch_assoc($result);
	
	if(!$booking) {
		echo "<script>alert('Booking not found or access denied'); window.location='booking_history.php';</script>";
		exit;
	}
	
	if(isset($_POST['submit'])) {
		$advance_amount = $_POST['advance_amount'];
		$payment_method = $_POST['payment_method'];
		$remaining = $booking['price'] - $advance_amount;
		
		// Update booking with payment details
		$update_query = "UPDATE booking SET 
			advance_paid = '$advance_amount', 
			remaining_amount = '$remaining',
			payment_status = 'partial',
			booking_status = 'confirmed'
			WHERE id = '$booking_id'";
		
		if(mysqli_query($con, $update_query)) {
			// Insert payment record
			$transaction_id = 'TXN' . time() . rand(1000, 9999);
			
			$payment_query = "INSERT INTO payments (booking_id, user_id, amount, payment_type, payment_method, transaction_id) 
				VALUES ('$booking_id', '$user_id', '$advance_amount', 'advance', '$payment_method', '$transaction_id')";
			
			if(mysqli_query($con, $payment_query)) {
				echo "<script>alert('Payment successful! Transaction ID: $transaction_id'); window.location='booking_history.php';</script>";
			}
		} else {
			echo "<script>alert('Payment failed. Please try again.');</script>";
		}
	}
?>

<style>
/* Simple Payment Button Styling */
.btn-proceed:hover {
    background: linear-gradient(45deg, #20c997, #28a745) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 6px 15px rgba(40, 167, 69, 0.4) !important;
}

.btn-cancel:hover {
    background: linear-gradient(45deg, #5a6268, #6c757d) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 6px 15px rgba(108, 117, 125, 0.4) !important;
}

.btn-proceed:active, .btn-cancel:active {
    transform: translateY(0) !important;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2) !important;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .btn-proceed, .btn-cancel {
        display: block !important;
        width: 80% !important;
        margin: 10px auto !important;
        text-align: center !important;
    }
}
</style>

<div class="banner about-bnr">
	<div class="container">
	</div>
</div>

<div class="codes">
	<div class="container"> 
		<h2 class="w3ls-hdg" align="center">Advance Payment</h2>
		
		<div class="grid_3 grid_4">
			<div class="tab-content">
				<div class="tab-pane active">
					<!-- Booking Summary -->
					<div style="background: #f8f9fa; padding: 20px; margin-bottom: 30px; border-radius: 5px;">
						<h4 style="color: #333; margin-bottom: 15px;">Booking Summary</h4>
						<div class="row">
							<div class="col-md-6">
								<p><strong>Booking ID:</strong> #<?php echo $booking['id']; ?></p>
								<p><strong>Theme:</strong> <?php echo $booking['thm_nm']; ?></p>
								<p><strong>Event Date:</strong> <?php echo $booking['date']; ?></p>
							</div>
							<div class="col-md-6">
								<p><strong>Total Amount:</strong> ৳<?php echo number_format($booking['price']); ?></p>
								<p><strong>Already Paid:</strong> ৳<?php echo number_format($booking['advance_paid']); ?></p>
								<p><strong>Remaining:</strong> ৳<?php echo number_format($booking['price'] - $booking['advance_paid']); ?></p>
							</div>
						</div>
					</div>
					
					<!-- Payment Form -->
					<form class="form-horizontal" action="" method="post">
						<div class="form-group">
							<label for="advance_amount" class="col-sm-2 control-label">Advance Amount</label>
							<div class="col-sm-8">
								<input type="number" class="form-control1" name="advance_amount" id="advance_amount" 
									placeholder="Enter advance amount" min="1450" 
									max="<?php echo $booking['price'] - $booking['advance_paid']; ?>" required>
								<small style="color: #666;">Minimum advance: ৳1,450</small>
							</div>
						</div>
						
						<div class="form-group">
							<label for="payment_method" class="col-sm-2 control-label">Payment Method</label>
							<div class="col-sm-8">
								<select class="form-control1" name="payment_method" id="payment_method" required>
									<option value="">Select Payment Method</option>
									<option value="credit_card">Credit Card</option>
									<option value="debit_card">Debit Card</option>
									<option value="upi">UPI</option>
									<option value="net_banking">Net Banking</option>
									<option value="wallet">Digital Wallet</option>
								</select>
							</div>
						</div>
						
						<!-- Payment Gateway Simulation -->
						<div id="payment_details" style="display: none; background: #e9ecef; padding: 20px; margin: 20px 0; border-radius: 5px;">
							<h5 style="color: #333;">Payment Gateway (Demo)</h5>
							<p style="color: #666; margin-bottom: 15px;">This is a demo payment gateway. In production, integrate with actual payment providers.</p>
							
							<div class="form-group">
								<label class="col-sm-2 control-label">Card Number</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1" placeholder="1234 5678 9012 3456" maxlength="19">
								</div>
							</div>
							
							<div class="form-group">
								<label class="col-sm-2 control-label">Expiry Date</label>
								<div class="col-sm-4">
									<input type="text" class="form-control1" placeholder="MM/YY" maxlength="5">
								</div>
								<label class="col-sm-2 control-label">CVV</label>
								<div class="col-sm-4">
									<input type="text" class="form-control1" placeholder="123" maxlength="3">
								</div>
							</div>
						</div>
						
						<div class="contact-w3form" align="center" style="margin-top: 10px;">
							<input type="submit" name="submit" class="btn btn-proceed" 
								   style="background: linear-gradient(45deg, #28a745, #20c997); 
								          color: white; 
								          padding: 8px 16px; 
								          margin-right: 10px; 
								          border-radius: 4px; 
								          border: none;
								          font-weight: 600;
								          font-size: 12px;
								          cursor: pointer;
								          box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
								          transition: all 0.3s ease;
								          display: inline-block;"
								   onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(40, 167, 69, 0.4)'"
								   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(40, 167, 69, 0.3)'" 
								   value="💳 PAY">
							<a href="booking_history.php" class="btn btn-cancel" 
							   style="background: linear-gradient(45deg, #6c757d, #5a6268); 
							          color: white; 
							          padding: 8px 16px; 
							          border-radius: 4px; 
							          text-decoration: none; 
							          font-weight: 600;
							          font-size: 12px;
							          box-shadow: 0 2px 6px rgba(108, 117, 125, 0.3);
							          transition: all 0.3s ease;
							          display: inline-block;"
							   onmouseover="this.style.transform='translateY(-1px)'; this.style.boxShadow='0 4px 8px rgba(108, 117, 125, 0.4)'"
							   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 6px rgba(108, 117, 125, 0.3)'">
								❌ CANCEL
							</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
// Show payment details when payment method is selected
document.getElementById('payment_method').addEventListener('change', function() {
	var paymentDetails = document.getElementById('payment_details');
	if(this.value && (this.value === 'credit_card' || this.value === 'debit_card')) {
		paymentDetails.style.display = 'block';
	} else {
		paymentDetails.style.display = 'none';
	}
});

// Format card number input
document.querySelector('input[placeholder="1234 5678 9012 3456"]').addEventListener('input', function() {
	this.value = this.value.replace(/\s/g, '').replace(/(.{4})/g, '$1 ').trim();
});

// Format expiry date input
document.querySelector('input[placeholder="MM/YY"]').addEventListener('input', function() {
	this.value = this.value.replace(/\D/g, '').replace(/(.{2})/, '$1/');
});
</script>

<?php 
	include_once("footer.php");
?>
