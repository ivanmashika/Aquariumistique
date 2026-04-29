<?php include 'header.php';
# Окно редактирования заказов
// Изменение статуса
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $orderId = (int) $_POST['order_id'];
    $newStatus = $_POST['status'];
    $stmt = $pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $orderId]);
}

// Фильтр по пользователю
$userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : '';
$statusFilter = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT o.*, u.name AS user_name FROM orders o LEFT JOIN users u ON o.user_id = u.id WHERE 1=1";
if ($userId)
    $sql .= " AND o.user_id = $userId";
if ($statusFilter)
    $sql .= " AND o.status = '$statusFilter'";
$sql .= " ORDER BY o.created_at DESC";
$orders = $pdo->query($sql)->fetchAll();

// Список пользователей для фильтра
$users = $pdo->query("SELECT id, name, email FROM users ORDER BY name")->fetchAll();
?>

<h2>Все заказы</h2>

<form method="GET" style="margin-bottom:20px; display:flex; gap:10px; align-items:center;">
    <select name="user_id">
        <option value="">Все пользователи</option>
        <?php foreach ($users as $u): ?>
            <option value="<?= $u['id'] ?>" <?= $userId == $u['id'] ? 'selected' : '' ?>><?= htmlspecialchars($u['name']) ?>
                (<?= htmlspecialchars($u['email']) ?>)</option>
        <?php endforeach; ?>
    </select>
    <select name="status">
        <option value="">Все статусы</option>
        <option value="new" <?= $statusFilter === 'new' ? 'selected' : '' ?>>Новый</option>
        <option value="processing" <?= $statusFilter === 'processing' ? 'selected' : '' ?>>В сборке</option>
        <option value="in_transit" <?= $statusFilter === 'in_transit' ? 'selected' : '' ?>>В пути</option>
        <option value="delivered" <?= $statusFilter === 'delivered' ? 'selected' : '' ?>>Доставлен</option>
        <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Отменён</option>
        <option value="paid" <?= $statusFilter === 'paid' ? 'selected' : '' ?>>Оплачен</option>
        <option value="shipped" <?= $statusFilter === 'shipped' ? 'selected' : '' ?>>Отправлен</option>
    </select>
    <button type="submit" class="btn-admin">Применить</button>
</form>
<div class="admin-table-container">
    <table class="admin-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= htmlspecialchars($order['user_name'] ?? 'Гость') ?></td>
                    <td><?= number_format($order['total'], 2) ?> ₽</td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <select name="status" class="status-select" onchange="this.form.submit()">
                                <option value="new" <?= $order['status'] === 'new' ? 'selected' : '' ?>>Новый</option>
                                <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>В сборке
                                </option>
                                <option value="in_transit" <?= $order['status'] === 'in_transit' ? 'selected' : '' ?>>В пути
                                </option>
                                <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Доставлен
                                </option>
                                <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Отменён
                                </option>
                                <option value="paid" <?= $order['status'] === 'paid' ? 'selected' : '' ?>>Оплачен</option>
                                <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Отправлен
                                </option>
                            </select>
                            <input type="hidden" name="update_status" value="1">
                        </form>
                    </td>
                    <td><?= date('d.m.Y H:i', strtotime($order['created_at'])) ?></td>
                    <td><a href="order_details.php?id=<?= $order['id'] ?>" class="btn-admin">Детали</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>