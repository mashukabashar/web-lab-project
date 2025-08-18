<?php 
	include("header.php");
	include("session.php");
	
	// Validate and sanitize input
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		echo "<script>alert('Invalid event ID!'); window.location.assign('bday_gal.php');</script>";
		exit();
	}
	
	$id = intval($_GET['id']);	
	include('Database/connect.php');
	
	// Check if user is logged in
	if(!isset($_SESSION['user_id'])) {
		echo "<script>alert('Please login first!'); window.location.assign('login.php');</script>";
		exit();
	}
	
	// Form submission functionality removed
	?>

<?php
			$id = intval($_GET['id']);
			$list=mysqli_query($con,"select * from birthday where id=".$id);
			
			if(!$list) {
				echo "<div class='alert alert-danger'>Database error: " . mysqli_error($con) . "</div>";
				exit();
			}
			
			if($q=mysqli_fetch_assoc($list))
			{
			?>
			
<!-- modal -->
	<div role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">	
				<a href="bday_gal.php">BACK TO BIRTHDAY</a>					
				</div>
				<div class="modal-body">
					<img src="images/<?php echo htmlspecialchars($q['img']); ?>" alt="img" height="300" width="545"> 
					<p>
					<br/><?php echo "Name : ".htmlspecialchars($q['nm']).""; ?><br/>
					<?php echo "Price : ৳".number_format($q['price']).""; ?><br/>
					</p>
					<?php } else { 
						echo "<div class='alert alert-danger'>Birthday event not found!</div>";
					} ?>>
		</div> 
			</div>
		</div>
	</div><br/><br><br>
	<!-- //modal -->  
	<?php include("footer.php");
	?>