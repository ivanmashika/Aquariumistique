<?php include 'header.php';
#Окно редактирования пользователей
$users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY name")->fetchAll();
?>

<h2>Пользователи</h2>
<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Дата регистрации</th>
                <th>Заказы</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?= htmlspecialchars($u['name']) ?></td>
                    <td><?= htmlspecialchars($u['email']) ?></td>
                    <td><?= $u['role'] ?></td>
                    <td><?= date('d.m.Y', strtotime($u['created_at'])) ?></td>
                    <td><a href="orders.php?user_id=<?= $u['id'] ?>" class="btn-admin">Заказы</a></td>
                </tr>
                <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>