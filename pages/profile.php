<?php
$userId = $_SESSION['uid'] ?? 0;

if ($userId === 0) {
    header("Location: /?page=login");
    die();
}


$sql = "SELECT
        o.id,
        o.created_at,
        o.status,
        p.name,
        p.price,
        oi.count
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        JOIN products p ON oi.product_id = p.id
        WHERE user_id = $userId
        ORDER BY o.created_at DESC ";
$orders = $connect->query($sql)->fetchAll(PDO::FETCH_GROUP);

?>

<h1>Профиль</h1>

<?php if ($orders): ?>
    <?php foreach ($orders as $orderId => $order): ?>
        <?= 'Заказ №' . $orderId ?>
        <?= 'Дата создания ' . $order[0]['created_at'] ?>
        <?= 'Статус ' . $order[0]['status'] ?>
        <?php foreach ($order as $item): ?>
            <p><?= $item['name'] ?></p>
            <p><?= $item['count'] ?></p>
            <p><?= $item['price'] ?></p>
        <?php endforeach ?>
    <?php endforeach; ?>

<?php else: ?>
    <p>Заказов не обнаружено</p>
<?php endif ?>