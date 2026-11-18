<?php
$errors = [];

$sql = "SELECT * FROM categories";
$categories = $connect->query($sql)->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $category = $connect->query($sql)->fetch();
        if (!$category) {
            $errors['category_id'] = 'Данной категории не сущетвует';
        }
    }

    $image = $_FILES['image'];

    $types = ['png', 'jpg', 'jpeg'];

    if ($image['error'][0] === 0) {

        foreach ($image['name'] as $id => $nameImg) {
            $ext = pathinfo($nameImg, PATHINFO_EXTENSION);
            if (!in_array($ext, $types)) {
                $errors['image'] = 'Неверный тип изображения';
            } elseif ($image['size'][$id] > 2 * 1024 * 1024) {
                $errors['image'] = 'Изображение больше 2 Гб';
            } elseif ($image['error'][$id] !== 0) {
                $errors['image'] = 'Ошибка загрузки';
            }
        }
    } else {
        $errors['image'] = 'Загрузите изображение';
    }

    if (empty($errors)) {
        $sql = "INSERT INTO products (name,text,price,category_id) VALUES ('$name','$text','$price','$category_id')";
        $connect->query($sql);
        $product_id = $connect->lastInsertId();

        foreach ($image['name'] as $id => $img) {
            $ext = pathinfo($image['name'][$id], PATHINFO_EXTENSION);
            $imageName = 'upload/' . uniqid() . '.' . $ext;
            $dir = dirname(__DIR__) . '/' . $imageName;

            if (!move_uploaded_file($image['tmp_name'][$id], $dir)) {
                $errors['image'] = "Ошибка загрузки";
            }

            $sqlImg = "INSERT INTO images (url, product_id) VALUES ('$imageName', $product_id)";
            $connect->query($sqlImg);
        }

        header('Location:/');
    }
}
?>

<h1>Создание товара</h1>

<form action="" method="post" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Название" id="" value="<?= $name ?? '' ?>">
    <?= $errors['name'] ?>
    <br><br>
    <textarea name="text" placeholder="Описание" id=""><?= $text ?? '' ?></textarea>
    <?= $errors['text'] ?>
    <br><br>
    <input type="number" name="price" placeholder="Цена" id="" value="<?= $price ?? '' ?>">
    <?= $errors['price'] ?>
    <br><br>
    <select name="category_id" id="">
        <option value="">Выберите категорию</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
        <?php endforeach ?>
    </select>
    <?= $errors['category_id'] ?>
    <br><br>
    <input type="file" name="image[]" id="" multiple>
    <?= $errors['image'] ?>
    <br><br>
    <input type="submit" value="Создание товара">
    <br><br>
</form>