<?php include('header.php'); ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if ($_GET) {
  $id = $_GET['id'];
  $statement = $pdo->prepare("SELECT products.*, categories.name AS cname FROM products LEFT JOIN categories ON products.category_id = categories.id WHERE products.id=:id");
  $statement->execute([":id" => $id]);
  $result = $statement->fetchAll();
}
?>
<!--================Single Product Area =================-->
<div>
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6">
        <img width="540px" height="584" src="admin/images/<?= $result[0]['image'] ?>" alt="">
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <h3><?= escape($result[0]['name']) ?></h3>
          <h2>$<?= escape($result[0]['price']) ?></h2>
          <ul class="list">
            <li><a class="active" href="#"><span>Category</span> : <?= escape($result[0]['cname']) ?></a></li>
            <li><a href="#"><span>Availibility</span> : <?= $result[0]['quantity'] == 0 ? "Outofstock" : "Instock" ?></a></li>
          </ul>
          <p><?= escape($result[0]['description']) ?></p>
          <form action="add_to_cart.php" method="POST">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
            <input type="hidden" name="id" value="<?= escape($result[0]['id']) ?>">
            <div class="product_count">
              <label for="qty">Quantity:</label>
              <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
              <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;" class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
            </div>
            <div class="card_area d-flex align-items-center">
              <button class="primary-btn" style="border:none;">Add to Cart</button>
              <a class="primary-btn" href="index.php">Back</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include('footer.php'); ?>