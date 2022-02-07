<?php
session_start();
require "../config/config.php";
require "../config/common.php";
if (!isset($_SESSION['user_id']) and !isset($_SESSION['logged_in']) and $_SESSION['role'] != 1) {
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
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard</title>

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
                        <a href="#" class="d-block"><?= escape($_SESSION['username']) ?></a>
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
                                <div class="card-header">
                                    <h3 class="card-title">Order Listings</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <?php
                                    if (!empty($_GET['pageno'])) {
                                        $pageno = $_GET['pageno'];
                                    } else {
                                        $pageno = 1;
                                    }
                                    $numOfRecord = 6;
                                    $offset = ($pageno - 1) * $numOfRecord;
                                    $statement = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC");
                                    $statement->execute();
                                    $rawResult = $statement->fetchAll();

                                    $total_pages = ceil(count($rawResult) / $numOfRecord);
                                    $statement = $pdo->prepare("SELECT * FROM sale_orders ORDER BY id DESC LIMIT $offset,$numOfRecord");
                                    $statement->execute();
                                    $result = $statement->fetchAll();
                                    ?>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">#</th>
                                                <th>User</th>
                                                <th>Total Price</th>
                                                <th>Order Date</th>
                                                <th style="width: 160px">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $i = 1;
                                            if ($result) : foreach ($result as $value) : ?>
                                                    <?php
                                                    $userStatement = $pdo->prepare("SELECT * FROM users WHERE id=:id");
                                                    $userStatement->execute([":id" => $value['user_id']]);
                                                    $userResult = $userStatement->fetchAll();
                                                    ?>
                                                    <tr>
                                                        <td><?= $i ?></td>
                                                        <td><?= escape($userResult[0]['name']) ?></td>
                                                        <td>
                                                            <div>
                                                                <?= escape($value['total_price']) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div>
                                                                <?= escape(date('Y-m-d',strtotime($value['order_date']))) ?>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <a href="order_detail.php?id=<?= $value['id'] ?>" class="btn btn-success" type="button">View</a>
                                                        </td>
                                                    </tr>
                                            <?php $i++;
                                                endforeach;
                                            endif ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mx-3">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination justify-content-end">
                                            <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                                            <li class="page-item <?php if ($pageno <= 1) {
                                                                        echo "disabled";
                                                                    } ?>">
                                                <a class="page-link" href="<?php if ($pageno <= 1) {
                                                                                echo "#";
                                                                            } else {
                                                                                echo "?pageno=" . $pageno - 1;
                                                                            } ?>">Previous</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="?pageno=<?= $pageno ?>"><?= $pageno ?></a></li>
                                            <li class="page-item <?php if ($pageno >= $total_pages) {
                                                                        echo "disabled";
                                                                    } ?>">
                                                <a class="page-link" href="<?php if ($pageno >= $total_pages) {
                                                                                echo "#";
                                                                            } else {
                                                                                echo "?pageno=" . $pageno + 1;
                                                                            } ?>">Next</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="?pageno=<?= $total_pages ?>">Last</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <a href="cat_add.php" class="btn btn-success mx-3 mb-3" type="button">New Category</a>
                                <!-- /.card-body -->
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