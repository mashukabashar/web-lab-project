<?php
	include_once("Database/connect.php");
	if(isset($_POST['submit']))
	{
		session_start();
		$uname=$_POST['unm'];
		$pswd=$_POST['pswd'];
		
		// Check login credentials (removed all validation)
		$qr=mysqli_query($con,"select * from login where unm='$uname' and pswd='$pswd'");
		if(mysqli_num_rows($qr))
		{
			// Get user details from registration table
			$user_query = mysqli_query($con,"select * from registration where unm='$uname'");
			$user_data = mysqli_fetch_assoc($user_query);
			
			if($user_data) {
				// Set all necessary session variables
				$_SESSION['uname'] = $uname;
				$_SESSION['email'] = $user_data['email'];
				$_SESSION['user_id'] = $user_data['id'];
				$_SESSION['full_name'] = trim($user_data['nm'] . ' ' . $user_data['surnm']);
				
				echo "<script> alert('Login Successful! Welcome " . $user_data['nm'] . "'); window.location.assign('dashboard.php');</script>";
			} else {
				// If no registration data found, set basic session info from login table
				$_SESSION['uname'] = $uname;
				$_SESSION['email'] = $uname; // Assuming username is email
				$_SESSION['user_id'] = 0; // Default value
				$_SESSION['full_name'] = $uname; // Fallback to username
				
				echo "<script> alert('Login Successful! Welcome'); window.location.assign('dashboard.php');</script>";
			}
		}	
		else
		{
			echo "<script> alert('Username or password does not exist');</script>";
		}
	}
	include_once("header.php");
?>
<div class="banner about-bnr">
		<div class="container">
		</div>
	</div>
	<div class="codes">
		<div class="container"> 
		<h2 class="w3ls-hdg" align="center">User Login</h2>
				  
	<div class="grid_3 grid_4">
				<div class="tab-content">
					<div class="tab-pane active" id="horizontal-form">
						<form class="form-horizontal" action="" method="post">
							<div class="form-group">
								<label for="focusedinput" class="col-sm-2 control-label">User Name</label>
								<div class="col-sm-8">
									<input type="text" class="form-control1"  name="unm" id="focusedinput" placeholder="User Name">
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword" class="col-sm-2 control-label">Password</label>
								<div class="col-sm-8">
									<input type="password" class="form-control1" name="pswd" id="inputPassword" placeholder="Password">
								</div>
							</div>
							<div class="contact-w3form" align="center">
					<input type="submit" name="submit" value="SEND">
					</div>
						</form><br/>
						<div align="center"><h5>Not an account? <a href="registration.php">Registration Here</a></h5></div>
						</div>
					</div>
				</div>
			</div>
		</div>
				<?php
				include_once("footer.php");
			?>