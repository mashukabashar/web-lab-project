<!--
Author: EVENTEASE
-->
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- <link rel="shortcut icon" href="images/logo.png"> -->
        
<title>EventEase</title>
<!-- meta tags -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Light Fixture Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
	SmartPhone Compatible web template, free WebDesigns for Nokia, Samsung, LG, Sony Ericsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- //meta tags -->
<!-- Custom Theme files -->
<link href="css/bootstrap.css" type="text/css" rel="stylesheet" media="all">
<link href="css/style.css" type="text/css" rel="stylesheet" media="all">
<link rel="stylesheet" href="css/flexslider.css" type="text/css" media="screen" />
<link href="css/font-awesome.css" rel="stylesheet"> 
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
							<li><span class="glyphicon glyphicon-earphone"></span> +0124578965</li>
							<li><a class="email-link" href="mailto:example@mail.com"> <span class="glyphicon glyphicon-envelope"></span> info@eventease.in </a></li>
							<li class="social-icons"> 
							<?php
							@session_start();
							if(isset($_SESSION['uname']))
							{
								echo "<a href='dashboard.php'><button class='btn' style='background: #b9722d; color: white; margin-right: 5px; padding: 8px 15px; border: none; border-radius: 5px;'><i class='fa fa-tachometer'></i> DASHBOARD</button></a> ";
								echo "<a href='gallery.php'><button class='btn' style='background: #17a2b8; color: white; margin-right: 5px; padding: 8px 15px; border: none; border-radius: 5px;'><i class='fa fa-plus-circle'></i> BOOK EVENT</button></a> ";
								echo "<a href='booking_history.php'><button class='btn' style='background: #28a745; color: white; margin-right: 5px; padding: 8px 15px; border: none; border-radius: 5px;'><i class='fa fa-list-alt'></i> MY BOOKINGS</button></a> ";
								echo "<a href='logout.php'><button class='btn' style='background: #dc3545; color: white; padding: 8px 15px; border: none; border-radius: 5px;'><i class='fa fa-sign-out'></i> LOGOUT</button></a>";
							}
							else
							{
								echo "<a href='registration.php'><button class='btn' style='background: #28a745; color: white; margin-right: 5px; padding: 8px 15px; border: none; border-radius: 5px;'><i class='fa fa-user-plus'></i> SIGN UP</button></a> ";
								echo "<a href='login.php'><button class='btn' style='background: #b9722d; color: white; padding: 8px 15px; border: none; border-radius: 5px;'><i class='fa fa-sign-in'></i> LOGIN</button></a>";
							}
							?>
								<div class="clearfix"> </div> 
							</li>
						</ul>
					</div>
					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">					
						<ul class="nav navbar-nav navbar-left">
							<li><a href="index.php" class="link link--yaku"><span>H</span><span>O</span><span>M</span><span>E</span></a></li>
							<li><a href="about.php" class="link link--yaku"><span>A</span><span>B</span><span>O</span><span>U</span><span>T</span> <span>U</span><span>S</span></a></li>
							<li><a href="#" class="dropdown-toggle link link--yaku" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span>S</span><span>E</span><span>R</span><span>V</span><span>I</span><span>C</span><span>E</span><span>S</span><span class="caret"></span></a>
								<ul class="dropdown-menu">
									<li><a href="wedding.php" class="link link--yaku"><span>W</span><span>E</span><span>D</span><span>D</span><span>I</span><span>N</span><span>G</span></a></li>
									<li><a href="birthday.php" class="link link--yaku"><span>B</span><span>I</span><span>R</span><span>T</span><span>H</span><span>D</span><span>A</span><span>Y</span> <span>P</span><span>A</span><span>R</span><span>T</span><span>Y</span></a></li>
									<li><a href="anniversary.php" class="link link--yaku"><span>A</span><span>N</span><span>N</span><span>I</span><span>V</span><span>A</span><span>R</span><span>S</span><span>A</span><span>R</span><span>Y</span></a></li>
									<li><a href="other_events.php" class="link link--yaku"><span>O</span><span>T</span><span>H</span><span>E</span><span>R</span> <span>E</span><span>V</span><span>E</span><span>N</span><span>T</span><span>S</span></a></li>
								</ul>
							</li>
							<li><a href="gallery.php" class="link link--yaku"><span>G</span><span>A</span><span>L</span><span>L</span><span>E</span><span>R</span><span>Y</span></a></li>
							<li><a href="contact.php" class="link link--yaku"><span>C</span><span>O</span><span>N</span><span>T</span><span>A</span><span>C</span><span>T</span> <span>U</span><span>S</span></a></li>
						</ul>		
						<div class="clearfix"> </div>
					</div><!-- //navigation -->
				</div>
				<div class="clearfix"> </div>
			</div>	
		</nav>		
	</div>	
	<!-- //header -->
