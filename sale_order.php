<?php
session_start();
require "config/config.php";
require "config/common.php";
if (!isset($_SESSION['user_id']) and !isset($_SESSION['logged_in']) and !isset($_SESSION['username'])) {
	header('location: login.php');
}
if (isset($_POST['search'])) {
	setcookie('search', $_POST['search'], time() + (86400 * 30), "/");
} else {
	if (empty($_GET['pageno'])) {
		unset($_COOKIE['search']);
		setcookie('search', null, -1, '/');
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
	<title>PHP Shop | Login</title>

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
					<h1>Welcome <?= escape($_SESSION['username']) ?></h1>
					<a href="logout.php" class="primary-btn" style="background: white; color:#f9a631;line-height: 40px;font-weight:bold;">Logout</a>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<?php


	require "config/config.php";
	if (!empty($_SESSION['cart'])) {
		$userId = $_SESSION['user_id'];
		$total = 0;
		foreach ($_SESSION['cart'] as $key => $qty) {
			$id = str_replace("id", "", $key);
			$statement = $pdo->prepare("SELECT * FROM products WHERE id=:id");
			$statement->execute([":id" => $id]);
			$result = $statement->fetch(PDO::FETCH_ASSOC);
			$total += $result['price'] * $qty;
		}

		//insert into sale_orders table
		$statement = $pdo->prepare("INSERT INTO sale_orders(user_id, total_price, order_date) VALUES(:user_id, :total, :odate)");
		$result = $statement->execute([
			":user_id" => $_SESSION['user_id'],
			":total" => $total,
			":odate" => date("Y-m-d H:i:s")
		]);
		if ($result) {
			//insert into sale_order_detail
			$saleOrderId = $pdo->lastInsertId();
			foreach ($_SESSION['cart'] as $key => $qty) {
				$id = str_replace("id", "", $key);
				$statement = $pdo->prepare("INSERT INTO sale_order_detail(sale_order_id, product_id, quantity, order_date) VALUES(:sid, :pid, :qty, :odate)");
				$result = $statement->execute([
					":sid" => $saleOrderId,
					":pid" => $id,
					":qty" => $qty,
					":odate" => date("Y-m-d H:i:s")
				]);
				$qtyStatement = $pdo->prepare("SELECT quantity FROM products WHERE id=:id");
				$qtyStatement->execute([":id" => $id]);
				$qtyResult = $qtyStatement->fetch(PDO::FETCH_ASSOC);
				$updateQty = $qtyResult['quantity'] - $qty;

				$statement = $pdo->prepare("UPDATE products SET quantity=:qty WHERE id=:pid");
				$statement->execute([":qty" => $updateQty, ":pid" => $id]);
			}
			unset($_SESSION['cart']);
		}
	} else {
		header("location: index.php");
	}
	?>
	<!--================Order Details Area =================-->
	<section class="order_details section_gap">
		<div class="container">
			<h3 class="title_confirmation">Thank you. Your order has been received.</h3>
			<div class="text-center">
				<a href="index.php">Return</a>
			</div>
		</div>
	</section>
	<!--================End Order Details Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class=" m-0">
					Copyright &copy;<script>
						document.write(new Date().getFullYear());
					</script> All rights reserved
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