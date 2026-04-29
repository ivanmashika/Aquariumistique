<!-- Общий хэдер для админ-панели, боковое меню для перехода по страницам -->
<?php
require_once '../config.php';
requireAdmin();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель | AquaStyle</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
<header class="top-menu">
    <div class="logo"><a href="../index.php"><img src="../src/logo.png" alt="Лого"></a></div>
    <nav><ul>
        <li><a href="../catalog.php">Каталог</a></li>
        <li><a href="../contacts.php">Контакты</a></li>
        <li><a href="../about.php">О нас</a></li>
        <li><a href="../profile.php">Профиль</a></li>
    </ul></nav>
    <div class="cart"><a href="../cart.php"><img src="../src/cart.png" alt="Корзина"></a></div>
</header>
<div class="admin-wrapper">
    <aside class="admin-sidebar">
        <h3>Админ-панель</h3>
        <ul>
            <li><a href="index.php">Дашборд</a></li>
            <li><a href="orders.php">Заказы</a></li>
            <li><a href="products.php">Товары</a></li>
            <li><a href="categories.php">Категории</a></li>
            <li><a href="users.php">Пользователи</a></li>
            <li><a href="../index.php">Вернуться на сайт</a></li>
        </ul>
    </aside>