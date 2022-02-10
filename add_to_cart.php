<?php
session_start();
require "config/config.php";
require "config/common.php";

if($_POST){
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    $statement = $pdo->prepare("SELECT * FROM products WHERE id=".$id);
    $statement->execute();
    $result=$statement->fetch(PDO::FETCH_ASSOC);
    if($qty > $result['quantity'] 
    // or $_SESSION['cart']["id".$id] > $result['quantity']
    ){
        echo "<script>alert('no enough stock');window.location.href='index.php'</script>";
    }else{
        if(isset($_SESSION['cart']["id".$id])){
            $_SESSION['cart']["id".$id] += $qty;
        } else {
            $_SESSION['cart']["id".$id] += $qty;
        }
        header("location: index.php");
    }
}
