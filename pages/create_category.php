<?php
$errors = [];

if($_SERVER['REQUEST_METHOD'] == "POST") {
    $name = trim($_POST['name'] ?? '');

    if(!$name){
        $errors['name'] = 'Введите название';
    }elseif(mb_strlen($name) < 3){
        $errors['name'] = 'Не менее 3 сим';
    }

    if(empty($errors)){
        $sql = "INSERT INTO categories (name) VALUES ('$name')";
        $connect->query($sql);
        header("Location: /");
    }
}
?>

<form action="" method="post">
    <input type="text" name="name" id="" placeholder="Введите название" value="<?= $name ?? '' ?>">
    <?= $errors['name'] ?? '' ?><br><br>
    <input type="submit" value="Создать">
</form>