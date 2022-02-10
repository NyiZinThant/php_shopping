<?php include('header.php') ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
if (empty($_POST['search']) and empty($_COOKIE['search'])) {
	if (!empty($_GET['pageno'])) {
		$pageno = $_GET['pageno'];
	} else {
		$pageno = 1;
	}
	$numOfRecord = 6;
	$offset = ($pageno - 1) * $numOfRecord;
	if (isset($_GET['cid'])) {
		$cid = $_GET['cid'];
		$statement = $pdo->prepare("SELECT * FROM products WHERE category_id=:cid AND quantity > 0 ORDER BY id DESC");
		$statement->execute([":cid" => $cid]);
		$rawResult = $statement->fetchAll();

		$total_pages = ceil(count($rawResult) / $numOfRecord);
		$statement = $pdo->prepare("SELECT * FROM products  WHERE category_id=:cid AND quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfRecord");
		$statement->execute([":cid" => $cid]);
		$result = $statement->fetchAll();
	} else {
		$statement = $pdo->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC");
		$statement->execute();
		$rawResult = $statement->fetchAll();

		$total_pages = ceil(count($rawResult) / $numOfRecord);
		$statement = $pdo->prepare("SELECT * FROM products WHERE quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfRecord");
		$statement->execute();
		$result = $statement->fetchAll();
	}
} else {
	$search = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
	if (!empty($_GET['pageno'])) {
		$pageno = $_GET['pageno'];
	} else {
		$pageno = 1;
	}
	$numOfRecord = 6;
	$offset = ($pageno - 1) * $numOfRecord;
	$statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$search%' AND quantity > 0 ORDER BY id DESC");
	$statement->execute();
	$rawResult = $statement->fetchAll();

	$total_pages = ceil(count($rawResult) / $numOfRecord);
	$statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$search%' AND quantity > 0 ORDER BY id DESC LIMIT $offset,$numOfRecord");
	$statement->execute();
	$result = $statement->fetchAll();
}
?>
<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-md-5">
			<div class="sidebar-categories">
				<div class="head">Browse Categories</div>
				<ul class="main-categories">
					<?php
					$catStatement = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
					$catStatement->execute();
					$catResult = $catStatement->fetchAll();
					?>
					<?php foreach ($catResult as $value) : ?>
						<li class="main-nav-list"><a href="index.php?cid=<?= $value['id'] ?>"><span class="lnr lnr-arrow-right"></span><?= escape($value['name']) ?></a></li>
					<?php endforeach ?>
				</ul>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<!-- Start Filter Bar -->

			<div class="filter-bar d-flex flex-wrap align-items-center">
				<div class="pagination">
					<a href="?pageno=1" class="">First</a>
					<a href="<?php if ($pageno <= 1) {
									echo "#";
								} else {
									echo "?pageno=" . $pageno - 1;
								} ?>" class="prev-arrow <?php if ($pageno <= 1) {
															echo "disabled";
														} ?>"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
					<a href="?pageno=<?= $pageno ?>" class="active"><?= $pageno ?></a>
					<a href="<?php if ($pageno >= $total_pages) {
									echo "#";
								} else {
									echo "?pageno=" . $pageno + 1;
								} ?>" class="next-arrow <?php if ($pageno >= $total_pages) {
															echo "#";
														} else {
															echo "?pageno=" . $pageno + 1;
														} ?>"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					<a href="?pageno=<?= $total_pages ?>" class="">Last</a>
				</div>
			</div>
			<!-- End Filter Bar -->
			<!-- Start Best Seller -->
			<section class="lattest-product-area pb-40 category-list">
				<div class="row">
					<!-- single product -->
					<?php if ($result) {
						foreach ($result as $value) { ?>
							<div class="col-lg-4 col-md-6">
								<div class="single-product">
									<a href="./product_detail.php?id=<?= $value['id'] ?>"><img height="255px" width="271px" src="admin/images/<?= escape($value['image']) ?>" alt=""></a>
									<div class="product-details">
										<h6><?= escape($value['name']) ?></h6>
										<div class="price">
											<h6>$<?= $value['price'] ?></h6>
										</div>
										<div class="prd-bottom">
											<form action="add_to_cart.php" method="post">
												<input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
												<input type="hidden" name="id" value="<?= escape($result[0]['id']) ?>">
												<input type="hidden" name="qty" value="1">
												<div class="social-info">
													<button type="submit" style="padding: 0;margin: 0;border: none;background:none">
														<span class="ti-bag"></span>
														<p class="hover-text" style="left:20px;">add to bag</p>
													</button>
												</div>
												<a href="./product_detail.php?id=<?= $value['id'] ?>" class="social-info">
												<span class="lnr lnr-move"></span>
												<p class="hover-text">view more</p>
											</a>
											</form>
										</div>
									</div>
								</div>
							</div>
					<?php }
					} ?>
				</div>
			</section>
			<!-- End Best Seller -->
			<?php include('footer.php'); ?>