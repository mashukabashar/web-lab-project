<?php
	@session_start();
	if(!isset($_SESSION['admin']))
	{
		header("location:login.php");
		exit;
	}
?>
<!--
Author: EVENTEASE
-->
<!DOCTYPE html>
<html lang="en">
<head>
<title>EventEase</title>
<!-- meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Light Fixture Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
	SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //meta tags -->
<!-- Custom Theme files -->
<link href="../css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
<link href="../css/style.css" type="text/css" rel="stylesheet" media="all">
<link rel="stylesheet" href="../css/flexslider.css" type="text/css" media="screen" />
<link href="../css/font-awesome.css" rel="stylesheet"> 
<!-- //Custom Theme files -->
<!-- js -->
<script src="js/jquery-1.11.1.min.js"></script> 
<!-- //js --> 
<!-- web fonts -->
<link href="//fonts.googleapis.com/css?family=Abel" rel="stylesheet">
<link href="//fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
<!-- //web fonts -->
</head>
<body>
	<!-- header -->
	<div class="header">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-header navbar-left">
										<h1 style="font-family: 'Roboto', sans-serif; font-weight: 700; color: #b9722d; font-size: 2.5em; margin: 0; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">
						<i class="fa fa-calendar-check-o" style="margin-right: 10px; color: #17a2b8;"></i>
						EventEase

					</h1>
				</div>
				
				<!-- navigation --> 
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">EventEase Menu</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="header-right">
					<div class="agileits-topnav">
						<ul>
							<a href="logout.php"><div align="right"><button class="btn warning">LOG OUT</button></div></a>
								<div class="clearfix"> </div> 
							</ul>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">					
						<ul class="nav navbar-nav navbar-left">
							<li><a href="index.php" class="link link--yaku"><span>H</span><span>O</span><span>M</span><span>E</span></a></li>
							<li><a href="#" class="dropdown-toggle link link--yaku" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span>S</span><span>E</span><span>R</span><span>V</span><span>I</span><span>C</span><span>E</span><span>S</span><span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="add_wed.php" class="link link--yaku"><span>W</span><span>E</span><span>D</span><span>D</span><span>I</span><span>N</span><span>G</span></a></li>
									<li><a href="add_birthd.php" class="link link--yaku"><span>B</span><span>I</span><span>R</span><span>T</span><span>H</span><span>D</span><span>A</span><span>Y</span> <span>P</span><span>A</span><span>R</span><span>T</span><span>Y</span></a></li>
									<li><a href="add_anni.php" class="link link--yaku"><span>A</span><span>N</span><span>N</span><span>I</span><span>V</span><span>A</span><span>R</span><span>S</span><span>A</span><span>R</span><span>Y</span></a></li>
									<li><a href="add_other.php" class="link link--yaku"><span>O</span><span>T</span><span>H</span><span>E</span><span>R</span> <span>E</span><span>V</span><span>E</span><span>N</span><span>T</span><span>S</span></a></li>
								</ul>
							</li>
							<li>
								<a href="view_order.php" class="link link--yaku" style="position: relative;">
									<span>B</span><span>O</span><span>O</span><span>K</span><span>I</span><span>N</span><span>G</span> <span>R</span><span>E</span><span>Q</span><span>U</span><span>E</span><span>S</span><span>T</span><span>S</span>
									<?php
									include_once("../Database/connect.php");
									$pending_count_query = "SELECT COUNT(*) as count FROM booking WHERE booking_status = 'pending'";
									$pending_result = mysqli_query($con, $pending_count_query);
									$pending_count = mysqli_fetch_assoc($pending_result)['count'];
									if($pending_count > 0):
									?>
									<span style="position: absolute; top: -8px; right: -8px; background: #dc3545; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px; min-width: 18px; text-align: center;"><?php echo $pending_count; ?></span>
									<?php endif; ?>
								</a>
							</li>
							<li><a href="view_feedback.php" class="link link--yaku"><span>F</span><span>E</span><span>E</span><span>D</span> <span>B</span><span>A</span><span>C</span><span>K</span></a></li>
						</ul>		
						<div class="clearfix"> </div>
					</div><!-- //navigation -->
				</div>
				<div class="clearfix"> </div>
			</div>	
		</nav>		
	</div>	
	<!-- //header -->
