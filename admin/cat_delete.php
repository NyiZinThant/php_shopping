<?php
require "../config/config.php";
$statement = $pdo->prepare("DELETE FROM categories WHERE id=:id");
$statement->execute([":id" => $_GET['id']]);
header('location: category.php');
