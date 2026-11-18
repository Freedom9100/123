<?php
$id = (int) ($_GET['id'] ?? 0);

// var_dump() - инструмент отладки

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

$userId = $_SESSION['uid'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $sql = "SELECT * FROM buskets WHERE user_id = $userId AND product_id = $id";
    $productIsset = $connect->query($sql)->fetch();
    if (!$productIsset) {
        $sql = "INSERT INTO buskets (user_id,product_id) VALUES ($userId, $id)";
        $connect->query($sql);
        header('Location: /?page=product&id=$id');
    }else{
        $sql = "UPDATE buskets SET count = count + 1 WHERE id = {$productIsset['id']}";
        $connect->query($sql);
    }
}
?>

<h1>Продукт: <?= $product['name'] ?></h1>
<p><?= $product['text'] ?></p>
<p><?= $product['price'] ?></p>

<?php if (isset($_SESSION['uid'])): ?>
    <form action="" method="post">
        <input type="submit" value="Добавить в корзину">
    </form>
    <?php if ($USER['role'] == 2): ?>
        <a href="/?page=update&id=<?= $id ?>">Редактировать</a>
        <a href="/?page=del&id=<?= $id ?>">Удалить</a>
    <?php endif ?>
<?php endif ?>