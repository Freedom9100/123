<?php
$id = (int) ($_GET['id'] ?? 0);

if ($id == 0) {
    include('404.php');
    die();
}

$sql = "SELECT * FROM products WHERE id = $id";
$product = $connect->query($sql)->fetch();

if (!$product) {
    include('404.php');
    die();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $sql = "SELECT * FROM images WHERE product_id = $id";
    $images = $connect->query($sql)->fetchAll();
    foreach ($images as $image) {
        unlink(dirname(__DIR__) . '/' . $image['url']);
        $sql = "DELETE FROM images WHERE id = {$image['id']}";
        $connect->query($sql);
    }

    $sql = "DELETE FROM products WHERE id = $id";
    $connect->query($sql);

    header("Location:/");
}
?>

<form action="" method="post">
    <p>Удалить товар <?= $product['name'] ?> ?</p>
    <a href="/?page=product&id=<?= $id ?>">Назад</a>
    <input type="submit" value="Удалить">
    <br><br>
</form>