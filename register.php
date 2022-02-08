<?php
session_start();

require "config/config.php";
require "config/common.php";

if ($_POST) {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
	$address = $_POST['address'];
	$phone = $_POST['phone'];
	$role = 0;
	if (empty($name) or empty($email) or empty($_POST['password']) or strlen($_POST['password']) < 4 or empty($address) or empty($phone)) {
		if (empty($name)) {
			$nameError = "Username is required";
		}
		if (empty($email)) {
			$emailError = "Email is required";
		}
		if (empty($_POST['password'])) {
			$passwordError = "Password is required";
		} elseif (strLen($_POST['password']) < 4) {
			$passwordError = "Password should be 4 characters at least";
		}
		if (empty($address)) {
			$addressError = "Address is required";
		}
		if (empty($phone)) {
			$phoneError = "phone is required";
		}
	} else {
		$statement = $pdo->prepare("SELECT * FROM users WHERE email=:email");
		$statement->execute([
			":email" => $email,
		]);
		$user = $statement->fetch(PDO::FETCH_ASSOC);
		if ($user) {
			echo "<script>alert('Your email is already used.');</script>";
		} else {
			$statement = $pdo->prepare("INSERT INTO users(name,email,password,address,phone,role,created_at) VALUES (:name,:email,:password,:address,:phone,:role,now())");
			$result = $statement->execute([
				":name" => $name,
				":email" => $email,
				":password" => $password,
				":address" => $address,
				":phone" => $phone,
				":role" => $role
			]);
			if ($result) {
				echo "<script>alert('Success Register You Can Now Login');window.location.href='login.php';</script>";
			}
		}
	}
}



?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>PHP Shop | Register</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<h3 style="margin:24px;" class="navbar-brand logo_h">
						PHP Shopping
					</h3>
				</div>
			</nav>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Login/Register</h1>
					<nav class="d-flex align-items-center">
						<a href="#">Home<span class="lnr lnr-arrow-right"></span></a>
						<a href="login.html">Login/Register</a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Login Box Area =================-->
	<section class="login_box_area section_gap">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="login_form_inner" style="padding-bottom: 115px;">
						<h3>Create a account</h3>
						<form class="row login_form" action="register.php" method="post" id="contactForm" novalidate="novalidate">
							<input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
							<div class="col-md-12 form-group">
								<input style="<?= isset($nameError)? "border-color: red;" : "" ?>" type="text" class="form-control" id="name" name="name" placeholder="<?= isset($nameError) ? $nameError : "Name" ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Name'">
							</div>
							<div class="col-md-12 form-group">
								<input style="<?= isset($emailError)? "border-color: red;" : "" ?>" type="email" class="form-control" id="email" name="email" placeholder="<?= isset($emailError) ? $emailError : "Email" ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Email'">
							</div>
							<div class="col-md-12 form-group">
								<input style="<?= isset($phoneError)? "border-color: red;" : "" ?>" type="number" class="form-control" id="phone" name="phone" placeholder="<?= isset($phoneError) ? $phoneError : "Phone Number" ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Phone Number'">
							</div>
							<div class="col-md-12 form-group">
								<input style="<?= isset($addressError)? "border-color: red;" : "" ?>" type="text" class="form-control" id="address" name="address" placeholder="<?= isset($addressError) ? $addressError : "Address" ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Address'">
							</div>
							<div class="col-md-12 form-group">
								<input style="<?= isset($passwordError)? "border-color: red;" : "" ?>" type="password" class="form-control" id="password" name="password" placeholder="<?= isset($passwordError) ? $passwordError : "Password" ?>" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Password'">
							</div>
							<div class="col-md-12 form-group">
								<button type="submit" value="submit" class="primary-btn">Register</button>
								<a href="login.php">Login</a>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!--================End Login Box Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0">
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Copyright &copy;<script>
						document.write(new Date().getFullYear());
					</script> All rights reserved
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				</p>
			</div>
		</div>
	</footer>
	<!-- End footer Area -->


	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>