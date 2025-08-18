<?php
	include('Database/connect.php');
	include('session.php');
	
	$booking_id = $_GET['booking_id'];
	$user_email = $_SESSION['email'];
	
	// Get booking details with payment information and verify access
	$query = "SELECT b.*, p.transaction_id, p.payment_date, p.amount as paid_amount 
		FROM booking b 
		LEFT JOIN payments p ON b.id = p.booking_id 
		WHERE b.id = '$booking_id' AND b.email = '$user_email'";
	$result = mysqli_query($con, $query);
	$booking = mysqli_fetch_assoc($result);
	
	if(!$booking) {
		echo "<script>alert('Invoice not found or access denied'); window.location='booking_history.php';</script>";
		exit;
	}
	
	// Set headers for PDF download
	header('Content-Type: text/html');
	header('Content-Disposition: inline; filename="invoice_'.$booking_id.'.html"');
?>

<!DOCTYPE html>
<html>
<head>
	<title>Invoice #<?php echo $booking_id; ?></title>
	<style>
		body {
			font-family: Arial, sans-serif;
			margin: 0;
			padding: 20px;
			background: #fff;
		}
		.invoice-container {
			max-width: 800px;
			margin: 0 auto;
			background: #fff;
			padding: 30px;
			border: 1px solid #ddd;
		}
		.header {
			text-align: center;
			margin-bottom: 30px;
			border-bottom: 2px solid #808080;
			padding-bottom: 20px;
		}
		.company-name {
			font-size: 28px;
			font-weight: bold;
			color: #333;
			margin-bottom: 5px;
		}
		.company-details {
			color: #666;
			font-size: 14px;
		}
		.invoice-title {
			font-size: 24px;
			color: #808080;
			margin: 20px 0;
		}
		.details-section {
			margin: 20px 0;
		}
		.details-row {
			display: flex;
			justify-content: space-between;
			margin: 10px 0;
			padding: 8px 0;
			border-bottom: 1px solid #eee;
		}
		.details-label {
			font-weight: bold;
			color: #333;
		}
		.details-value {
			color: #666;
		}
		.amount-section {
			background: #f8f9fa;
			padding: 20px;
			margin: 20px 0;
			border-radius: 5px;
		}
		.total-amount {
			font-size: 18px;
			font-weight: bold;
			color: #28a745;
		}
		.footer {
			margin-top: 40px;
			text-align: center;
			color: #666;
			font-size: 12px;
			border-top: 1px solid #ddd;
			padding-top: 20px;
		}
		.status-badge {
			padding: 5px 10px;
			border-radius: 3px;
			color: white;
			font-size: 12px;
		}
		.status-confirmed { background: #28a745; }
		.status-pending { background: #ffc107; }
		.status-paid { background: #28a745; }
		.status-unpaid { background: #dc3545; }
		.status-partial { background: #17a2b8; }
		
		@media print {
			body { margin: 0; }
			.no-print { display: none; }
		}
	</style>
</head>
<body>
	<div class="invoice-container">
		<!-- Header -->
		<div class="header">
			<div class="company-name">EVENTEASE</div>
			<div class="company-details">
				Professional Event Management Services<br>
				Phone: +91 90333 36811 | Email: info@eventease.com
			</div>
		</div>
		
		<!-- Invoice Title -->
		<div class="invoice-title">
			INVOICE #<?php echo str_pad($booking_id, 6, '0', STR_PAD_LEFT); ?>
		</div>
		
		<!-- Customer Details -->
		<div class="details-section">
			<h3 style="color: #333; margin-bottom: 15px;">Customer Details</h3>
			<div class="details-row">
				<span class="details-label">Name:</span>
				<span class="details-value"><?php echo $booking['nm']; ?></span>
			</div>
			<div class="details-row">
				<span class="details-label">Email:</span>
				<span class="details-value"><?php echo $booking['email']; ?></span>
			</div>
			<div class="details-row">
				<span class="details-label">Mobile:</span>
				<span class="details-value"><?php echo $booking['mo']; ?></span>
			</div>
		</div>
		
		<!-- Event Details -->
		<div class="details-section">
			<h3 style="color: #333; margin-bottom: 15px;">Event Details</h3>
			<div class="details-row">
				<span class="details-label">Theme:</span>
				<span class="details-value"><?php echo $booking['thm_nm']; ?></span>
			</div>
			<div class="details-row">
				<span class="details-label">Event Date:</span>
				<span class="details-value"><?php echo $booking['date']; ?></span>
			</div>
			<div class="details-row">
				<span class="details-label">Booking Date:</span>
				<span class="details-value"><?php echo date('d/m/Y H:i', strtotime($booking['booking_date'])); ?></span>
			</div>
			<div class="details-row">
				<span class="details-label">Status:</span>
				<span class="details-value">
					<span class="status-badge status-<?php echo $booking['booking_status']; ?>">
						<?php echo ucfirst($booking['booking_status']); ?>
					</span>
				</span>
			</div>
		</div>
		
		<!-- Payment Details -->
		<div class="amount-section">
			<h3 style="color: #333; margin-bottom: 15px;">Payment Summary</h3>
			<div class="details-row">
				<span class="details-label">Total Amount:</span>
				<span class="details-value">৳<?php echo number_format($booking['price']); ?></span>
			</div>
			<div class="details-row">
				<span class="details-label">Advance Paid:</span>
				<span class="details-value">৳<?php echo number_format($booking['advance_paid']); ?></span>
			</div>
			<div class="details-row">
				<span class="details-label">Remaining Amount:</span>
				<span class="details-value">৳<?php echo number_format($booking['remaining_amount']); ?></span>
			</div>
			<div class="details-row">
				<span class="details-label">Payment Status:</span>
				<span class="details-value">
					<span class="status-badge status-<?php echo $booking['payment_status']; ?>">
						<?php echo ucfirst($booking['payment_status']); ?>
					</span>
				</span>
			</div>
			<?php if($booking['transaction_id']): ?>
			<div class="details-row">
				<span class="details-label">Transaction ID:</span>
				<span class="details-value"><?php echo $booking['transaction_id']; ?></span>
			</div>
			<?php endif; ?>
		</div>
		
		<!-- Terms & Conditions -->
		<div class="details-section">
			<h4 style="color: #333;">Terms & Conditions:</h4>
			<ul style="color: #666; font-size: 12px; line-height: 1.6;">
				<li>Full payment must be completed 7 days before the event date.</li>
				<li>Cancellation charges apply as per company policy.</li>
				<li>Any changes to the event details must be communicated 48 hours in advance.</li>
				<li>Company is not liable for any delays due to unforeseen circumstances.</li>
			</ul>
		</div>
		
		<!-- Footer -->
		<div class="footer">
			<p>Thank you for choosing EventEase!</p>
			<p>Generated on: <?php echo date('d/m/Y H:i:s'); ?></p>
		</div>
		
		<!-- Print/Download Buttons -->
		<div class="no-print" style="text-align: center; margin-top: 30px;">
			<button onclick="window.print()" style="background: #808080; color: white; padding: 10px 20px; border: none; border-radius: 3px; margin: 5px; cursor: pointer;">Print Invoice</button>
			<button onclick="window.close()" style="background: #6c757d; color: white; padding: 10px 20px; border: none; border-radius: 3px; margin: 5px; cursor: pointer;">Close</button>
		</div>
	</div>
</body>
</html>
