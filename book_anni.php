<?php 
	include("header.php");
	include("session.php");
	
	// Validate and sanitize input
	if(!isset($_GET['id']) || !is_numeric($_GET['id'])) {
		echo "<script>alert('Invalid event ID!'); window.location.assign('anni_gal.php');</script>";
		exit();
	}
	
	$id = intval($_GET['id']);	
	include('Database/connect.php');
	
	// Check if user is logged in
	if(!isset($_SESSION['user_id'])) {
		echo "<script>alert('Please login first!'); window.location.assign('login.php');</script>";
		exit();
	}
	
	if(isset($_POST['submit']))
	{
		$user_id = $_SESSION['user_id'];
		$list=mysqli_query($con,"select * from anniversary where id=".$id);
		
		if(!$list) {
			echo "<script>alert('Database error: " . mysqli_error($con) . "'); window.history.back();</script>";
			exit();
		}
		
		if($q=mysqli_fetch_assoc($list))
		{
			$event_id=$q['id'];
			$image=$q['img'];
			$name=$q['nm'];
			$price=$q['price'];
		} else {
			echo "<script>alert('Anniversary event not found!'); window.location.assign('anni_gal.php');</script>";
			exit();
		}
				// Clear only current user's temp data
				mysqli_query($con,"DELETE FROM temp WHERE user_id = '$user_id'");
				$qr1=mysqli_query($con,"insert into temp (id, img, nm, price, user_id, created_at) values('$event_id','$image','$name',$price,'$user_id',NOW())");
						if($qr1)
						{
							echo "<script> window.location.assign('cart.php');</script>";	
						}
						else
						{
							echo "<script>alert('Not added to cart: " . mysqli_error($con) . "');</script>";	
						}
					
					}
	?>

<?php
			$id = intval($_GET['id']);
			$list=mysqli_query($con,"select * from anniversary where id=".$id);
			
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
				<a href="anni_gal.php">BACK TO ANNIVERSARY</a>					
				</div> <form method="post">
				<div class="modal-body">
					<img src="images/<?php echo htmlspecialchars($q['img']); ?>" alt="img" height="300" width="545"> 
					<p>
					<br/><?php echo "Name : ".htmlspecialchars($q['nm']).""; ?><br/>
					<?php echo "Price : ৳".number_format($q['price']).""; ?><br/>
					<input type='submit' name='submit' value='BOOK NOW' class='btn my'/>
					</p>
					<?php } else { 
						echo "<div class='alert alert-danger'>Anniversary event not found!</div>";
					} ?>
					</form>
		</div> 
			</div>
		</div>
	</div><br/><br><br>
	<!-- //modal -->  
	<?php include("footer.php");
	?>