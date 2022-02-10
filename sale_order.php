<?php require "header.php" ?>
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
}else{
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
			<p class="footer-text m-0">
				<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				Copyright &copy;<script>
					document.write(new Date().getFullYear());
				</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
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
