<?php
				include_once('../Database/connect.php');
				include_once('session.php');
				include_once("header.php");
				
				// First, check if booking_status column exists, if not add it
				$check_column_query = "SHOW COLUMNS FROM booking LIKE 'booking_status'";
				$column_result = mysqli_query($con, $check_column_query);
				
				if(mysqli_num_rows($column_result) == 0) {
					// Add the booking_status column with default value
					$add_column_query = "ALTER TABLE booking ADD COLUMN booking_status varchar(20) DEFAULT 'pending'";
					mysqli_query($con, $add_column_query);
				}
				
				// Update any existing records that have NULL booking_status
				$update_null_status = "UPDATE booking SET booking_status = 'pending' WHERE booking_status IS NULL OR booking_status = ''";
				mysqli_query($con, $update_null_status);
				
				$list=mysqli_query($con,"select * from booking ORDER BY booking_date DESC");
				echo "<div class='codes'>
				<div class='container'>
				<h3 class='w3ls-hdg' align='center'>Booking Requests</h3>
				<div class='grid_3 grid_5 '><br/>
					<table class='table table-bordered' >
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Email</th>
								<th>Mobile</th>
								<th>Theme</th>
								<th>Price</th>
								<th>Event Date</th>
								<th>Status</th>
								<th>Actions</th>
							</tr>
						</thead>";
						
				while($q = mysqli_fetch_assoc($list))
				{
					// Set default status if null or empty
					if(is_null($q['booking_status']) || $q['booking_status'] == '') {
						$q['booking_status'] = 'pending';
					}
					
					$status_color = '';
					if($q['booking_status'] == 'pending') {
						$status_color = 'style="color: orange; font-weight: bold;"';
					} else if($q['booking_status'] == 'approved') {
						$status_color = 'style="color: green; font-weight: bold;"';
					} else if($q['booking_status'] == 'rejected') {
						$status_color = 'style="color: red; font-weight: bold;"';
					}
					
					echo '<tbody><tr> 
						<td>'.$q['id'].'</td>
						<td>'.$q['nm'].'</td>
						<td>'.$q['email'].'</td>
						<td>'.$q['mo'].'</td>
						<td><img src="../images/'.$q['theme'].'" height="60" width="80" style="object-fit: cover;"><br><small>'.$q['thm_nm'].'</small></td>
						<td>৳'.number_format($q['price']).'</td>
						<td>'.$q['date'].'</td>
						<td><span '.$status_color.'>'.ucfirst($q['booking_status']).'</span></td>
						<td>';
						
					if($q['booking_status'] == 'pending') {
						echo '<a href="approve_booking.php?id='.$q['id'].'&action=approve" onClick="return confirm(\'Approve this booking?\')" style="color: green;"><u>Approve</u></a> | ';
						echo '<a href="approve_booking.php?id='.$q['id'].'&action=reject" onClick="return confirm(\'Reject this booking?\')" style="color: red;"><u>Reject</u></a> | ';
					} else {
						// Show change status options for processed bookings
						if($q['booking_status'] == 'approved') {
							echo '<a href="approve_booking.php?id='.$q['id'].'&action=force_reject" onClick="return confirm(\'Change status to REJECTED?\')" style="color: red;"><u>Reject</u></a> | ';
						} else if($q['booking_status'] == 'rejected') {
							echo '<a href="approve_booking.php?id='.$q['id'].'&action=force_approve" onClick="return confirm(\'Change status to APPROVED?\')" style="color: green;"><u>Approve</u></a> | ';
						}
						echo '<a href="approve_booking.php?id='.$q['id'].'&action=reset" onClick="return confirm(\'Reset to PENDING status?\')" style="color: orange;"><u>Reset</u></a> | ';
					}
				?>
				<a href="delete_book.php?id=<?php echo $q['id'];?>" onClick="return confirm('Delete this booking?')" style="color: #333;"><u>Delete</u></a></td></tr><?php } ?>
				</tbody></table></div></div></div>";
		<?php
				include_once("footer.php");
?>