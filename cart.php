<?php include("header.php"); ?>
<!--================Cart Area =================-->
<section class="cart_area">
    <div class="container">
        <div class="cart_inner">
            <div class="table-responsive">
                <?php if (isset($_SESSION['cart'])) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            require("config/config.php");
                            $total = 0;
                            foreach ($_SESSION['cart'] as $key => $qty) :
                                $id = str_replace("id", "", $key);

                                $statement = $pdo->prepare("SELECT * FROM products WHERE id=:id");
                                $statement->execute([":id" => $id]);
                                $result = $statement->fetch(PDO::FETCH_ASSOC);

                                $total += $result['price'] * $qty;
                            ?>
                                <tr>
                                    <td>
                                        <div class="media">
                                            <div class="d-flex">
                                                <img src="admin/images/<?= escape($result['image']) ?>" height="75px" alt="">
                                            </div>
                                            <div class="media-body">
                                                <p><?= escape($result['name']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>$<?= escape($result['price']) ?></h5>
                                    </td>
                                    <td>
                                        <div class="product_count">
                                            <input type="text" value="<?= $qty ?>" title="Quantity:" class="input-text qty" readonly>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>$<?= escape($result['price']) * $qty ?></h5>
                                    </td>
                                    <td>
                                        <a href="cart_item_clear.php?pid=<?= $id ?>" style="line-height: 40px;" class="primary-btn">Clear</a>
                                    </td>
                                </tr>

                            <?php endforeach ?>
                            <tr>
                                <td colspan="3"></td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5>$<?= $total ?></h5>
                                </td>
                            </tr>
                            <tr class="out_button_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="gray_btn" href="clear_all.php">Clear All</a>
                                        <a class="primary-btn" href="sale_order.php">Order Submit</a>
                                        <a class="gray_btn" href="index.php">Continue Shopping</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif ?>
            </div>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->

</div>
</div>
</div>



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
<script src="js/gmaps.min.js"></script>
<script src="js/main.js"></script>
</body>

</html>