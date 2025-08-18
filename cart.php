<?php
	include('Database/connect.php');
	include('session.php');		
	include("header.php");
	
	// Check if user is logged in
	if(!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
		echo "<script>alert('Please login first to complete your booking.'); window.location='login.php';</script>";
		exit;
	}
	
	$user_id = $_SESSION['user_id'];
	$user_email = $_SESSION['email'];
	
	// Get items from temp table for this specific user
	$q=mysqli_query($con,"SELECT * FROM temp WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 1");
	
	if(mysqli_num_rows($q) == 0) {
		echo "<script>alert('Your cart is empty. Please select an event first.'); window.location='gallery.php';</script>";
		exit;
	}
	
	$f=mysqli_fetch_row($q);
	$id=$f[0];
	$image=$f[1];
	$name=$f[2];
	$price=$f[3];
	
	if(isset($_POST['submit']))
	{
		$customer_name = mysqli_real_escape_string($con, trim($_POST['nm']));
		$customer_email = mysqli_real_escape_string($con, trim($_POST['email']));
		$mobile = mysqli_real_escape_string($con, trim($_POST['mo']));
		$event_date = mysqli_real_escape_string($con, trim($_POST['date']));
		
		// Validate required fields
		if (empty($customer_name) || empty($customer_email) || empty($mobile) || empty($event_date)) {
			echo "<script>alert('Please fill in all required fields.');</script>";
			return;
		}
		
		// Validate email format
		if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
			echo "<script>alert('Please enter a valid email address.');</script>";
			return;
		}
		
		// Validate event date (must be in the future)
		$selected_date = strtotime($event_date);
		$today = strtotime(date('Y-m-d'));
		if($selected_date <= $today) {
			echo "<script>alert('Event date must be in the future.');</script>";
			return;
		}
		
		// Get current item from temp table for this user
		$temp_query = mysqli_query($con,"SELECT * FROM temp WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 1");
		$temp_item = mysqli_fetch_array($temp_query);
		
		if($temp_item) {
			$temp_id = $temp_item['id'];
			$theme_image = $temp_item['img'];
			$theme_name = $temp_item['nm'];
			$theme_price = $temp_item['price'];
			
			// Insert booking with complete information
			$booking_query = "INSERT INTO booking(
				nm, email, mo, theme, thm_nm, price, date, user_id, 
				remaining_amount, booking_status, payment_status, booking_date
			) VALUES(
				'$customer_name', '$user_email', '$mobile', '$theme_image', 
				'$theme_name', '$theme_price', '$event_date', '$user_id', 
				'$theme_price', 'pending', 'unpaid', NOW()
			)";
			
			$booking_result = mysqli_query($con, $booking_query);
			
			if($booking_result) {
				// Clear this user's temp data after successful booking
				mysqli_query($con,"DELETE FROM temp WHERE user_id='$user_id'");
				
				echo "<script>alert('🎉 Your Event is Successfully Booked!\\n\\nBooking Details:\\n- Theme: $theme_name\\n- Price: ৳" . number_format($theme_price) . "\\n- Event Date: $event_date\\n\\nYou can now make an advance payment to confirm your booking.');</script>";
				echo '<script type="text/javascript">window.location="booking_history.php";</script>';
			}
			else
			{
				echo "<script>alert('Booking failed: " . mysqli_error($con) . "\\nPlease try again.');</script>";
			}
		} else {
			echo "<script>alert('No items found in cart. Please select an event first.');</script>";
			echo '<script type="text/javascript">window.location="gallery.php";</script>';
		}
	}		
	
	// Get current cart item for display
	$qry=mysqli_query($con,"SELECT * FROM temp WHERE user_id='$user_id' ORDER BY created_at DESC LIMIT 1");
	$row=mysqli_fetch_row($qry);	
?>

<script>
$(function()
{
	$("#datepicker").datepicker
	({
		changeMonth:true,
		changeYear:true
	});
});
</script>

<div class="codes">
<div class="container"> 
<h3 class='w3ls-hdg' align="center">BOOKING</h3>
<div class="grid_3 grid_4">
				<div class="tab-content">
					<div class="tab-pane active" id="horizontal-form">
						<form class="form-horizontal" action="" method="post" enctype="multipart/form-data">							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1" name="nm" id="focusedinput" placeholder="Name" required maxlength="50">
								</div>
							</div>
							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm">Email</label>
								<div class="col-sm-8">
									<input type="email" class="form-control1 input-sm" name="email" id="smallinput" 
										   value="<?php echo $_SESSION['email']; ?>" placeholder="Email" required maxlength="100" readonly 
										   style="background-color: #f8f9fa;">
									<small style="color: #666;">Your registered email address</small>
								</div>
							</div>
							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm">Mobile no</label>
								<div class="col-sm-8">
									<input type="tel" class="form-control1 input-sm" name="mo" id="smallinput" placeholder="Mobile no" required maxlength="15"/>
								</div>
							</div>
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">Your Theme :</label>
								<div class="col-sm-8">
								<img src="./images/<?php echo $row[1]; ?>" height="200"  width="300"/></div>		
							</div>
							<div class="form-group">
								<label for="disabledinput" class="col-sm-2 control-label">Theme Name :</label>
								<div class="col-sm-8">
									<input disabled="" type="text" class="form-control1" value="<?php echo $row[2]; ?>" name="price" id="focusedinput" placeholder="Theme Price" >
								</div>
							</div>
							<div class="form-group">
								<label for="disabledinput" class="col-sm-2 control-label">Theme Price :</label>
								<div class="col-sm-8">
								<input disabled="" type="text" class="form-control1" value="<?php echo $row[3]; ?>" name="price" id="focusedinput" placeholder="Theme Price" >
								</div>
							</div>
							<div class="form-group">
								<label for="smallinput" class="col-sm-2 control-label label-input-sm">Event Date</label>
								<div class="col-sm-8">
									<input type="date" class="form-control1 input-sm" name="date" id="smallinput" placeholder="DD/MM/YYYY" required/>
								</div>
							</div>
					<div class="contact-w3form" align="center">
					<a href="book.php">
					<input type="submit" name="submit" class="btn"  value="BOOK"></a>
					</div>
					</form>
					</div>
				</div>
			</div>
		</div>
	</div>
	</div>
<?php 
	include_once("footer.php");
?>