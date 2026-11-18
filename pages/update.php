<?php
$id = (int) ($_GET['id'] ?? 0);

if ($id == 0) {
    include('404.php');
    die();
}

$sql = "SELECT * FROM products WHERE id = :id";

$stmt = $connect->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
// $stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$product = $connect->query($sql)->fetch();

$sql = "SELECT * FROM categories";
$stmt = $connect->prepare($sql);
$stmt->execute();
$categories = $stmt($sql)->fetchAll();

if (!$product) {
    include('404.php');
    die();
}

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name'] ?? '');
    $text = trim($_POST['text'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $category_id = (int)(trim($_POST['category_id'] ?? 0));

    if (empty($name)) {
        $errors['name'] = 'Введите название';
    }

    if (empty($price)) {
        $errors['price'] = 'Введите цену';
    } elseif (!is_numeric($price)) {
        $errors['price'] = 'Введите число';
    } elseif ($price <= 0) {
        $errors['price'] = 'Введите число > 0';
    }

    if ($category_id === 0) {
        $errors['category_id'] = 'Выберите категорию';
    } else {
        $sql = "SELECT * FROM categories WHERE id = $category_id";
        $categories = $connect->query($sql)->fetch();
        if (!$categories) {
            $errors['category_id'] = 'Данной категории не сущетвует';
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE products SET name = '$name', text = '$text', price = $price, category_id = $category_id WHERE id = $id";
        $connect->query($sql);
        header("Location:/?page=product&id=$id");
    }
}
?>

<h1>Редактирование товара</h1>

<form action="" method="post">
    <input type="text" name="name" placeholder="Название" id="" value="<?= $name ?? $product['name'] ?>">
    <?= $errors['name'] ?>
    <br><br>
    <textarea name="text" placeholder="Описание" id=""><?= $text ?? $product['text'] ?></textarea>
    <?= $errors['text'] ?>
    <br><br>
    <input type="number" name="price" placeholder="Цена" id="" value="<?= $price ?? $product['price'] ?>">
    <?= $errors['price'] ?>
    <br><br>
    <select name="category_id" id="">
        <option value="">Выберите категорию</option>
        <?php $issetCategory = $category_id ?? $product['category_id'] ?>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>" <?= $issetCategory === $category['id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
        <?php endforeach ?>
    </select>
    <?= $errors['category_id'] ?>
    <br><br>
    <input type="submit" value="Изменить данные">
    <br><br>
</form>