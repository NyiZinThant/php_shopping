<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if (!isset($_SESSION['user_id']) or !isset($_SESSION['logged_in']) or $_SESSION['role'] == 0) {
    header('location: login.php');
}
if ($_POST) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $image = $_FILES['image']['name'];
    if (
        empty($name) or empty($description) or $_POST['category'] == 0 or empty($image)
        or empty($quantity) or $quantity < 1 or empty($price) or $quantity < 1
    ) {
        if (empty($name)) {
            $nameError = "Your product name is empty";
        }
        if (empty($description)) {
            $descriptionError = "Your product description is empty";
        }
        if ($_POST['category'] == 0) {
            $categoryError = "Your product category is empty";
        }
        if (empty($quantity)) {
            $quantityError = "Quantity is empty";
        } else if ($quantity < 0) {
            $quantityError = "Can't accept mius";
        } else if (!empty($quantity) && is_int($quantity) != 1) {
            $quantityError = "Quantity should be number only";
        }
        if (empty($price)) {
            $priceError = "Your product price is empty";
        } else if ($price < 1) {
            $priceError = "Price error";
        } else if (!empty($price) && is_int($price) != 1) {
            $priceError = "Price should be number only";
        }
        if (empty($image)) {
            $imageError = "Your product image is empty";
        }
    } else {
        $file = "images/" . ($image);
        $imageType = pathinfo($file, PATHINFO_EXTENSION);
        if ($imageType != "png" and $imageType != "jpg" and $imageType != "jpeg") {
            $imageError = "Input must be png,jpg,jpeg";
        } else {
            move_uploaded_file($_FILES['image']['tmp_name'], $file,);
            $statement = $pdo->prepare("INSERT INTO products(name,description,category_id,quantity,price,image,created_at) VALUES(:name,:description,:category_id,:quantity,:price,:image,NOW())");
            $result = $statement->execute(array(":name" => $name, ":description" => $description, ":category_id" => $category, ":quantity" => $quantity, ":price" => $price, ":image" => $image));
            if ($result) {
                echo "<script>alert('Product is Added');window.location.href='index.php';</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard | Add Category</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Messages Dropdown Menu -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="index3.html" class="brand-link">
                <img src="./dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Admin Panel</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="./dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block"><?= $_SESSION['username'] ?></a>
                    </div>
                </div>

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link">
                                <i class="nav-icon fas fa-th"></i>
                                <p>
                                    Products
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="category.php" class="nav-link">
                                <i class="nav-icon fas fa-list"></i>
                                <p>
                                    Categories
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="users.php" class="nav-link">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Users
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="order_list.php" class="nav-link">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    Order
                                </p>
                            </a>
                        </li>
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link active">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>
                                    Reports
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="weekly_report.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Weekly Report
                                        </p>
                                    </a>
                                <li class="nav-item">
                                    <a href="monthly_report.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Monthly Report
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="royal_cus.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Royal Customer
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="best_sale.php" class="nav-link">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Best Sale Item
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Main content -->
            <div class="content mt-4">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="product_add.php" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <p class="text-danger d-inline-block ml-2"><?= empty($nameError) ? "" : "*" . $nameError ?></p>
                                            <input type="text" class="form-control" id="name" name="name">
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <p class="text-danger d-inline-block ml-2"><?= empty($descriptionError) ? "" : "*" . $descriptionError ?></p>
                                            <textarea type="text" class="form-control" id="description" name="description"></textarea>
                                        </div>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM categories");
                                        $statement->execute();
                                        $result = $statement->fetchAll();
                                        ?>
                                        <div class="form-group">
                                            <label for="name">Category</label>
                                            <p class="text-danger d-inline-block ml-2"><?= empty($categoryError) ? "" : "*" . $categoryError ?></p>
                                            <select name="category" class="form-control" id="category">
                                                <option value="0" selected>Select Category</option>
                                                <?php foreach ($result as $value) : ?>
                                                    <option value="<?= $value['id'] ?>"><?= $value['name'] ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="quantity">Quantity</label>
                                            <p class="text-danger d-inline-block ml-2"><?= empty($quantityError) ? "" : "*" . $quantityError ?></p>
                                            <input type="number" class="form-control" id="quantity" name="quantity">
                                        </div>
                                        <div class="form-group">
                                            <label for="price">Price</label>
                                            <p class="text-danger d-inline-block ml-2"><?= empty($priceError) ? "" : "*" . $priceError ?></p>
                                            <input type="number" class="form-control" id="price" name="price">
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Image</label>
                                            <p class="text-danger d-inline-block ml-2"><?= empty($imageError) ? "" : "*" . $imageError ?></p>
                                            <input type="file" id="image" name="image">
                                        </div>
                                        <div class="form-group mb-0">
                                            <input type="submit" class="btn btn-success" value="Submit">
                                            <a href="index.php" class="btn btn-secondary mr-2">Back</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include("footer.php") ?>