<?php
	session_start();
	// Clear all session variables
	$_SESSION = array();
	
	// Destroy the session
	session_destroy();
	
	// Clear the session cookie
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	
	echo "<script>alert('You have been successfully logged out.'); window.location='index.php';</script>";
?>