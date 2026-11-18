<a href="/">Главная</a>
<?php if (!isset($_SESSION['uid'])): ?>
    <a href="./?page=login">Логин</a>
    <a href="./?page=reg">Регистрация</a>
<?php else: ?>
    <?php if ($USER['role'] == 2): ?>
        <a href="/?page=create">Создание товара</a>
        <a href="/?page=c_category">Создание категории</a>
    <?php endif ?>
    <a href="/?page=profile">Профиль</a>
    <a href="/?page=busket">Корзина</a>
    <a href="/?exit">Выйти</a>
<?php endif ?>
