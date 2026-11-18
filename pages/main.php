<?php
$search = trim($_GET['search'] ?? '');

$category_id = $_GET['category_id'] ?? 0;

$min = (int)($_GET['min'] ?? 0);
$max = (int)($_GET['max'] ?? 0);

$sort = trim($_GET['sort'] ?? 'new');

$page = (int) ($_GET['p'] ?? 1);
if ($page < 1) {
    $page = 1;
}

$limit = 3;

$conditions = [];

if ($search) {
    $conditions[] = "(p.name LIKE '%$search%' OR p.text LIKE '%$search%')";
}

if ($category_id > 0) {
    $conditions[] = "p.category_id = $category_id";
}

if($min > 0){
    $conditions[] = "p.price >= $min";
}

if($max > 0){
    $conditions[] = "p.price <= $max";
}

if($sort === 'cheap'){
    $orderBy = " p.price ASC ";
} elseif ($sort === 'expensive'){
    $orderBy = " p.price DESC ";
}else{
    $orderBy = " p.id DESC ";
}

$where = '';

if (count($conditions) > 0) {
    $where = ' WHERE ' . implode(' AND ', $conditions);
}


$total = $connect->query("SELECT COUNT(*) FROM products p $where")->fetchColumn();

$totalPage = ceil($total / $limit);

$offset = ($page - 1) * $limit;

$sql = "SELECT p.*, c.name AS category_name FROM products p 
        JOIN categories c ON c.id = p.category_id $where ORDER BY $orderBy
        LIMIT $limit OFFSET $offset
        ";
$products = $connect->query($sql)->fetchAll();

$sqlCategories = "SELECT * FROM categories";
$categories = $connect->query($sqlCategories)->fetchAll();
?>

<h1>Главная страница</h1>

<form method="get">
    <select name="sort" id="">
        <option value="new" <?= $sort === 'new' ? 'selected' : '' ?>>Сначала новые</option>
        <option value="cheap" <?= $sort === 'new' ? 'selected' : '' ?>>Сначала дешевле</option>
        <option value="expensive" <?= $sort === 'new' ? 'selected' : '' ?>>Сначала дороже</option>
    </select>

    <select name="category_id" id="">
        <option value="">Выберите категорию</option>
        <?php foreach ($categories as $category): ?>
            <option value="<?= $category['id'] ?>" <?= $category_id == $category['id'] ? 'selected' : '' ?>><?= $category['name'] ?></option>
        <?php endforeach ?>
    </select>

    <br><br>
    <input type="number" name="min" value="<?= $min !== 0 ? $min : '' ?>" placeholder="Минимум">
    <input type="number" name="max" value="<?= $max !== 0 ? $max : '' ?>" placeholder="Максимум">
    <br><br>

    <input type="text" name="search" id="" placeholder="Поиск" value="<?= $search ?? '' ?>"><br><br>
    <input type="submit" value="Применить">
    <a href="/">Сбросить</a>
    <br><br>
</form>

<?php if (count($products) !== 0) : ?>
    <?php foreach ($products as $product): ?>
        <?= $product['name'] ?>
        <?= $product['text'] ?>
        <?= $product['price'] ?>
        <?= $product['category_name'] ?? 'Категории нет' ?>
        <br>
        <?php
        $sqlImg = "SELECT * FROM images WHERE product_id = {$product['id']}";
        $images = $connect->query($sqlImg)->fetchAll();
        foreach ($images as $image):
        ?>
            <img src="./<?= $image['url'] ?>" alt="" height="200px">
        <?php endforeach ?>
        <br>
        <a href="/?page=product&id=<?= $product['id'] ?>">Подробнее</a>
        <br><br>
    <?php endforeach ?>
    <?php for ($i = 1; $i <= $totalPage; $i++): ?>
        <a href="/?<?= http_build_query([
                        'p' => $i,
                        'search' => $search,
                        'category_id' => $category_id,
                        'min' => $min,
                        'max' => $max,
                        'sort' => $sort
                    ]) ?>"><?= $i ?></a>
    <?php endfor ?>
<?php else: ?>
    <p>Товаров нет</p>
<?php endif ?>