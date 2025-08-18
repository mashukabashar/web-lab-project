<?php 
	include("header.php");
	include('session.php');
	include('Database/connect.php');
	
	// Check if user is logged in
	if(!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
		echo "<script>alert('Please login first to book an event.'); window.location='login.php';</script>";
		exit;
	}
	
	// Check if ID is provided
	if(!isset($_GET['id']) || empty($_GET['id'])) {
		echo "<script>alert('Invalid wedding selection.'); window.location='gallery.php';</script>";
		exit;
	}
	
	$id = intval($_GET['id']);	
	
	if(isset($_POST['submit']))
	{
		$list=mysqli_query($con,"select * from wedding where id=".$id);
		
		if(!$list || mysqli_num_rows($list) == 0) {
			echo "<script>alert('Wedding not found.'); window.location='gallery.php';</script>";
			exit;
		}
							
		while($q=mysqli_fetch_assoc($list))
		{
			$wedding_id=$q['id'];
			$image=$q['img'];
			$name=$q['nm'];
			$price=$q['price'];
		}
		
		// Clear any existing temp data for this user
		$user_id = $_SESSION['user_id'];
		mysqli_query($con,"DELETE FROM temp WHERE user_id='$user_id'");
		
		// Insert new item into temp with user identification
		$qr1=mysqli_query($con,"INSERT INTO temp (id, img, nm, price, user_id, created_at) VALUES('$wedding_id','$image','$name',$price,'$user_id',NOW())");
		
		if($qr1)
		{
			echo "<script>alert('Wedding theme added to cart!'); window.location.assign('cart.php');</script>";	
		}
		else
		{
			echo "<script>alert('Failed to add to cart. Please try again.'); console.log('SQL Error: " . mysqli_error($con) . "');</script>";	
		}
	}
?>

<?php
	$id = intval($_GET['id']);
	$list=mysqli_query($con,"select * from wedding where id=".$id);
	
	if(!$list || mysqli_num_rows($list) == 0) {
		echo "<script>alert('Wedding not found.'); window.location='gallery.php';</script>";
		exit;
	}
				
	while($q=mysqli_fetch_assoc($list))
	{
?>
			
<!-- modal -->
<div role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">	
			<a href="wedding.php" style="color: #808080; text-decoration: none; font-weight: bold;">← BACK TO WEDDING GALLERY</a>					
			</div> 
			<form method="post">
			<div class="modal-body" style="text-align: center; padding: 30px;">
				<img src="images/<?php echo $q['img']; ?>" alt="img" style="height: 300px; width: 100%; max-width: 545px; object-fit: cover; border-radius: 8px; margin-bottom: 20px;"> 
				<div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
					<h4 style="color: #333; margin-bottom: 15px;"><?php echo $q['nm']; ?></h4>
					<p style="font-size: 18px; color: #28a745; font-weight: bold;">Price: ৳<?php echo number_format($q['price']); ?></p>
					<p style="color: #666; margin-top: 10px;">Click "Add to Cart" to proceed with booking this wedding theme.</p>
				</div>
				<input type='submit' name='submit' value='ADD TO CART' 
					   class='btn' 
					   style='background: linear-gradient(45deg, #28a745, #20c997); 
					          color: white; 
					          padding: 12px 30px; 
					          border: none; 
					          border-radius: 6px; 
					          font-weight: 600; 
					          cursor: pointer; 
					          transition: all 0.3s ease;'
					   onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 5px 15px rgba(40, 167, 69, 0.4)'"
					   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'"/>
			</div>
			</form>
			<?php } ?>
		</div> 
	</div>
</div><br/><br><br>
<!-- //modal -->  
<?php include("footer.php"); ?>