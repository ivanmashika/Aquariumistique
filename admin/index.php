<?php include 'header.php'; ?>
<!-- Дэшборд с небольшой статистикой-->
<h2>Обзор магазина</h2>
<div class="admin-dashboard-stats">
    <?php
    $usersCount = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $productsCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    ?>
    <div class="stat-card"><div class="stat-number"><?= $usersCount ?></div><div>Пользователей</div></div>
    <div class="stat-card"><div class="stat-number"><?= $productsCount ?></div><div>Товаров</div></div>
    <div class="stat-card"><div class="stat-number"><?= $ordersCount ?></div><div>Заказов</div></div>
</div>
<?php include 'footer.php'; ?>