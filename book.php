<?php
	include_once("header.php");
	include_once('Database/connect.php');
	include_once("session.php");
	
	// Check if user is logged in
	if(!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
		echo "<script>alert('Please login first to book an event.'); window.location='login.php';</script>";
		exit;
	}
	
	// Get user information from session
	$user_id = $_SESSION['user_id'];
	$user_email = $_SESSION['email'];
	
	$q=mysqli_query($con,"select * from temp");
	$im="";
	$nm="";
	$pri=0;
	$r=mysqli_num_rows($q);
	
	if($r > 0) {
		while($res=mysqli_fetch_array($q))
		{
				$temp_id=$res[0];
				$im=$res[1];
				$nm=$res[2];
				$pri=$res[3];
				
				// Insert booking with proper user information
				$q1=mysqli_query($con,"INSERT INTO booking(
					nm, email, mo, theme, thm_nm, price, date, user_id, 
					remaining_amount, booking_status, payment_status, booking_date
				) VALUES(
					'', '$user_email', '', '$im', '$nm', '$pri', '', '$user_id', 
					'$pri', 'pending', 'unpaid', NOW()
				)");
				
				if($q1>0)
				{
					// Clear temp table after successful booking
					mysqli_query($con,"DELETE FROM temp WHERE id='$temp_id'");
					
					echo "<script>alert('Event added to your bookings! Please complete the booking details.');</script>";
					echo '<script type="text/javascript">window.location="cart.php";</script>';
				}
				else
				{
					echo "<script>alert('Booking failed. Please try again.');</script>";
					echo '<script type="text/javascript">window.location="gallery.php";</script>';
				}
		}
	} else {
		echo "<script>alert('No items in cart. Please select an event first.');</script>";
		echo '<script type="text/javascript">window.location="gallery.php";</script>';
	}

	include_once("footer.php");
?>