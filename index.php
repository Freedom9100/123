<?php
session_start();

include('connect/db.php');
include('connect/auth_control.php');

$sql = "SELECT * FROM products";
$products = $connect->query($sql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Повторение</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <?php include('components/header.php') ?>

    <?php
    $page = $_GET['page'] ?? '';
    if (isset($page)) {
        if ($page == '') {
            include('pages/main.php');
        } elseif ($page == 'create') {
            include('pages/create.php');
        } elseif ($page == 'c_category') {
            include('pages/create_category.php');
        } elseif ($page == 'del') {
            include('pages/del.php');
        } elseif ($page == 'product') {
            include('pages/product.php');
        } elseif ($page == 'update') {
            include('pages/update.php');
        } elseif ($page == 'reg') {
            include('pages/reg.php');
        } elseif ($page == 'login') {
            include('pages/login.php');
        } elseif ($page == 'busket') {
            include('pages/busket.php');
        } elseif ($page == 'profile') {
            include('pages/profile.php');
        } else {
            include('pages/404.php');
        }
    }
    ?>

    <?php include('components/footer.php') ?>
</body>

</html>