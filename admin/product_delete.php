<?php
require "../config/config.php";
$statement = $pdo->prepare("DELETE FROM products WHERE id=:id");
$statement->execute([":id" => $_GET['id']]);
header('location: index.php');
