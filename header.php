<?php
require_once __DIR__ . '/config.php';
$currentUser = getCurrentUser($pdo);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'AquaStyle' ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="top-menu">
    <div class="logo"><a href="index.php"><img src="src/logo.png" alt="Лого"></a></div>
    <nav>
        <ul>
            <li><a href="catalog.php">Каталог</a></li>
            <li><a href="contacts.php">Контакты</a></li>
            <li><a href="about.php">О нас</a></li>
            <li><a href="profile.php">Профиль</a></li>
            <?php if ($currentUser && $currentUser['role'] === 'admin'): ?>
                <li><a href="admin/index.php" style="background: #fbbf24; color: #000;">⚙️ Админка</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="cart"><a href="cart.php"><img src="src/cart.png" alt="Корзина"></a></div>
</header>