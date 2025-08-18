<?php
@session_start();

// Check if user is logged in
if(!isset($_SESSION['uname']))
{
	echo "<script> window.location.assign('login.php');</script>";	
	exit;
}

// Include database connection for session repair if needed
include_once(__DIR__ . '/Database/connect.php');

// Repair session if needed - ensure all required session variables are set
if(!isset($_SESSION['email']) || !isset($_SESSION['user_id']) || !isset($_SESSION['full_name'])) {
	$uname = $_SESSION['uname'];
	$user_query = mysqli_query($con,"select * from registration where unm='$uname'");
	$user_data = mysqli_fetch_assoc($user_query);
	
	if($user_data) {
		$_SESSION['email'] = $user_data['email'];
		$_SESSION['user_id'] = $user_data['id'];
		$_SESSION['full_name'] = trim($user_data['nm'] . ' ' . $user_data['surnm']);
	} else {
		// Fallback for users without registration data
		$_SESSION['email'] = $_SESSION['uname']; // Assume username is email
		$_SESSION['user_id'] = 0;
		$_SESSION['full_name'] = $_SESSION['uname'];
	}
}
?>