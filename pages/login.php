<?php

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$email || !$password) {
        $errors['email'] = 'Введите данные';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    } else {
        $sql = "SELECT * FROM users WHERE email =  '$email'";
        $userIsset = $connect->query($sql)->fetch();

        if (!$userIsset) {
            $errors['email'] = 'Неверные данные';
        } elseif ($userIsset && password_verify($password, $userIsset['password'])) {
            $_SESSION['uid'] = $userIsset['id'];
            header('Location: /');
        } else {
            $errors['password'] = 'Неверные данные';
        }
    }
}
?>

<form action="" method="post">
    <input type="email" name="email" id="" placeholder="Введите email" value="<?= $email ?? '' ?>">
    <br>
    <?= $errors['email'] ?? '' ?>
    <br>
    <input type="password" name="password" id="" placeholder="Введите пароль" value="<?= $password ?? '' ?>">
    <br>
    <?= $errors['password'] ?? '' ?>
    <br>
    <input type="submit" value="Войти">
</form>