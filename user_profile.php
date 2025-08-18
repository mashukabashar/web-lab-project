<?php
	include('Database/connect.php');
	include('session.php');		
	include("header.php");
	
	$user_email = $_SESSION['email'];
	$user_id = $_SESSION['user_id'];
	
	// Get current user data
	$user_query = "SELECT * FROM registration WHERE id = '$user_id'";
	$user_result = mysqli_query($con, $user_query);
	$user_data = mysqli_fetch_assoc($user_result);
	
	if(isset($_POST['submit'])) {
		$name = $_POST['nm'];
		$surname = $_POST['surnm'];
		$mobile = $_POST['mo'];
		$address = $_POST['adrs'];
		
		$update_query = "UPDATE registration SET 
			nm = '$name', 
			surnm = '$surname', 
			mo = '$mobile', 
			adrs = '$address'
			WHERE id = '$user_id'";
		
		if(mysqli_query($con, $update_query)) {
			// Update session data
			$_SESSION['full_name'] = $name . ' ' . $surname;
			echo "<script>alert('Profile updated successfully!'); window.location='dashboard.php';</script>";
		} else {
			echo "<script>alert('Error updating profile. Please try again.');</script>";
		}
	}
?>

<div class="banner about-bnr">
	<div class="container">
	</div>
</div>

<div class="codes">
	<div class="container"> 
		<h2 class="w3ls-hdg" align="center">Edit Profile</h2>
		<p align="center" style="color: #666; margin-bottom: 30px;">Update your personal information</p>
		
		<div class="grid_3 grid_4">
			<div class="tab-content">
				<div class="tab-pane active">
					<form class="form-horizontal" action="" method="post">
						<div class="form-group">
							<label for="focusedinput" class="col-sm-2 control-label">Name</label>
							<div class="col-sm-8">
								<input type="text" class="form-control1" name="nm" id="focusedinput" 
									value="<?php echo $user_data['nm']; ?>" placeholder="Name" required>
							</div>
						</div>
						
						<div class="form-group">
							<label for="focusedinput" class="col-sm-2 control-label">Surname</label>
							<div class="col-sm-8">
								<input type="text" class="form-control1" name="surnm" id="focusedinput" 
									value="<?php echo $user_data['surnm']; ?>" placeholder="Surname" required>
							</div>
						</div>
						
						<div class="form-group">
							<label for="focusedinput" class="col-sm-2 control-label">Username</label>
							<div class="col-sm-8">
								<input type="text" class="form-control1" value="<?php echo $user_data['unm']; ?>" 
									placeholder="Username" readonly style="background: #f8f9fa;">
								<small style="color: #666;">Username cannot be changed</small>
							</div>
						</div>
						
						<div class="form-group">
							<label for="smallinput" class="col-sm-2 control-label label-input-sm">Email</label>
							<div class="col-sm-8">
								<input type="email" class="form-control1 input-sm" value="<?php echo $user_data['email']; ?>" 
									placeholder="Email" readonly style="background: #f8f9fa;">
								<small style="color: #666;">Email cannot be changed</small>
							</div>
						</div>
						
						<div class="form-group">
							<label for="smallinput" class="col-sm-2 control-label label-input-sm">Mobile no</label>
							<div class="col-sm-8">
								<input type="tel" class="form-control1 input-sm" name="mo" id="smallinput" 
									value="<?php echo $user_data['mo']; ?>" placeholder="Mobile no" required>
							</div>
						</div>
						
						<div class="form-group">
							<label for="txtarea1" class="col-sm-2 control-label">Address</label>
							<div class="col-sm-8">
								<textarea name="adrs" id="txtarea1" cols="50" rows="4" class="form-control1" 
									placeholder="Address"><?php echo $user_data['adrs']; ?></textarea>
							</div>
						</div>
						
						<div class="contact-w3form" align="center">
							<input type="submit" name="submit" class="btn" style="background: #28a745;" value="UPDATE PROFILE">
							<a href="dashboard.php" class="btn" style="background: #6c757d; margin-left: 10px;">CANCEL</a>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<?php 
	include_once("footer.php");
?>
