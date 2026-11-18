<?php
$userId = $_SESSION['uid'] ?? 0;

if ($userId === 0) {
    header('Location: /?page=login');
    die();
}

$sql = "SELECT b.id AS busket_id,
        p.name,
        p.price,
        b.count,
        p.id AS product_id
        FROM buskets b
        JOIN products p ON b.product_id = p.id
        WHERE user_id = $userId";
$buskets = $connect->query($sql)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $busketId = $_POST['busket_id'] ?? 0;
    $sql = "SELECT * FROM buskets WHERE id = $busketId";
    $busket = $connect->query($sql)->fetch();

    if (isset($_POST['minus'])) {
        if ($busket['count'] > 1) {
            $sql = "UPDATE buskets SET count = count - 1 WHERE id = $busketId";
            $connect->query($sql);
        } else {
            $sql = "DELETE FROM buskets WHERE id = $busketId";
            $connect->query($sql);
        }
    }
    if (isset($_POST['plus'])) {
        $sql = "UPDATE buskets SET count = count + 1 WHERE id = $busketId";
        $connect->query($sql);
    }

    if (isset($_POST['order'])) {
        $sum = $POST['sum'] ?? 0;
        $sql = "INSERT INTO orders (user_id, sum) VALUES ($userId, $sum)";
        $connect->query($sql);

        $orderId = $connect->lastInsertId();

        foreach ($buskets as $busk) {
            $sql = "INSERT INTO order_items (product_id, order_id, count) 
                    VALUES ({$busk['product_id']}, $orderId, {$busk['count']})";
            $connect->query($sql);

            $sql = "DELETE FROM buskets WHERE id = {$busk['busket_id']}";
            $connect->query($sql);
        }

        header('Location: /?page=profile');
        exit();
    }

    header('Location: /?page=busket');
}
$sum = 0;
?>

<h1>Корзина</h1>
<?php if ($buskets): ?>
    <?php foreach ($buskets as $busk): ?>
        <?php $sum += $busk['count'] * $busk['price'] ?>
        <p><?= $busk['name'] ?></p>
        <p><?= $busk['price'] ?></p>
        <form action="" method="post">
            <input type="hidden" name="busket_id" value="<?= $busk['busket_id'] ?>">
            <input type="submit" value="-" name="minus">
        </form>
        <p><?= $busk['count'] ?></p>
        <form action="" method="post">
            <input type="hidden" name="busket_id" value="<?= $busk['busket_id'] ?>">
            <input type="submit" value="+" name="plus">
        </form>
    <?php endforeach ?>
    <?= "Сумма: " . $sum ?>

    <form action="" method="post">
        <input type="hidden" name="sum" value="<?= $sum ?>">
        <input type="submit" value="Создать заказ" name="order">
    </form>
<?php else: ?>
    <p>Корзина пуста</p>
<?php endif ?>