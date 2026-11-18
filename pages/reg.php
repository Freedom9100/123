<?php

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $password2 = trim($_POST['password2'] ?? '');

    if (!$name) {
        $errors['name'] = 'Заполните имя';
    } elseif (mb_strlen($name) < 3) {
        $errors['name'] = 'Имя не менее 3 символов ';
    }

    if (!$email) {
        $errors['email'] = 'Заполните почту';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некоррестный email';
    } else {
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $check = $connect->query($sql)->fetch();
        if ($check) {
            $errors['email'] = 'Почта занята';
        }
    }

    if (!$password) {
        $errors['password'] = 'Введите пароль';
    } elseif (mb_strlen($password) < 6) {
        $errors['password'] = 'Пароль больше 6 сим';
    } elseif ($password !== $password2) {
        $errors['password'] = 'Пароли не совпадают';
    }

    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name,email,password) VALUES ('$name','$email','$hash')";
        $connect->query($sql);
        header("Location: /?page=login");
    }
}

?>

<form action="" method="post">
    <input type="text" name="name" id="" placeholder="Введите имя" value="<?= $name ?? '' ?>">
    <?= $errors['name'] ?? '' ?>
    <br><br>
    <input type="email" name="email" id="" placeholder="Введите email" value="<?= $email ?? '' ?>">
    <?= $errors['email'] ?? '' ?>
    <br><br>
    <input type="password" name="password" id="" placeholder="Введите пароль">
    <?= $errors['password'] ?? '' ?>
    <br><br>
    <input type="password" name="password2" id="" placeholder="Повторите пароль">
    <br><br>
    <input type="submit" value="Зарегистрироваться">
</form>